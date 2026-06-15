<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $albums = GalleryAlbum::where('is_published', true)
            ->with('images')
            ->latest()
            ->paginate(12);

        return view('public.gallery.index', compact('albums'));
    }

    public function show($slug)
    {
        $album = GalleryAlbum::where('slug', $slug)
            ->where('is_published', true)
            ->with('images')
            ->firstOrFail();

        return view('public.gallery.show', compact('album'));
    }
}
