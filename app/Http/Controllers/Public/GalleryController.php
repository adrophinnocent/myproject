<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;

class GalleryController extends Controller
{
    public function index()
    {
        $albums = GalleryAlbum::published()
            ->with('images')
            ->latest()
            ->paginate(12);

        return view('public.gallery.index', compact('albums'));
    }

    public function show(GalleryAlbum $album)
    {
        $album->load('images');

        $response = response()->view('public.gallery.show', compact('album'));

        // Placeholder albums with no photos are kept for visitors but should
        // not compete in search while they are still empty.
        if ($album->images->isEmpty()) {
            $response->header('X-Robots-Tag', 'noindex, follow, noarchive, nosnippet');
        }

        return $response;
    }
}
