<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tour_id' => 'nullable|exists:tours,id',
            'safari_id' => 'nullable|exists:safaris,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'content' => 'required|string|min:10',
            'trip_date' => 'nullable|date|before_or_equal:today',
            'country' => 'nullable|string|max:100',
        ]);

        $validated['is_approved'] = false; // Admin must moderate

        $review = Review::create($validated);

        $itemTitle = $review->tour?->title ?? $review->safari?->title ?? 'Tanzania Safari';

        // Create Admin Notification
        AdminNotification::create([
            'type' => 'review',
            'title' => 'New Review Submitted',
            'message' => "New review from {$review->name} for {$itemTitle}",
            'link' => route('admin.reviews.index'),
            'is_read' => false,
        ]);

        return back()->with('success', __('Thank you! Your review has been submitted and is awaiting moderation.'));
    }
}
