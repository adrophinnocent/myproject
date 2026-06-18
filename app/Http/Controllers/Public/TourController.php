<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Destination;
use App\Models\Tour;
use Illuminate\Http\Request;

class TourController extends Controller
{
    public function index(Request $request)
    {
        $tourQuery = Tour::where('is_published', true)->with(['category', 'destination']);
        $safariQuery = \App\Models\Safari::where('is_published', true)->with(['category', 'destination']);

        // Helper to apply filters to both queries
        $applyFilters = function($query) use ($request) {
            if ($request->search) {
                $query->where(function ($q) use ($request) {
                    $q->where('title', 'LIKE', "%{$request->search}%")
                        ->orWhere('short_description', 'LIKE', "%{$request->search}%")
                        ->orWhere('description', 'LIKE', "%{$request->search}%");
                });
            }

            if ($request->destination) {
                if (is_numeric($request->destination)) {
                    $query->where('destination_id', $request->destination);
                } else {
                    $query->whereHas('destination', function($q) use ($request) {
                        $q->where('slug', $request->destination);
                    });
                }
            }

            $categoryParam = $request->category ?? $request->tour_type;
            if ($categoryParam) {
                if (is_numeric($categoryParam)) {
                    $query->where('category_id', $categoryParam);
                } else {
                    $query->whereHas('category', function($q) use ($categoryParam) {
                        $q->where('slug', $categoryParam);
                    });
                }
            }

            // Special handling for 'tour_type' if it refers to a destination
            if ($request->tour_type && !is_numeric($request->tour_type)) {
                $destExists = Destination::where('slug', $request->tour_type)->exists();
                if ($destExists) {
                    $query->whereHas('destination', function($q) use ($request) {
                        $q->where('slug', $request->tour_type);
                    });
                }
            }

            if ($request->difficulty) {
                $query->where('difficulty_level', $request->difficulty);
            }

            if ($request->duration) {
                [$min, $max] = explode('-', $request->duration) + [1 => 999];
                if ($max === '+') {
                    $query->where('duration_days', '>=', $min);
                } else {
                    $query->whereBetween('duration_days', [(int) $min, (int) $max]);
                }
            }
            return $query;
        };

        $tourQuery = $applyFilters($tourQuery);
        $safariQuery = $applyFilters($safariQuery);

        // Fetch all and merge
        $tours = $tourQuery->get()->map(function($t) { $t->item_type = 'tour'; return $t; });
        $safaris = $safariQuery->get()->map(function($s) { $s->item_type = 'safari'; return $s; });

        $combined = $tours->concat($safaris);

        switch ($request->sort) {
            case 'price_asc':
                $combined = $combined->sortBy('price');
                break;
            case 'price_desc':
                $combined = $combined->sortByDesc('price');
                break;
            case 'duration':
                $combined = $combined->sortBy('duration_days');
                break;
            case 'newest':
                $combined = $combined->sortByDesc('created_at');
                break;
            default:
                $combined = $combined->sortByDesc('is_featured')->values();
        }

        // Manual Pagination
        $page = $request->get('page', 1);
        $perPage = 9;
        $paginatedItems = new \Illuminate\Pagination\LengthAwarePaginator(
            $combined->forPage($page, $perPage),
            $combined->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $tours = $paginatedItems;
        $categories = Category::where('is_active', true)->get();
        $destinations = Destination::where('is_active', true)->get();

        if ($request->view === 'destinations') {
            return view('public.destinations.index', compact('destinations'));
        }

        return view('public.tours.index', compact('tours', 'categories', 'destinations'));
    }

    public function show(string $type, string $slug)
    {
        $model = ($type === 'safari') ? \App\Models\Safari::class : Tour::class;
        $tour = $model::where('slug', $slug)->firstOrFail();

        $tour->load(['category', 'destination', 'images', 'reviews']);

        $relatedTours = Tour::where('is_published', true)
            ->where('id', '!=', $tour->id)
            ->where(function ($q) use ($tour) {
                $q->where('category_id', $tour->category_id)
                    ->orWhere('destination_id', $tour->destination_id);
            })
            ->take(4)
            ->get()
            ->map(function($t) { $t->item_type = 'tour'; return $t; });

        return view('public.tours.show', compact('tour', 'relatedTours'));
    }
}
