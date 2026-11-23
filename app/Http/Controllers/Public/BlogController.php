<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    /**
     * Old best-time articles are replaced by the evergreen planning guide.
     * Keep their inbound links useful instead of serving an error page.
     */
    private const LEGACY_BEST_TIME_SLUGS = [
        'best-time-to-go-on-safari-in-tanzania',
        'best-time-to-visit-tanzania-for-an-incredible-safari',
    ];

    public function index()
    {
        $posts = BlogPost::published()->with('category')->latest()->paginate(9);
        $categories = BlogCategory::withCount('posts')->get();

        return view('public.blog.index', compact('posts', 'categories'));
    }

    public function show($slug)
    {
        $slug = str_replace('.html', '', $slug);

        try {
            $post = BlogPost::where('slug', $slug)->published()->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            if (in_array($slug, self::LEGACY_BEST_TIME_SLUGS, true)) {
                return redirect()->route('guides.best-time', status: 301);
            }

            throw $exception;
        }
        // A pageview is not a content update: increment without touching
        // updated_at so sitemap lastmod stays truthful.
        DB::table($post->getTable())->whereKey($post->getKey())->increment('views_count');

        $relatedPosts = BlogPost::where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->published()
            ->take(3)
            ->get();

        return view('public.blog.show', compact('post', 'relatedPosts'));
    }
}
