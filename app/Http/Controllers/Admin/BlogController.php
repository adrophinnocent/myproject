<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::with('category')->latest()->get();
        return view('admin.blog.index', compact('posts'));
    }

    public function create()
    {
        $categories = BlogCategory::all();
        return view('admin.blog.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'category_id' => 'required|exists:blog_categories,id',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|max:5120',
            'is_published' => 'nullable',
        ]);

        $post = new BlogPost();
        $post->title = $request->title;
        $post->slug = $request->slug;
        $post->category_id = $request->category_id;
        $post->content = $request->content;
        $post->excerpt = $request->excerpt;
        $post->author_id = auth()->id();
        $post->is_published = $request->has('is_published');
        $post->published_at = $post->is_published ? now() : null;

        if ($request->hasFile('featured_image')) {
            $post->featured_image = $request->file('featured_image')->store('blog', 'public');
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
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'category_id' => 'required|exists:blog_categories,id',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|max:5120',
            'is_published' => 'nullable',
            'translations' => 'nullable|array',
        ]);

        $blog->title = $request->title;
        $blog->slug = $request->slug;
        $blog->category_id = $request->category_id;
        $blog->content = $request->content;
        $blog->excerpt = $request->excerpt;
        $blog->is_published = $request->has('is_published');

        if ($blog->is_published && !$blog->published_at) {
            $blog->published_at = now();
        }

        if ($request->hasFile('featured_image')) {
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $blog->featured_image = $request->file('featured_image')->store('blog', 'public');
        }

        $blog->save();

        // Save Translations
        if ($request->has('translations')) {
            foreach ($request->translations as $locale => $fields) {
                foreach ($fields as $field => $text) {
                    if (!empty($text)) {
                        $blog->translations()->updateOrCreate(
                            ['locale' => $locale, 'field' => $field],
                            ['text' => $text]
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
}
