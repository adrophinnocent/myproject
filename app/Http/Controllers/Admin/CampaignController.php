<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::withCount(['leads', 'stats'])->latest()->paginate(10);
        return view('admin.campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        $tours = \App\Models\Tour::orderBy('title')->get();
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('admin.campaigns.create', compact('tours', 'categories'));
    }

    public function getTourData($id)
    {
        $tour = \App\Models\Tour::with(['category', 'destination'])->findOrFail($id);

        // Standardize Itinerary for Landing Page
        $itineraryText = '';
        if (is_array($tour->itinerary)) {
            foreach ($tour->itinerary as $index => $day) {
                $dayTitle = $day['title'] ?? "Day " . ($index + 1);
                $dayDesc = $day['description'] ?? '';
                $itineraryText .= "DAY " . ($index + 1) . ": " . strtoupper($dayTitle) . "\n" . $dayDesc . "\n\n";
            }
        } else {
            $itineraryText = $tour->itinerary;
        }

        $formatList = function($items) {
            if (empty($items)) return '';
            if (is_array($items)) {
                return implode("\n", array_map(function($i) {
                    return is_array($i) ? implode(', ', $i) : $i;
                }, $items));
            }
            return (string)$items;
        };

        return response()->json([
            'title' => $tour->title,
            'description' => $tour->short_description ?? Str::limit(strip_tags($tour->description), 160),
            'itinerary' => trim($itineraryText),
            'highlights' => $formatList($tour->highlights),
            'inclusions' => $formatList($tour->inclusions),
            'exclusions' => $formatList($tour->exclusions),
            'price' => (float) $tour->price,
            'category' => $tour->category?->name ?? 'Luxury Safari',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tour_id' => 'nullable|exists:tours,id',
            'title' => 'required|string|max:255',
            'type' => 'required|string',
            'description' => 'nullable|string',
            'itinerary' => 'nullable|string',
            'highlights' => 'nullable|string',
            'inclusions' => 'nullable|string',
            'exclusions' => 'nullable|string',
            'price' => 'nullable|numeric',
            'image' => 'nullable|image|max:5120',
            'status' => 'required|string',
        ]);

        $validated['slug'] = Str::slug($request->title);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('campaigns', 'public');
        }

        Campaign::create($validated);

        return redirect()->route('admin.campaigns.index')->with('success', 'Advertisement campaign created successfully!');
    }

    public function edit(Campaign $campaign)
    {
        $tours = \App\Models\Tour::orderBy('title')->get();
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('admin.campaigns.edit', compact('campaign', 'tours', 'categories'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'tour_id' => 'nullable|exists:tours,id',
            'title' => 'required|string|max:255',
            'type' => 'required|string',
            'description' => 'nullable|string',
            'itinerary' => 'nullable|string',
            'highlights' => 'nullable|string',
            'inclusions' => 'nullable|string',
            'exclusions' => 'nullable|string',
            'price' => 'nullable|numeric',
            'image' => 'nullable|image|max:5120',
            'status' => 'required|string',
        ]);

        if ($request->hasFile('image')) {
            if ($campaign->image) {
                Storage::disk('public')->delete($campaign->image);
            }
            $validated['image'] = $request->file('image')->store('campaigns', 'public');
        }

        $campaign->update($validated);

        return redirect()->route('admin.campaigns.index')->with('success', 'Campaign updated!');
    }

    public function destroy(Campaign $campaign)
    {
        if ($campaign->image) {
            Storage::disk('public')->delete($campaign->image);
        }
        $campaign->delete();
        return redirect()->route('admin.campaigns.index')->with('success', 'Campaign deleted!');
    }

    public function analytics(Campaign $campaign)
    {
        $stats = $campaign->stats()->latest()->take(100)->get();
        $leads = $campaign->leads()->latest()->get();

        $clicksByType = $campaign->stats()->select('type', \DB::raw('count(*) as count'))->groupBy('type')->pluck('count', 'type');

        return view('admin.campaigns.analytics', compact('campaign', 'stats', 'leads', 'clicksByType'));
    }
}
