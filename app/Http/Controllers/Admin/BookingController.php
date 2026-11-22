<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BookingApproved;
use App\Mail\BookingRejected;
use App\Mail\PaymentConfirmation;
use App\Mail\BookingStatusChangedMail;
use App\Models\Booking;
use App\Models\BookingPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $relations = ['tour', 'safari'];
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('booking_payments')) {
                $relations[] = 'payments';
            }
        } catch (\Throwable $e) {}

        $query = Booking::with($relations);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
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

    public function show($id)
    {
        $relations = ['tour', 'safari'];
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('booking_payments')) {
                $relations['payments'] = function($q) { $q->latest(); };
            }
        } catch (\Throwable $e) {}

        $booking = Booking::with($relations)
        ->where('id', $id)
        ->orWhere('booking_reference', $id)
        ->first();

        if (!$booking) {
            return redirect()->route('admin.bookings.index')->with('error', "Booking not found (ID/Ref: $id).");
        }

        return view('admin.bookings.show', compact('booking'));
    }

    public function approve(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $validated = $request->validate([
            'deposit_percentage' => 'nullable|numeric|min:5|max:100',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        if (isset($validated['deposit_percentage'])) {
            $booking->deposit_percentage = (float) $validated['deposit_percentage'];
            $booking->deposit_required = round(($booking->total_price * $booking->deposit_percentage) / 100, 2);
            $booking->balance_amount = max(0, $booking->total_price - $booking->deposit_paid);
        }

        if (!empty($validated['admin_notes'])) {
            $booking->admin_notes = $validated['admin_notes'];
        }

        $booking->status = Booking::STATUS_APPROVED;
        $booking->approved_at = now();
        $booking->save();

        // Send Approval Email with Payment Link
        try {
            Mail::to($booking->email)->send(new BookingApproved($booking));
        } catch (\Exception $e) {
            Log::error('Failed sending booking approval email: ' . $e->getMessage());
        }

        return back()->with('success', 'Booking approved! Payment link sent to customer email.');
    }

    public function reject(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

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
            Log::error('Failed sending booking rejection email: ' . $e->getMessage());
        }

        return back()->with('success', 'Booking request rejected and customer notified.');
    }

    public function recordPayment(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'gateway' => 'required|string',
            'payment_type' => 'required|in:deposit,balance,full',
            'notes' => 'nullable|string|max:500',
        ]);

        $payment = BookingPayment::create([
            'booking_id' => $booking->id,
            'transaction_reference' => 'TXN-' . strtoupper(\Illuminate\Support\Str::random(10)),
            'gateway' => $validated['gateway'],
            'payment_type' => $validated['payment_type'],
            'amount' => (float) $validated['amount'],
            'currency' => $booking->currency ?? 'USD',
            'status' => BookingPayment::STATUS_COMPLETED,
            'payer_name' => $booking->full_name,
            'payer_email' => $booking->email,
            'notes' => $validated['notes'] ?? 'Recorded manually by Admin',
            'paid_at' => now(),
        ]);

        $totalPaid = (float) $booking->payments()->where('status', BookingPayment::STATUS_COMPLETED)->sum('amount');
        $booking->deposit_paid = $totalPaid;
        $booking->balance_amount = max(0, (float)$booking->total_price - $totalPaid);

        if ($booking->balance_amount <= 0) {
            $booking->payment_status = Booking::PAYMENT_FULLY_PAID;
            $booking->status = Booking::STATUS_CONFIRMED;
            $booking->confirmed_at = now();
        } else {
            $booking->payment_status = Booking::PAYMENT_DEPOSIT_PAID;
            $booking->status = Booking::STATUS_DEPOSIT_PAID;
        }

        $booking->save();

        try {
            Mail::to($booking->email)->send(new PaymentConfirmation($booking));
        } catch (\Exception $e) {
            Log::error('Failed sending payment confirmation email: ' . $e->getMessage());
        }

        return back()->with('success', 'Payment of ' . $booking->currency_symbol . number_format($validated['amount'], 2) . ' recorded successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,approved,deposit_paid,confirmed,completed,cancelled',
            'payment_status' => 'nullable|in:unpaid,deposit_paid,fully_paid,refunded',
            'admin_notes' => 'nullable|string',
            'total_price' => 'nullable|numeric|min:0',
        ]);

        $oldStatus = $booking->status;

        if (isset($validated['total_price']) && $validated['total_price'] != $booking->total_price) {
            $booking->total_price = (float) $validated['total_price'];
            $booking->deposit_required = round(($booking->total_price * $booking->deposit_percentage) / 100, 2);
            $booking->balance_amount = max(0, $booking->total_price - $booking->deposit_paid);
        }

        $booking->update($validated);

        if ($oldStatus !== $booking->status) {
            try {
                Mail::to($booking->email)->send(new BookingStatusChangedMail($booking));
            } catch (\Exception $e) {
                Log::error('Failed sending booking status changed email: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Booking details updated successfully!');
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->route('admin.bookings.index')->with('success', 'Booking deleted!');
    }

    public function downloadItinerary($id)
    {
        $booking = Booking::findOrFail($id);
        $pdf = Pdf::loadView('emails.itinerary-pdf', compact('booking'));
        return $pdf->download('Itinerary-' . $booking->booking_reference . '.pdf');
    }

    public function downloadInvoice($id)
    {
        $booking = Booking::findOrFail($id);
        $pdf = Pdf::loadView('emails.invoice-pdf', compact('booking'));
        return $pdf->download('Invoice-' . $booking->booking_reference . '.pdf');
    }

    public function export()
    {
        $bookings = Booking::with(['tour', 'safari'])->latest()->get();

        $filename = "Twina_Safaris_Bookings_" . date('Y_m_d') . ".csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function() use ($bookings) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Reference', 'Customer Name', 'Email', 'Phone', 'Country',
                'Tour / Safari', 'Travel Date', 'Adults', 'Children',
                'Total Price', 'Deposit Paid', 'Balance', 'Status', 'Payment Status', 'Created At'
            ]);

            foreach ($bookings as $b) {
                fputcsv($file, [
                    $b->booking_reference,
                    $b->full_name,
                    $b->email,
                    $b->phone,
                    $b->country,
                    $b->bookable_item->title ?? 'N/A',
                    $b->travel_date ? $b->travel_date->format('Y-m-d') : '',
                    $b->number_of_adults,
                    $b->number_of_children,
                    $b->total_price,
                    $b->deposit_paid,
                    $b->balance_amount,
                    $b->status,
                    $b->payment_status,
                    $b->created_at->format('Y-m-d H:i')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
