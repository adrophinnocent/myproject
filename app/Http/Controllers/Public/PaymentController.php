<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private const MANUAL_GATEWAYS = ['bank_transfer', 'mpesa', 'tigopesa', 'airtelmoney'];

    public function show($token)
    {
        $booking = Booking::where('token', $token)
            ->with(['tour', 'safari', 'payments' => fn ($q) => $q->latest()])
            ->firstOrFail();

        // Bank details from settings
        $bankDetails = [
            'bank_name' => Setting::get('bank_name', 'CRDB Bank Plc / NMB Bank'),
            'account_name' => Setting::get('bank_account_name', 'Twina Safaris Limited'),
            'account_number' => Setting::get('bank_account_number', '0150999888777'),
            'swift_code' => Setting::get('bank_swift_code', 'CORUTZTZ'),
            'branch' => Setting::get('bank_branch', 'Moshi Branch, Tanzania'),
        ];

        $mobileMoney = [
            'mpesa' => Setting::get('mpesa_number', '+255 795 482 197'),
            'tigopesa' => Setting::get('tigopesa_number', '+255 795 482 197'),
            'airtelmoney' => Setting::get('airtelmoney_number', '+255 795 482 197'),
        ];

        return response()
            ->view('public.booking.payment', compact('booking', 'bankDetails', 'mobileMoney'))
            ->header('X-Robots-Tag', 'noindex, nofollow, noarchive, nosnippet');
    }

    public function processPayment(Request $request, $token)
    {
        $booking = Booking::where('token', $token)->firstOrFail();

        $validated = $request->validate([
            'gateway' => 'required|in:'.implode(',', self::MANUAL_GATEWAYS),
            'payment_type' => 'required|in:deposit,balance,full',
            'payer_name' => 'nullable|string|max:255',
            'payer_email' => 'nullable|email|max:255',
            'transaction_notes' => 'nullable|string|max:1000',
        ]);

        if (in_array($booking->status, [Booking::STATUS_CANCELLED, Booking::STATUS_COMPLETED], true)) {
            return back()->with('error', 'This booking can no longer receive payments.');
        }

        // Amounts are always calculated on the server. Never trust a hidden
        // "amount" field from the browser.
        $totalPrice = (float) $booking->total_price;
        $paidTotal = (float) $booking->payments()
            ->where('status', BookingPayment::STATUS_COMPLETED)
            ->sum('amount');

        $amountDue = match ($validated['payment_type']) {
            'deposit' => max(0, (float) $booking->calculated_deposit_required - $paidTotal),
            'full', 'balance' => max(0, $totalPrice - $paidTotal),
        };

        if ($amountDue <= 0) {
            return back()->with('info', 'This booking is already fully paid. No further payment is required.');
        }

        // Avoid duplicate pending submissions from repeated clicks.
        $pendingExists = $booking->payments()
            ->where('status', BookingPayment::STATUS_PENDING)
            ->exists();

        if ($pendingExists) {
            return back()->with('info', 'A payment is already awaiting verification. Our team will confirm it shortly.');
        }

        $payment = BookingPayment::create([
            'booking_id' => $booking->id,
            'gateway' => $validated['gateway'],
            'payment_type' => $validated['payment_type'],
            'amount' => $amountDue,
            'currency' => $booking->currency ?? 'USD',
            'status' => BookingPayment::STATUS_PENDING,
            'payer_name' => $validated['payer_name'] ?? $booking->full_name,
            'payer_email' => $validated['payer_email'] ?? $booking->email,
            'notes' => 'Manual payment submitted by customer. Awaiting admin verification. Note: '
                .($validated['transaction_notes'] ?? 'N/A'),
        ]);

        try {
            AdminNotification::create([
                'type' => 'payment_submitted',
                'title' => 'Payment Submitted: '.$booking->booking_reference,
                'message' => "Customer {$booking->full_name} submitted a {$payment->gateway} payment of "
                    .$booking->currency_symbol.number_format($amountDue, 2)
                    ." for booking {$booking->booking_reference}.",
                'link' => route('admin.bookings.show', $booking),
                'is_read' => false,
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed creating payment admin notification: '.$e->getMessage());
        }

        return redirect()->route('booking.status', $booking->token)
            ->with('success', 'Your payment submission has been received! Our team will verify the transaction and update your booking status.');
    }
}
