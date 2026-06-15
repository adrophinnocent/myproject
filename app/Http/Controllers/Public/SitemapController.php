<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Models\Safari;
use App\Models\BlogPost;
use App\Models\Destination;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $tours = Tour::published()->latest()->get();
        $safaris = Safari::where('is_published', true)->latest()->get();
        $blogs = BlogPost::published()->latest()->get();
        $destinations = Destination::where('is_active', true)->get();

        return response()->view('public.sitemap', compact('tours', 'safaris', 'blogs', 'destinations'))
            ->header('Content-Type', 'text/xml');
    }
}
