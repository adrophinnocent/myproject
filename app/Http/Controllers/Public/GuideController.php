<?php

namespace App\Http\Controllers\Public;

use App\Helpers\Currency;
use App\Helpers\SeoHelper;
use App\Http\Controllers\Controller;
use App\Models\Safari;
use App\Models\Tour;
use Illuminate\View\View;

class GuideController extends Controller
{
    /**
     * The company-selection guide is temporarily unavailable. Keep the URL
     * registered so it reports 410 Gone rather than redirecting or falling
     * through to a generic 404.
     */
    public function chooseCompany(): never
    {
        abort(410);
    }

    public function safariCost(): View
    {
        $all = Tour::published()
            ->with('destination')
            ->get(['id', 'title', 'slug', 'price', 'duration_days', 'destination_id'])
            ->concat(Safari::published()
                ->with('destination')
                ->get(['id', 'title', 'slug', 'price', 'duration_days', 'destination_id']));

        $items = $all->filter(fn ($item) => $item->slug && (float) $item->price > 0)
            ->map(function ($item) {
                $price = (float) $item->price;
                $days = max(1, (int) $item->duration_days);

                return [
                    'title' => $item->title,
                    'path' => SeoHelper::preferredTourPath($item->slug),
                    'price' => $price,
                    'price_label' => Currency::format($price, 'USD', 0),
                    'days' => $days,
                    'per_day' => $price / $days,
                    'per_day_label' => Currency::format($price / $days, 'USD', 0),
                    'destination' => $item->destination?->name ?? 'Tanzania',
                ];
            })
            ->values();

        return view('public.guides.safari-cost', [
            'examples' => $items->sortBy('price')->take(8)->values(),
            'tourCount' => $items->count(),
            'minPrice' => $items->isNotEmpty() ? Currency::format((float) $items->min('price'), 'USD', 0) : null,
            'maxPrice' => $items->isNotEmpty() ? Currency::format((float) $items->max('price'), 'USD', 0) : null,
            'minPerDay' => $items->isNotEmpty() ? Currency::format((float) $items->min('per_day'), 'USD', 0) : null,
            'maxPerDay' => $items->isNotEmpty() ? Currency::format((float) $items->max('per_day'), 'USD', 0) : null,
        ]);
    }

    public function bestTime(): View
    {
        return view('public.guides.best-time');
    }

    public function serengeti(): View
    {
        return view('public.guides.serengeti');
    }

    public function ngorongoro(): View
    {
        return view('public.guides.ngorongoro');
    }

    public function kilimanjaro(): View
    {
        return view('public.guides.kilimanjaro');
    }

    public function zanzibar(): View
    {
        return view('public.guides.zanzibar');
    }
}
