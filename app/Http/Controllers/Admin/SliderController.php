<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::orderBy('order')->get();
        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|string', // library path
            'image_upload' => 'nullable|image|max:10240', // direct file
            'page' => 'required|string|in:home,contact',
            'cta_text' => 'nullable|string|max:255',
            'cta_url' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'active' => 'nullable',
        ]);

        $slider = new Slider();
        $slider->title = $request->title;
        $slider->subtitle = $request->subtitle;
        $slider->page = $request->page;
        $slider->cta_text = $request->cta_text;
        $slider->cta_url = $request->cta_url;
        $slider->order = $request->order ?? 0;
        $slider->active = $request->has('active');

        if ($request->hasFile('image_upload')) {
            $slider->image = $request->file('image_upload')->store('sliders', 'public');
        } elseif ($request->filled('image')) {
            $slider->image = $request->image;
        }

        if (empty($slider->image)) {
            return back()->withErrors(['image' => 'An image is required. Pick from library or upload.']);
        }

        $slider->save();

        return redirect()->route('admin.sliders.index')->with('success', 'Slider created successfully!');
    }

    public function edit(Slider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, Slider $slider)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|string',
            'image_upload' => 'nullable|image|max:10240',
            'page' => 'required|string|in:home,contact',
            'cta_text' => 'nullable|string|max:255',
            'cta_url' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'active' => 'nullable',
        ]);

        $slider->title = $request->title;
        $slider->subtitle = $request->subtitle;
        $slider->page = $request->page;
        $slider->cta_text = $request->cta_text;
        $slider->cta_url = $request->cta_url;
        $slider->order = $request->order ?? 0;
        $slider->active = $request->has('active');

        if ($request->hasFile('image_upload')) {
            if ($slider->image && !str_contains($slider->image, 'media/')) {
                 Storage::disk('public')->delete($slider->image);
            }
            $slider->image = $request->file('image_upload')->store('sliders', 'public');
        } elseif ($request->filled('image')) {
            $slider->image = $request->image;
        }

        $slider->save();

        return redirect()->route('admin.sliders.index')->with('success', 'Slider updated successfully!');
    }

    public function destroy(Slider $slider)
    {
        if ($slider->image) {
            Storage::disk('public')->delete($slider->image);
        }
        $slider->delete();

        return redirect()->route('admin.sliders.index')->with('success', 'Slider deleted successfully!');
    }
}
