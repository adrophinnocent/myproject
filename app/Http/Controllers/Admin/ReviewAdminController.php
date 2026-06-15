<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewAdminController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::with('tour')
            ->when($request->status === 'approved', fn ($q) => $q->where('is_approved', true))
            ->when($request->status === 'pending', fn ($q) => $q->where('is_approved', false))
            ->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => ! $review->is_approved]);

        return back()->with('success', 'Review '.($review->is_approved ? 'approved' : 'unapproved').'!');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('success', 'Review deleted.');
    }
}
