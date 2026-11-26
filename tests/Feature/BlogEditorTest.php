<?php

namespace Tests\Feature;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BlogEditorTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['is_admin' => true]);
    }

    public function test_admin_editor_offers_all_required_blocks_preview_seo_and_upload(): void
    {
        $html = $this->actingAs($this->admin)
            ->get(route('admin.blog.create'))
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('window.blogStoryEditorConfig = {', $html);
        $this->assertStringContainsString('x-data="blogStoryEditor(window.blogStoryEditorConfig)"', $html);
        $this->assertStringNotContainsString('x-data="blogStoryEditor({"', $html);
        $this->assertStringContainsString('Live Preview', $html);
        $this->assertStringContainsString('Drag to reorder, or use the arrows.', $html);
        $this->assertStringContainsString('AUTO 3:2', $html);
        $this->assertStringContainsString('META TITLE', $html);
        $this->assertStringContainsString('META DESCRIPTION', $html);
        $this->assertStringContainsString('TAGS', $html);
        $this->assertStringContainsString('upload-image', $html);

        foreach ([
            'Kichwa Kikubwa', 'Kichwa Kidogo', 'Aya', 'Orodha ya ✓', 'Orodha ya •',
            'Hatua 1, 2, 3', 'Picha 3:2', 'Nukuu Kubwa', 'Kidokezo / Tahadhari / Taarifa',
            'Swali na Jibu', 'Kisanduku cha Kitufe', 'Mstari wa Mapambo',
        ] as $label) {
            $this->assertStringContainsString($label, $html);
        }

        foreach ([
            'article-check-list', 'article-bullet-list', 'article-steps', 'article-quote',
            'article-note--tip', 'article-note--warning', 'article-note--info',
            'article-faq', 'article-cta', 'article-divider',
        ] as $block) {
            $this->assertStringContainsString($block, $html);
        }
    }

    public function test_admin_can_publish_a_story_with_hero_caption_seo_tags_and_three_two_photos(): void
    {
        Storage::fake('public');

        $category = BlogCategory::create(['name' => 'Safari Stories', 'slug' => 'safari-stories']);
        $content = '<h2 class="article-heading-lg">Kilimanjaro</h2>'
            .'<p>Wildlife in the Serengeti.</p>'
            .'<ul class="article-check-list"><li>Passport</li><li>Vaccination</li></ul>'
            .'<ol class="article-steps"><li><span class="article-step-num">1</span>Book</li></ol>'
            .'<figure class="image article-photo"><img src="/storage/blog/content/lion.webp" alt="Lion" loading="lazy"><figcaption>A lion</figcaption></figure>'
            .'<details class="article-faq"><summary>Is it safe?</summary><div class="article-faq-answer"><p>Yes.</p></div></details>'
            .'<div class="article-cta"><h3>Plan your trip</h3><a class="btn-gold" href="/tours">Read More</a></div>'
            .'<div class="article-divider">✦ ✦ ✦</div>'
            .'<blockquote class="article-quote"><p>Pole pole.</p></blockquote>';

        $this->actingAs($this->admin)->post(route('admin.blog.store'), [
            'title' => 'A Professional Safari Story',
            'slug' => 'a-professional-safari-story',
            'category_id' => $category->id,
            'content' => $content,
            'excerpt' => 'A modern journal article.',
            'hero_caption' => 'Sunrise over the Kilimanjaro summit',
            'meta_title' => 'A Professional Safari Story | Twina Safaris',
            'meta_description' => 'Read our professional safari journal story from Tanzania.',
            'tags' => 'Serengeti, Big Five, Serengeti',
            'featured_image' => UploadedFile::fake()->image('hero.jpg', 1800, 1200),
            'is_published' => '1',
        ])->assertRedirect(route('admin.blog.index'));

        $post = BlogPost::where('slug', 'a-professional-safari-story')->firstOrFail();

        $this->assertSame('Sunrise over the Kilimanjaro summit', $post->hero_caption);
        $this->assertSame('A Professional Safari Story | Twina Safaris', $post->meta_title);
        $this->assertSame('Read our professional safari journal story from Tanzania.', $post->meta_description);
        $this->assertSame(['Serengeti', 'Big Five'], $post->tags);
        $this->assertTrue($post->is_published);
        $this->assertNotNull($post->published_at);
        $this->assertStringEndsWith('.webp', $post->featured_image);
        $this->assertStringContainsString('article-check-list', $post->content);
        $this->assertStringContainsString('article-faq', $post->content);

        $coverFiles = Storage::disk('public')->allFiles('blog/cover');
        $this->assertCount(1, $coverFiles);
        $size = getimagesize(Storage::disk('public')->path($coverFiles[0]));
        $this->assertSame(1600, $size[0]);
    }

    public function test_admin_update_keeps_seo_tags_and_hero_caption(): void
    {
        $category = BlogCategory::create(['name' => 'Guides', 'slug' => 'guides']);
        $post = BlogPost::create([
            'title' => 'Old Guide',
            'slug' => 'old-guide',
            'category_id' => $category->id,
            'content' => '<p>Old content</p>',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $this->actingAs($this->admin)->put(route('admin.blog.update', $post), [
            'title' => 'Updated Guide',
            'slug' => 'old-guide',
            'category_id' => $category->id,
            'content' => '<p>New content</p>',
            'hero_caption' => 'A new hero caption',
            'meta_title' => 'Updated Guide | Twina Safaris',
            'meta_description' => 'Updated description for search engines.',
            'tags' => 'Kilimanjaro, Trekking',
            'is_published' => '1',
        ])->assertRedirect(route('admin.blog.index'));

        $post->refresh();

        $this->assertSame('A new hero caption', $post->hero_caption);
        $this->assertSame('Updated Guide | Twina Safaris', $post->meta_title);
        $this->assertSame('Updated description for search engines.', $post->meta_description);
        $this->assertSame(['Kilimanjaro', 'Trekking'], $post->tags);
        $this->assertStringContainsString('New content', $post->content);
    }

    public function test_admin_edit_page_restores_existing_story_blocks(): void
    {
        $category = BlogCategory::create(['name' => 'Guides', 'slug' => 'guides']);
        $post = BlogPost::create([
            'title' => 'Existing Story',
            'slug' => 'existing-story',
            'category_id' => $category->id,
            'content' => '<h2>Heading</h2><p>First paragraph.</p>'
                .'<figure class="image article-photo"><img src="/storage/blog/content/x.webp" alt="Lion"><figcaption>A lion</figcaption></figure>',
            'hero_caption' => 'A lion resting',
            'tags' => ['Serengeti'],
            'is_published' => true,
            'published_at' => now(),
        ]);

        $html = $this->actingAs($this->admin)
            ->get(route('admin.blog.edit', $post))
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('x-data="blogStoryEditor(window.blogStoryEditorConfig)"', $html);
        $this->assertStringContainsString('First paragraph.', $html);
        $this->assertStringContainsString('\u003Cfigure class=\u0022image article-photo\u0022', $html);
        $this->assertStringContainsString('A lion resting', $html);
        $this->assertStringContainsString('value="Serengeti"', $html);
    }

    public function test_inline_photo_upload_is_cropped_to_three_two_webp_and_returns_a_public_url(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)
            ->post(route('admin.blog.upload-image'), [
                'image' => UploadedFile::fake()->image('portrait.jpg', 800, 1200),
            ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('width', 1200)
            ->assertJsonPath('height', 800)
            ->assertJsonPath('url', fn (string $url) => str_contains($url, '/storage/blog/content/'));

        $files = Storage::disk('public')->allFiles('blog/content');
        $this->assertCount(1, $files);
        $this->assertStringEndsWith('.webp', $files[0]);

        $size = getimagesize(Storage::disk('public')->path($files[0]));
        $this->assertSame(1200, $size[0]);
        $this->assertSame(800, $size[1]);
    }

    public function test_reading_time_allows_for_photo_stops_in_safari_guides(): void
    {
        $category = BlogCategory::create(['name' => 'Safari', 'slug' => 'safari']);
        $post = BlogPost::create([
            'title' => 'A Long Safari Guide',
            'slug' => 'a-long-safari-guide',
            'category_id' => $category->id,
            'content' => '<p>'.implode(' ', array_fill(0, 1047, 'safari')).'</p>',
        ]);

        $this->assertSame('9 min read', $post->reading_time);
    }

    public function test_public_story_shows_three_two_images_hero_caption_and_all_blocks(): void
    {
        $category = BlogCategory::create(['name' => 'Guides', 'slug' => 'guides']);
        $post = BlogPost::create([
            'title' => 'Three Two Story',
            'slug' => 'three-two-story',
            'category_id' => $category->id,
            'content' => '<p>A paragraph.</p>'
                .'<figure class="image article-photo"><img src="/storage/blog/content/x.webp" alt="X" loading="lazy" decoding="async"><figcaption>A caption</figcaption></figure>'
                .'<ul class="article-check-list"><li>One</li></ul>'
                .'<ul class="article-bullet-list"><li>Two</li></ul>'
                .'<ol class="article-steps"><li><span class="article-step-num">1</span>Step</li></ol>'
                .'<blockquote class="article-quote"><p>Quote</p></blockquote>'
                .'<div class="article-note article-note--tip"><strong>Tip</strong><p>Advice</p></div>'
                .'<details class="article-faq"><summary>Question?</summary><div class="article-faq-answer"><p>Answer.</p></div></details>'
                .'<div class="article-cta"><h3>Go</h3><a class="btn-gold" href="/tours">Read More</a></div>'
                .'<div class="article-divider">✦ ✦ ✦</div>',
            'hero_caption' => 'The hero caption under the photo',
            'tags' => ['Serengeti', 'Big Five'],
            'meta_description' => 'Professional safari journal styling.',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $html = $this->get('/blog/'.$post->slug.'.html')
            ->assertOk()
            ->assertSee('Professional safari journal styling.', false)
            ->getContent();

        $this->assertStringContainsString('article-hero', $html);
        $this->assertStringContainsString('The hero caption under the photo', $html);
        $this->assertStringContainsString('Ready for Adventure?', $html);
        $this->assertStringContainsString('Browse Tanzania Safari Packages', $html);
        $this->assertStringContainsString('aspect-ratio: 3 / 2', $html);
        $this->assertStringContainsString('#Serengeti', $html);
        $this->assertStringContainsString('"keywords":"Serengeti, Big Five"', $html);
        $this->assertStringContainsString('max-width: 760px', $html);

        foreach ([
            'article-check-list', 'article-bullet-list', 'article-steps', 'article-quote',
            'article-note--tip', 'article-faq', 'article-cta', 'article-divider',
        ] as $block) {
            $this->assertStringContainsString($block, $html);
        }
    }
}
