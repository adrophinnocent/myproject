<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use Database\Seeders\ChristmasSafariArticleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ChristmasSafariSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_seeds_the_published_christmas_safari_article_once(): void
    {
        Storage::fake('public');

        $this->seed(ChristmasSafariArticleSeeder::class);

        $post = BlogPost::where('slug', 'christmas-new-year-safari-tanzania-2026-2027')->firstOrFail();

        $this->assertSame('Christmas and New Year Safari in Tanzania 2026/2027', $post->title);
        $this->assertSame('Sunrise over the southern Serengeti plains', $post->hero_caption);
        $this->assertSame('Safari', $post->category->name);
        $this->assertSame('Admin Twina', $post->author->name);
        $this->assertSame('9 min read', $post->reading_time);
        $this->assertTrue($post->is_published);
        $this->assertCount(12, $post->tags);
        $this->assertStringContainsString('Wildebeest herds spread across the southern plains', $post->content);
        $this->assertStringContainsString('The floor of Ngorongoro Crater', $post->content);
        $this->assertStringContainsString('A festive evening at a safari camp', $post->content);
        $this->assertStringContainsString('Start Planning', $post->content);

        $covers = Storage::disk('public')->allFiles('blog/cover');
        $this->assertCount(1, $covers);
        $this->assertStringEndsWith('.webp', $covers[0]);
        $coverSize = getimagesize(Storage::disk('public')->path($covers[0]));
        $this->assertSame(1600, $coverSize[0]);

        $inline = Storage::disk('public')->allFiles('blog/content');
        $this->assertCount(3, $inline);
        foreach ($inline as $file) {
            $size = getimagesize(Storage::disk('public')->path($file));
            $this->assertSame(1200, $size[0]);
            $this->assertSame(800, $size[1]);
        }

        // Running migrations/seeding again must not overwrite an editor's work.
        $this->seed(ChristmasSafariArticleSeeder::class);
        $this->assertSame(1, BlogPost::where('slug', $post->slug)->count());
        $this->assertCount(1, Storage::disk('public')->allFiles('blog/cover'));
        $this->assertCount(3, Storage::disk('public')->allFiles('blog/content'));
    }
}
