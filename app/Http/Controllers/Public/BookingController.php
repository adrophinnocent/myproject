<?php

namespace App\Http\Controllers\Public;

use App\Helpers\Currency;
use App\Http\Controllers\Controller;
use App\Mail\AdminNewBookingNotification;
use App\Mail\CustomerBookingConfirmation;
use App\Models\AdminNotification;
use App\Models\Booking;
use App\Models\Safari;
use App\Models\Setting;
use App\Models\Tour;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
                ?? Safari::withTrashed()->where('slug', $slug)->firstOrFail();

        if ($tour->trashed()) {
            return redirect()->route('tours.index')->with('error', __('This tour package is no longer available.'));
        }

        // Present the estimate in the visitor's selected currency; the same
        // conversion is applied server-side in store().
        $currency = Currency::current();
        $symbol = Currency::symbol($currency);
        $decimals = in_array($currency, ['TZS', 'KES', 'UGX'], true) ? 0 : 2;

        $extras = [];
        foreach ((array) ($tour->extras ?? []) as $extra) {
            if (is_array($extra)) {
                $extra['price'] = Currency::convert((float) ($extra['price'] ?? 0), $currency);
            }
            $extras[] = $extra;
        }

        return view('public.booking.create', compact('tour', 'currency', 'symbol', 'decimals', 'extras'));
    }

    public function store(Request $request, $slug)
    {
        $tour = Tour::withTrashed()->where('slug', $slug)->first()
                ?? Safari::withTrashed()->where('slug', $slug)->firstOrFail();

        if ($tour->trashed()) {
            return redirect()->route('tours.index')->with('error', __('This tour package is no longer available.'));
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'number_of_adults' => 'required|integer|min:1|max:50',
            'number_of_children' => 'nullable|integer|min:0|max:50',
            'travel_date' => 'required|date|after:today',
            'special_requests' => 'nullable|string|max:2000',
            'accommodation_preference' => 'nullable|string|max:255',
            // Only manual payment methods are supported until a real gateway is integrated.
            'payment_method' => 'nullable|in:bank_transfer,mpesa,tigopesa,airtelmoney',
        ]);

        $booking = new Booking;
        $booking->first_name = $validated['first_name'];
        $booking->last_name = $validated['last_name'];
        $booking->email = $validated['email'];
        $booking->phone = $validated['phone'];
        $booking->country = $validated['country'] ?? $validated['nationality'] ?? 'International';
        $booking->nationality = $validated['nationality'] ?? $validated['country'] ?? 'International';
        $booking->number_of_adults = $validated['number_of_adults'];
        $booking->number_of_children = $validated['number_of_children'] ?? 0;
        $booking->travel_date = $validated['travel_date'];
        $booking->special_requests = $validated['special_requests'] ?? null;
        $booking->accommodation_preference = $validated['accommodation_preference'] ?? null;
        $booking->payment_method = $validated['payment_method'] ?? 'bank_transfer';
        $booking->currency = Currency::current();

        if ($tour instanceof Tour) {
            $booking->tour_id = $tour->id;
        } else {
            $booking->safari_id = $tour->id;
        }

        // Status & Financial Calculations
        $booking->status = Booking::STATUS_PENDING;
        $booking->payment_status = Booking::PAYMENT_UNPAID;

        $adults = (int) $booking->number_of_adults;
        $children = (int) $booking->number_of_children;
        $totalGuests = max(1, $adults + $children);

        // Base tour price per adult
        $adultPrice = (float) $tour->price;
        // Children price discount (70% of adult price if not set)
        $childPrice = (float) ($tour->child_price ?? ($tour->price * 0.7));

        $basePrice = ($adultPrice * $adults) + ($childPrice * $children);

        // Process selected optional extras
        $selectedExtras = [];
        $extrasTotal = 0.00;

        if ($request->has('selected_extras') && is_array($request->selected_extras)) {
            $tourExtras = is_array($tour->extras) ? $tour->extras : [];

            foreach ($request->selected_extras as $extraName) {
                foreach ($tourExtras as $tExtra) {
                    $name = is_array($tExtra) ? ($tExtra['name'] ?? $tExtra['title'] ?? '') : $tExtra;
                    if ($name === $extraName) {
                        $price = is_array($tExtra) ? (float) ($tExtra['price'] ?? 0) : 0;
                        $priceType = is_array($tExtra) ? ($tExtra['price_type'] ?? 'per_person') : 'per_person';
                        $desc = is_array($tExtra) ? ($tExtra['description'] ?? '') : '';

                        // Extras are priced in USD; charge in the booking currency.
                        $price = Currency::convert($price, $booking->currency);
                        $itemTotal = ($priceType === 'per_person') ? ($price * $totalGuests) : $price;
                        $extrasTotal += $itemTotal;

                        $selectedExtras[] = [
                            'name' => $name,
                            'price' => $price,
                            'price_type' => $priceType,
                            'description' => $desc,
                            'item_total' => $itemTotal,
                        ];
                        break;
                    }
                }
            }
        }

        $booking->selected_extras = $selectedExtras;
        $booking->extras_total = $extrasTotal;

        $totalPrice = round(Currency::convert($basePrice, $booking->currency) + $extrasTotal, 2);
        $booking->total_price = $totalPrice;

        // Deposit Percentage (20%)
        $depositPct = (float) Setting::get('deposit_percentage', 20.00);
        $booking->deposit_percentage = $depositPct;
        $booking->deposit_required = round(($totalPrice * $depositPct) / 100, 2);
        $booking->deposit_paid = 0.00;
        $booking->balance_amount = $totalPrice;

        if ($booking->travel_date) {
            $booking->balance_due_date = $booking->travel_date->copy()->subDays(60);
        }

        $booking->save();

        // Load relationships
        $booking->load(['tour', 'safari']);

        // 1. Generate PDF Itinerary (stored privately)
        $pdfPath = null;
        try {
            $pdfPath = $this->pdfService->generateItinerary($booking);
        } catch (\Throwable $e) {
            Log::error('PDF Generation failed: '.$e->getMessage());
        }

        // 2. Send Booking Confirmation email to Customer
        try {
            Mail::to($booking->email)->send(new CustomerBookingConfirmation($booking, $pdfPath));
        } catch (\Throwable $e) {
            Log::error('Customer Mail failed: '.$e->getMessage());
        } finally {
            // The PDF is an email attachment, not a public document.
            if ($pdfPath && file_exists($pdfPath)) {
                @unlink($pdfPath);
            }
        }

        // 3. Send Admin Alert Notification
        try {
            $adminEmail = Setting::publicEmail();
            Mail::to($adminEmail)->send(new AdminNewBookingNotification($booking));
        } catch (\Throwable $e) {
            Log::error('Admin Email Alert failed: '.$e->getMessage());
        }

        // 4. Create Admin Dashboard Notification
        try {
            AdminNotification::create([
                'type' => 'booking',
                'title' => 'New Booking Request: '.$booking->booking_reference,
                'message' => "New safari request from {$booking->full_name} for ".($booking->bookable_item->title ?? 'Safari')." ({$booking->formatted_total_price})",
                'link' => route('admin.bookings.show', $booking),
                'is_read' => false,
            ]);
        } catch (\Throwable $e) {
            Log::error('Admin Notification DB create failed: '.$e->getMessage());
        }

        return redirect()->route('booking.confirmation', $booking->token)
            ->with('success', __('Your booking request has been submitted successfully! Ref: ').$booking->booking_reference);
    }

    public function confirmation($token)
    {
        $booking = Booking::where('token', $token)
            ->with(['tour', 'safari', 'payments'])
            ->firstOrFail();

        return response()
            ->view('public.booking.confirmation', compact('booking'))
            ->header('X-Robots-Tag', 'noindex, nofollow, noarchive, nosnippet');
    }

    public function status($token)
    {
        $booking = Booking::where('token', $token)
            ->with(['tour', 'safari', 'payments' => fn ($q) => $q->latest()])
            ->firstOrFail();

        return response()
            ->view('public.booking.status', compact('booking'))
            ->header('X-Robots-Tag', 'noindex, nofollow, noarchive, nosnippet');
    }

    public function myBookings(Request $request)
    {
        $bookings = collect();
        $reference = trim((string) $request->input('reference', ''));
        $email = trim((string) $request->input('email', ''));

        if ($reference !== '' && $email !== '') {
            $bookings = Booking::where('booking_reference', strtoupper($reference))
                ->whereRaw('LOWER(email) = ?', [strtolower($email)])
                ->with(['tour', 'safari'])
                ->latest()
                ->get();
        }

        return response()
            ->view('public.booking.my-bookings', compact('bookings', 'reference', 'email'))
            ->header('X-Robots-Tag', 'noindex, nofollow, noarchive, nosnippet');
    }

    public function policies()
    {
        return view('public.booking.policies');
    }

    public function success($token)
    {
        return redirect()->route('booking.confirmation', $token);
    }

    public function downloadItinerary($token)
    {
        $booking = Booking::where('token', $token)->with(['tour', 'safari'])->firstOrFail();

        try {
            $pdfPath = $this->pdfService->generateItinerary($booking);

            return response()
                ->download($pdfPath, 'Itinerary-'.$booking->booking_reference.'.pdf')
                ->deleteFileAfterSend(true);
        } catch (\Throwable $e) {
            Log::error('PDF Download failed: '.$e->getMessage());

            return back()->with('error', 'Sorry, we could not generate the PDF at this moment. Please try again later.');
        }
    }
}
