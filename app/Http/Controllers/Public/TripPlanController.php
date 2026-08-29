<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\TripPlan;
use App\Models\Tour;
use App\Services\PdfService;
use Illuminate\Http\Request;

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
            ->take(3)
            ->get();

        return view('public.pages.plan-my-trip', compact('destinations', 'inspiringTours'));
    }

    public function show($id)
    {
        // For security, we might want to use a UUID or hash, but for now ID is fine for MVP
        $tripPlan = TripPlan::findOrFail($id);

        // Track view
        if ($tripPlan->status === 'sent') {
            $tripPlan->update([
                'status' => 'viewed',
                'viewed_at' => now()
            ]);
        }

        return view('public.pages.trip-plan-view', compact('tripPlan'));
    }

    public function downloadPdf($id)
    {
        $tripPlan = TripPlan::findOrFail($id);

        try {
            $pdfPath = $this->pdfService->generateItinerary($tripPlan);
            return response()->download($pdfPath, 'Itinerary-TP' . $tripPlan->id . '.pdf');
        } catch (\Exception $e) {
            \Log::error('Trip Plan PDF Download failed: ' . $e->getMessage());
            return back()->with('error', 'Sorry, we could not generate the PDF at this moment.');
        }
    }

    public function accept($id)
    {
        $tripPlan = TripPlan::findOrFail($id);
        $tripPlan->update(['status' => 'accepted', 'accepted_at' => now()]);

        return back()->with('success', 'Thank you! You have accepted the trip plan. Our team will contact you shortly to finalize the booking.');
    }

    public function requestChanges(Request $request, $id)
    {
        $request->validate(['message' => 'required|string']);

        $tripPlan = TripPlan::findOrFail($id);
        $tripPlan->update(['status' => 'changes_requested']);

        $tripPlan->messages()->create([
            'sender_type' => 'customer',
            'message' => $request->message,
        ]);

        return back()->with('success', 'Your request for changes has been sent. We will update the plan and get back to you.');
    }
}
