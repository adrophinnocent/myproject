<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accommodation;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AccommodationController extends Controller
{
    public function index(Request $request)
    {
        $query = Accommodation::with('destination');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('location_name', 'like', "%{$search}%")
                ->orWhere('type', 'like', "%{$search}%");
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category_level')) {
            $query->where('category_level', $request->category_level);
        }

        $accommodations = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.accommodations.index', compact('accommodations'));
    }

    public function create()
    {
        $destinations = Destination::orderBy('name')->get();

        return view('admin.accommodations.create', compact('destinations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'category_level' => 'required|string|max:100',
            'destination_id' => 'nullable|exists:destinations,id',
            'location_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'star_rating' => 'nullable|numeric|min:1|max:5',
            'supplier_cost_per_night' => 'nullable|numeric|min:0',
            'amenities' => 'nullable|array',
            'is_active' => 'nullable',
            'photos.*' => 'nullable|image|max:10240',
        ]);

        $uploadedImages = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('accommodations', 'public');
                $uploadedImages[] = 'storage/'.$path;
            }
        }

        Accommodation::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'type' => $validated['type'],
            'category_level' => $validated['category_level'],
            'destination_id' => $validated['destination_id'] ?? null,
            'location_name' => $validated['location_name'] ?? null,
            'description' => $validated['description'] ?? null,
            'star_rating' => $validated['star_rating'] ?? 4.0,
            'supplier_cost_per_night' => $validated['supplier_cost_per_night'] ?? null,
            'amenities' => $validated['amenities'] ?? [],
            'images' => $uploadedImages,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.accommodations.index')->with('success', 'Accommodation record added successfully!');
    }

    public function edit(Accommodation $accommodation)
    {
        $destinations = Destination::orderBy('name')->get();

        return view('admin.accommodations.edit', compact('accommodation', 'destinations'));
    }

    public function update(Request $request, Accommodation $accommodation)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'category_level' => 'required|string|max:100',
            'destination_id' => 'nullable|exists:destinations,id',
            'location_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'star_rating' => 'nullable|numeric|min:1|max:5',
            'supplier_cost_per_night' => 'nullable|numeric|min:0',
            'amenities' => 'nullable|array',
            'is_active' => 'nullable',
            'photos.*' => 'nullable|image|max:10240',
            'existing_photos' => 'nullable|array',
        ]);

        $currentImages = $request->input('existing_photos', []);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('accommodations', 'public');
                $currentImages[] = 'storage/'.$path;
            }
        }

        $accommodation->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'type' => $validated['type'],
            'category_level' => $validated['category_level'],
            'destination_id' => $validated['destination_id'] ?? null,
            'location_name' => $validated['location_name'] ?? null,
            'description' => $validated['description'] ?? null,
            'star_rating' => $validated['star_rating'] ?? 4.0,
            'supplier_cost_per_night' => $validated['supplier_cost_per_night'] ?? null,
            'amenities' => $validated['amenities'] ?? [],
            'images' => $currentImages,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.accommodations.index')->with('success', 'Accommodation updated successfully!');
    }

    public function destroy(Accommodation $accommodation)
    {
        // Safety check if itinerary days exist
        if ($accommodation->itineraryDays()->count() > 0) {
            return back()->with('error', 'Cannot delete this accommodation because it is assigned to existing tour packages. Deactivate it instead.');
        }

        $accommodation->delete();

        return redirect()->route('admin.accommodations.index')->with('success', 'Accommodation deleted.');
    }
}
