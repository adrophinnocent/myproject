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

    public function test_admin_editor_offers_blocks_preview_seo_and_upload(): void
    {
        $html = $this->actingAs($this->admin)
            ->get(route('admin.blog.create'))
            ->assertOk()
            ->getContent();

        $this->assertStringContainsString('window.blogStoryEditorConfig = {', $html);
        $this->assertStringContainsString('x-data="blogStoryEditor(window.blogStoryEditorConfig)"', $html);
        $this->assertStringNotContainsString('x-data="blogStoryEditor({"', $html);
        $this->assertStringContainsString('Live Preview', $html);
        $this->assertStringContainsString('Drag blocks to reorder', $html);
        $this->assertStringContainsString('Add square photo after this paragraph', $html);
        $this->assertStringContainsString('META TITLE', $html);
        $this->assertStringContainsString('META DESCRIPTION', $html);
        $this->assertStringContainsString('TAGS', $html);
        $this->assertStringContainsString('upload-image', $html);
        $this->assertStringContainsString('article-video', $html);
        $this->assertStringContainsString('article-callout', $html);
        $this->assertStringContainsString('article-quote', $html);
    }

    public function test_admin_can_publish_a_story_with_seo_tags_and_square_photos(): void
    {
        $category = BlogCategory::create(['name' => 'Safari Stories', 'slug' => 'safari-stories']);
        $content = '<h2>Knysna</h2>'
            .'<p>Wildlife in the Serengeti.</p>'
            .'<figure class="image article-photo"><img src="/storage/blog/content/lion.jpg" alt="Lion"></figure>'
            .'<blockquote class="article-quote"><p>Knysna</p></blockquote>';

        $this->actingAs($this->admin)->post(route('admin.blog.store'), [
            'title' => 'A Professional Safari Story',
            'slug' => 'a-professional-safari-story',
            'category_id' => $category->id,
            'content' => $content,
            'excerpt' => 'A modern journal article.',
            'meta_title' => 'A Professional Safari Story | Twina Safaris',
            'meta_description' => 'Read our professional safari journal story from Tanzania.',
            'tags' => 'Serengeti, Big Five, Serengeti',
            'is_published' => '1',
        ])->assertRedirect(route('admin.blog.index'));

        $post = BlogPost::where('slug', 'a-professional-safari-story')->firstOrFail();

        $this->assertSame('A Professional Safari Story | Twina Safaris', $post->meta_title);
        $this->assertSame('Read our professional safari journal story from Tanzania.', $post->meta_description);
        $this->assertSame(['Serengeti', 'Big Five'], $post->tags);
        $this->assertTrue($post->is_published);
        $this->assertNotNull($post->published_at);
        $this->assertStringContainsString('article-photo', $post->content);
    }

    public function test_admin_update_keeps_seo_and_tags(): void
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
            'meta_title' => 'Updated Guide | Twina Safaris',
            'meta_description' => 'Updated description for search engines.',
            'tags' => 'Kilimanjaro, Trekking',
            'is_published' => '1',
        ])->assertRedirect(route('admin.blog.index'));

        $post->refresh();

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
                .'<figure class="image article-photo"><img src="/storage/blog/content/x.jpg" alt="Lion"><figcaption>A lion</figcaption></figure>',
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
        $this->assertStringContainsString('value="Serengeti"', $html);
    }

    public function test_inline_photo_upload_returns_a_public_url(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)
            ->post(route('admin.blog.upload-image'), [
                'image' => UploadedFile::fake()->image('serengeti.jpg'),
            ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('url', fn (string $url) => str_contains($url, '/storage/blog/content/'));

        $path = null;
        foreach (Storage::disk('public')->allFiles('blog/content') as $file) {
            $path = $file;
        }

        $this->assertNotNull($path, 'The inline photo must be stored on the public disk.');
        Storage::disk('public')->assertExists($path);
    }

    public function test_public_story_shows_tags_and_square_photo_styling(): void
    {
        $category = BlogCategory::create(['name' => 'Guides', 'slug' => 'guides']);
        $post = BlogPost::create([
            'title' => 'Square Photo Story',
            'slug' => 'square-photo-story',
            'category_id' => $category->id,
            'content' => '<p>A paragraph.</p><figure class="image article-photo"><img src="/storage/blog/content/x.jpg" alt="X"></figure>',
            'tags' => ['Serengeti', 'Big Five'],
            'meta_description' => 'Professional safari journal styling.',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $html = $this->get('/blog/'.$post->slug.'.html')
            ->assertOk()
            ->assertSee('Professional safari journal styling.', false)
            ->getContent();

        $this->assertStringContainsString('#Serengeti', $html);
        $this->assertStringContainsString('"keywords": "Serengeti, Big Five"', $html);
        $this->assertStringContainsString('aspect-ratio: 1 / 1', $html);
        $this->assertStringContainsString('article-photo', $html);
    }
}
