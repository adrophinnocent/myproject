<?php

namespace App\Http\Controllers\Public;

use App\Helpers\SeoHelper;
use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Destination;
use App\Models\GalleryAlbum;
use App\Models\Safari;
use App\Models\Setting;
use App\Models\Tour;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class LlmsController extends Controller
{
    /**
     * A curated, plain-text map for AI assistants. This complements sitemap.xml;
     * it does not replace crawlable HTML or structured data.
     */
    public function __invoke(): Response
    {
        $site = SeoHelper::siteUrl();
        $name = Setting::get('site_name', 'Twina Safaris');
        $email = Setting::publicEmail();
        $phone = Setting::get('site_phone', '+255 795 482 197');

        $lines = [
            '# '.$name,
            '',
            '> Tanzania-based safari and trekking operator in Moshi. We design private and small-group itineraries in the Serengeti, Ngorongoro Crater, Tarangire, Lake Manyara, Mount Kilimanjaro and Zanzibar.',
            '> Prices shown on the website are indicative USD prices until confirmed by email. Contact: '.$phone.', '.$email.'.',
            '',
            '## Core pages',
            '',
            '- [Home]('.$site.'/): Overview of published journeys and planning guides.',
            '- [All safari packages]('.$site.'/tours): Compare routes, durations, prices and inclusions.',
            '- [Destinations]('.$site.'/destinations): Parks and regions covered by '.$name.'.',
            '- [About]('.$site.'/about): Who we are and how we work.',
            '- [Contact]('.$site.'/contact): Email, phone, WhatsApp and social profiles.',
            '- [Booking policies]('.$site.'/booking/policies): Deposit, balance and cancellation terms.',
            '- [Frequently asked questions]('.$site.'/faqs): Booking, safari and travel questions.',
            '- [Custom safari]('.$site.'/custom-safari): Request a tailor-made itinerary.',
            '',
            '## Published tours',
            '',
        ];

        foreach (Tour::published()->orderBy('title')->get(['slug', 'title', 'short_description', 'price', 'duration_days']) as $tour) {
            $summary = Str::of((string) $tour->short_description)->stripTags()->squish()->limit(160, '')->toString();
            $duration = (int) $tour->duration_days > 0 ? (int) $tour->duration_days.' days' : 'duration on request';
            $price = (float) $tour->price > 0 ? ' from $'.number_format((float) $tour->price, 0) : '';
            $lines[] = '- ['.$tour->title.']('.$site.'/tours/tour/'.$tour->slug.'.html): '.$duration.$price.'. '.($summary !== '' ? $summary : 'See the full itinerary and accommodation tiers.');
        }

        foreach (Safari::published()->orderBy('title')->get(['slug', 'title', 'short_description', 'price', 'duration_days']) as $safari) {
            $summary = Str::of((string) $safari->short_description)->stripTags()->squish()->limit(160, '')->toString();
            $duration = (int) $safari->duration_days > 0 ? (int) $safari->duration_days.' days' : 'duration on request';
            $price = (float) $safari->price > 0 ? ' from $'.number_format((float) $safari->price, 0) : '';
            $lines[] = '- ['.$safari->title.']('.$site.'/tours/safari/'.$safari->slug.'.html): '.$duration.$price.'. '.($summary !== '' ? $summary : 'See the full itinerary and inclusions.');
        }

        $lines[] = '';
        $lines[] = '## Destinations';
        $lines[] = '';

        foreach (Destination::active()->orderBy('sort_order')->get(['slug', 'name', 'country']) as $destination) {
            $lines[] = '- ['.$destination->name.']('.$site.'/destinations/'.$destination->slug.'): Safari itineraries, best season and practical route information'.($destination->country ? ' in '.$destination->country : '').'.';
        }

        $lines[] = '';
        $lines[] = '## Planning guides';
        $lines[] = '';

        foreach ([
            '/tanzania-safari-cost' => 'What affects safari prices and typical per-day costs.',
            '/best-time-for-tanzania-safari' => 'Month-by-month wildlife and weather guidance.',
            '/serengeti-safari' => 'Migration routes, parks and itinerary planning.',
            '/ngorongoro-tour' => 'Crater game drives, rules and route planning.',
            '/kilimanjaro-trekking-company' => 'Trekking routes, preparation and how to choose an operator.',
            '/zanzibar-holiday-packages' => 'Beach, culture and combined safari itineraries.',
        ] as $path => $description) {
            $lines[] = '- ['.$description.']('.$site.$path.')';
        }

        $lines[] = '';
        $lines[] = '## Blog';
        $lines[] = '';

        foreach (BlogPost::published()
            ->whereNotIn('slug', BlogPost::LEGACY_BEST_TIME_SLUGS)
            ->orderBy('published_at', 'desc')
            ->get(['slug', 'title']) as $post) {
            $lines[] = '- ['.$post->title.']('.$site.'/blog/'.$post->slug.'.html)';
        }

        $albums = GalleryAlbum::published()
            ->has('images')
            ->orderBy('sort_order')
            ->get(['slug', 'name']);

        if ($albums->isNotEmpty()) {
            $lines[] = '';
            $lines[] = '## Photo galleries';
            $lines[] = '';
            foreach ($albums as $album) {
                $lines[] = '- ['.$album->name.']('.$site.'/gallery/'.$album->slug.')';
            }
        }

        $lines[] = '';
        $lines[] = '## Policies';
        $lines[] = '';
        $lines[] = '- [Privacy policy]('.$site.'/privacy-policy)';
        $lines[] = '- [Terms of service]('.$site.'/terms-of-service)';
        $lines[] = '';

        return response(implode("\n", $lines), 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
            'Cache-Control' => 'public, max-age=3600',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
