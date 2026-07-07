<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryAlbum;
use App\Models\GalleryImage;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $albums = GalleryAlbum::withCount('images')->latest()->paginate(10);

        return view('admin.gallery.index', compact('albums'));
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:gallery_albums',
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:10240',
            'is_published' => 'nullable',
        ]);

        $album = new GalleryAlbum();
        $album->name = $request->name;
        $album->slug = $request->slug;
        $album->description = $request->description;
        $album->is_published = $request->has('is_published');

        if ($request->hasFile('cover_image')) {
            $album->cover_image = $request->file('cover_image')->store('gallery', 'public');
        }

        $album->save();

        return redirect()->route('admin.gallery.index')->with('success', 'Album created successfully! You can now add images to it.');
    }

    public function edit(GalleryAlbum $album)
    {
        return view('admin.gallery.edit', compact('album'));
    }

    public function update(Request $request, GalleryAlbum $album)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:gallery_albums,slug,'.$album->id,
            'description' => 'nullable|string',
            'cover_image' => 'nullable|image|max:10240',
            'is_published' => 'nullable',
        ]);

        $album->name = $request->name;
        $album->slug = $request->slug;
        $album->description = $request->description;
        $album->is_published = $request->has('is_published');

        if ($request->hasFile('cover_image')) {
            if ($album->cover_image) {
                \Storage::disk('public')->delete($album->cover_image);
            }
            $album->cover_image = $request->file('cover_image')->store('gallery', 'public');
        }

        $album->save();

        return back()->with('success', 'Album updated!');
    }

    public function destroy(GalleryAlbum $album)
    {
        foreach ($album->images as $image) {
            if ($image->image) {
                \Storage::disk('public')->delete($image->image);
            }
            $image->delete();
        }

        if ($album->cover_image) {
            \Storage::disk('public')->delete($album->cover_image);
        }
        $album->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'Album deleted!');
    }

    public function addImage(Request $request, GalleryAlbum $album)
    {
        $request->validate([
            'image' => 'required|image|max:10240', // 10MB
            'caption' => 'nullable|string|max:255',
        ]);

        $image = new GalleryImage();
        $image->album_id = $album->id;
        $image->caption = $request->caption;
        $image->sort_order = $album->images()->count() + 1;
        $image->image = $request->file('image')->store('gallery', 'public');

        $image->save();

        return back()->with('success', 'Image added!');
    }

    public function removeImage(GalleryImage $image)
    {
        if ($image->image) {
            \Storage::disk('public')->delete($image->image);
        }
        $image->delete();

        return back()->with('success', 'Image removed!');
    }
}
