<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::published()->with('category')->latest()->paginate(9);
        $categories = BlogCategory::withCount('posts')->get();
        return view('public.blog.index', compact('posts', 'categories'));
    }

    public function show($slug)
    {
        $post = BlogPost::where('slug', $slug)->published()->firstOrFail();
        $post->increment('views_count');

        $relatedPosts = BlogPost::where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->published()
            ->take(3)
            ->get();

        return view('public.blog.show', compact('post', 'relatedPosts'));
    }
}
