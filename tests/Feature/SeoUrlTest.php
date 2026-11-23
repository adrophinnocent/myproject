<?php

namespace Tests\Feature;

use App\Helpers\SeoHelper;
use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Destination;
use App\Models\Testimonial;
use App\Models\Tour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SeoUrlTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Match production so generated absolute URLs are stable in tests.
        config(['app.url' => 'https://twinasafaris.com']);
        $this->app['url']->forceRootUrl('https://twinasafaris.com');
        $this->app['url']->forceScheme('https');
    }

    private function makeTour(array $overrides = []): Tour
    {
        $category = Category::create(['name' => 'Safari', 'slug' => 'safari']);
        $destination = Destination::create(['name' => 'Serengeti', 'slug' => 'serengeti']);

        return Tour::create(array_merge([
            'title' => 'Luxury Serengeti Safari',
            'slug' => 'luxury-serengeti-safari',
            'category_id' => $category->id,
            'destination_id' => $destination->id,
            'short_description' => 'A perfect Serengeti experience.',
            'description' => 'Explore the Serengeti with experienced local guides.',
            'price' => 2500,
            'duration_days' => 5,
            'duration_nights' => 4,
            'is_published' => true,
            'is_featured' => true,
            'difficulty_level' => 'moderate',
            'accommodation_type' => 'Luxury Lodge',
            'departure_location' => 'Moshi',
        ], $overrides));
    }

    public function test_wrong_tour_type_is_301_redirected_to_the_real_type(): void
    {
        $this->makeTour();

        $response = $this->get('/tours/safari/luxury-serengeti-safari.html');

        $response->assertStatus(301);
        $response->assertRedirect('https://twinasafaris.com/tours/tour/luxury-serengeti-safari.html');
    }

    public function test_itinerary_alias_is_301_redirected_to_preferred_tour_url(): void
    {
        $this->makeTour();

        $response = $this->get('/itineraries/luxury-serengeti-safari.html');

        $response->assertStatus(301);
        $response->assertRedirect('https://twinasafaris.com/tours/tour/luxury-serengeti-safari.html');
    }

    public function test_legacy_itineraries_index_is_301_redirected_to_tours(): void
    {
        $this->get('/itineraries')->assertStatus(301)->assertRedirect('https://twinasafaris.com/tours');
    }

    public function test_unpublished_tour_cannot_be_viewed(): void
    {
        $this->makeTour(['is_published' => false, 'slug' => 'draft-safari']);

        $this->get('/tours/tour/draft-safari.html')->assertNotFound();
    }

    public function test_pagination_is_self_canonical_but_locale_is_dropped(): void
    {
        $response = $this->get('/?page=2&lang=fr');

        $response->assertOk();
        $response->assertSee('<link rel="canonical" href="https://twinasafaris.com/?page=2">', false);
    }

    #[DataProvider('localizedPageProvider')]
    public function test_language_parameter_always_canonicalizes_to_the_clean_primary_url(string $path, string $expected): void
    {
        $response = $this->get($path);

        $response->assertOk();
        $response->assertSee('<link rel="canonical" href="'.$expected.'">', false);
        $response->assertSee('hreflang="x-default" href="'.$expected.'"', false);
    }

    public static function localizedPageProvider(): array
    {
        return [
            ['/?lang=fr', 'https://twinasafaris.com/'],
            ['/about?lang=de', 'https://twinasafaris.com/about'],
            ['/tours?lang=zh', 'https://twinasafaris.com/tours'],
            ['/blog?lang=it', 'https://twinasafaris.com/blog'],
            ['/faqs?lang=nl', 'https://twinasafaris.com/faqs'],
            ['/destinations?lang=es', 'https://twinasafaris.com/destinations'],
        ];
    }

    public function test_language_pages_do_not_advertise_language_specific_hreflang(): void
    {
        $response = $this->get('/?lang=fr');

        $response->assertOk();
        $response->assertSee('hreflang="x-default"', false);
        foreach (SeoHelper::supportedLocales() as $locale) {
            $response->assertDontSee('hreflang="'.$locale.'"', false);
        }
    }

    public function test_default_language_parameter_is_301_redirected_to_the_clean_url(): void
    {
        $this->get('/?lang=en')
            ->assertStatus(301)
            ->assertRedirect('https://twinasafaris.com/');

        $this->get('/about?lang=en&page=2')
            ->assertStatus(301)
            ->assertRedirect('https://twinasafaris.com/about?page=2');
    }

    public function test_language_switcher_keeps_the_preference_without_indexable_language_urls(): void
    {
        $this->get('/lang/fr?redirect=/tours')
            ->assertRedirect('https://twinasafaris.com/tours');

        $response = $this->get('/tours');

        $response->assertOk();
        $this->assertSame('fr', app()->getLocale());
        // A session-only choice must never leak into canonical or hreflang.
        $response->assertSee('<link rel="canonical" href="https://twinasafaris.com/tours">', false);
        $response->assertDontSee('hreflang="fr"', false);
        $response->assertSee('href="https://twinasafaris.com/lang/fr?redirect=%2Ftours"', false);
    }

    public function test_www_requests_redirect_to_the_non_www_host_with_301(): void
    {
        $this->get('https://www.twinasafaris.com/about')
            ->assertStatus(301)
            ->assertRedirect('https://twinasafaris.com/about');

        $this->get('https://www.twinasafaris.com/tours?tour_type=safari')
            ->assertStatus(301)
            ->assertRedirect('https://twinasafaris.com/tours?tour_type=safari');
    }

    public function test_sitemap_exposes_only_x_default_and_cache_header(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/xml; charset=utf-8');
        $this->assertStringContainsString('max-age=3600', (string) $response->headers->get('Cache-Control'));
        $response->assertSee('xmlns:xhtml="http://www.w3.org/1999/xhtml"', false);
        $response->assertSee('hreflang="x-default" href="https://twinasafaris.com/"', false);
        foreach (SeoHelper::supportedLocales() as $locale) {
            $response->assertDontSee('hreflang="'.$locale.'"', false);
        }
        $response->assertDontSee('?lang=', false);
    }

    public function test_sitemap_is_well_formed_xml_with_correct_elements(): void
    {
        $response = $this->get('/sitemap.xml');
        $body = $response->getContent();

        $document = new \DOMDocument;
        $previous = libxml_use_internal_errors(true);
        $loaded = $document->loadXML($body, LIBXML_NONET);
        $errors = libxml_get_errors();
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $this->assertTrue($loaded, 'Sitemap is not well-formed: '.implode('; ', array_map(fn ($error) => trim($error->message), $errors)));

        $namespace = 'http://www.sitemaps.org/schemas/sitemap/0.9';
        $this->assertSame('urlset', $document->documentElement->localName);
        $this->assertSame($namespace, $document->documentElement->namespaceURI);

        $nodes = $document->getElementsByTagNameNS($namespace, 'url');
        $this->assertGreaterThan(0, $nodes->length);

        foreach ($nodes as $node) {
            $locs = $node->getElementsByTagNameNS($namespace, 'loc');
            $this->assertSame(1, $locs->length);
            $this->assertStringStartsWith('https://twinasafaris.com/', trim($locs->item(0)->textContent));

            $this->assertSame(1, $node->getElementsByTagNameNS($namespace, 'changefreq')->length);
            $this->assertSame(1, $node->getElementsByTagNameNS($namespace, 'priority')->length);
            $this->assertLessThanOrEqual(1, $node->getElementsByTagNameNS($namespace, 'lastmod')->length);

            $links = $node->getElementsByTagNameNS('http://www.w3.org/1999/xhtml', 'link');
            $this->assertSame(1, $links->length);
            $this->assertSame('x-default', $links->item(0)->getAttribute('hreflang'));
        }

        $this->assertStringNotContainsString('savannatrails.com', $body);

        foreach (BlogPost::LEGACY_BEST_TIME_SLUGS as $slug) {
            $this->assertStringNotContainsString($slug, $body);
        }
    }

    public function test_tour_schema_does_not_invent_a_rating_without_real_reviews(): void
    {
        $this->makeTour();

        $response = $this->get('/tours/tour/luxury-serengeti-safari.html');

        $response->assertOk();
        $this->assertStringNotContainsString('AggregateRating', $response->getContent());
        $this->assertStringNotContainsString('Sarah Mitchell', $response->getContent());
    }

    #[DataProvider('guidePageProvider')]
    public function test_planning_guides_target_real_search_queries(string $path, string $expectedText): void
    {
        $response = $this->get($path);

        $response->assertOk();
        $response->assertSee($expectedText);
        $response->assertSee('FAQPage', false);
        $response->assertSee('BreadcrumbList', false);
        $response->assertSee('<link rel="canonical" href="https://twinasafaris.com'.$path.'">', false);
    }

    public static function guidePageProvider(): array
    {
        return [
            ['/choose-safari-company', 'How to Choose a Tanzania Safari Company'],
            ['/tanzania-safari-cost', 'Tanzania Safari Cost'],
            ['/best-time-for-tanzania-safari', 'Best Time to Visit Tanzania for Safari'],
            ['/serengeti-safari', 'Serengeti Safari: Routes, Seasons and What to Expect'],
            ['/ngorongoro-tour', 'Ngorongoro Tour: Crater Safari Guide'],
            ['/kilimanjaro-trekking-company', 'Climb Kilimanjaro with a Local Moshi Trekking Team'],
            ['/zanzibar-holiday-packages', 'Zanzibar Holiday Packages: Beach, Culture and Safari Combinations'],
        ];
    }

    public function test_english_only_guide_does_not_advertise_untranslated_hreflang(): void
    {
        $response = $this->get('/tanzania-safari-cost?lang=fr');

        $response->assertOk();
        $response->assertSee('<link rel="canonical" href="https://twinasafaris.com/tanzania-safari-cost">', false);
        $response->assertSee('hreflang="x-default"', false);
        $response->assertDontSee('hreflang="fr"', false);
    }

    #[DataProvider('legacyBestTimeSlugProvider')]
    public function test_missing_legacy_best_time_articles_redirect_to_the_guide(string $slug): void
    {
        $response = $this->get('/blog/'.$slug.'.html');

        $response->assertStatus(301);
        $response->assertRedirect('https://twinasafaris.com/best-time-for-tanzania-safari');
    }

    public static function legacyBestTimeSlugProvider(): array
    {
        return [
            ['best-time-to-go-on-safari-in-tanzania'],
            ['best-time-to-visit-tanzania-for-an-incredible-safari'],
        ];
    }

    public function test_sitemap_lists_all_planning_guides(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        foreach (array_column(self::guidePageProvider(), 0) as $path) {
            $response->assertSee('https://twinasafaris.com'.$path, false);
        }
    }

    public function test_safari_cost_guide_uses_published_itinerary_prices(): void
    {
        $this->makeTour(['price' => 2500, 'duration_days' => 5]);

        $response = $this->get('/tanzania-safari-cost');

        $response->assertOk();
        $response->assertSee('Luxury Serengeti Safari');
        $response->assertSee('$2,500');
        $response->assertSee('$500');
    }

    public function test_homepage_trust_lines_are_plain_text_without_icons(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Based in Tanzania');
        $response->assertSee('Tailor-made itineraries');
        $response->assertSee('Clear written quotes');
        $response->assertSee('Responsible travel practices');
        $response->assertDontSee('📍');
        $response->assertDontSee('🧭');
        $response->assertDontSee('📝');
        $response->assertDontSee('🌿');
    }

    public function test_homepage_signature_expedition_is_cleanly_organised(): void
    {
        $this->makeTour([
            'title' => '8-Day Machame Route Kilimanjaro Trek',
            'slug' => '8-day-machame-route',
            'duration_days' => 8,
            'duration_nights' => 7,
            'short_description' => 'A scenic and well-paced route to the summit.',
            'inclusions' => ['Professional English-speaking mountain guide', 'All Kilimanjaro National Park fees'],
            'itinerary' => [
                ['title' => 'Day 1: Machame Gate to Machame Camp', 'description' => 'Drive to Machame Gate and trek to camp.'],
                ['title' => 'Day 2: Machame Camp to Shira Camp', 'description' => 'Trek through the moorland.'],
            ],
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Signature Expedition');
        $response->assertSee('A scenic and well-paced route to the summit.');
        $response->assertSee('What is included');
        $response->assertSee('Professional English-speaking mountain guide');
        $response->assertSee('Route summary');
        $response->assertSee('Machame Gate to Machame Camp');
        $response->assertSee('See full itinerary');
        $response->assertSee('BOOK THIS TOUR');
        $response->assertSee('TOUR DETAILS');
        $response->assertSee('EXPLORE ALL TOURS');
        $response->assertDontSee('Day 1: Day 1:');
        $response->assertDontSee('Day 1: Day 2:');
    }

    public function test_tour_itinerary_does_not_repeat_day_prefixes_or_start_at_zero(): void
    {
        $this->makeTour([
            'title' => '8-Day Machame Route Kilimanjaro Trek',
            'slug' => '8-day-machame-route-kilimanjaro-trek',
            'duration_days' => 8,
            'duration_nights' => 7,
            'itinerary' => [
                ['title' => 'Day 1: Machame Gate to Machame Camp', 'description' => 'Drive to the gate and trek to camp.'],
                ['title' => 'Day 2: Machame Camp to Shira Camp', 'description' => 'Trek through the moorland.'],
            ],
            'inclusions' => ['Professional English-speaking mountain guide', 'All Kilimanjaro National Park fees'],
        ]);

        $response = $this->get('/tours/tour/8-day-machame-route-kilimanjaro-trek.html');

        $response->assertOk();
        $response->assertSee('Route at a Glance');
        $response->assertSee('Machame Gate to Machame Camp');
        $response->assertSee('Day 1 of 2');
        $response->assertSee('Day 2 of 2');
        $response->assertSee('Professional English-speaking mountain guide');
        $response->assertDontSee('Day 1: Day 1:');
        $response->assertDontSee('Day 0:');
    }

    public function test_homepage_uses_published_testimonials_and_removes_fabricated_social_proof(): void
    {
        Testimonial::create([
            'name' => 'Amina Hassan',
            'location' => 'Arusha, Tanzania',
            'content' => 'A clear itinerary and a knowledgeable local guide.',
            'rating' => 4,
            'is_published' => true,
            'is_featured' => true,
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Amina Hassan');
        $response->assertSee('A clear itinerary and a knowledgeable local guide.');
        $response->assertSee('4.0 from 1 guest review');
        $response->assertDontSee('Sarah Mitchell');
        $response->assertDontSee('4.9/5');
        $response->assertDontSee('5.0/5');
        $response->assertDontSee('TripAdvisor Excellence');
        $response->assertDontSee('Verified Google Reviews');
    }
}
