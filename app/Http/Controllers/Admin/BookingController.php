<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BookingApproved;
use App\Mail\BookingRejected;
use App\Mail\BookingStatusChangedMail;
use App\Mail\PaymentConfirmation;
use App\Models\Booking;
use App\Models\BookingPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['tour', 'safari', 'payments']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_reference', 'LIKE', "%{$search}%")
                    ->orWhere('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $bookings = $query->latest()->paginate(15);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['tour', 'safari', 'payments' => fn ($q) => $q->latest()]);

        return view('admin.bookings.show', compact('booking'));
    }

    public function approve(Request $request, Booking $booking)
    {
        if (in_array($booking->status, [Booking::STATUS_CANCELLED, Booking::STATUS_COMPLETED], true)) {
            return back()->withErrors(['status' => 'This booking cannot be approved in its current state.']);
        }

        $validated = $request->validate([
            'deposit_percentage' => 'nullable|numeric|min:5|max:100',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        if (isset($validated['deposit_percentage'])) {
            $booking->deposit_percentage = (float) $validated['deposit_percentage'];
            $booking->deposit_required = round(($booking->total_price * $booking->deposit_percentage) / 100, 2);
            $booking->balance_amount = max(0, $booking->total_price - $booking->deposit_paid);
        }

        if (! empty($validated['admin_notes'])) {
            $booking->admin_notes = $validated['admin_notes'];
        }

        $booking->status = Booking::STATUS_APPROVED;
        $booking->approved_at = now();
        $booking->save();

        // Send Approval Email with Payment Link
        try {
            Mail::to($booking->email)->send(new BookingApproved($booking));
        } catch (\Exception $e) {
            Log::error('Failed sending booking approval email: '.$e->getMessage());
        }

        return back()->with('success', 'Booking approved! Payment link sent to customer email.');
    }

    public function reject(Request $request, Booking $booking)
    {
        if ($booking->status === Booking::STATUS_CANCELLED) {
            return back()->with('success', 'This booking is already cancelled.');
        }

        if ($booking->status === Booking::STATUS_COMPLETED || $booking->payment_status === Booking::PAYMENT_FULLY_PAID) {
            return back()->withErrors(['status' => 'Fully paid or completed bookings must be refunded before they can be cancelled.']);
        }

        $validated = $request->validate([
            'cancellation_reason' => 'required|string|max:1000',
        ]);

        $booking->status = Booking::STATUS_CANCELLED;
        $booking->cancellation_reason = $validated['cancellation_reason'];
        $booking->cancelled_at = now();
        $booking->save();

        try {
            Mail::to($booking->email)->send(new BookingRejected($booking));
        } catch (\Exception $e) {
            Log::error('Failed sending booking rejection email: '.$e->getMessage());
        }

        return back()->with('success', 'Booking request rejected and customer notified.');
    }

    public function recordPayment(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'gateway' => 'required|string|max:100',
            'payment_type' => 'required|in:deposit,balance,full',
            'notes' => 'nullable|string|max:500',
        ]);

        $amount = round((float) $validated['amount'], 2);

        try {
            DB::transaction(function () use ($booking, $validated, $amount) {
                // Lock the row so concurrent admin payments cannot race.
                $locked = Booking::whereKey($booking->id)->lockForUpdate()->firstOrFail();

                if (in_array($locked->status, [Booking::STATUS_CANCELLED, Booking::STATUS_COMPLETED], true)) {
                    throw new \RuntimeException('Payments cannot be recorded on a cancelled or completed booking.');
                }

                $outstanding = max(0, (float) $locked->total_price - (float) $locked->deposit_paid);

                if ($amount > $outstanding + 0.001) {
                    throw new \RuntimeException(
                        'Amount exceeds the outstanding balance of '.$locked->currency_symbol.number_format($outstanding, 2).'.'
                    );
                }

                // If the customer submitted a matching manual payment, confirm that
                // record instead of creating a duplicate. Any other pending records are
                // marked failed so the customer can submit again if needed.
                $pending = $locked->payments()
                    ->where('status', BookingPayment::STATUS_PENDING)
                    ->latest()
                    ->first();

                if ($pending && abs((float) $pending->amount - $amount) < 0.01) {
                    $pending->update([
                        'status' => BookingPayment::STATUS_COMPLETED,
                        'gateway' => $validated['gateway'],
                        'notes' => $validated['notes'] ?? $pending->notes,
                        'paid_at' => now(),
                    ]);
                } else {
                    $locked->payments()
                        ->where('status', BookingPayment::STATUS_PENDING)
                        ->update([
                            'status' => BookingPayment::STATUS_FAILED,
                            'notes' => 'Superseded by an admin-recorded payment.',
                        ]);

                    BookingPayment::create([
                        'booking_id' => $locked->id,
                        'gateway' => $validated['gateway'],
                        'payment_type' => $validated['payment_type'],
                        'amount' => $amount,
                        'currency' => $locked->currency ?? 'USD',
                        'status' => BookingPayment::STATUS_COMPLETED,
                        'payer_name' => $locked->full_name,
                        'payer_email' => $locked->email,
                        'notes' => $validated['notes'] ?? 'Recorded manually by Admin',
                        'paid_at' => now(),
                    ]);
                }

                $totalPaid = (float) $locked->payments()->where('status', BookingPayment::STATUS_COMPLETED)->sum('amount');
                $locked->deposit_paid = $totalPaid;
                $locked->balance_amount = max(0, (float) $locked->total_price - $totalPaid);

                if ($locked->balance_amount <= 0) {
                    $locked->payment_status = Booking::PAYMENT_FULLY_PAID;
                    $locked->status = Booking::STATUS_CONFIRMED;
                    $locked->confirmed_at = $locked->confirmed_at ?? now();
                } else {
                    $locked->payment_status = Booking::PAYMENT_DEPOSIT_PAID;

                    // Only mark the booking "deposit paid" once the required
                    // deposit is actually covered, not after any tiny payment.
                    if ($totalPaid >= $locked->calculated_deposit_required
                        && in_array($locked->status, [Booking::STATUS_PENDING, Booking::STATUS_APPROVED], true)) {
                        $locked->status = Booking::STATUS_DEPOSIT_PAID;
                    }
                }

                $locked->save();
            });
        } catch (\RuntimeException $e) {
            return back()->withErrors(['amount' => $e->getMessage()]);
        }

        $booking->refresh();

        try {
            Mail::to($booking->email)->send(new PaymentConfirmation($booking));
        } catch (\Exception $e) {
            Log::error('Failed sending payment confirmation email: '.$e->getMessage());
        }

        return back()->with('success', 'Payment of '.$booking->currency_symbol.number_format($amount, 2).' recorded successfully!');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,deposit_paid,confirmed,completed,cancelled',
            'payment_status' => 'nullable|in:unpaid,deposit_paid,fully_paid,refunded',
            'admin_notes' => 'nullable|string|max:2000',
            'total_price' => 'nullable|numeric|min:0',
        ]);

        // Guard against invalid state transitions (e.g. completing a cancelled
        // booking or re-activating a completed one without going through pending).
        $allowedTransitions = [
            Booking::STATUS_PENDING => [Booking::STATUS_APPROVED, Booking::STATUS_DEPOSIT_PAID, Booking::STATUS_CONFIRMED, Booking::STATUS_CANCELLED],
            Booking::STATUS_APPROVED => [Booking::STATUS_PENDING, Booking::STATUS_DEPOSIT_PAID, Booking::STATUS_CONFIRMED, Booking::STATUS_CANCELLED],
            Booking::STATUS_DEPOSIT_PAID => [Booking::STATUS_CONFIRMED, Booking::STATUS_CANCELLED],
            Booking::STATUS_CONFIRMED => [Booking::STATUS_COMPLETED, Booking::STATUS_CANCELLED],
            Booking::STATUS_COMPLETED => [],
            Booking::STATUS_CANCELLED => [Booking::STATUS_PENDING],
        ];

        $newStatus = $validated['status'];

        if ($newStatus !== $booking->status
            && ! in_array($newStatus, $allowedTransitions[$booking->status] ?? [], true)) {
            return back()->withErrors(['status' => "Cannot change status from '{$booking->status}' to '{$newStatus}'."]);
        }

        $oldStatus = $booking->status;

        if (isset($validated['total_price']) && $validated['total_price'] != $booking->total_price) {
            if ((float) $validated['total_price'] < (float) $booking->deposit_paid) {
                return back()->withErrors(['total_price' => 'Total price cannot be lower than the amount already paid.']);
            }

            $booking->total_price = (float) $validated['total_price'];
            $booking->deposit_required = round(($booking->total_price * $booking->deposit_percentage) / 100, 2);
            $booking->balance_amount = max(0, $booking->total_price - $booking->deposit_paid);
        }

        $booking->update($validated);

        if ($oldStatus !== $booking->status) {
            try {
                Mail::to($booking->email)->send(new BookingStatusChangedMail($booking));
            } catch (\Exception $e) {
                Log::error('Failed sending booking status changed email: '.$e->getMessage());
            }
        }

        return back()->with('success', 'Booking details updated successfully!');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->route('admin.bookings.index')->with('success', 'Booking deleted!');
    }

    public function downloadItinerary(Booking $booking)
    {
        $pdf = Pdf::loadView('emails.itinerary-pdf', compact('booking'));

        return $pdf->download('Itinerary-'.$booking->booking_reference.'.pdf');
    }

    public function downloadInvoice(Booking $booking)
    {
        $pdf = Pdf::loadView('emails.invoice-pdf', compact('booking'));

        return $pdf->download('Invoice-'.$booking->booking_reference.'.pdf');
    }

    public function export()
    {
        $filename = 'Twina_Safaris_Bookings_'.date('Y_m_d').'.csv';
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Reference', 'Customer Name', 'Email', 'Phone', 'Country',
                'Tour / Safari', 'Travel Date', 'Adults', 'Children',
                'Total Price', 'Deposit Paid', 'Balance', 'Status', 'Payment Status', 'Created At',
            ]);

            // Stream rows one at a time instead of loading the whole table.
            Booking::with(['tour', 'safari'])->latest()->cursor()->each(function ($b) use ($file) {
                fputcsv($file, [
                    $this->csvSafe($b->booking_reference),
                    $this->csvSafe($b->full_name),
                    $this->csvSafe($b->email),
                    $this->csvSafe($b->phone),
                    $this->csvSafe($b->country),
                    $this->csvSafe($b->bookable_item->title ?? 'N/A'),
                    $b->travel_date ? $b->travel_date->format('Y-m-d') : '',
                    $b->number_of_adults,
                    $b->number_of_children,
                    $b->total_price,
                    $b->deposit_paid,
                    $b->balance_amount,
                    $b->status,
                    $b->payment_status,
                    $b->created_at->format('Y-m-d H:i'),
                ]);
            });
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Neutralize spreadsheet formula injection (CSV injection).
     */
    private function csvSafe(mixed $value): string
    {
        $value = (string) ($value ?? '');

        if ($value !== '' && in_array($value[0], ['=', '+', '-', '@', "\t", "\r"], true)) {
            return "'".$value;
        }

        return $value;
    }
}
