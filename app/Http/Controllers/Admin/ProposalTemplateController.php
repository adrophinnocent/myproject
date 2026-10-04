<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProposalTemplate;
use App\Models\ProposalTemplateDay;
use App\Models\ProposalTemplateAccommodation;
use App\Models\ProposalTemplateInclusion;
use App\Models\ProposalTemplateExclusion;
use App\Models\ProposalTemplatePrice;
use App\Models\ProposalDayTemplate;
use App\Models\Proposal;
use Illuminate\Http\Request;

class ProposalTemplateController extends Controller
{
    public function index(Request $request)
    {
        $query = ProposalTemplate::with(['days', 'templateAccommodations', 'templateInclusions', 'templateExclusions', 'templatePrice'])
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('safari_style', 'like', "%{$search}%")
                  ->orWhere('accommodation_level', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            if ($request->status !== 'all') {
                $query->where('status', $request->status);
            }
        } else {
            $query->where('status', 'active');
        }

        $templates = $query->paginate(15);

        return view('admin.proposal-templates.index', compact('templates'));
    }

    public function create()
    {
        $dayTemplates = ProposalDayTemplate::orderBy('title')->get();
        return view('admin.proposal-templates.create', compact('dayTemplates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'duration_days' => 'required|integer|min:1',
            'duration_nights' => 'nullable|integer|min:0',
            'start_location' => 'nullable|string|max:255',
            'end_location' => 'nullable|string|max:255',
            'route_summary' => 'nullable|string|max:255',
            'destinations' => 'nullable|array',
            'safari_style' => 'nullable|string|max:255',
            'accommodation_level' => 'nullable|string|max:255',
            'transport_type' => 'nullable|string|max:255',
            'is_private' => 'nullable|boolean',
            'highlights' => 'nullable|array',
            'default_total_price' => 'required|numeric|min:0',
            'default_adult_price' => 'nullable|numeric|min:0',
            'default_child_price' => 'nullable|numeric|min:0',
            'default_deposit_percentage' => 'nullable|numeric|min:0|max:100',
            'payment_terms' => 'nullable|string',
            'payment_methods' => 'nullable|string',
            'payment_instructions' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'cancellation_policy' => 'nullable|string',
            'refund_policy' => 'nullable|string',
            'optional_extras' => 'nullable|array',
            'status' => 'nullable|string|in:active,archived',
        ]);

        $itinerary = $this->parseItineraryInput($request->input('itinerary', []));
        $inclusions = $this->parseListInput($request->input('inclusions'));
        $exclusions = $this->parseListInput($request->input('exclusions'));
        $highlights = $this->parseListInput($request->input('highlights'));
        $destinations = $this->parseListInput($request->input('destinations'));
        $accommodations = $request->input('accommodations', []);
        $internalCosting = $request->input('internal_costing', []);

        $template = ProposalTemplate::create([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'duration_days' => $validated['duration_days'],
            'duration_nights' => $validated['duration_nights'] ?? max(0, $validated['duration_days'] - 1),
            'start_location' => $validated['start_location'] ?? null,
            'end_location' => $validated['end_location'] ?? null,
            'route_summary' => $validated['route_summary'] ?? null,
            'destinations' => $destinations,
            'safari_style' => $validated['safari_style'] ?? null,
            'accommodation_level' => $validated['accommodation_level'] ?? null,
            'transport_type' => $validated['transport_type'] ?? null,
            'is_private' => $request->has('is_private') ? true : false,
            'highlights' => $highlights,
            'itinerary' => $itinerary,
            'inclusions' => $inclusions,
            'exclusions' => $exclusions,
            'optional_extras' => $validated['optional_extras'] ?? [],
            'accommodations' => $accommodations,
            'default_total_price' => $validated['default_total_price'],
            'default_adult_price' => $validated['default_adult_price'] ?? null,
            'default_child_price' => $validated['default_child_price'] ?? null,
            'default_deposit_percentage' => $validated['default_deposit_percentage'] ?? 30.00,
            'payment_terms' => $validated['payment_terms'] ?? null,
            'payment_methods' => $validated['payment_methods'] ?? null,
            'payment_instructions' => $validated['payment_instructions'] ?? null,
            'terms_conditions' => $validated['terms_conditions'] ?? null,
            'cancellation_policy' => $validated['cancellation_policy'] ?? null,
            'refund_policy' => $validated['refund_policy'] ?? null,
            'internal_costing' => $internalCosting,
            'status' => $validated['status'] ?? 'active',
        ]);

        $this->syncRelationalRecords($template, $itinerary, $accommodations, $inclusions, $exclusions, $internalCosting, $validated);

        return redirect()->route('admin.proposal-templates.index')
            ->with('success', 'Proposal template "' . $template->title . '" created successfully.');
    }

    public function show(ProposalTemplate $template)
    {
        $template->load(['days', 'templateAccommodations', 'templateInclusions', 'templateExclusions', 'templatePrice']);
        return view('admin.proposal-templates.show', compact('template'));
    }

    public function edit(ProposalTemplate $template)
    {
        $template->load(['days', 'templateAccommodations', 'templateInclusions', 'templateExclusions', 'templatePrice']);
        $dayTemplates = ProposalDayTemplate::orderBy('title')->get();
        return view('admin.proposal-templates.edit', compact('template', 'dayTemplates'));
    }

    public function update(Request $request, ProposalTemplate $template)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'duration_days' => 'required|integer|min:1',
            'duration_nights' => 'nullable|integer|min:0',
            'start_location' => 'nullable|string|max:255',
            'end_location' => 'nullable|string|max:255',
            'route_summary' => 'nullable|string|max:255',
            'destinations' => 'nullable|array',
            'safari_style' => 'nullable|string|max:255',
            'accommodation_level' => 'nullable|string|max:255',
            'transport_type' => 'nullable|string|max:255',
            'is_private' => 'nullable|boolean',
            'highlights' => 'nullable|array',
            'default_total_price' => 'required|numeric|min:0',
            'default_adult_price' => 'nullable|numeric|min:0',
            'default_child_price' => 'nullable|numeric|min:0',
            'default_deposit_percentage' => 'nullable|numeric|min:0|max:100',
            'payment_terms' => 'nullable|string',
            'payment_methods' => 'nullable|string',
            'payment_instructions' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'cancellation_policy' => 'nullable|string',
            'refund_policy' => 'nullable|string',
            'optional_extras' => 'nullable|array',
            'status' => 'nullable|string|in:active,archived',
        ]);

        $itinerary = $this->parseItineraryInput($request->input('itinerary', []));
        $inclusions = $this->parseListInput($request->input('inclusions'));
        $exclusions = $this->parseListInput($request->input('exclusions'));
        $highlights = $this->parseListInput($request->input('highlights'));
        $destinations = $this->parseListInput($request->input('destinations'));
        $accommodations = $request->input('accommodations', []);
        $internalCosting = $request->input('internal_costing', []);

        $template->update([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'duration_days' => $validated['duration_days'],
            'duration_nights' => $validated['duration_nights'] ?? max(0, $validated['duration_days'] - 1),
            'start_location' => $validated['start_location'] ?? null,
            'end_location' => $validated['end_location'] ?? null,
            'route_summary' => $validated['route_summary'] ?? null,
            'destinations' => $destinations,
            'safari_style' => $validated['safari_style'] ?? null,
            'accommodation_level' => $validated['accommodation_level'] ?? null,
            'transport_type' => $validated['transport_type'] ?? null,
            'is_private' => $request->has('is_private') ? true : false,
            'highlights' => $highlights,
            'itinerary' => $itinerary,
            'inclusions' => $inclusions,
            'exclusions' => $exclusions,
            'optional_extras' => $validated['optional_extras'] ?? [],
            'accommodations' => $accommodations,
            'default_total_price' => $validated['default_total_price'],
            'default_adult_price' => $validated['default_adult_price'] ?? null,
            'default_child_price' => $validated['default_child_price'] ?? null,
            'default_deposit_percentage' => $validated['default_deposit_percentage'] ?? 30.00,
            'payment_terms' => $validated['payment_terms'] ?? null,
            'payment_methods' => $validated['payment_methods'] ?? null,
            'payment_instructions' => $validated['payment_instructions'] ?? null,
            'terms_conditions' => $validated['terms_conditions'] ?? null,
            'cancellation_policy' => $validated['cancellation_policy'] ?? null,
            'refund_policy' => $validated['refund_policy'] ?? null,
            'internal_costing' => $internalCosting,
            'status' => $validated['status'] ?? $template->status,
        ]);

        $this->syncRelationalRecords($template, $itinerary, $accommodations, $inclusions, $exclusions, $internalCosting, $validated);

        return redirect()->route('admin.proposal-templates.index')
            ->with('success', 'Proposal template "' . $template->title . '" updated successfully.');
    }

    public function duplicate(ProposalTemplate $template)
    {
        $template->load(['days', 'templateAccommodations', 'templateInclusions', 'templateExclusions', 'templatePrice']);

        $newTemplate = $template->replicate();
        $newTemplate->title = 'Copy of ' . $template->title;
        $newTemplate->status = 'active';
        $newTemplate->save();

        foreach ($template->days as $day) {
            $newDay = $day->replicate();
            $newDay->proposal_template_id = $newTemplate->id;
            $newDay->save();
        }

        foreach ($template->templateAccommodations as $acc) {
            $newAcc = $acc->replicate();
            $newAcc->proposal_template_id = $newTemplate->id;
            $newAcc->save();
        }

        foreach ($template->templateInclusions as $inc) {
            $newInc = $inc->replicate();
            $newInc->proposal_template_id = $newTemplate->id;
            $newInc->save();
        }

        foreach ($template->templateExclusions as $exc) {
            $newExc = $exc->replicate();
            $newExc->proposal_template_id = $newTemplate->id;
            $newExc->save();
        }

        if ($template->templatePrice) {
            $newPrice = $template->templatePrice->replicate();
            $newPrice->proposal_template_id = $newTemplate->id;
            $newPrice->save();
        }

        return redirect()->route('admin.proposal-templates.index')
            ->with('success', 'Duplicated proposal template: "' . $newTemplate->title . '"');
    }

    public function archive(ProposalTemplate $template)
    {
        $newStatus = $template->status === 'archived' ? 'active' : 'archived';
        $template->update(['status' => $newStatus]);

        return redirect()->back()->with('success', 'Template status updated to ' . strtoupper($newStatus));
    }

    public function destroy(ProposalTemplate $template)
    {
        $template->delete();
        return redirect()->route('admin.proposal-templates.index')
            ->with('success', 'Proposal template deleted.');
    }

    public function useTemplate(ProposalTemplate $template)
    {
        return redirect()->route('admin.proposals.create', ['template_id' => $template->id, 'start_type' => 'template']);
    }

    // Helper method to sync relational records
    private function syncRelationalRecords(ProposalTemplate $template, array $itinerary, array $accommodations, array $inclusions, array $exclusions, array $internalCosting, array $validated): void
    {
        // 1. Days
        $template->days()->delete();
        foreach ($itinerary as $index => $item) {
            ProposalTemplateDay::create([
                'proposal_template_id' => $template->id,
                'day_number' => (int) ($item['day'] ?? ($index + 1)),
                'title' => $item['title'] ?? '',
                'destination' => $item['destination'] ?? null,
                'starting_point' => $item['starting_point'] ?? null,
                'ending_point' => $item['ending_point'] ?? null,
                'route' => $item['route'] ?? null,
                'description' => $item['description'] ?? null,
                'activities' => $item['activities'] ?? null,
                'optional_activities' => $item['optional_activities'] ?? null,
                'driving_time' => $item['driving_time'] ?? null,
                'meals' => $item['meals'] ?? null,
                'accommodation_property' => $item['accommodation_property'] ?? null,
                'room_type' => $item['room_type'] ?? null,
                'cover_image' => $item['cover_image'] ?? null,
                'gallery_images' => $item['gallery_images'] ?? [],
                'sort_order' => $index,
            ]);
        }

        // 2. Accommodations
        $template->templateAccommodations()->delete();
        foreach ($accommodations as $aIndex => $acc) {
            if (empty($acc['property_name'])) continue;
            ProposalTemplateAccommodation::create([
                'proposal_template_id' => $template->id,
                'property_name' => $acc['property_name'],
                'location' => $acc['location'] ?? null,
                'category' => $acc['category'] ?? null,
                'room_type' => $acc['room_type'] ?? null,
                'nights' => (int) ($acc['nights'] ?? 1),
                'meal_plan' => $acc['meal_plan'] ?? null,
                'description' => $acc['description'] ?? null,
                'image' => $acc['image'] ?? null,
                'website_url' => $acc['website_url'] ?? null,
                'sort_order' => $aIndex,
            ]);
        }

        // 3. Inclusions
        $template->templateInclusions()->delete();
        foreach ($inclusions as $iIndex => $inc) {
            ProposalTemplateInclusion::create([
                'proposal_template_id' => $template->id,
                'item' => $inc,
                'sort_order' => $iIndex,
            ]);
        }

        // 4. Exclusions
        $template->templateExclusions()->delete();
        foreach ($exclusions as $eIndex => $exc) {
            ProposalTemplateExclusion::create([
                'proposal_template_id' => $template->id,
                'item' => $exc,
                'sort_order' => $eIndex,
            ]);
        }

        // 5. Price
        $template->templatePrice()->delete();
        ProposalTemplatePrice::create([
            'proposal_template_id' => $template->id,
            'currency' => 'USD',
            'subtotal_price' => $validated['default_total_price'] ?? 0.00,
            'discount_amount' => 0.00,
            'total_price' => $validated['default_total_price'] ?? 0.00,
            'adult_price' => $validated['default_adult_price'] ?? null,
            'child_price' => $validated['default_child_price'] ?? null,
            'deposit_required' => round((($validated['default_total_price'] ?? 0) * ($validated['default_deposit_percentage'] ?? 30)) / 100, 2),
            'deposit_percentage' => $validated['default_deposit_percentage'] ?? 30.00,
            'accommodation_cost' => (float) ($internalCosting['accommodation_cost'] ?? 0),
            'park_fees' => (float) ($internalCosting['park_fees'] ?? 0),
            'vehicle_cost' => (float) ($internalCosting['vehicle_cost'] ?? 0),
            'guide_cost' => (float) ($internalCosting['guide_cost'] ?? 0),
            'meals_cost' => (float) ($internalCosting['meals_cost'] ?? 0),
            'transfers_cost' => (float) ($internalCosting['transfers_cost'] ?? 0),
            'flights_cost' => (float) ($internalCosting['flights_cost'] ?? 0),
            'activities_cost' => (float) ($internalCosting['activities_cost'] ?? 0),
            'government_fees' => (float) ($internalCosting['government_fees'] ?? 0),
            'other_costs' => (float) ($internalCosting['other_costs'] ?? 0),
        ]);
    }

    // API / Backward-compatibility methods
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

    public function getProposalTemplates()
    {
        $templates = ProposalTemplate::with(['days', 'templateAccommodations', 'templateInclusions', 'templateExclusions', 'templatePrice'])->orderBy('title')->get();
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
            'route_summary' => $proposal->route_summary,
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
            'default_deposit_percentage' => $proposal->deposit_percentage ?? 30.00,
            'payment_terms' => $proposal->payment_terms,
            'payment_methods' => $proposal->payment_methods,
            'payment_instructions' => $proposal->payment_instructions,
            'terms_conditions' => $proposal->terms_conditions,
            'cancellation_policy' => $proposal->cancellation_policy,
            'refund_policy' => $proposal->refund_policy,
            'internal_costing' => $proposal->internal_costing,
            'status' => 'active',
        ]);

        $this->syncRelationalRecords(
            $template,
            $proposal->itinerary ?? [],
            $proposal->accommodations ?? [],
            $proposal->inclusions ?? [],
            $proposal->exclusions ?? [],
            $proposal->internal_costing ?? [],
            ['default_total_price' => $proposal->total_price, 'default_adult_price' => $proposal->adult_price, 'default_child_price' => $proposal->child_price, 'default_deposit_percentage' => $proposal->deposit_percentage]
        );

        return redirect()->back()->with('success', 'Saved proposal as reusable template "' . $template->title . '".');
    }

    public function destroyProposalTemplate(ProposalTemplate $template)
    {
        $template->delete();
        return redirect()->back()->with('success', 'Proposal template deleted.');
    }

    private function parseItineraryInput($rawItinerary): array
    {
        if (is_string($rawItinerary)) {
            $decoded = json_decode($rawItinerary, true);
            $rawItinerary = is_array($decoded) ? $decoded : [];
        }

        if (!is_array($rawItinerary)) {
            return [];
        }

        $parsed = [];
        foreach ($rawItinerary as $index => $item) {
            if (empty($item['title']) && empty($item['description'])) {
                continue;
            }

            $gallery = [];
            if (!empty($item['gallery_images'])) {
                if (is_string($item['gallery_images'])) {
                    $decodedG = json_decode($item['gallery_images'], true);
                    $gallery = is_array($decodedG) ? $decodedG : [];
                } elseif (is_array($item['gallery_images'])) {
                    $gallery = $item['gallery_images'];
                }
            }

            $parsed[] = [
                'day' => (int) ($item['day'] ?? ($index + 1)),
                'title' => trim($item['title'] ?? ''),
                'destination' => trim($item['destination'] ?? ''),
                'starting_point' => trim($item['starting_point'] ?? ''),
                'ending_point' => trim($item['ending_point'] ?? ''),
                'route' => trim($item['route'] ?? ''),
                'description' => trim($item['description'] ?? ''),
                'activities' => trim($item['activities'] ?? ''),
                'optional_activities' => trim($item['optional_activities'] ?? ''),
                'driving_time' => trim($item['driving_time'] ?? ''),
                'meals' => is_array($item['meals'] ?? null) ? implode(', ', $item['meals']) : trim($item['meals'] ?? ''),
                'accommodation_property' => trim($item['accommodation_property'] ?? ($item['accommodation'] ?? '')),
                'room_type' => trim($item['room_type'] ?? ''),
                'cover_image' => trim($item['cover_image'] ?? ''),
                'gallery_images' => $gallery,
            ];
        }

        return $parsed;
    }

    private function parseListInput($input): array
    {
        if (is_array($input)) {
            return array_values(array_filter(array_map('trim', $input)));
        }

        if (is_string($input)) {
            $lines = preg_split('/\r\n|\r|\n/', $input);
            return array_values(array_filter(array_map('trim', $lines)));
        }

        return [];
    }
}
