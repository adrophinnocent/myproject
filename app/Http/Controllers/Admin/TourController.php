<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Destination;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class TourController extends Controller
{
    public function index()
    {
        $tours = Tour::with(['category', 'destination'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->latest()
            ->paginate(10);
        $categories = Category::withCount('tours')->orderBy('name')->get();

        return view('admin.tours.index', compact('tours', 'categories'));
    }

    public function togglePublish(Tour $tour)
    {
        $tour->update(['is_published' => !$tour->is_published]);
        Cache::forget('nav_categories_v7');

        return back()->with('success', 'Tour '.($tour->is_published ? 'published' : 'unpublished').'!');
    }

    public function create()
    {
        $categories = Category::all();
        $destinations = Destination::all();

        return view('admin.tours.create', compact('categories', 'destinations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'highlights' => 'nullable|array',
            'price' => 'required|numeric',
            'child_price' => 'nullable|numeric',
            'group_discount' => 'nullable|numeric',
            'deposit_percent' => 'nullable|integer',
            'currency' => 'nullable|string',
            'duration_days' => 'required|integer',
            'duration_nights' => 'nullable|integer',
            'group_size_min' => 'nullable|integer',
            'group_size_max' => 'nullable|integer',
            'difficulty_level' => 'nullable|string',
            'tour_type' => 'nullable|string',
            'accommodation_type' => 'nullable|string',
            'transport_type' => 'nullable|string',
            'departure_location' => 'nullable|string',
            'departure_time' => 'nullable|string',
            'meeting_point' => 'nullable|string',
            'pickup_included' => 'nullable',
            'airport_pickup' => 'nullable',
            'transport_included' => 'nullable',
            'map_location' => 'nullable|string',
            'assigned_guide' => 'nullable|string',
            'languages_offered' => 'nullable|array',
            'special_notes' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'available_slots' => 'nullable|integer',
            'seasonal_tag' => 'nullable|string',
            'seasonal_pricing' => 'nullable|array',
            'availability_dates' => 'nullable|array',
            'booking_deadline_days' => 'nullable|integer',
            'min_age' => 'nullable|integer',
            'max_age' => 'nullable|integer',
            'itinerary' => 'nullable|array',
            'inclusions' => 'nullable|array',
            'exclusions' => 'nullable|array',
            'faqs' => 'nullable|array',
            'what_to_bring' => 'nullable|array',
            'good_to_know' => 'nullable|array',
            'featured_image' => 'nullable|string',
            'featured_image_upload' => 'nullable|image|max:10240',
            'video_url' => 'nullable|string',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'destination_id' => 'nullable|exists:destinations,id',
            'is_published' => 'nullable',
            'is_featured' => 'nullable',
            'is_bestseller' => 'nullable',
            'is_new' => 'nullable',
            'limited_offer' => 'nullable',
            'availability_notes' => 'nullable|string',
            'gallery_images.*' => 'nullable|image|max:10240',
            'translations' => 'nullable|array',
            'itinerary_raw' => 'nullable|string',
            'pickup_locations' => 'nullable|array',
        ]);

        $data = $request->except(['featured_image', 'featured_image_upload', 'gallery_images', 'translations', 'itinerary_raw']);

        // Handle Magic Code Itinerary & Package Data
        if ($request->filled('itinerary_raw')) {
            $decoded = json_decode($request->itinerary_raw, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withInput()->withErrors(['itinerary_raw' => 'Invalid JSON Format: ' . json_last_error_msg()]);
            }

            // If it's a full package object with keys
            if (isset($decoded['itinerary'])) {
                $data['itinerary'] = $decoded['itinerary'];
                if (isset($decoded['highlights'])) $data['highlights'] = $decoded['highlights'];
                if (isset($decoded['inclusions'])) $data['inclusions'] = $decoded['inclusions'];
                if (isset($decoded['exclusions'])) $data['exclusions'] = $decoded['exclusions'];
                if (isset($decoded['faqs'])) $data['faqs'] = $decoded['faqs'];
                if (isset($decoded['what_to_bring'])) $data['what_to_bring'] = $decoded['what_to_bring'];
                if (isset($decoded['good_to_know'])) $data['good_to_know'] = $decoded['good_to_know'];
            } else {
                // Fallback to just itinerary if it's a simple array
                $data['itinerary'] = $decoded;
            }
        }

        $data['is_published'] = $request->has('is_published') ? 1 : 0;
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $data['is_bestseller'] = $request->has('is_bestseller') ? 1 : 0;
        $data['is_new'] = $request->has('is_new') ? 1 : 0;
        $data['limited_offer'] = $request->has('limited_offer') ? 1 : 0;
        $data['pickup_included'] = $request->has('pickup_included') ? 1 : 0;
        $data['airport_pickup'] = $request->has('airport_pickup') ? 1 : 0;
        $data['transport_included'] = $request->has('transport_included') ? 1 : 0;

        if ($request->hasFile('featured_image_upload')) {
            $image = $request->file('featured_image_upload');
            $filename = time() . '.webp';
            $img = imagecreatefromstring(file_get_contents($image->getRealPath()));
            ob_start();
            imagewebp($img, null, 75); // 75% quality compression
            $content = ob_get_clean();
            Storage::disk('public')->put('tours/' . $filename, $content);
            $data['featured_image'] = 'tours/' . $filename;
            imagedestroy($img);
        } elseif ($request->filled('featured_image')) {
            $data['featured_image'] = $request->featured_image;
        }

        $tour = Tour::create($data);

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $path = $image->store('tours/gallery', 'public');
                $tour->images()->create([
                    'image_path' => $path,
                    'is_primary' => false,
                ]);
            }
        }

        // Save Translations
        if ($request->has('translations')) {
            foreach ($request->translations as $locale => $fields) {
                $tour->translations()->updateOrCreate(
                    ['locale' => $locale],
                    $fields
                );
            }
        }

        Cache::forget('nav_categories_v7');

        return redirect()->route('admin.tours.index')->with('success', 'Tour created successfully!');
    }

    public function show(Tour $tour)
    {
        return view('admin.tours.show', compact('tour'));
    }

    public function edit(Tour $tour)
    {
        $categories = Category::all();
        $destinations = Destination::all();

        return view('admin.tours.edit', compact('tour', 'categories', 'destinations'));
    }

    public function update(Request $request, Tour $tour)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'highlights' => 'nullable|array',
            'price' => 'required|numeric',
            'child_price' => 'nullable|numeric',
            'group_discount' => 'nullable|numeric',
            'deposit_percent' => 'nullable|integer',
            'currency' => 'nullable|string',
            'duration_days' => 'required|integer',
            'duration_nights' => 'nullable|integer',
            'group_size_min' => 'nullable|integer',
            'group_size_max' => 'nullable|integer',
            'difficulty_level' => 'nullable|string',
            'tour_type' => 'nullable|string',
            'accommodation_type' => 'nullable|string',
            'transport_type' => 'nullable|string',
            'departure_location' => 'nullable|string',
            'departure_time' => 'nullable|string',
            'meeting_point' => 'nullable|string',
            'pickup_included' => 'nullable',
            'airport_pickup' => 'nullable',
            'transport_included' => 'nullable',
            'map_location' => 'nullable|string',
            'assigned_guide' => 'nullable|string',
            'languages_offered' => 'nullable|array',
            'special_notes' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'available_slots' => 'nullable|integer',
            'seasonal_tag' => 'nullable|string',
            'seasonal_pricing' => 'nullable|array',
            'availability_dates' => 'nullable|array',
            'booking_deadline_days' => 'nullable|integer',
            'min_age' => 'nullable|integer',
            'max_age' => 'nullable|integer',
            'itinerary' => 'nullable|array',
            'inclusions' => 'nullable|array',
            'exclusions' => 'nullable|array',
            'faqs' => 'nullable|array',
            'what_to_bring' => 'nullable|array',
            'good_to_know' => 'nullable|array',
            'featured_image' => 'nullable|string',
            'featured_image_upload' => 'nullable|image|max:10240',
            'video_url' => 'nullable|string',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'destination_id' => 'nullable|exists:destinations,id',
            'is_published' => 'nullable',
            'is_featured' => 'nullable',
            'is_bestseller' => 'nullable',
            'is_new' => 'nullable',
            'limited_offer' => 'nullable',
            'availability_notes' => 'nullable|string',
            'gallery_images.*' => 'nullable|image|max:10240',
            'translations' => 'nullable|array',
            'itinerary_raw' => 'nullable|string',
            'pickup_locations' => 'nullable|array',
        ]);

        $data = $request->except(['featured_image', 'featured_image_upload', 'gallery_images', 'translations', 'itinerary_raw']);

        // Handle Magic Code Itinerary & Package Data
        if ($request->filled('itinerary_raw')) {
            $decoded = json_decode($request->itinerary_raw, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return back()->withInput()->withErrors(['itinerary_raw' => 'Invalid JSON Format: ' . json_last_error_msg()]);
            }

            // If it's a full package object with keys
            if (isset($decoded['itinerary'])) {
                $data['itinerary'] = $decoded['itinerary'];
                if (isset($decoded['highlights'])) $data['highlights'] = $decoded['highlights'];
                if (isset($decoded['inclusions'])) $data['inclusions'] = $decoded['inclusions'];
                if (isset($decoded['exclusions'])) $data['exclusions'] = $decoded['exclusions'];
                if (isset($decoded['faqs'])) $data['faqs'] = $decoded['faqs'];
                if (isset($decoded['what_to_bring'])) $data['what_to_bring'] = $decoded['what_to_bring'];
                if (isset($decoded['good_to_know'])) $data['good_to_know'] = $decoded['good_to_know'];
            } else {
                // Fallback to just itinerary if it's a simple array
                $data['itinerary'] = $decoded;
            }
        }

        $data['is_published'] = $request->has('is_published') ? 1 : 0;
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $data['is_bestseller'] = $request->has('is_bestseller') ? 1 : 0;
        $data['is_new'] = $request->has('is_new') ? 1 : 0;
        $data['limited_offer'] = $request->has('limited_offer') ? 1 : 0;
        $data['pickup_included'] = $request->has('pickup_included') ? 1 : 0;
        $data['airport_pickup'] = $request->has('airport_pickup') ? 1 : 0;
        $data['transport_included'] = $request->has('transport_included') ? 1 : 0;

        if ($request->hasFile('featured_image_upload')) {
            if ($tour->featured_image && !str_contains($tour->featured_image, 'media/')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($tour->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image_upload')->store('tours', 'public');
        } elseif ($request->filled('featured_image')) {
            $data['featured_image'] = $request->featured_image;
        }

        $tour->update($data);

        // Save Translations
        if ($request->has('translations')) {
            foreach ($request->translations as $locale => $fields) {
                $tour->translations()->updateOrCreate(
                    ['locale' => $locale],
                    $fields
                );
            }
        }

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $path = $image->store('tours/gallery', 'public');
                $tour->images()->create([
                    'image_path' => $path,
                    'is_primary' => false,
                ]);
            }
        }

        Cache::forget('nav_categories_v7');

        return redirect()->route('admin.tours.index')->with('success', 'Tour updated successfully!');
    }

    public function deleteImage(\App\Models\TourImage $image)
    {
        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return back()->with('success', 'Gallery image deleted!');
    }

    public function destroy(Tour $tour)
    {
        if ($tour->featured_image) {
            Storage::disk('public')->delete($tour->featured_image);
        }
        $tour->delete();
        Cache::forget('nav_categories_v7');

        return redirect()->route('admin.tours.index')->with('success', 'Tour deleted successfully!');
    }
}
