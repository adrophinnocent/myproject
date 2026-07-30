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
            return redirect()->route('tours.index')->with('error', __('This tour package is no longer available.'));
        }

        return view('public.booking.create', compact('tour'));
    }

    public function store(Request $request, $slug)
    {
        $tour = Tour::withTrashed()->where('slug', $slug)->first()
                ?? \App\Models\Safari::withTrashed()->where('slug', $slug)->firstOrFail();

        if ($tour->trashed()) {
            return redirect()->route('tours.index')->with('error', __('This tour package is no longer available.'));
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

        // Ensure relationships are loaded for email templates
        $booking->load(['tour', 'safari']);

        // 1. Generate PDF Itinerary
        try {
            $pdfPath = $this->pdfService->generateItinerary($booking);
        } catch (\Exception $e) {
            \Log::error('PDF Generation failed: ' . $e->getMessage());
            $pdfPath = null;
        }

        // 2. Send Booking Confirmation to Customer (with PDF attachment)
        try {
            Mail::to($booking->email)->send(new CustomerBookingConfirmation($booking, $pdfPath));
        } catch (\Exception $e) {
            \Log::error('Customer Mail failed: ' . $e->getMessage());
        }

        // 3. Send Admin Alert
        try {
            $adminEmail = \App\Models\Setting::get('site_email', 'info@twinasafaris.com');
            Mail::to($adminEmail)->send(new AdminNewBookingNotification($booking));

            // WhatsApp Notification to Admin
            $whatsappPhone = \App\Models\Setting::get('site_phone', '255795482197');
            $apiKey = \App\Models\Setting::get('whatsapp_api_key', ''); // We will add this to settings

            if ($apiKey) {
                $message = "🚨 *NEW BOOKING ALERT!*%0A%0A"
                         . "*Ref:* " . $booking->booking_reference . "%0A"
                         . "*Client:* " . $booking->first_name . " " . $booking->last_name . "%0A"
                         . "*Tour:* " . ($booking->tour->title ?? $booking->safari->title ?? 'N/A') . "%0A"
                         . "*Travel Date:* " . $booking->travel_date->format('d M Y') . "%0A"
                         . "*Adults:* " . $booking->number_of_adults . "%0A"
                         . "*Total Price:* $" . number_format($booking->total_price, 2) . "%0A%0A"
                         . "View details: https://twinasafaris.com/admin/bookings/" . $booking->id;

                $url = "https://api.callmebot.com/whatsapp.php?phone=" . preg_replace('/\D/', '', $whatsappPhone) . "&text=" . $message . "&apikey=" . $apiKey;

                \Illuminate\Support\Facades\Http::get($url);
            }
        } catch (\Exception $e) {
            \Log::error('Admin Notifications failed: ' . $e->getMessage());
        }

        // 4. Create Admin Dashboard Notification
        try {
            AdminNotification::create([
                'type' => 'booking',
                'title' => 'New Booking: ' . $booking->booking_reference,
                'message' => 'New reservation from ' . $booking->first_name . ' for ' . ($booking->tour->title ?? $booking->safari->title ?? 'Tour'),
                'link' => route('admin.bookings.show', $booking),
                'is_read' => false
            ]);
        } catch (\Exception $e) {
            \Log::error('Admin Notification failed: ' . $e->getMessage());
        }

        return redirect()->route('booking.success', $booking->booking_reference);
    }

    public function success($reference)
    {
        $booking = Booking::where('booking_reference', $reference)->with(['tour', 'safari'])->firstOrFail();
        return view('public.booking.success', compact('booking'));
    }

    public function downloadItinerary($reference)
    {
        $booking = Booking::where('booking_reference', $reference)->with(['tour', 'safari'])->firstOrFail();

        try {
            $pdfPath = $this->pdfService->generateItinerary($booking);
            return response()->download($pdfPath, 'Itinerary-' . $booking->booking_reference . '.pdf');
        } catch (\Exception $e) {
            \Log::error('PDF Download failed: ' . $e->getMessage());
            return back()->with('error', 'Sorry, we could not generate the PDF at this moment. Please try again later.');
        }
    }
}
