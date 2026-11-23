<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\AdminNewTripPlanMail;
use App\Models\Destination;
use App\Models\Setting;
use App\Models\Tour;
use App\Models\TripPlan;
use App\Services\PdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TripPlanController extends Controller
{
    protected $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }

    public function index()
    {
        $destinations = Destination::active()->get();

        $inspiringTours = Tour::published()
            ->featured()
            ->with(['destination', 'category'])
            ->latest()
            ->get()
            ->take(3)
            ->map(function ($t) {
                $t->item_type = 'tour';

                return $t;
            });

        return view('public.pages.plan-my-trip', compact('destinations', 'inspiringTours'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'nationality' => 'required|string|max:100',
            'destination_ids' => 'nullable|array',
            'destination_ids.*' => 'integer|exists:destinations,id',
            'travel_style' => 'nullable|string|max:100',
            'budget_range' => 'nullable|string|max:100',
            'duration' => 'nullable|string|max:100',
            'accommodation_level' => 'nullable|string|max:100',
            'interests' => 'nullable|array',
            'interests.*' => 'string|max:100',
            'travel_date' => 'nullable|date|after:today',
            'adults' => 'required|integer|min:1|max:50',
            'children' => 'nullable|integer|min:0|max:50',
            'message' => 'nullable|string|max:5000',
        ]);

        $validated['status'] = TripPlan::STATUS_NEW;
        $validated['group_size'] = (int) $validated['adults'] + (int) ($validated['children'] ?? 0);

        $plan = TripPlan::create($validated);

        try {
            $adminEmail = Setting::publicEmail();
            Mail::to($adminEmail)->send(new AdminNewTripPlanMail($plan));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send trip plan admin mail: '.$e->getMessage());
        }

        return back()->with('success', 'Your trip request has been received! Our experts will contact you shortly.');
    }

    public function show($token)
    {
        $tripPlan = TripPlan::where('token', $token)->firstOrFail();

        // Track view
        if ($tripPlan->status === TripPlan::STATUS_SENT) {
            $tripPlan->update([
                'status' => TripPlan::STATUS_VIEWED,
                'viewed_at' => now(),
            ]);
        }

        return response()
            ->view('public.pages.trip-plan-view', compact('tripPlan'))
            ->header('X-Robots-Tag', 'noindex, nofollow, noarchive, nosnippet');
    }

    public function downloadPdf($token)
    {
        $tripPlan = TripPlan::where('token', $token)->firstOrFail();

        try {
            $pdfPath = $this->pdfService->generateItinerary($tripPlan);

            return response()
                ->download($pdfPath, 'Itinerary-TP'.$tripPlan->id.'.pdf')
                ->deleteFileAfterSend(true);
        } catch (\Throwable $e) {
            \Log::error('Trip Plan PDF Download failed: '.$e->getMessage());

            return back()->with('error', 'Sorry, we could not generate the PDF at this moment.');
        }
    }

    public function accept($token)
    {
        $tripPlan = TripPlan::where('token', $token)->firstOrFail();

        if (! in_array($tripPlan->status, [
            TripPlan::STATUS_SENT,
            TripPlan::STATUS_VIEWED,
            TripPlan::STATUS_CHANGES_REQUESTED,
        ], true)) {
            return back()->with('error', 'This trip plan can no longer be accepted in its current state.');
        }

        $tripPlan->update(['status' => TripPlan::STATUS_ACCEPTED, 'accepted_at' => now()]);

        return back()->with('success', 'Thank you! You have accepted the trip plan. Our team will contact you shortly to finalize the booking.');
    }

    public function requestChanges(Request $request, $token)
    {
        $request->validate(['message' => 'required|string|max:2000']);

        $tripPlan = TripPlan::where('token', $token)->firstOrFail();

        if (in_array($tripPlan->status, [
            TripPlan::STATUS_ACCEPTED,
            TripPlan::STATUS_DECLINED,
            TripPlan::STATUS_CANCELLED,
            TripPlan::STATUS_CLOSED,
        ], true)) {
            return back()->with('error', 'This trip plan can no longer be changed. Please contact our team.');
        }

        $tripPlan->update(['status' => TripPlan::STATUS_CHANGES_REQUESTED]);

        $tripPlan->messages()->create([
            'sender_type' => 'customer',
            'message' => $request->message,
        ]);

        return back()->with('success', 'Your request for changes has been sent. We will update the plan and get back to you.');
    }
}
