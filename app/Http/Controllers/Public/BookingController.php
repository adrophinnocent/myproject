<?php

namespace App\Http\Controllers\Public;

use App\Helpers\Currency;
use App\Http\Controllers\Controller;
use App\Mail\AdminNewBookingNotification;
use App\Mail\CustomerBookingConfirmation;
use App\Models\AdminNotification;
use App\Models\Booking;
use App\Models\PackageRoomOption;
use App\Models\RoomType;
use App\Models\Safari;
use App\Models\Setting;
use App\Models\Tour;
use App\Models\TourPackage;
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

        // Present the estimate in the visitor's selected currency
        $currency = Currency::current();
        $symbol = Currency::symbol($currency);
        $decimals = in_array($currency, ['TZS', 'KES', 'UGX'], true) ? 0 : 2;

        $packages = TourPackage::where('tour_id', $tour->id)
            ->where('is_active', true)
            ->with(['itineraryDays.accommodation', 'roomOptions.roomType'])
            ->orderBy('sort_order')
            ->get();

        $roomTypes = RoomType::active()->get();

        $extras = [];
        foreach (array_values((array) ($tour->extras ?? [])) as $eIdx => $extra) {
            if (is_array($extra)) {
                $extra['id'] = 'ext_'.$eIdx;
                $extra['price'] = Currency::convert((float) ($extra['price'] ?? 0), $currency);
                $extras[] = $extra;
            } elseif (is_string($extra) && trim($extra) !== '') {
                // Legacy extras stored as plain names.
                $extras[] = [
                    'id' => 'ext_'.$eIdx,
                    'name' => trim($extra),
                    'price' => 0,
                    'price_type' => 'per_person',
                ];
            }
        }

        // Process Tier Parameter from URL if present
        $selectedTier = $this->applyTierPrice($tour, request('tier'));

        $selectedPackageId = old('tour_package_id', request('tour_package_id') ?? request('package_id'));
        $selectedRoomTypeId = old('room_type_id', request('room_type_id'));

        // Prices used by the live summary must match the server-side calculation.
        $packageOptions = $packages->map(function ($pkg) use ($currency) {
            $adultPrice = (float) ($pkg->selling_price ?: $pkg->adult_price ?: 0);

            return [
                'id' => $pkg->id,
                'adult_price' => Currency::convert($adultPrice, $currency),
                'child_price' => Currency::convert((float) ($pkg->child_price ?? ($adultPrice * 0.7)), $currency),
                'room_supplements' => $pkg->roomOptions->mapWithKeys(function ($option) use ($currency) {
                    return [$option->room_type_id => Currency::convert((float) $option->supplement_price, $currency)];
                })->all(),
            ];
        })->values()->all();

        $roomSupplements = $roomTypes->mapWithKeys(function ($roomType) use ($currency) {
            return [$roomType->id => Currency::convert((float) $roomType->default_supplement_price, $currency)];
        })->all();

        return view('public.booking.create', compact(
            'tour',
            'packages',
            'roomTypes',
            'currency',
            'symbol',
            'decimals',
            'extras',
            'selectedTier',
            'selectedPackageId',
            'selectedRoomTypeId',
            'packageOptions',
            'roomSupplements'
        ));
    }

    public function store(Request $request, $slug)
    {
        $tour = Tour::withTrashed()->where('slug', $slug)->first()
                ?? Safari::withTrashed()->where('slug', $slug)->firstOrFail();

        if ($tour->trashed()) {
            return redirect()->route('tours.index')->with('error', __('This tour package is no longer available.'));
        }

        // Keep the submitted price in sync with the tier shown on the booking page.
        $selectedTier = $this->applyTierPrice($tour, $request->input('tier'));

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
            'payment_method' => 'nullable|string|max:100',
            'tour_package_id' => 'nullable|exists:tour_packages,id',
            'room_type_id' => 'nullable|exists:room_types,id',
            'tier' => 'nullable|string|max:100',
            'selected_extras_qty' => 'nullable|array',
            'selected_extras_qty.*' => 'nullable|integer|min:0|max:50',
            'selected_extras' => 'nullable|array',
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
        $booking->selected_accommodation_tier = $selectedTier;

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

        // 1. Process Accommodation Package Selection
        $packageId = $request->input('tour_package_id') ?? $request->input('package_id');
        $selectedPackage = null;
        if ($packageId) {
            $selectedPackage = TourPackage::where('id', $packageId)->with('itineraryDays.accommodation')->first();
        }

        // 2. Process Room Type Selection & Room Supplement
        $roomTypeId = $request->input('room_type_id');
        $selectedRoomType = null;
        $roomSupplement = 0.00;
        if ($roomTypeId) {
            $selectedRoomType = RoomType::find($roomTypeId);
            if ($selectedRoomType) {
                $roomOption = $selectedPackage ? PackageRoomOption::where('tour_package_id', $selectedPackage->id)->where('room_type_id', $roomTypeId)->first() : null;
                $roomSupplement = $roomOption ? (float) $roomOption->supplement_price : (float) $selectedRoomType->default_supplement_price;
            }
        }

        $booking->tour_package_id = $selectedPackage?->id;
        $booking->package_name = $selectedPackage?->name;
        $booking->room_type_id = $selectedRoomType?->id;
        $booking->room_type_name = $selectedRoomType?->name;
        $booking->room_supplement_amount = $roomSupplement;

        if ($selectedPackage) {
            // Match the live estimate shown on the booking page: a package may
            // store its sellable rate in either selling_price or adult_price.
            $adultPrice = (float) ($selectedPackage->selling_price ?: $selectedPackage->adult_price ?: 0);
            $childPrice = (float) ($selectedPackage->child_price ?? ($adultPrice * 0.7));
            $booking->accommodation_preference = $selectedPackage->name;

            if ($selectedPackage->itineraryDays->count() > 0) {
                $hotelSummary = [];
                foreach ($selectedPackage->itineraryDays as $day) {
                    $hotelSummary[] = [
                        'day' => $day->day_number,
                        'title' => $day->title,
                        'hotel' => $day->accommodation?->name ?: 'Standard Lodge/Camp',
                        'category' => $day->accommodation?->category_level ?: 'Standard',
                    ];
                }
                $booking->selected_hotels_summary = $hotelSummary;
            }
        } else {
            $adultPrice = (float) $tour->price;
            $childPrice = (float) ($tour->child_price ?? ($tour->price * 0.7));
        }

        $basePrice = ($adultPrice * $adults) + ($childPrice * $children) + $roomSupplement;

        // Process selected optional extras (Supports Qty Map & List)
        $selectedExtras = [];
        $extrasTotal = 0.00;

        $tourExtras = array_values((array) ($tour->extras ?? []));
        $extrasQtyMap = $request->input('selected_extras_qty', []);
        $extrasList = (array) $request->input('selected_extras', []);

        foreach ($tourExtras as $eIdx => $tExtra) {
            if (! is_array($tExtra)) {
                continue;
            }
            $name = trim($tExtra['name'] ?? $tExtra['title'] ?? '');
            if (empty($name)) {
                continue;
            }

            $extId = 'ext_'.$eIdx;

            $qty = 0;
            if (isset($extrasQtyMap[$extId]) && (int) $extrasQtyMap[$extId] > 0) {
                $qty = (int) $extrasQtyMap[$extId];
            } elseif (isset($extrasQtyMap[$name]) && (int) $extrasQtyMap[$name] > 0) {
                $qty = (int) $extrasQtyMap[$name];
            } elseif (in_array($name, $extrasList) || in_array($extId, $extrasList)) {
                $qty = $totalGuests;
            }

            $priceType = $tExtra['price_type'] ?? 'per_person';
            // Never charge for more travellers than the booking actually has.
            $qty = ($priceType === 'per_group') ? 1 : min($qty, $totalGuests);

            if ($qty > 0) {
                $price = (float) ($tExtra['price'] ?? 0);
                $desc = $tExtra['description'] ?? '';

                // Extras are priced in USD; charge in the booking currency.
                $convertedPrice = Currency::convert($price, $booking->currency);
                $itemTotal = ($priceType === 'per_group') ? $convertedPrice : ($convertedPrice * $qty);
                $extrasTotal += $itemTotal;

                $selectedExtras[] = [
                    'id' => $extId,
                    'name' => $name,
                    'price' => $convertedPrice,
                    'price_type' => $priceType,
                    'quantity' => $qty,
                    'description' => $desc,
                    'item_total' => $itemTotal,
                ];
            }
        }

        $booking->selected_extras = $selectedExtras;
        $booking->extras_total = $extrasTotal;

        $totalPrice = round(Currency::convert($basePrice, $booking->currency) + $extrasTotal, 2);
        $booking->total_price = $totalPrice;

        // Public booking terms: pay 30% today, the rest 65 days before departure.
        $depositPct = Booking::DEPOSIT_PERCENTAGE;
        $booking->deposit_percentage = $depositPct;
        $depositRequired = round(($totalPrice * $depositPct) / 100, 2);
        $booking->deposit_required = $depositRequired;
        $booking->deposit_paid = 0.00;
        $booking->balance_amount = round(max(0, $totalPrice - $depositRequired), 2);

        if ($booking->travel_date) {
            $balanceDueDate = $booking->travel_date->copy()
                ->subDays(Booking::BALANCE_DUE_DAYS_BEFORE_DEPARTURE);

            // If departure is already inside the 65-day window, the balance is
            // due now rather than showing an impossible overdue date.
            if ($balanceDueDate->lt(now()->startOfDay())) {
                $balanceDueDate = now()->startOfDay();
            }

            $booking->balance_due_date = $balanceDueDate;
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

    /**
     * Apply the accommodation tier selected in the URL to the tour price so the
     * live estimate and the stored booking total always use the same figure.
     */
    private function applyTierPrice($tour, $tierId): ?array
    {
        if (! $tierId || empty($tour->accommodation_options) || ! is_array($tour->accommodation_options)) {
            return null;
        }

        foreach ($tour->accommodation_options as $option) {
            if (is_array($option) && ($option['id'] ?? '') === (string) $tierId) {
                if (! empty($option['price'])) {
                    $tour->price = (float) $option['price'];
                }

                return $option;
            }
        }

        return null;
    }
}
