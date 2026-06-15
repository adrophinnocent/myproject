<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Destination;
use App\Models\Safari;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SafariController extends Controller
{
    public function index()
    {
        $safaris = Safari::with(['category', 'destination'])->latest()->paginate(10);
        return view('admin.safaris.index', compact('safaris'));
    }

    public function create()
    {
        $categories = Category::all();
        $destinations = Destination::all();
        return view('admin.safaris.create', compact('categories', 'destinations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'duration_days' => 'required|integer',
            'category_id' => 'nullable|exists:categories,id',
            'destination_id' => 'nullable|exists:destinations,id',
            'featured_image' => 'nullable|image|max:10240',
            // ... other fields can be added as needed, matching Tour structure
        ]);

        $data = $request->all();
        $data['is_published'] = $request->has('is_published') ? 1 : 0;
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('safaris', 'public');
        }

        Safari::create($data);

        return redirect()->route('admin.safaris.index')->with('success', 'Safari created successfully!');
    }

    public function edit(Safari $safari)
    {
        $categories = Category::all();
        $destinations = Destination::all();
        return view('admin.safaris.edit', compact('safari', 'categories', 'destinations'));
    }

    public function update(Request $request, Safari $safari)
    {
        $data = $request->all();
        $data['is_published'] = $request->has('is_published') ? 1 : 0;
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        if ($request->hasFile('featured_image')) {
            if ($safari->featured_image) {
                Storage::disk('public')->delete($safari->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('safaris', 'public');
        }

        $safari->update($data);

        return redirect()->route('admin.safaris.index')->with('success', 'Safari updated successfully!');
    }

    public function destroy(Safari $safari)
    {
        if ($safari->featured_image) {
            Storage::disk('public')->delete($safari->featured_image);
        }
        $safari->delete();
        return redirect()->route('admin.safaris.index')->with('success', 'Safari deleted successfully!');
    }
}
