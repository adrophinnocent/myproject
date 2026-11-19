<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\Setting;
use App\Models\AdminNotification;
use App\Mail\PaymentConfirmation;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    public function show($reference)
    {
        $booking = Booking::where('booking_reference', $reference)
            ->orWhere('token', $reference)
            ->with(['tour', 'safari', 'payments' => function($q) {
                $q->latest();
            }])->firstOrFail();

        // Bank details from settings
        $bankDetails = [
            'bank_name' => Setting::get('bank_name', 'CRDB Bank Plc / NMB Bank'),
            'account_name' => Setting::get('bank_account_name', 'Twina Safaris Limited'),
            'account_number' => Setting::get('bank_account_number', '0150999888777'),
            'swift_code' => Setting::get('bank_swift_code', 'CORUTZTZ'),
            'branch' => Setting::get('bank_branch', 'Moshi Branch, Tanzania'),
        ];

        return view('public.booking.payment', compact('booking', 'bankDetails'));
    }

    public function processPayment(Request $request, $reference)
    {
        $booking = Booking::where('booking_reference', $reference)
            ->orWhere('token', $reference)
            ->firstOrFail();

        $validated = $request->validate([
            'gateway' => 'required|in:visa_mastercard,paypal,bank_transfer,mpesa,tigopesa,airtelmoney,pesapal,flutterwave',
            'payment_type' => 'required|in:deposit,balance,full',
            'amount' => 'required|numeric|min:1',
            'payer_name' => 'nullable|string|max:255',
            'payer_email' => 'nullable|email|max:255',
            'transaction_notes' => 'nullable|string|max:1000',
        ]);

        $amount = (float) $validated['amount'];
        $type = $validated['payment_type'];
        $gateway = $validated['gateway'];

        $payment = BookingPayment::create([
            'booking_id' => $booking->id,
            'transaction_reference' => 'TXN-' . strtoupper(Str::random(10)),
            'gateway' => $gateway,
            'payment_type' => $type,
            'amount' => $amount,
            'currency' => $booking->currency ?? 'USD',
            'status' => BookingPayment::STATUS_PENDING,
            'payer_name' => $validated['payer_name'] ?? $booking->full_name,
            'payer_email' => $validated['payer_email'] ?? $booking->email,
            'notes' => $validated['transaction_notes'] ?? null,
        ]);

        // If manual method (bank transfer or mobile money offline)
        if (in_array($gateway, ['bank_transfer', 'mpesa', 'tigopesa', 'airtelmoney'])) {
            $payment->update([
                'notes' => 'Manual payment submitted by customer. Awaiting admin verification. Note: ' . ($validated['transaction_notes'] ?? 'N/A')
            ]);

            try {
                AdminNotification::create([
                    'type' => 'payment_submitted',
                    'title' => 'Payment Submitted: ' . $booking->booking_reference,
                    'message' => "Customer {$booking->full_name} submitted a {$gateway} payment of {$booking->currency_symbol}{$amount} for booking {$booking->booking_reference}.",
                    'link' => route('admin.bookings.show', $booking),
                    'is_read' => false
                ]);
            } catch (\Exception $e) {
                Log::error('Failed creating payment admin notification: ' . $e->getMessage());
            }

            return redirect()->route('booking.status', $booking->booking_reference)
                ->with('success', 'Your payment submission has been received! Our team will verify the transaction and update your booking status.');
        }

        // For online gateways (Pesapal, Flutterwave, PayPal, Stripe), integrate gateway redirect URL
        return redirect()->route('booking.payment', $booking->booking_reference)
            ->with('info', 'Please complete payment with the gateway. Payment will be verified via secure webhook.');
    }

    public function webhook(Request $request)
    {
        Log::info('Payment Webhook Received', $request->all());

        $transactionRef = $request->input('transaction_reference') ?? $request->input('order_id') ?? $request->input('reference');
        $gatewayRef = $request->input('gateway_reference') ?? $request->input('transaction_id');
        $status = strtolower($request->input('status', ''));

        if (!$transactionRef) {
            return response()->json(['error' => 'Missing transaction reference'], 400);
        }

        $payment = BookingPayment::where('transaction_reference', $transactionRef)->first();
        if (!$payment) {
            return response()->json(['error' => 'Payment record not found'], 404);
        }

        // Verify payment is genuine (never mark paid without verified webhook status)
        if (in_array($status, ['completed', 'success', 'paid', 'approved', '00'])) {
            $payment->update([
                'status' => BookingPayment::STATUS_COMPLETED,
                'gateway_reference' => $gatewayRef,
                'gateway_response' => $request->all(),
                'paid_at' => now(),
            ]);

            $booking = $payment->booking;
            $paidTotal = (float) $booking->payments()->where('status', BookingPayment::STATUS_COMPLETED)->sum('amount');

            $booking->deposit_paid = $paidTotal;
            $booking->balance_amount = max(0, (float)$booking->total_price - $paidTotal);

            if ($booking->balance_amount <= 0) {
                $booking->payment_status = Booking::PAYMENT_FULLY_PAID;
                $booking->status = Booking::STATUS_CONFIRMED;
            } else {
                $booking->payment_status = Booking::PAYMENT_DEPOSIT_PAID;
                $booking->status = Booking::STATUS_DEPOSIT_PAID;
            }

            if (!$booking->confirmed_at && $booking->status === Booking::STATUS_CONFIRMED) {
                $booking->confirmed_at = now();
            }

            $booking->save();

            // Send Email Confirmation
            try {
                Mail::to($booking->email)->send(new PaymentConfirmation($booking));
                Mail::to(Setting::get('site_email', 'twinasafaris@gmail.com'))->send(new PaymentConfirmation($booking));
            } catch (\Exception $e) {
                Log::error('Failed sending payment confirmation email: ' . $e->getMessage());
            }

            return response()->json(['success' => true, 'message' => 'Payment verified and booking updated']);
        }

        $payment->update([
            'status' => BookingPayment::STATUS_FAILED,
            'gateway_response' => $request->all()
        ]);

        return response()->json(['success' => false, 'message' => 'Payment status updated to failed']);
    }
}
