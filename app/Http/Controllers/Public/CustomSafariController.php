<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\CustomSafariInquiry;
use App\Models\AdminNotification;
use App\Models\Destination;
use Illuminate\Http\Request;

class CustomSafariController extends Controller
{
    public function create()
    {
        $destinations = Destination::active()->orderBy('name')->get();
        return view('public.custom-safari', compact('destinations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'children_ages' => 'nullable|string|max:255',
            'travel_date' => 'nullable|date',
            'flexible_dates' => 'nullable|boolean',
            'duration_days' => 'required|integer|min:1',
            'trip_type' => 'nullable|string|max:100',
            'accommodation_preference' => 'nullable|string|max:100',
            'budget_per_person' => 'nullable|string|max:100',
            'travel_style' => 'nullable|string|max:100',
            'group_type' => 'nullable|string|max:100',
            'activities' => 'nullable|array',
            'special_requests' => 'nullable|string',
        ]);

        $inquiry = CustomSafariInquiry::create([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'country' => $validated['country'] ?? null,
            'adults' => $validated['adults'],
            'children' => $validated['children'] ?? 0,
            'children_ages' => $validated['children_ages'] ?? null,
            'travel_date' => $validated['travel_date'] ?? null,
            'flexible_dates' => $request->has('flexible_dates') ? true : false,
            'duration_days' => $validated['duration_days'],
            'trip_type' => $validated['trip_type'] ?? 'Safari',
            'accommodation_preference' => $validated['accommodation_preference'] ?? null,
            'budget_per_person' => $validated['budget_per_person'] ?? null,
            'travel_style' => $validated['travel_style'] ?? null,
            'group_type' => $validated['group_type'] ?? null,
            'activities' => $validated['activities'] ?? [],
            'special_requests' => $validated['special_requests'] ?? null,
            'status' => CustomSafariInquiry::STATUS_NEW,
        ]);

        // Create Admin Dashboard Notification
        AdminNotification::create([
            'type' => 'inquiry',
            'title' => 'Custom Safari Request: ' . $inquiry->full_name,
            'message' => "New {$inquiry->duration_days}-day {$inquiry->trip_type} request from {$inquiry->full_name} ({$inquiry->adults} Adults, {$inquiry->children} Children)",
            'link' => route('admin.custom-inquiries.show', $inquiry),
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'Thank you! Your custom safari request has been submitted. One of our lead travel consultants will prepare a personalized itinerary proposal for you.');
    }
}
