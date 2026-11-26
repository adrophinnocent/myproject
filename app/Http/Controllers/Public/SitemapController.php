<?php

namespace App\Http\Controllers\Public;

use App\Helpers\SeoHelper;
use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Destination;
use App\Models\GalleryAlbum;
use App\Models\Safari;
use App\Models\Tour;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $siteUrl = SeoHelper::siteUrl();

        // Static pages have no reliable content timestamp, so no lastmod is
        // emitted for them. Only content that actually changed gets one.
        $urls = [
            ['loc' => $siteUrl.'/', 'lastmod' => null, 'changefreq' => 'daily', 'priority' => '1.0'],
            ['loc' => $siteUrl.'/about', 'lastmod' => null, 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => $siteUrl.'/contact', 'lastmod' => null, 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => $siteUrl.'/tours', 'lastmod' => null, 'changefreq' => 'daily', 'priority' => '0.9'],
            ['loc' => $siteUrl.'/destinations', 'lastmod' => null, 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => $siteUrl.'/blog', 'lastmod' => null, 'changefreq' => 'daily', 'priority' => '0.8'],
            ['loc' => $siteUrl.'/faqs', 'lastmod' => null, 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => $siteUrl.'/gallery', 'lastmod' => null, 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => $siteUrl.'/custom-safari', 'lastmod' => null, 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => $siteUrl.'/booking/policies', 'lastmod' => null, 'changefreq' => 'yearly', 'priority' => '0.4'],
            ['loc' => $siteUrl.'/tanzania-safari-cost', 'lastmod' => null, 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => $siteUrl.'/best-time-for-tanzania-safari', 'lastmod' => null, 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => $siteUrl.'/serengeti-safari', 'lastmod' => null, 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => $siteUrl.'/ngorongoro-tour', 'lastmod' => null, 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => $siteUrl.'/kilimanjaro-trekking-company', 'lastmod' => null, 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => $siteUrl.'/zanzibar-holiday-packages', 'lastmod' => null, 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => $siteUrl.'/privacy-policy', 'lastmod' => null, 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['loc' => $siteUrl.'/terms-of-service', 'lastmod' => null, 'changefreq' => 'yearly', 'priority' => '0.3'],
        ];

        foreach (Tour::published()->latest()->get() as $tour) {
            if (! $tour->slug) {
                continue;
            }
            $urls[] = [
                'loc' => $siteUrl.'/tours/tour/'.$tour->slug.'.html',
                'lastmod' => $tour->updated_at?->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.9',
            ];
        }

        foreach (Safari::where('is_published', true)->latest()->get() as $safari) {
            if (! $safari->slug) {
                continue;
            }
            $urls[] = [
                'loc' => $siteUrl.'/tours/safari/'.$safari->slug.'.html',
                'lastmod' => $safari->updated_at?->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.9',
            ];
        }

        foreach (BlogPost::published()
            ->whereNotIn('slug', BlogPost::LEGACY_BEST_TIME_SLUGS)
            ->latest()
            ->get() as $blog) {
            if (! $blog->slug) {
                continue;
            }
            $urls[] = [
                'loc' => $siteUrl.'/blog/'.$blog->slug.'.html',
                'lastmod' => $blog->updated_at?->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }

        foreach (Destination::active()->orderBy('sort_order')->get() as $destination) {
            if (! $destination->slug) {
                continue;
            }
            $urls[] = [
                'loc' => $siteUrl.'/destinations/'.$destination->slug,
                'lastmod' => $destination->updated_at?->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ];
        }

        // Empty albums are user-facing placeholders, not useful indexable pages.
        foreach (GalleryAlbum::published()->has('images')->orderBy('sort_order')->get() as $album) {
            if (! $album->slug) {
                continue;
            }
            $urls[] = [
                'loc' => $siteUrl.'/gallery/'.$album->slug,
                'lastmod' => $album->updated_at?->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.5',
            ];
        }

        return response()->view('public.sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml; charset=utf-8')
            ->header('X-Content-Type-Options', 'nosniff')
            ->header('Cache-Control', 'public, max-age=3600');
    }
}
