<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TourPackage;
use App\Models\Tour;
use App\Models\Safari;
use App\Models\Accommodation;
use App\Models\RoomType;
use App\Models\PackageItineraryDay;
use App\Models\PackageRoomOption;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TourPackageAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = TourPackage::with(['tour', 'safari', 'itineraryDays.accommodation']);

        if ($request->filled('tour_id')) {
            $query->where('tour_id', $request->tour_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $packages = $query->orderBy('sort_order')->orderBy('name')->paginate(15)->withQueryString();
        $tours = Tour::published()->orderBy('title')->get();

        return view('admin.tour-packages.index', compact('packages', 'tours'));
    }

    public function create(Request $request)
    {
        $tours = Tour::published()->orderBy('title')->get();
        $safaris = Safari::published()->orderBy('title')->get();
        $accommodations = Accommodation::active()->get();
        $roomTypes = RoomType::active()->get();

        $selectedTourId = $request->get('tour_id');

        return view('admin.tour-packages.create', compact('tours', 'safaris', 'accommodations', 'roomTypes', 'selectedTourId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tour_id' => 'nullable|exists:tours,id',
            'safari_id' => 'nullable|exists:safaris,id',
            'selling_price' => 'required|numeric|min:0',
            'adult_price' => 'nullable|numeric|min:0',
            'child_price' => 'nullable|numeric|min:0',
            'single_supplement' => 'nullable|numeric|min:0',
            'deposit_percentage' => 'nullable|numeric|min:1|max:100',
            'currency' => 'required|string|max:10',
            'description' => 'nullable|string',
            'is_active' => 'nullable',
            // Private Admin Costing Fields
            'supplier_hotel_cost' => 'nullable|numeric|min:0',
            'park_fees_cost' => 'nullable|numeric|min:0',
            'transport_cost' => 'nullable|numeric|min:0',
            'guide_cost' => 'nullable|numeric|min:0',
            'meals_cost' => 'nullable|numeric|min:0',
            'transfers_cost' => 'nullable|numeric|min:0',
            'other_costs' => 'nullable|numeric|min:0',
            // Itinerary Days
            'itinerary_days' => 'nullable|array',
            // Room Options
            'room_supplements' => 'nullable|array',
        ]);

        $package = TourPackage::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name'] . '-' . Str::random(5)),
            'tour_id' => $validated['tour_id'] ?? null,
            'safari_id' => $validated['safari_id'] ?? null,
            'selling_price' => $validated['selling_price'],
            'adult_price' => $validated['adult_price'] ?? $validated['selling_price'],
            'child_price' => $validated['child_price'] ?? round($validated['selling_price'] * 0.7, 2),
            'single_supplement' => $validated['single_supplement'] ?? 0.00,
            'deposit_percentage' => $validated['deposit_percentage'] ?? 20.00,
            'currency' => $validated['currency'] ?? 'USD',
            'description' => $validated['description'] ?? null,
            'is_active' => $request->has('is_active'),
            // Internal Costing
            'supplier_hotel_cost' => $validated['supplier_hotel_cost'] ?? 0.00,
            'park_fees_cost' => $validated['park_fees_cost'] ?? 0.00,
            'transport_cost' => $validated['transport_cost'] ?? 0.00,
            'guide_cost' => $validated['guide_cost'] ?? 0.00,
            'meals_cost' => $validated['meals_cost'] ?? 0.00,
            'transfers_cost' => $validated['transfers_cost'] ?? 0.00,
            'other_costs' => $validated['other_costs'] ?? 0.00,
        ]);

        // Save Itinerary Days & Nightly Hotel Assignments
        if ($request->has('itinerary_days') && is_array($request->itinerary_days)) {
            foreach ($request->itinerary_days as $index => $day) {
                if (!empty($day['title']) || !empty($day['accommodation_id'])) {
                    PackageItineraryDay::create([
                        'tour_package_id' => $package->id,
                        'day_number' => $index + 1,
                        'title' => $day['title'] ?? ('Day ' . ($index + 1)),
                        'description' => $day['description'] ?? null,
                        'accommodation_id' => $day['accommodation_id'] ?? null,
                        'accommodation_notes' => $day['accommodation_notes'] ?? null,
                        'meals' => $day['meals'] ?? 'Breakfast, Lunch, Dinner',
                        'sort_order' => $index + 1,
                    ]);
                }
            }
        }

        // Save Room Type Options
        if ($request->has('room_supplements') && is_array($request->room_supplements)) {
            foreach ($request->room_supplements as $roomTypeId => $suppPrice) {
                PackageRoomOption::create([
                    'tour_package_id' => $package->id,
                    'room_type_id' => $roomTypeId,
                    'supplement_price' => (float) $suppPrice,
                    'is_available' => true,
                ]);
            }
        }

        return redirect()->route('admin.tour-packages.index')->with('success', 'Tour Package created successfully!');
    }

    public function edit(TourPackage $tourPackage)
    {
        $tourPackage->load(['itineraryDays.accommodation', 'roomOptions']);

        $tours = Tour::published()->orderBy('title')->get();
        $safaris = Safari::published()->orderBy('title')->get();
        $accommodations = Accommodation::active()->get();
        $roomTypes = RoomType::active()->get();

        return view('admin.tour-packages.edit', compact('tourPackage', 'tours', 'safaris', 'accommodations', 'roomTypes'));
    }

    public function update(Request $request, TourPackage $tourPackage)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tour_id' => 'nullable|exists:tours,id',
            'safari_id' => 'nullable|exists:safaris,id',
            'selling_price' => 'required|numeric|min:0',
            'adult_price' => 'nullable|numeric|min:0',
            'child_price' => 'nullable|numeric|min:0',
            'single_supplement' => 'nullable|numeric|min:0',
            'deposit_percentage' => 'nullable|numeric|min:1|max:100',
            'currency' => 'required|string|max:10',
            'description' => 'nullable|string',
            'is_active' => 'nullable',
            // Private Admin Costing Fields
            'supplier_hotel_cost' => 'nullable|numeric|min:0',
            'park_fees_cost' => 'nullable|numeric|min:0',
            'transport_cost' => 'nullable|numeric|min:0',
            'guide_cost' => 'nullable|numeric|min:0',
            'meals_cost' => 'nullable|numeric|min:0',
            'transfers_cost' => 'nullable|numeric|min:0',
            'other_costs' => 'nullable|numeric|min:0',
            // Itinerary Days
            'itinerary_days' => 'nullable|array',
            'room_supplements' => 'nullable|array',
        ]);

        $tourPackage->update([
            'name' => $validated['name'],
            'tour_id' => $validated['tour_id'] ?? null,
            'safari_id' => $validated['safari_id'] ?? null,
            'selling_price' => $validated['selling_price'],
            'adult_price' => $validated['adult_price'] ?? $validated['selling_price'],
            'child_price' => $validated['child_price'] ?? round($validated['selling_price'] * 0.7, 2),
            'single_supplement' => $validated['single_supplement'] ?? 0.00,
            'deposit_percentage' => $validated['deposit_percentage'] ?? 20.00,
            'currency' => $validated['currency'] ?? 'USD',
            'description' => $validated['description'] ?? null,
            'is_active' => $request->has('is_active'),
            // Internal Costing
            'supplier_hotel_cost' => $validated['supplier_hotel_cost'] ?? 0.00,
            'park_fees_cost' => $validated['park_fees_cost'] ?? 0.00,
            'transport_cost' => $validated['transport_cost'] ?? 0.00,
            'guide_cost' => $validated['guide_cost'] ?? 0.00,
            'meals_cost' => $validated['meals_cost'] ?? 0.00,
            'transfers_cost' => $validated['transfers_cost'] ?? 0.00,
            'other_costs' => $validated['other_costs'] ?? 0.00,
        ]);

        // Sync Itinerary Days
        $tourPackage->itineraryDays()->delete();
        if ($request->has('itinerary_days') && is_array($request->itinerary_days)) {
            foreach ($request->itinerary_days as $index => $day) {
                if (!empty($day['title']) || !empty($day['accommodation_id'])) {
                    PackageItineraryDay::create([
                        'tour_package_id' => $tourPackage->id,
                        'day_number' => $index + 1,
                        'title' => $day['title'] ?? ('Day ' . ($index + 1)),
                        'description' => $day['description'] ?? null,
                        'accommodation_id' => $day['accommodation_id'] ?? null,
                        'accommodation_notes' => $day['accommodation_notes'] ?? null,
                        'meals' => $day['meals'] ?? 'Breakfast, Lunch, Dinner',
                        'sort_order' => $index + 1,
                    ]);
                }
            }
        }

        // Sync Room Options
        $tourPackage->roomOptions()->delete();
        if ($request->has('room_supplements') && is_array($request->room_supplements)) {
            foreach ($request->room_supplements as $roomTypeId => $suppPrice) {
                PackageRoomOption::create([
                    'tour_package_id' => $tourPackage->id,
                    'room_type_id' => $roomTypeId,
                    'supplement_price' => (float) $suppPrice,
                    'is_available' => true,
                ]);
            }
        }

        return redirect()->route('admin.tour-packages.index')->with('success', 'Tour Package updated successfully!');
    }

    public function duplicate(TourPackage $tourPackage)
    {
        $newPackage = $tourPackage->replicate();
        $newPackage->name = $tourPackage->name . ' (Copy)';
        $newPackage->slug = Str::slug($newPackage->name . '-' . Str::random(5));
        $newPackage->save();

        foreach ($tourPackage->itineraryDays as $day) {
            $newDay = $day->replicate();
            $newDay->tour_package_id = $newPackage->id;
            $newDay->save();
        }

        foreach ($tourPackage->roomOptions as $opt) {
            $newOpt = $opt->replicate();
            $newOpt->tour_package_id = $newPackage->id;
            $newOpt->save();
        }

        return redirect()->route('admin.tour-packages.edit', $newPackage->id)->with('success', 'Package duplicated! You can now customize its hotels and prices.');
    }

    public function destroy(TourPackage $tourPackage)
    {
        $tourPackage->delete();
        return redirect()->route('admin.tour-packages.index')->with('success', 'Tour Package deleted.');
    }
}
