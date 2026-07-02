<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\TripPlan;
use App\Models\Tour;
use Illuminate\Http\Request;

class TripPlanController extends Controller
{
    public function index()
    {
        $destinations = Destination::where('is_active', true)->orderBy('name')->get();

        $inspiringTours = Tour::where('is_published', true)
            ->where('is_featured', true)
            ->with(['destination', 'category'])
            ->latest()
            ->take(3)
            ->get();

        return view('public.pages.plan-my-trip', compact('destinations', 'inspiringTours'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'nationality' => 'nullable|string|max:100',
            'destination_ids' => 'nullable|array',
            'travel_style' => 'nullable|string|max:100',
            'budget_range' => 'nullable|string|max:100',
            'duration' => 'nullable|string|max:50',
            'accommodation_level' => 'nullable|string|max:100',
            'interests' => 'nullable|array',
            'travel_date' => 'nullable|date|after:today',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'message' => 'nullable|string|max:2000',
        ]);

        $validated['group_size'] = ($validated['adults'] ?? 1) + ($validated['children'] ?? 0);
        $validated['status'] = 'new';

        $plan = TripPlan::create($validated);

        // Notify Admin
        try {
            $adminEmail = \App\Models\Setting::get('site_email', 'info@twinasafaris.com');
            \Illuminate\Support\Facades\Mail::to($adminEmail)->send(new \App\Mail\AdminNewTripPlanMail($plan));

            \App\Models\AdminNotification::create([
                'type' => 'trip_plan',
                'title' => 'New Trip Plan Request',
                'message' => 'New request from ' . $plan->name,
                'link' => route('admin.trip-plans.show', $plan),
                'is_read' => false
            ]);
        } catch (\Exception $e) {
            \Log::error('Trip plan notification failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Thank you! We\'ll send you a personalized itinerary within 24 hours.');
    }
}
