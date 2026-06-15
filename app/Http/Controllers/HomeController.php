<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Testimonial;
use App\Models\Tour;
use App\Models\BlogPost;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = \App\Models\Slider::where('page', 'home')->active()->get();

        $featuredTours = Tour::where('is_published', true)
            ->where('is_featured', true)
            ->with(['category', 'destination'])
            ->latest()
            ->get()
            ->map(function($t) { $t->item_type = 'tour'; return $t; });

        $featuredSafaris = \App\Models\Safari::where('is_published', true)
            ->where('is_featured', true)
            ->with(['category', 'destination'])
            ->latest()
            ->get()
            ->map(function($s) { $s->item_type = 'safari'; return $s; });

        $featuredTours = $featuredTours->concat($featuredSafaris)->sortByDesc('created_at')->take(3);

        $allPublishedTours = Tour::where('is_published', true)->count() + \App\Models\Safari::where('is_published', true)->count();

        $destinations = Destination::where('is_active', true)
            ->withCount('tours')
            ->get();

        $testimonials = Testimonial::where('is_published', true)
            ->latest()
            ->take(6)
            ->get();

        $faqs = \App\Models\Faq::active()->take(10)->get();

        $categories = \App\Models\Category::where('is_active', true)->get();

        $latestPosts = BlogPost::published()
            ->with('category')
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('public.home', compact('sliders', 'featuredTours', 'allPublishedTours', 'destinations', 'testimonials', 'faqs', 'latestPosts', 'categories'));
    }
}
