<?php

namespace App\Http\Controllers\Public;

use App\Helpers\SeoHelper;
use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Destination;
use App\Models\Safari;
use App\Models\Setting;
use App\Models\Tour;
use App\Services\Markdown\HtmlToMarkdown;
use App\Support\AiDiscovery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

/**
 * Plain-Markdown representations of the public HTML pages.
 *
 * Nothing here is a new "AI product": it is the same published content in a
 * text format that agents can read without parsing HTML or JavaScript.
 */
class MarkdownController extends Controller
{
    private const GUIDES = [
        'tanzania-safari-cost' => 'safariCost',
        'best-time-for-tanzania-safari' => 'bestTime',
        'serengeti-safari' => 'serengeti',
        'ngorongoro-tour' => 'ngorongoro',
        'kilimanjaro-trekking-company' => 'kilimanjaro',
        'zanzibar-holiday-packages' => 'zanzibar',
    ];

    public function __construct(private readonly HtmlToMarkdown $markdown) {}

    public function tours(): Response
    {
        $site = SeoHelper::siteUrl();
        $lines = [
            '# Safari and trekking packages | Twina Safaris',
            '',
            '> Indicative prices are in USD until confirmed in writing by Twina Safaris.',
            '',
            'Canonical page: '.$site.'/tours',
            '',
        ];

        $items = Tour::published()->with(['category', 'destination'])->orderBy('title')->get()
            ->concat(Safari::published()->with(['category', 'destination'])->orderBy('title')->get());

        foreach ($items as $item) {
            $path = AiDiscovery::markdownUrl($site.SeoHelper::preferredTourPath($item->slug));
            $duration = (int) $item->duration_days > 0 ? (int) $item->duration_days.' days' : 'duration on request';
            $price = (float) $item->price > 0 ? 'from USD '.number_format((float) $item->price, 0) : 'price on request';
            $destination = $item->destination?->name ?? 'Tanzania';

            $lines[] = '- ['.$item->title.']('.$path.') — '.$destination.', '.$duration.', '.$price.'.';
        }

        $lines[] = '';
        $lines[] = 'Categories: '.Category::where('is_active', true)->orderBy('name')->pluck('name')->implode(', ').'.';
        $lines[] = '';
        $lines[] = 'Contact: '.Setting::publicEmail().' | '.Setting::get('site_phone', '+255 795 482 197').'.';
        $lines[] = '';

        return $this->response(implode("\n", $lines), $site.'/tours');
    }

    public function tour(string $type, string $slug): Response|RedirectResponse
    {
        $slug = str_replace('.md', '', $slug);
        $tour = Tour::published()->where('slug', $slug)->first();
        $safari = $tour ? null : Safari::published()->where('slug', $slug)->first();
        $item = $tour ?? $safari ?? abort(404);
        $preferred = $tour ? 'tour' : 'safari';

        if ($type !== $preferred) {
            return redirect()->to(route('markdown.tour', ['type' => $preferred, 'slug' => $slug], false), 301);
        }

        return $this->renderTour($item);
    }

    /** Used by Accept negotiation on /tours/{slug}.html aliases. */
    public function tourBySlug(string $slug): Response
    {
        $slug = str_replace('.html', '', $slug);
        $item = Tour::published()->where('slug', $slug)->first()
            ?? Safari::published()->where('slug', $slug)->first()
            ?? abort(404);

        return $this->renderTour($item);
    }

    private function renderTour(Tour|Safari $item): Response
    {
        $site = SeoHelper::siteUrl();
        $path = SeoHelper::preferredTourPath($item->slug);
        $canonical = $site.$path;
        $summary = trim(strip_tags((string) $item->short_description));

        $lines = [
            '# '.$item->title,
            '',
            'Canonical page: '.$canonical,
            'Markdown: '.AiDiscovery::markdownUrl($canonical),
            '',
        ];

        if ($summary !== '') {
            $lines[] = $summary;
            $lines[] = '';
        }

        $lines[] = '## At a glance';
        $lines[] = '';
        $lines[] = '- Duration: '.((int) $item->duration_days > 0 ? (int) $item->duration_days.' days' : 'on request');

        $price = (float) $item->price > 0 ? 'USD '.number_format((float) $item->price, 0) : 'on request';
        $lines[] = '- Indicative price: '.$price.' (per person, confirm by email)';
        $lines[] = '- Destination: '.($item->destination?->name ?? 'Tanzania');
        $lines[] = '- Category: '.($item->category?->name ?? 'Safari');
        $lines[] = '- Departure: '.($item->departure_location ?: 'Moshi, Tanzania');

        if (! empty($item->duration_nights)) {
            $lines[] = '- Nights: '.(int) $item->duration_nights;
        }
        if ($item->difficulty_level) {
            $lines[] = '- Difficulty: '.$item->difficulty_level;
        }
        if ($item->accommodation_type) {
            $lines[] = '- Accommodation: '.$item->accommodation_type;
        }
        if (! empty($item->price_note)) {
            $lines[] = '- Price note: '.trim(strip_tags((string) $item->price_note));
        }
        $lines[] = '';

        $description = $this->markdown->convert($item->description);
        if ($description !== '') {
            $lines[] = '## Description';
            $lines[] = '';
            $lines[] = $description;
        }

        foreach ([
            'Highlights' => $item->highlights ?? [],
            'Included' => $item->inclusions ?? [],
            'Excluded' => $item->exclusions ?? [],
            'What to bring' => $item->what_to_bring ?? [],
            'Good to know' => $item->good_to_know ?? [],
        ] as $heading => $values) {
            $block = $this->bullets($values);
            if ($block !== '') {
                $lines[] = '## '.$heading;
                $lines[] = '';
                $lines[] = $block;
            }
        }

        $itinerary = $this->itinerary($item->itinerary ?? []);
        if ($itinerary !== '') {
            $lines[] = '## Itinerary';
            $lines[] = '';
            $lines[] = $itinerary;
        }

        $faqs = $this->qa($item->faqs ?? []);
        if ($faqs !== '') {
            $lines[] = '## Frequently asked questions';
            $lines[] = '';
            $lines[] = $faqs;
        }

        $lines[] = '## Booking and contact';
        $lines[] = '';
        $lines[] = '- Booking policies: '.$site.'/booking/policies';
        $lines[] = '- Request this itinerary: '.$site.'/custom-safari';
        $lines[] = '- Email: '.Setting::publicEmail();
        $lines[] = '- Phone / WhatsApp: '.Setting::get('site_phone', '+255 795 482 197');
        $lines[] = '';
        $lines[] = 'Prices and availability are indicative until Twina Safaris confirms them in writing.';
        $lines[] = '';

        return $this->response(implode("\n", $lines), $canonical);
    }

    public function destinations(): Response
    {
        $site = SeoHelper::siteUrl();
        $lines = [
            '# Destinations | Twina Safaris',
            '',
            'Canonical page: '.$site.'/destinations',
            '',
        ];

        foreach (Destination::active()->orderBy('sort_order')->get() as $destination) {
            $count = $destination->total_items;
            $lines[] = '- ['.$destination->name.']('.$site.'/destinations/'.$destination->slug.'.md)'
                .($destination->country ? ' — '.$destination->country : '')
                .' ('.$count.' published '.($count === 1 ? 'itinerary' : 'itineraries').').';
        }

        $lines[] = '';
        $lines[] = 'See the [full destination guide]('.$site.'/destinations) for seasons, routes and practical advice.';
        $lines[] = '';

        return $this->response(implode("\n", $lines), $site.'/destinations');
    }

    public function destination(string $slug): Response
    {
        $destination = Destination::active()->where('slug', $slug)->firstOrFail();
        $site = SeoHelper::siteUrl();
        $canonical = $site.'/destinations/'.$destination->slug;
        $name = $destination->translate('name') ?: $destination->name;

        $facts = DestinationController::FACTS[$destination->slug] ?? [
            'summary' => 'Explore '.$name.' with Twina Safaris and compare itineraries led by local guides.',
            'best_time' => 'Our local team can advise on the best season for your travel dates and interests.',
            'highlights' => ['Wildlife and scenery', 'Local expert guides', 'Tailor-made itineraries'],
            'getting_there' => 'Ask our team for the most practical route based on your arrival city and dates.',
            'guide' => ['label' => 'Plan a Custom Safari', 'url' => '/custom-safari'],
        ];

        $lines = [
            '# '.$name.' safari and tours',
            '',
            'Canonical page: '.$canonical,
            '',
            $facts['summary'],
            '',
            '## Best time to visit',
            '',
            $facts['best_time'],
            '',
            '## Highlights',
            '',
            $this->bullets($facts['highlights']),
        ];

        $description = $this->markdown->convert($destination->translate('description') ?: $destination->description);
        if ($description !== '') {
            $lines[] = '## About this destination';
            $lines[] = '';
            $lines[] = $description;
        }

        $lines[] = '## Getting there';
        $lines[] = '';
        $lines[] = $facts['getting_there'];
        $lines[] = '';

        $tours = Tour::published()->where('destination_id', $destination->id)->with('destination')->orderBy('title')->get();
        $safaris = Safari::published()->where('destination_id', $destination->id)->orderBy('title')->get();

        if ($tours->isNotEmpty() || $safaris->isNotEmpty()) {
            $lines[] = '## Published itineraries';
            $lines[] = '';
            foreach ($tours->concat($safaris) as $item) {
                $url = $site.AiDiscovery::markdownUrl(SeoHelper::preferredTourPath($item->slug));
                $lines[] = '- ['.$item->title.']('.$url.')';
            }
            $lines[] = '';
        }

        $lines[] = 'More detail: ['.$facts['guide']['label'].']('.$site.$facts['guide']['url'].').';
        $lines[] = '';
        $lines[] = 'Contact: '.Setting::publicEmail().' | '.Setting::get('site_phone', '+255 795 482 197').'.';
        $lines[] = '';

        return $this->response(implode("\n", $lines), $canonical);
    }

    public function blogIndex(): Response
    {
        $site = SeoHelper::siteUrl();
        $lines = [
            '# Travel blog | Twina Safaris',
            '',
            'Canonical page: '.$site.'/blog',
            '',
        ];

        foreach (BlogPost::published()->latest('published_at')->get(['slug', 'title', 'excerpt']) as $post) {
            $lines[] = '- ['.$post->title.']('.$site.'/blog/'.$post->slug.'.md)'
                .($post->excerpt ? ' — '.trim(strip_tags($post->excerpt)) : '').'.';
        }

        $lines[] = '';
        return $this->response(implode("\n", $lines), $site.'/blog');
    }

    public function blog(string $slug): Response|RedirectResponse
    {
        $slug = str_replace('.md', '', $slug);

        if (in_array($slug, BlogPost::LEGACY_BEST_TIME_SLUGS, true)) {
            return redirect()->to('/best-time-for-tanzania-safari.md', 301);
        }

        $post = BlogPost::with(['author', 'category'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        $site = SeoHelper::siteUrl();
        $canonical = $site.'/blog/'.$post->slug.'.html';
        $lines = [
            '# '.$post->title,
            '',
            'Canonical page: '.$canonical,
        ];

        if ($post->published_at) {
            $lines[] = 'Published: '.$post->published_at->toDateString();
        }
        if ($post->category) {
            $lines[] = 'Category: '.$post->category->name;
        }
        $lines[] = '';

        if ($post->excerpt) {
            $lines[] = trim(strip_tags($post->excerpt));
            $lines[] = '';
        }

        $content = $this->markdown->convert($post->content);
        if ($content !== '') {
            $lines[] = $content;
        }

        $tags = is_array($post->tags) ? array_filter($post->tags, 'is_scalar') : [];
        if ($tags !== []) {
            $lines[] = 'Tags: '.implode(', ', array_map('strval', $tags)).'.';
            $lines[] = '';
        }

        $lines[] = 'Contact: '.Setting::publicEmail().' | '.Setting::get('site_phone', '+255 795 482 197').'.';
        $lines[] = '';

        return $this->response(implode("\n", $lines), $canonical);
    }

    public function guide(string $slug): Response
    {
        $method = self::GUIDES[$slug] ?? abort(404);
        $view = app(GuideController::class)->{$method}();
        $rendered = $view->render();

        preg_match('/<title>(.*?)<\/title>/s', $rendered, $titleMatch);
        $title = html_entity_decode($titleMatch[1] ?? 'Twina Safaris guide', ENT_QUOTES, 'UTF-8');

        $canonical = SeoHelper::siteUrl().'/'.$slug;
        $body = $this->markdown->convert($this->markdown->mainContent($rendered));

        $lines = [
            '# '.trim($title),
            '',
            'Canonical page: '.$canonical,
            '',
            $body,
            'Contact: '.Setting::publicEmail().' | '.Setting::get('site_phone', '+255 795 482 197').'.',
            '',
        ];

        return $this->response(implode("\n", $lines), $canonical);
    }

    private function bullets(mixed $values): string
    {
        if (is_string($values)) {
            $values = trim($values) === '' ? [] : (preg_split('/\R/u', strip_tags($values)) ?: []);
        }
        if (! is_array($values)) {
            return '';
        }

        $lines = '';
        foreach ($values as $value) {
            if (is_array($value)) {
                $label = (string) ($value['title'] ?? $value['name'] ?? $value['question'] ?? '');
                $detail = (string) ($value['description'] ?? $value['answer'] ?? $value['text'] ?? '');
                $line = trim($label);
                if ($detail !== '') {
                    $line .= ' — '.trim(strip_tags($detail));
                }
            } else {
                $line = trim(strip_tags((string) $value));
            }

            if ($line !== '') {
                $lines .= '- '.$line."\n";
            }
        }

        return $lines === '' ? '' : $lines."\n";
    }

    private function itinerary(mixed $days): string
    {
        if (! is_array($days)) {
            return '';
        }

        $out = '';
        $day = 1;
        foreach ($days as $entry) {
            if (is_array($entry)) {
                $heading = trim((string) ($entry['title'] ?? $entry['name'] ?? 'Day '.$day));
                $body = trim(strip_tags((string) ($entry['description'] ?? $entry['content'] ?? '')));
                // Some editors already prefix "Day N:"; do not repeat it.
                if (preg_match('/^day\s*\d+\s*:/i', $heading)) {
                    $heading = 'Day '.$day.': '.trim(preg_replace('/^day\s*\d+\s*:/i', '', $heading) ?? '');
                } elseif (! str_starts_with(strtolower($heading), 'day ')) {
                    $heading = 'Day '.$day.': '.$heading;
                }
            } else {
                $heading = 'Day '.$day;
                $body = trim(strip_tags((string) $entry));
            }

            $out .= '### '.$heading."\n\n";
            if ($body !== '') {
                $out .= $body."\n\n";
            }
            $day++;
        }

        return $out;
    }

    private function qa(mixed $faqs): string
    {
        if (! is_array($faqs)) {
            return '';
        }

        $out = '';
        foreach ($faqs as $faq) {
            if (! is_array($faq)) {
                continue;
            }
            $question = trim(strip_tags((string) ($faq['question'] ?? $faq['title'] ?? '')));
            $answer = trim(strip_tags((string) ($faq['answer'] ?? $faq['description'] ?? '')));
            if ($question === '') {
                continue;
            }
            $out .= '### '.$question."\n\n".$answer."\n\n";
        }

        return $out;
    }

    private function response(string $markdown, string $canonical): Response
    {
        $markdown = preg_replace("/[ \t]+\n/", "\n", $markdown) ?? $markdown;
        $markdown = preg_replace("/\n{3,}/", "\n\n", $markdown) ?? $markdown;
        $markdown = trim($markdown)."\n";

        return response($markdown, 200, [
            'Content-Type' => 'text/markdown; charset=utf-8',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'public, max-age=3600',
            'Link' => '<'.$canonical.'>; rel="canonical"',
        ]);
    }
}
