<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\BookingApproved;
use App\Mail\BookingRejected;
use App\Mail\PaymentConfirmation;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with('tour')->latest()->paginate(10);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'admin_notes' => 'nullable|string',
            'payment_status' => 'nullable|string',
        ]);

        $oldStatus = $booking->status;
        $oldPaymentStatus = $booking->payment_status;

        if ($request->status === 'confirmed' && $oldStatus !== 'confirmed') {
            $booking->confirmed_at = now();
            // Send approval email
            Mail::to($booking->email)->send(new BookingApproved($booking));
        } elseif ($request->status === 'cancelled' && $oldStatus !== 'cancelled') {
            $booking->cancelled_at = now();
            // Send rejection email
            Mail::to($booking->email)->send(new BookingRejected($booking));
        }

        $booking->update($validated);

        // Notify customer of status change
        if ($oldStatus !== $booking->status) {
            Mail::to($booking->email)->send(new \App\Mail\BookingStatusChangedMail($booking));
        }

        // Check if payment status changed to paid
        if ($request->payment_status === 'paid' && $oldPaymentStatus !== 'paid') {
            Mail::to($booking->email)->send(new PaymentConfirmation($booking));
        }

        return back()->with('success', 'Booking status updated successfully!');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->route('admin.bookings.index')->with('success', 'Booking deleted!');
    }

    public function downloadItinerary(Booking $booking)
    {
        $pdf = Pdf::loadView('emails.itinerary-pdf', compact('booking'));
        return $pdf->download('itinerary-' . $booking->booking_reference . '.pdf');
    }
}
