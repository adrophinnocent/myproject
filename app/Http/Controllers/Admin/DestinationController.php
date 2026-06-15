<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function index()
    {
        $destinations = Destination::latest()->paginate(10);

        return view('admin.destinations.index', compact('destinations'));
    }

    public function create()
    {
        return view('admin.destinations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'description' => 'nullable|string',
            'featured_image' => 'nullable|image|max:10240',
            'is_active' => 'nullable',
            'is_featured' => 'nullable',
        ]);

        $destination = new Destination();
        $destination->name = $request->name;
        $destination->slug = $request->slug;
        $destination->country = $request->country;
        $destination->description = $request->description;
        $destination->is_active = $request->has('is_active');
        $destination->is_featured = $request->has('is_featured');

        if ($request->hasFile('featured_image')) {
            $destination->featured_image = $request->file('featured_image')->store('destinations', 'public');
        }

        $destination->save();

        return redirect()->route('admin.destinations.index')->with('success', 'Destination created successfully!');
    }

    public function edit(Destination $destination)
    {
        return view('admin.destinations.edit', compact('destination'));
    }

    public function update(Request $request, Destination $destination)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'description' => 'nullable|string',
            'featured_image' => 'nullable|image|max:10240',
            'is_active' => 'nullable',
            'is_featured' => 'nullable',
        ]);

        $destination->name = $request->name;
        $destination->slug = $request->slug;
        $destination->country = $request->country;
        $destination->description = $request->description;
        $destination->is_active = $request->has('is_active');
        $destination->is_featured = $request->has('is_featured');

        if ($request->hasFile('featured_image')) {
            if ($destination->featured_image) {
                \Storage::disk('public')->delete($destination->featured_image);
            }
            $destination->featured_image = $request->file('featured_image')->store('destinations', 'public');
        }

        $destination->save();

        return redirect()->route('admin.destinations.index')->with('success', 'Destination updated successfully!');
    }

    public function destroy(Destination $destination)
    {
        $destination->delete();

        return redirect()->route('admin.destinations.index')->with('success', 'Destination deleted successfully!');
    }
}
