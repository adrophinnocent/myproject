<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Faq;
use App\Models\Safari;
use App\Models\Slider;
use App\Models\Testimonial;
use App\Models\Tour;

class HomeController extends Controller
{
    public function index()
    {
        $sliders = Slider::where('page', 'home')->active()->get();

        $featuredTours = Tour::published()
            ->featured()
            ->with(['category', 'destination'])
            ->latest()
            ->get()
            ->map(function ($t) {
                $t->item_type = 'tour';

                return $t;
            });

        $featuredSafaris = Safari::published()
            ->featured()
            ->with(['category', 'destination'])
            ->latest()
            ->get()
            ->map(function ($s) {
                $s->item_type = 'safari';

                return $s;
            });

        $featuredItems = $featuredTours->concat($featuredSafaris)->sortByDesc('created_at');

        // Ensure we always have a collection, even if empty
        if (! $featuredItems) {
            $featuredItems = collect();
        }

        // Fill up to 8 items so we have 2 complete 4-card rows on home page
        if ($featuredItems->count() < 8) {
            $needed = 8 - $featuredItems->count();

            $extraTours = Tour::published()
                ->whereNotIn('id', $featuredItems->where('item_type', 'tour')->pluck('id'))
                ->with(['category', 'destination'])
                ->latest()
                ->take($needed)
                ->get()
                ->map(function ($t) {
                    $t->item_type = 'tour';

                    return $t;
                });

            $featuredItems = $featuredItems->concat($extraTours);

            if ($featuredItems->count() < 8) {
                $needed = 8 - $featuredItems->count();
                $extraSafaris = Safari::published()
                    ->whereNotIn('id', $featuredItems->where('item_type', 'safari')->pluck('id'))
                    ->with(['category', 'destination'])
                    ->latest()
                    ->take($needed)
                    ->get()
                    ->map(function ($s) {
                        $s->item_type = 'safari';

                        return $s;
                    });
                $featuredItems = $featuredItems->concat($extraSafaris);
            }
        }

        $featuredTours = $featuredItems->take(8);

        $publishedTestimonials = Testimonial::published()->latest()->get();
        $testimonials = $publishedTestimonials->take(6);
        $testimonialCount = $publishedTestimonials->count();
        $testimonialAverage = (float) ($publishedTestimonials->avg('rating') ?? 0);

        $faqs = Faq::active()->take(10)->get();

        $latestPosts = BlogPost::published()
            ->with(['category', 'translations'])
            ->latest('published_at')
            ->take(3)
            ->get();

        // Find the specific "Hero Tour" Spotlight requested by user: christmas-new-year-tanzania-safari-2026-2027
        $heroTour = Tour::with(['category', 'destination', 'translations'])
                    ->where('slug', 'christmas-new-year-tanzania-safari-2026-2027')
                    ->first()
                    ?? Safari::with(['category', 'destination', 'translations'])
                    ->where('slug', 'christmas-new-year-tanzania-safari-2026-2027')
                    ->first()
                    ?? Tour::with(['category', 'destination', 'translations'])
                    ->where('slug', 'christmas-and-new-year-safari-in-tanzania-20262027')
                    ->first()
                    ?? Tour::with(['category', 'destination', 'translations'])
                    ->published()
                    ->featured()
                    ->first();
                    ?? Tour::with(['category', 'destination', 'translations'])
                    ->published()
                    ->featured()
                    ->first()
                    ?? Tour::with(['category', 'destination', 'translations'])
                    ->published()
                    ->first();

        return view('public.home', compact('sliders', 'featuredTours', 'testimonials', 'testimonialCount', 'testimonialAverage', 'faqs', 'latestPosts', 'heroTour'));
    }
}
