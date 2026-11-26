<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::with('category')->latest()->get();
        $categories = BlogCategory::withCount('posts')->orderBy('name')->get();

        return view('admin.blog.index', compact('posts', 'categories'));
    }

    public function create()
    {
        $categories = BlogCategory::all();

        return view('admin.blog.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->ensureTablesExist();

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'category_id' => 'required|exists:blog_categories,id',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'hero_caption' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:320',
            'tags' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|max:5120',
            'is_published' => 'nullable',
        ]);

        $post = new BlogPost;
        $post->title = $request->title;
        $post->slug = $request->slug;
        $post->category_id = $request->category_id;
        $post->content = $this->processContentImages($request->content);
        $post->excerpt = $request->excerpt;
        $post->hero_caption = $request->hero_caption;
        $post->meta_title = $request->meta_title;
        $post->meta_description = $request->meta_description;
        $post->tags = $this->parseTags($request->tags);
        $post->author_id = auth()->id();
        $post->is_published = $request->has('is_published');
        $post->published_at = $post->is_published ? now() : null;

        if ($request->hasFile('featured_image')) {
            $post->featured_image = $this->storeCoverPhoto($request->file('featured_image'));
        }

        $post->save();

        return redirect()->route('admin.blog.index')->with('success', 'Blog post created successfully');
    }

    public function edit(BlogPost $blog)
    {
        $categories = BlogCategory::all();

        return view('admin.blog.edit', compact('blog', 'categories'));
    }

    public function update(Request $request, BlogPost $blog)
    {
        $this->ensureTablesExist();
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'category_id' => 'required|exists:blog_categories,id',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'hero_caption' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:320',
            'tags' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|max:5120',
            'is_published' => 'nullable',
            'translations' => 'nullable|array',
        ]);

        $blog->title = $request->title;
        $blog->slug = $request->slug;
        $blog->category_id = $request->category_id;
        $blog->content = $this->processContentImages($request->content);
        $blog->excerpt = $request->excerpt;
        $blog->hero_caption = $request->hero_caption;
        $blog->meta_title = $request->meta_title;
        $blog->meta_description = $request->meta_description;
        $blog->tags = $this->parseTags($request->tags);
        $blog->is_published = $request->has('is_published');

        if ($blog->is_published && ! $blog->published_at) {
            $blog->published_at = now();
        }

        if ($request->hasFile('featured_image')) {
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $blog->featured_image = $this->storeCoverPhoto($request->file('featured_image'));
        }

        $blog->save();

        // Save Translations
        if ($request->has('translations')) {
            foreach ($request->translations as $locale => $fields) {
                foreach ($fields as $field => $text) {
                    if (! empty($text)) {
                        $processedText = ($field === 'content') ? $this->processContentImages($text) : $text;
                        $blog->translations()->updateOrCreate(
                            ['locale' => $locale, 'field' => $field],
                            ['text' => $processedText]
                        );
                    }
                }
            }
        }

        return redirect()->route('admin.blog.index')->with('success', 'Blog post updated successfully');
    }

    public function destroy(BlogPost $blog)
    {
        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }
        $blog->delete();

        return redirect()->route('admin.blog.index')->with('success', 'Blog post deleted successfully');
    }

    /**
     * Upload an inline article image. Every inline photo is cropped to the
     * site-wide 3:2 ratio (1200x800) and compressed to WebP so editors never
     * have to think about dimensions.
     */
    public function uploadImage(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|max:5120',
        ]);

        $path = $this->storeInlinePhoto($validated['image']);

        return response()->json([
            'success' => true,
            'url' => Storage::disk('public')->url($path),
            'width' => 1200,
            'height' => 800,
        ]);
    }

    /**
     * Hero/cover image: at least 1600px wide, kept in its natural aspect
     * ratio because the template crops it to 3:2 with CSS.
     */
    private function storeCoverPhoto(UploadedFile $file): string
    {
        return $this->storeOptimizedPhoto($file, 'blog/cover', 1600, null);
    }

    private function storeInlinePhoto(UploadedFile $file): string
    {
        return $this->storeOptimizedPhoto($file, 'blog/content', 1200, 800);
    }

    private function storeOptimizedPhoto(UploadedFile $file, string $directory, int $width, ?int $height): string
    {
        $manager = new ImageManager(new Driver);
        $image = $manager->decodePath($file->getPathname());

        if ($height !== null) {
            $image->cover($width, $height);
        } else {
            $image->scale(width: $width);
        }

        $filename = Str::lower(Str::random(20)).'.webp';
        $path = $directory.'/'.$filename;

        Storage::disk('public')->put($path, $image->encode(new WebpEncoder(quality: 80))->toString());

        return $path;
    }

    private function parseTags(?string $tags): array
    {
        if (empty($tags)) {
            return [];
        }

        return array_values(array_unique(array_filter(array_map(
            fn (string $tag) => trim($tag),
            explode(',', $tags)
        ))));
    }

    private function processContentImages(?string $content): ?string
    {
        if (empty($content)) {
            return $content;
        }

        // Process base64 embedded images in HTML content
        if (str_contains($content, 'data:image/')) {
            try {
                $dom = new \DOMDocument;
                libxml_use_internal_errors(true);
                $dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
                libxml_clear_errors();

                $images = $dom->getElementsByTagName('img');
                foreach ($images as $img) {
                    $src = $img->getAttribute('src');
                    if (str_starts_with($src, 'data:image/')) {
                        preg_match('/data:image\/(\w+);base64,/', $src, $typeMatches);
                        $ext = strtolower($typeMatches[1] ?? 'jpg');
                        if ($ext === 'jpeg') {
                            $ext = 'jpg';
                        }

                        $imageData = substr($src, strpos($src, ',') + 1);
                        $decodedBytes = base64_decode($imageData);

                        if ($decodedBytes !== false) {
                            $filename = 'article_'.time().'_'.Str::random(8).'.'.$ext;
                            $directory = storage_path('app/public/blog/content');

                            if (! file_exists($directory)) {
                                mkdir($directory, 0755, true);
                            }

                            file_put_contents($directory.'/'.$filename, $decodedBytes);
                            $img->setAttribute('src', asset('storage/blog/content/'.$filename));
                        }
                    }
                }

                $content = $dom->saveHTML();
            } catch (\Throwable $e) {
                Log::error('Failed to process blog content images: '.$e->getMessage());
            }
        }

        return $content;
    }

    private function ensureTablesExist(): void
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('blog_posts') && ! \Illuminate\Support\Facades\Schema::hasColumn('blog_posts', 'hero_caption')) {
                \Illuminate\Support\Facades\Schema::table('blog_posts', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->string('hero_caption')->nullable()->after('excerpt');
                });
            }
        } catch (\Throwable $e) {
            Log::error('Blog schema update failed: '.$e->getMessage());
        }
    }
}
