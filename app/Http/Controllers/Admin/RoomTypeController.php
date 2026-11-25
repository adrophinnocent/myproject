<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoomTypeController extends Controller
{
    public function index()
    {
        $roomTypes = RoomType::orderBy('sort_order')->orderBy('name')->get();

        return view('admin.room-types.index', compact('roomTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'max_occupants' => 'required|integer|min:1|max:10',
            'default_supplement_price' => 'nullable|numeric|min:0',
            'is_active' => 'nullable',
            'sort_order' => 'nullable|integer',
        ]);

        RoomType::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'max_occupants' => $validated['max_occupants'],
            'default_supplement_price' => $validated['default_supplement_price'] ?? 0.00,
            'is_active' => $request->has('is_active'),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.room-types.index')->with('success', 'Room type created successfully!');
    }

    public function update(Request $request, RoomType $roomType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'max_occupants' => 'required|integer|min:1|max:10',
            'default_supplement_price' => 'nullable|numeric|min:0',
            'is_active' => 'nullable',
            'sort_order' => 'nullable|integer',
        ]);

        $roomType->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'max_occupants' => $validated['max_occupants'],
            'default_supplement_price' => $validated['default_supplement_price'] ?? 0.00,
            'is_active' => $request->has('is_active'),
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.room-types.index')->with('success', 'Room type updated!');
    }

    public function destroy(RoomType $roomType)
    {
        $roomType->delete();

        return redirect()->route('admin.room-types.index')->with('success', 'Room type deleted.');
    }
}
