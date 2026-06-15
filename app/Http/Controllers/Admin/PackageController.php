<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::with('destination')->latest()->paginate(10);

        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        $destinations = Destination::all();

        return view('admin.packages.create', compact('destinations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:packages',
            'category' => 'required|string',
            'duration' => 'required|string',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'destination_id' => 'nullable|exists:destinations,id',
        ]);

        Package::create($validated);

        return redirect()->route('admin.packages.index')->with('success', 'Package created successfully!');
    }

    public function edit(Package $package)
    {
        $destinations = Destination::all();

        return view('admin.packages.edit', compact('package', 'destinations'));
    }

    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:packages,slug,'.$package->id,
            'category' => 'required|string',
            'duration' => 'required|string',
            'price' => 'required|numeric',
            'destination_id' => 'nullable|exists:destinations,id',
        ]);

        $package->update($validated);

        return redirect()->route('admin.packages.index')->with('success', 'Package updated successfully!');
    }

    public function destroy(Package $package)
    {
        $package->delete();

        return redirect()->route('admin.packages.index')->with('success', 'Package deleted successfully!');
    }
}
