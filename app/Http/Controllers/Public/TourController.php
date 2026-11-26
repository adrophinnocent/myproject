<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Destination;
use App\Models\Faq;
use App\Models\Safari;
use App\Models\Tour;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class TourController extends Controller
{
    public function index(Request $request)
    {
        // This view is a duplicate of /destinations and was only reachable via
        // a query string. Send it to the canonical page instead of indexing it.
        if ($request->query('view') === 'destinations') {
            $query = $request->query();
            unset($query['view']);

            return redirect()->to('/destinations'.($query ? '?'.http_build_query($query) : ''), 301);
        }

        $tourQuery = Tour::where('is_published', true)->with(['category', 'destination']);
        $safariQuery = Safari::where('is_published', true)->with(['category', 'destination']);

        // The tour_type slug check used to run inside the closure, i.e. twice
        // (once per model query). Resolve it once; the filter itself is unchanged.
        $tourTypeIsDestination = $request->tour_type && ! is_numeric($request->tour_type)
            ? Destination::where('slug', $request->tour_type)->exists()
            : false;

        // Helper to apply filters to both queries
        $applyFilters = function ($query) use ($request, $tourTypeIsDestination) {
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
                    $query->whereHas('destination', function ($q) use ($request) {
                        $q->where('slug', $request->destination);
                    });
                }
            }

            $categoryParam = $request->category ?? $request->tour_type;
            if ($categoryParam) {
                if (is_numeric($categoryParam)) {
                    $query->where('category_id', $categoryParam);
                } else {
                    $query->whereHas('category', function ($q) use ($categoryParam) {
                        $q->where('slug', $categoryParam);
                    });
                }
            }

            // Special handling for 'tour_type' if it refers to a destination
            if ($tourTypeIsDestination) {
                $query->whereHas('destination', function ($q) use ($request) {
                    $q->where('slug', $request->tour_type);
                });
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
        $tours = $tourQuery->get()->map(function ($t) {
            $t->item_type = 'tour';

            return $t;
        });
        $safaris = $safariQuery->get()->map(function ($s) {
            $s->item_type = 'safari';

            return $s;
        });

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
        $paginatedItems = new LengthAwarePaginator(
            $combined->forPage($page, $perPage),
            $combined->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $tours = $paginatedItems;
        $categories = Category::where('is_active', true)->get();
        $destinations = Destination::where('is_active', true)->get();

        return view('public.tours.index', compact('tours', 'categories', 'destinations'));
    }

    public function show(Request $request, $type, $slug = null)
    {
        // If slug is null, it means we only got one parameter (e.g. /tours/serengeti.html)
        if ($slug === null) {
            $slug = str_replace('.html', '', $type);
        } else {
            $slug = str_replace('.html', '', $slug);
        }

        $tour = $this->publishedTour($slug) ?? abort(404);
        $preferredType = $this->tourType($tour);

        // Any /tours/{wrong-type}/... URL must 301 to the real model type,
        // otherwise the same itinerary is indexable under multiple URLs.
        if ($type !== $preferredType) {
            $target = route('tours.show', ['type' => $preferredType, 'slug' => $slug]);

            return redirect()->to($target.($request->getQueryString() ? '?'.$request->getQueryString() : ''), 301);
        }

        $request->attributes->set('seo_tour_type', $preferredType);

        $tour->load(['category', 'destination', 'images', 'translations', 'reviews' => function ($q) {
            $q->where('is_approved', true)->latest();
        }]);

        // Tour's aggregate accessors now reuse the loaded reviews relation.
        // Safari's model is outside this change's scope, so preload the two
        // aggregates once instead of letting the view trigger six queries.
        if ($tour instanceof Safari) {
            $tour->loadCount('reviews')->loadAvg('reviews', 'rating');
        }

        // Kept for view compatibility; currently unused by public.tours.show.
        $relatedTours = Tour::where('is_published', true)
            ->where('id', '!=', $tour->id)
            ->where(function ($q) use ($tour) {
                $q->where('category_id', $tour->category_id)
                    ->orWhere('destination_id', $tour->destination_id);
            })
            ->with(['translations'])
            ->take(4)
            ->get();

        $publishedFaqs = collect();
        try {
            if (Schema::hasColumn('faqs', 'tour_id')) {
                $publishedFaqs = Faq::where('is_active', true)
                    ->where(function ($q) use ($tour) {
                        if ($tour instanceof Tour) {
                            $q->where('tour_id', $tour->id);
                        }
                        if (! empty($tour->destination_id)) {
                            $q->orWhere('destination_id', $tour->destination_id);
                        }
                        if (! empty($tour->category_id)) {
                            $q->orWhere('category_id', $tour->category_id);
                        }
                    })
                    ->orderBy('order')
                    ->get();
            } else {
                $publishedFaqs = Faq::where('is_active', true)->orderBy('order')->get();
            }
        } catch (\Throwable $e) {
        }

        return view('public.tours.show', compact('tour', 'relatedTours', 'publishedFaqs'));
    }

    public function showItinerary(Request $request, $slug)
    {
        $cleanSlug = str_replace('.html', '', $slug);
        $tour = $this->publishedTour($cleanSlug) ?? abort(404);

        // The itineraries prefix is a legacy alias. Make it a real redirect so
        // Google consolidates ranking signals on the preferred /tours URL.
        $target = route('tours.show', [
            'type' => $this->tourType($tour),
            'slug' => $cleanSlug,
        ]);

        return redirect()->to($target.($request->getQueryString() ? '?'.$request->getQueryString() : ''), 301);
    }

    private function publishedTour(string $slug): ?object
    {
        return Safari::where('slug', $slug)->where('is_published', true)->first()
            ?? Tour::where('slug', $slug)->where('is_published', true)->first();
    }

    private function tourType(object $tour): string
    {
        return $tour instanceof Safari ? 'safari' : 'tour';
    }

    public function downloadPdf($slug)
    {
        $cleanSlug = str_replace('.html', '', $slug);
        $tour = $this->publishedTour($cleanSlug) ?? abort(404);

        try {
            $pdf = Pdf::loadView('emails.itinerary-pdf', [
                'booking' => null,
                'tripPlan' => null,
                'tour' => $tour,
            ])->setOption('isRemoteEnabled', true)->setOption('isHtml5ParserEnabled', true);

            $filename = 'Tour-Brochure-'.Str::slug($tour->title).'.pdf';

            return response($pdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            ]);
        } catch (\Exception $e) {
            Log::error('Tour PDF Brochure Generation failed: '.$e->getMessage());

            return back()->with('error', 'Sorry, we could not generate the PDF brochure at this moment.');
        }
    }
}
