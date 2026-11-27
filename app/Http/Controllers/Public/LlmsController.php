<?php

namespace App\Http\Controllers\Public;

use App\Helpers\SeoHelper;
use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Destination;
use App\Models\GalleryAlbum;
use App\Models\Safari;
use App\Models\Setting;
use App\Models\Tour;
use App\Support\AiDiscovery;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class LlmsController extends Controller
{
    /**
     * Curated, plain-text map for AI assistants (llms.txt convention).
     *
     * This complements crawlable HTML, the XML sitemap, JSON-LD and the
     * Markdown representations. It is not an MCP endpoint or an AI agent.
     */
    public function __invoke(): Response
    {
        $site = SeoHelper::siteUrl();
        $name = Setting::get('site_name', 'Twina Safaris');
        $email = Setting::publicEmail();
        $phone = Setting::get('site_phone', '+255 795 482 197');
        $whatsapp = preg_replace('/\D/', '', (string) Setting::get('site_whatsapp', '255795482197'));

        $lines = [
            '# '.$name,
            '',
            '> Tanzania-based safari and trekking operator based in Moshi, Kilimanjaro. '
                .'We design private and small-group itineraries in the Serengeti, Ngorongoro Crater, '
                .'Tarangire, Lake Manyara, Mount Kilimanjaro and Zanzibar.',
            '> Prices shown on the website are indicative USD prices until confirmed in writing by email. '
                .'Contact: '.$phone.', '.$email.'.',
            '',
            '## About '.$name,
            '',
            '- Locally based team in Moshi, Tanzania; itineraries led by experienced local guides.',
            '- Tailor-made safari, Kilimanjaro trekking and Zanzibar beach combinations.',
            '- Clear written quotes, transparent inclusions and responsible travel practices.',
            '- Official site: '.$site.'/',
            '',
            '## Machine-readable formats',
            '',
            '- Canonical site: '.$site.'/',
            '- XML sitemap: '.$site.'/sitemap.xml',
            '- Crawler policy: '.$site.'/robots.txt',
            '- Any public page can be requested with `Accept: text/markdown` or by adding `.md` to its URL.',
            '- [All safari packages (Markdown)]('.$site.'/tours.md)',
            '- [All destinations (Markdown)]('.$site.'/destinations.md)',
            '- [All blog articles (Markdown)]('.$site.'/blog.md)',
            '- [Safari-planning agent skill]('.$site.'/skills/twinasafaris-safari-planning/SKILL.md): '
                .'an Agent Skills (SKILL.md) file for planning trips from this public content.',
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
            '- [Photo gallery]('.$site.'/gallery): Images from our safaris and treks.',
            '',
        ];

        $categories = Category::active()->orderBy('sort_order')->orderBy('name')->get(['name', 'slug']);
        if ($categories->isNotEmpty()) {
            $lines[] = '## Tour categories';
            $lines[] = '';
            foreach ($categories as $category) {
                $lines[] = '- '.$category->name.': compare the published '
                    .Str::lower($category->name).' itineraries at '.$site.'/tours (filter: `category='.$category->slug.'`).';
            }
            $lines[] = '';
        }

        $lines[] = '## Published tours';
        $lines[] = '';

        foreach (Tour::published()->orderBy('title')->get(['slug', 'title', 'short_description', 'price', 'duration_days']) as $tour) {
            $lines[] = $this->tourLine($site, $tour, 'tour');
        }

        foreach (Safari::published()->orderBy('title')->get(['slug', 'title', 'short_description', 'price', 'duration_days']) as $safari) {
            $lines[] = $this->tourLine($site, $safari, 'safari');
        }

        $lines[] = '';
        $lines[] = '## Destinations';
        $lines[] = '';

        foreach (Destination::active()->orderBy('sort_order')->get(['slug', 'name', 'country']) as $destination) {
            $lines[] = '- ['.$destination->name.']('.$site.'/destinations/'.$destination->slug.') '
                .'[Markdown]('.$site.'/destinations/'.$destination->slug.'.md): '
                .'Safari itineraries, best season and practical route information'
                .($destination->country ? ' in '.$destination->country : '').'.';
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
            $lines[] = '- ['.$description.']('.$site.$path.') [Markdown]('.$site.$path.'.md)';
        }

        $lines[] = '';
        $lines[] = '## Blog';
        $lines[] = '';

        foreach (BlogPost::published()
            ->whereNotIn('slug', BlogPost::LEGACY_BEST_TIME_SLUGS)
            ->orderBy('published_at', 'desc')
            ->get(['slug', 'title']) as $post) {
            $lines[] = '- ['.$post->title.']('.$site.'/blog/'.$post->slug.'.html) '
                .'[Markdown]('.$site.'/blog/'.$post->slug.'.md)';
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
        $lines[] = '## Contact';
        $lines[] = '';
        $lines[] = '- Email: '.$email;
        $lines[] = '- Phone: '.$phone;
        if ($whatsapp !== '') {
            $lines[] = '- WhatsApp: https://wa.me/'.$whatsapp;
        }
        $lines[] = '- Address: Moshi, Kilimanjaro, Tanzania.';

        $socials = array_values(array_filter([
            Setting::get('facebook_url'),
            Setting::get('instagram_url'),
            Setting::get('youtube_url'),
            Setting::get('twitter_url'),
            Setting::get('tiktok_url'),
        ], fn ($url) => is_string($url) && str_starts_with($url, 'http')));
        foreach ($socials as $social) {
            $lines[] = '- Social profile: '.$social;
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

    private function tourLine(string $site, Tour|Safari $item, string $type): string
    {
        $summary = Str::of((string) $item->short_description)->stripTags()->squish()->limit(160, '')->toString();
        $duration = (int) $item->duration_days > 0 ? (int) $item->duration_days.' days' : 'duration on request';
        $price = (float) $item->price > 0 ? ' from $'.number_format((float) $item->price, 0) : '';
        $path = '/tours/'.$type.'/'.$item->slug;

        return '- ['.$item->title.']('.$site.$path.'.html): '.$duration.$price.'. '
            .($summary !== '' ? $summary : 'See the full itinerary and accommodation tiers.')
            .' [Markdown]('.$site.AiDiscovery::markdownUrl($site.$path.'.html').')';
    }
}
