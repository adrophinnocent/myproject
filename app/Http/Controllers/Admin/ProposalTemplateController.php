<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProposalDayTemplate;
use App\Models\ProposalTemplate;
use App\Models\Proposal;
use Illuminate\Http\Request;

class ProposalTemplateController extends Controller
{
    // API/JSON response for inserting day template into builder
    public function getDayTemplates()
    {
        $templates = ProposalDayTemplate::orderBy('title')->get();
        return response()->json($templates);
    }

    public function storeDayTemplate(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'destination' => 'nullable|string|max:255',
            'starting_point' => 'nullable|string|max:255',
            'ending_point' => 'nullable|string|max:255',
            'route' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'activities' => 'nullable|string',
            'highlights' => 'nullable|string',
            'special_notes' => 'nullable|string',
            'optional_activities' => 'nullable|string',
            'distance' => 'nullable|string|max:100',
            'driving_time' => 'nullable|string|max:100',
            'transport_type' => 'nullable|string|max:100',
            'meals' => 'nullable|string|max:255',
            'accommodation_property' => 'nullable|string|max:255',
            'room_type' => 'nullable|string|max:255',
            'accommodation_location' => 'nullable|string|max:255',
            'nights' => 'nullable|integer',
            'meal_plan' => 'nullable|string|max:255',
            'cover_image' => 'nullable|string',
            'gallery_images' => 'nullable|array',
        ]);

        $dayTemplate = ProposalDayTemplate::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'template' => $dayTemplate]);
        }

        return redirect()->back()->with('success', 'Day template "' . $dayTemplate->title . '" saved successfully.');
    }

    public function destroyDayTemplate(ProposalDayTemplate $dayTemplate)
    {
        $dayTemplate->delete();
        return redirect()->back()->with('success', 'Day template deleted successfully.');
    }

    // API/JSON response for complete itinerary templates
    public function getProposalTemplates()
    {
        $templates = ProposalTemplate::orderBy('title')->get();
        return response()->json($templates);
    }

    public function saveProposalAsTemplate(Proposal $proposal)
    {
        $template = ProposalTemplate::create([
            'title' => $proposal->title . ' Template',
            'subtitle' => $proposal->subtitle,
            'duration_days' => $proposal->duration_days,
            'duration_nights' => $proposal->duration_nights,
            'start_location' => $proposal->start_location,
            'end_location' => $proposal->end_location,
            'destinations' => $proposal->destinations,
            'safari_style' => $proposal->safari_style,
            'accommodation_level' => $proposal->accommodation_level,
            'transport_type' => $proposal->transport_type,
            'is_private' => $proposal->is_private,
            'highlights' => $proposal->highlights,
            'itinerary' => $proposal->itinerary,
            'inclusions' => $proposal->inclusions,
            'exclusions' => $proposal->exclusions,
            'optional_extras' => $proposal->optional_extras,
            'accommodations' => $proposal->accommodations,
            'default_total_price' => $proposal->total_price,
            'default_adult_price' => $proposal->adult_price,
            'default_child_price' => $proposal->child_price,
            'payment_terms' => $proposal->payment_terms,
            'terms_conditions' => $proposal->terms_conditions,
            'cancellation_policy' => $proposal->cancellation_policy,
            'internal_costing' => $proposal->internal_costing,
        ]);

        return redirect()->back()->with('success', 'Saved proposal as reusable template "' . $template->title . '".');
    }

    public function destroyProposalTemplate(ProposalTemplate $template)
    {
        $template->delete();
        return redirect()->back()->with('success', 'Proposal template deleted.');
    }
}
