<?php

namespace Tests\Feature;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Destination;
use App\Models\Tour;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AiReadinessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['app.url' => 'https://twinasafaris.com']);
        $this->app['url']->forceRootUrl('https://twinasafaris.com');
        $this->app['url']->forceScheme('https');
    }

    private function makeTour(): Tour
    {
        $category = Category::create(['name' => 'Safari', 'slug' => 'safari']);
        $destination = Destination::create([
            'name' => 'Serengeti', 'slug' => 'serengeti', 'is_active' => true,
        ]);

        return Tour::create([
            'title' => 'Luxury Serengeti Safari',
            'slug' => 'luxury-serengeti-safari',
            'category_id' => $category->id,
            'destination_id' => $destination->id,
            'short_description' => 'A perfect Serengeti experience.',
            'description' => '<h2>Why this trip</h2><p>Great wildlife with local guides.</p>',
            'price' => 2500,
            'duration_days' => 5,
            'duration_nights' => 4,
            'is_published' => true,
            'departure_location' => 'Moshi',
            'inclusions' => ['Park fees', 'Professional guide'],
            'itinerary' => [
                ['title' => 'Day 1: Arrive in Arusha', 'description' => 'Transfer to your lodge.'],
            ],
        ]);
    }

    public function test_robots_txt_is_dynamic_and_never_blocks_search_engines(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/plain; charset=utf-8');
        $body = (string) $response->getContent();

        $this->assertStringContainsString('User-agent: *', $body);
        $this->assertStringContainsString('Allow: /', $body);
        $this->assertStringContainsString('Sitemap: https://twinasafaris.com/sitemap.xml', $body);
        $this->assertStringNotContainsString('User-agent: Googlebot', $body);
        $this->assertStringNotContainsString('User-agent: Bingbot', $body);
        $this->assertStringNotContainsString('Disallow: /tours', $body);
        $this->assertStringNotContainsString('Disallow: /blog', $body);
    }

    public function test_ai_crawler_groups_keep_all_private_rules(): void
    {
        $body = (string) $this->get('/robots.txt')->getContent();
        $crawlers = config('ai.crawlers');

        $this->assertIsArray($crawlers);
        foreach ($crawlers as $crawler) {
            $this->assertStringContainsString('User-agent: '.$crawler, $body);
        }

        // One wildcard group plus one group per AI crawler.
        $this->assertSame(count($crawlers) + 1, substr_count($body, 'Disallow: /admin/'));
        $this->assertSame(count($crawlers) + 1, substr_count($body, 'Disallow: /booking/'));
        $this->assertSame(count($crawlers) + 1, substr_count($body, 'Allow: /booking/policies'));
    }

    public function test_tour_markdown_endpoint_returns_accurate_plain_text(): void
    {
        $this->makeTour();

        $response = $this->get('/tours/tour/luxury-serengeti-safari.md');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/markdown; charset=utf-8');
        $response->assertSee('# Luxury Serengeti Safari');
        $response->assertSee('Canonical page: https://twinasafaris.com/tours/tour/luxury-serengeti-safari.html', false);
        $response->assertSee('- Duration: 5 days', false);
        $response->assertSee('## Itinerary', false);
        $response->assertSee('### Day 1: Arrive in Arusha', false);
        $response->assertDontSee('<html', false);
        $response->assertHeader('Link', '<https://twinasafaris.com/tours/tour/luxury-serengeti-safari.html>; rel="canonical"');
    }

    public function test_wrong_tour_type_markdown_url_is_301_redirected(): void
    {
        $this->makeTour();

        $this->get('/tours/safari/luxury-serengeti-safari.md')
            ->assertStatus(301)
            ->assertRedirect('https://twinasafaris.com/tours/tour/luxury-serengeti-safari.md');
    }

    public function test_unpublished_tour_has_no_markdown_endpoint(): void
    {
        $this->makeTour()->update(['is_published' => false]);

        $this->get('/tours/tour/luxury-serengeti-safari.md')->assertNotFound();
    }

    public function test_accept_header_negotiates_markdown_without_changing_the_canonical_url(): void
    {
        $this->makeTour();

        $markdown = $this->get('/tours/tour/luxury-serengeti-safari.html', ['Accept' => 'text/markdown']);
        $markdown->assertOk();
        $markdown->assertHeader('Content-Type', 'text/markdown; charset=utf-8');
        $markdown->assertSee('# Luxury Serengeti Safari');

        $html = $this->get('/tours/tour/luxury-serengeti-safari.html', ['Accept' => 'text/html,application/xhtml+xml']);
        $html->assertOk();
        $html->assertHeader('Content-Type', 'text/html; charset=utf-8');
        $html->assertSee('<link rel="canonical" href="https://twinasafaris.com/tours/tour/luxury-serengeti-safari.html">', false);
    }

    public function test_html_pages_advertise_canonical_markdown_and_sitemap_via_link_headers(): void
    {
        $this->makeTour();

        $response = $this->get('/tours/tour/luxury-serengeti-safari.html');
        $header = (string) $response->headers->get('Link');

        $this->assertStringContainsString('<https://twinasafaris.com/tours/tour/luxury-serengeti-safari.html>; rel="canonical"', $header);
        $this->assertStringContainsString('<https://twinasafaris.com/tours/tour/luxury-serengeti-safari.md>; rel="alternate"; type="text/markdown"', $header);
        $this->assertStringContainsString('<https://twinasafaris.com/sitemap.xml>; rel="sitemap"', $header);
        $this->assertStringContainsString('Accept', (string) $response->headers->get('Vary'));

        $home = $this->get('/');
        $this->assertStringContainsString('<https://twinasafaris.com/llms.txt>', (string) $home->headers->get('Link'));
    }

    public function test_destination_and_listing_markdown_endpoints(): void
    {
        $this->makeTour();

        $destination = $this->get('/destinations/serengeti.md');
        $destination->assertOk();
        $destination->assertSee('# Serengeti safari and tours');
        $destination->assertSee('## Best time to visit', false);
        $destination->assertSee('/tours/tour/luxury-serengeti-safari.md', false);

        foreach (['/tours.md', '/destinations.md', '/blog.md'] as $path) {
            $this->get($path)->assertOk()->assertHeader('Content-Type', 'text/markdown; charset=utf-8');
        }
    }

    public function test_blog_markdown_converts_html_to_readable_markdown(): void
    {
        $category = BlogCategory::create(['name' => 'Guides', 'slug' => 'guides']);
        BlogPost::create([
            'title' => 'Planning Guide',
            'slug' => 'planning-guide',
            'category_id' => $category->id,
            'excerpt' => 'Practical planning advice.',
            'content' => '<h2>When to go</h2><p>Read our <a href="/best-time-for-tanzania-safari">season guide</a>.</p>',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/blog/planning-guide.md');

        $response->assertOk();
        $response->assertSee('# Planning Guide');
        $response->assertSee('## When to go', false);
        $response->assertSee('[season guide](/best-time-for-tanzania-safari)', false);
        $response->assertDontSee('<h2>', false);
    }

    public function test_travel_guide_markdown_endpoint_reuses_published_guide_content(): void
    {
        $response = $this->get('/serengeti-safari.md');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/markdown; charset=utf-8');
        $response->assertSee('Canonical page: https://twinasafaris.com/serengeti-safari', false);
        $response->assertSee('### How many days do I need for a Serengeti safari?', false);
        $response->assertSee('Contact:', false);
        $response->assertDontSee('<html', false);
    }

    public function test_llms_txt_is_current_and_links_machine_readable_content(): void
    {
        $this->makeTour();

        $response = $this->get('/llms.txt');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/plain; charset=utf-8');
        $response->assertSee('# Twina Safaris', false);
        $response->assertSee('## Tour categories', false);
        $response->assertSee('## Machine-readable formats', false);
        $response->assertSee('https://twinasafaris.com/tours.md', false);
        $response->assertSee('https://twinasafaris.com/skills/twinasafaris-safari-planning/SKILL.md', false);
        $response->assertSee('twinasafaris@gmail.com', false);
        $response->assertSee('+255 795 482 197', false);
        $response->assertDontSee('/booking/confirmation', false);
        $response->assertDontSee('/proposal/', false);
    }

    public function test_static_pages_have_webpage_and_breadcrumb_json_ld(): void
    {
        foreach (['/', '/about', '/contact', '/privacy-policy', '/terms-of-service', '/booking/policies', '/gallery', '/destinations'] as $path) {
            $page = $this->get($path);
            $page->assertOk();
            $body = (string) $page->getContent();
            preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $body, $matches);

            $this->assertNotEmpty($matches[1], $path.' has no structured data');

            $types = [];
            foreach ($matches[1] as $json) {
                $data = json_decode(trim($json), true);
                $this->assertSame(JSON_ERROR_NONE, json_last_error(), $path.' has invalid JSON-LD');
                $roots = array_is_list($data) ? $data : [$data];

                foreach ($roots as $root) {
                    $this->assertSame('https://schema.org', $root['@context'] ?? null, $path.' has the wrong schema context');
                    $nodes = is_array($root['@graph'] ?? null) ? $root['@graph'] : [$root];
                    foreach ($nodes as $node) {
                        $types[] = $node['@type'] ?? null;
                    }
                }
            }

            $this->assertContains('WebPage', $types, $path.' has no WebPage node');
            if ($path !== '/') {
                $this->assertContains('BreadcrumbList', $types, $path.' has no breadcrumb');
            }
        }
    }

    public function test_markdown_urls_are_not_advertised_as_separate_sitemap_pages(): void
    {
        $this->makeTour();

        $body = (string) $this->get('/sitemap.xml')->getContent();

        $this->assertStringNotContainsString('.md<', $body);
        $this->assertStringNotContainsString('.md</loc>', $body);
        $this->assertStringContainsString('https://twinasafaris.com/tours', $body);
    }

    public function test_agent_skill_is_spec_valid_and_read_only(): void
    {
        $path = public_path('skills/twinasafaris-safari-planning/SKILL.md');
        $this->assertFileExists($path);

        $contents = (string) file_get_contents($path);
        $this->assertMatchesRegularExpression('/^---\n(.*?)\n---\n/s', $contents, 'SKILL.md needs YAML frontmatter');

        preg_match('/^---\n(.*?)\n---\n/s', $contents, $frontmatter);
        $this->assertStringContainsString('name: twinasafaris-safari-planning', $frontmatter[1]);
        $this->assertStringContainsString('description:', $frontmatter[1]);
        $this->assertLessThanOrEqual(1024, strlen((string) preg_replace('/^description:\s*/m', '', $frontmatter[1])));
        $this->assertStringContainsString('llms.txt', $contents);
        $this->assertStringContainsString('Accept: text/markdown', $contents);
        $this->assertStringContainsString('There is no MCP endpoint', $contents);
    }

    public function test_no_mcp_card_or_catalog_is_published_without_a_real_endpoint(): void
    {
        $this->get('/.well-known/mcp.json')->assertNotFound();
        $this->get('/.well-known/ai-catalog.json')->assertNotFound();
        $this->get('/mcp')->assertNotFound();
    }
}
