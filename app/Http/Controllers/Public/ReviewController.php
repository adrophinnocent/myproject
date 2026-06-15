<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Tour;
use App\Models\Safari;
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
            'trip_date' => 'nullable|date',
            'country' => 'nullable|string|max:100',
        ]);

        $validated['is_approved'] = false; // Admin must moderate

        $review = Review::create($validated);

        // Create Admin Notification
        \App\Models\AdminNotification::create([
            'type' => 'review',
            'title' => 'New Review Submitted',
            'message' => "New review from {$review->name} for " . ($review->tour ? $review->tour->title : $review->safari->title),
            'link' => route('admin.reviews.index'),
            'is_read' => false
        ]);

        return back()->with('success', 'Thank you! Your review has been submitted and is awaiting moderation.');
    }
}
