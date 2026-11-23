<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Destination;
use App\Models\Tour;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function test_pagination_and_locale_are_self_canonical(): void
    {
        $response = $this->get('/?page=2&lang=fr');

        $response->assertOk();
        $response->assertSee('<link rel="canonical" href="https://twinasafaris.com/?page=2&amp;lang=fr">', false);
    }

    public function test_sitemap_exposes_locale_alternates_and_cache_header(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $this->assertStringContainsString('max-age=3600', (string) $response->headers->get('Cache-Control'));
        $response->assertSee('xmlns:xhtml="http://www.w3.org/1999/xhtml"', false);
        $response->assertSee('hreflang="fr" href="https://twinasafaris.com/?lang=fr"', false);
    }

    public function test_tour_schema_does_not_invent_a_rating_without_real_reviews(): void
    {
        $this->makeTour();

        $response = $this->get('/tours/tour/luxury-serengeti-safari.html');

        $response->assertOk();
        $this->assertStringNotContainsString('AggregateRating', $response->getContent());
        $this->assertStringNotContainsString('Sarah Mitchell', $response->getContent());
    }
}
