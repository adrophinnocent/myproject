<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\CustomerBookingConfirmation;
use App\Mail\AdminNewBookingNotification;
use App\Models\Booking;
use App\Models\Tour;
use App\Models\AdminNotification;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    protected $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    public function create($slug)
    {
        $tour = Tour::withTrashed()->where('slug', $slug)->first()
                ?? \App\Models\Safari::withTrashed()->where('slug', $slug)->firstOrFail();

        if ($tour->trashed()) {
            return redirect()->route('tours.index')->with('error', 'This tour package is no longer available.');
        }

        return view('public.booking.create', compact('tour'));
    }

    public function store(Request $request, $slug)
    {
        $tour = Tour::withTrashed()->where('slug', $slug)->first()
                ?? \App\Models\Safari::withTrashed()->where('slug', $slug)->firstOrFail();

        if ($tour->trashed()) {
            return redirect()->route('tours.index')->with('error', 'This tour package is no longer available.');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'number_of_adults' => 'required|integer|min:1',
            'number_of_children' => 'nullable|integer|min:0',
            'travel_date' => 'required|date|after:today',
            'special_requests' => 'nullable|string',
            'accommodation_preference' => 'nullable|string',
            'payment_method' => 'required|in:paypal,bank_transfer',
        ]);

        $booking = new Booking($validated);

        if ($tour instanceof Tour) {
            $booking->tour_id = $tour->id;
        } else {
            $booking->safari_id = $tour->id;
        }

        $booking->status = 'pending';
        $booking->payment_status = 'unpaid';

        // Price calculation
        $totalGuests = $validated['number_of_adults'] + ($validated['number_of_children'] ?? 0);
        $booking->total_price = $tour->price * $totalGuests;

        $booking->save();

        // 1. Generate PDF Itinerary
        $pdfPath = $this->pdfService->generateItinerary($booking);

        // 2. Send Booking Confirmation to Customer (with PDF attachment)
        Mail::to($booking->email)->send(new CustomerBookingConfirmation($booking, $pdfPath));

        // 3. Send Admin Alert
        $adminEmail = \App\Models\Setting::get('site_email', 'info@twinasafaris.com');
        Mail::to($adminEmail)->send(new AdminNewBookingNotification($booking));

        // 4. Create Admin Dashboard Notification
        AdminNotification::create([
            'type' => 'booking',
            'title' => 'New Booking: ' . $booking->booking_reference,
            'message' => 'New reservation from ' . $booking->first_name . ' for ' . $tour->title,
            'link' => route('admin.bookings.show', $booking),
            'is_read' => false
        ]);

        return redirect()->route('booking.success', $booking->booking_reference);
    }

    public function success($reference)
    {
        $booking = Booking::where('booking_reference', $reference)->with(['tour', 'safari'])->firstOrFail();
        return view('public.booking.success', compact('booking'));
    }
}
