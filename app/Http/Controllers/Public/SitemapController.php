<?php

namespace App\Http\Controllers\Public;

use App\Helpers\SeoHelper;
use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Safari;
use App\Models\Tour;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $tours = Tour::published()->latest()->get();
        $safaris = Safari::where('is_published', true)->latest()->get();
        $blogs = BlogPost::published()->latest()->get();
        $siteUrl = SeoHelper::siteUrl();

        return response()->view('public.sitemap', compact('tours', 'safaris', 'blogs', 'siteUrl'))
            ->header('Content-Type', 'text/xml');
    }
}
