<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\Tour;
use App\Models\CustomSafariInquiry;
use App\Models\ProposalTemplate;
use App\Models\ProposalDayTemplate;
use App\Mail\ClientProposalMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ProposalController extends Controller
{
    public function index(Request $request)
    {
        $query = Proposal::with(['tour', 'customSafariInquiry'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('client_name', 'like', "%{$search}%")
                  ->orWhere('client_email', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('reference_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $proposals = $query->paginate(15);

        return view('admin.proposals.index', compact('proposals'));
    }

    public function create(Request $request)
    {
        $tours = Tour::published()->orderBy('title')->get();
        $proposalTemplates = ProposalTemplate::orderBy('title')->get();
        $dayTemplates = ProposalDayTemplate::orderBy('title')->get();

        $selectedTour = null;
        $selectedInquiry = null;
        $selectedProposalTemplate = null;

        if ($request->filled('tour_id')) {
            $selectedTour = Tour::find($request->tour_id);
        }

        if ($request->filled('inquiry_id')) {
            $selectedInquiry = CustomSafariInquiry::find($request->inquiry_id);
        }

        if ($request->filled('template_id')) {
            $selectedProposalTemplate = ProposalTemplate::find($request->template_id);
        }

        return view('admin.proposals.create', compact('tours', 'proposalTemplates', 'dayTemplates', 'selectedTour', 'selectedInquiry', 'selectedProposalTemplate'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'client_phone' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
            'tour_id' => 'nullable|exists:tours,id',
            'custom_safari_inquiry_id' => 'nullable|exists:custom_safari_inquiries,id',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'welcome_message' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'valid_until' => 'nullable|date',
            'duration_days' => 'required|integer|min:1',
            'duration_nights' => 'nullable|integer|min:0',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'start_location' => 'nullable|string|max:255',
            'end_location' => 'nullable|string|max:255',
            'route_summary' => 'nullable|string|max:255',
            'destinations' => 'nullable|array',
            'safari_style' => 'nullable|string|max:255',
            'accommodation_level' => 'nullable|string|max:255',
            'transport_type' => 'nullable|string|max:255',
            'is_private' => 'nullable|boolean',
            'highlights' => 'nullable|array',
            'currency' => 'required|string|max:10',
            'subtotal_price' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'price_per_person' => 'nullable|numeric|min:0',
            'adult_price' => 'nullable|numeric|min:0',
            'child_price' => 'nullable|numeric|min:0',
            'child_age_range' => 'nullable|string|max:100',
            'deposit_required' => 'nullable|numeric|min:0',
            'deposit_percentage' => 'nullable|numeric|min:0|max:100',
            'balance_amount' => 'nullable|numeric|min:0',
            'balance_due_date' => 'nullable|date',
            'payment_methods' => 'nullable|string',
            'payment_instructions' => 'nullable|string',
            'payment_terms' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'cancellation_policy' => 'nullable|string',
            'refund_policy' => 'nullable|string',
            'optional_extras' => 'nullable|array',
            'accommodations' => 'nullable|array',
        ]);

        $itinerary = $this->parseItineraryInput($request->input('itinerary', []));
        $inclusions = $this->parseListInput($request->input('inclusions'));
        $exclusions = $this->parseListInput($request->input('exclusions'));
        $highlights = $this->parseListInput($request->input('highlights'));
        $destinations = $this->parseListInput($request->input('destinations'));
        $internalCosting = $this->calculateInternalCosting($request->input('internal_costing', []), $validated['total_price']);

        $proposal = Proposal::create([
            'client_name' => $validated['client_name'],
            'client_email' => $validated['client_email'],
            'client_phone' => $validated['client_phone'] ?? null,
            'country' => $validated['country'] ?? null,
            'tour_id' => $validated['tour_id'] ?? null,
            'custom_safari_inquiry_id' => $validated['custom_safari_inquiry_id'] ?? null,
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'welcome_message' => $validated['welcome_message'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'valid_until' => $validated['valid_until'] ?? now()->addDays(30)->toDateString(),
            'duration_days' => $validated['duration_days'],
            'duration_nights' => $validated['duration_nights'] ?? max(0, $validated['duration_days'] - 1),
            'adults' => $validated['adults'],
            'children' => $validated['children'] ?? 0,
            'start_location' => $validated['start_location'] ?? null,
            'end_location' => $validated['end_location'] ?? null,
            'route_summary' => $validated['route_summary'] ?? null,
            'destinations' => $destinations,
            'safari_style' => $validated['safari_style'] ?? null,
            'accommodation_level' => $validated['accommodation_level'] ?? null,
            'transport_type' => $validated['transport_type'] ?? null,
            'is_private' => $request->has('is_private') ? true : false,
            'highlights' => $highlights,
            'currency' => strtoupper($validated['currency']),
            'subtotal_price' => $validated['subtotal_price'] ?? $validated['total_price'],
            'discount_amount' => $validated['discount_amount'] ?? 0,
            'total_price' => $validated['total_price'],
            'price_per_person' => $validated['price_per_person'] ?? null,
            'adult_price' => $validated['adult_price'] ?? null,
            'child_price' => $validated['child_price'] ?? null,
            'child_age_range' => $validated['child_age_range'] ?? null,
            'deposit_required' => $validated['deposit_required'] ?? null,
            'deposit_percentage' => $validated['deposit_percentage'] ?? 30,
            'balance_amount' => $validated['balance_amount'] ?? max(0, $validated['total_price'] - ($validated['deposit_required'] ?? 0)),
            'balance_due_date' => $validated['balance_due_date'] ?? null,
            'payment_methods' => $validated['payment_methods'] ?? null,
            'payment_instructions' => $validated['payment_instructions'] ?? null,
            'optional_extras' => $request->input('optional_extras', []),
            'accommodations' => $request->input('accommodations', []),
            'payment_terms' => $validated['payment_terms'] ?? null,
            'terms_conditions' => $validated['terms_conditions'] ?? null,
            'cancellation_policy' => $validated['cancellation_policy'] ?? null,
            'refund_policy' => $validated['refund_policy'] ?? null,
            'itinerary' => $itinerary,
            'inclusions' => $inclusions,
            'exclusions' => $exclusions,
            'internal_costing' => $internalCosting,
            'status' => Proposal::STATUS_DRAFT,
        ]);

        if (!empty($validated['custom_safari_inquiry_id'])) {
            $inquiry = CustomSafariInquiry::find($validated['custom_safari_inquiry_id']);
            if ($inquiry) {
                $inquiry->update([
                    'status' => CustomSafariInquiry::STATUS_CONVERTED,
                    'proposal_id' => $proposal->id,
                ]);
            }
        }

        return redirect()->route('admin.proposals.show', $proposal)
            ->with('success', 'Proposal ' . $proposal->full_reference . ' created successfully.');
    }

    public function show(Proposal $proposal)
    {
        $proposal->load(['tour', 'customSafariInquiry']);
        return view('admin.proposals.show', compact('proposal'));
    }

    public function edit(Proposal $proposal)
    {
        $tours = Tour::published()->orderBy('title')->get();
        $proposalTemplates = ProposalTemplate::orderBy('title')->get();
        $dayTemplates = ProposalDayTemplate::orderBy('title')->get();
        return view('admin.proposals.edit', compact('proposal', 'tours', 'proposalTemplates', 'dayTemplates'));
    }

    public function update(Request $request, Proposal $proposal)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'client_phone' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
            'tour_id' => 'nullable|exists:tours,id',
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'welcome_message' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'valid_until' => 'nullable|date',
            'duration_days' => 'required|integer|min:1',
            'duration_nights' => 'nullable|integer|min:0',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'start_location' => 'nullable|string|max:255',
            'end_location' => 'nullable|string|max:255',
            'route_summary' => 'nullable|string|max:255',
            'destinations' => 'nullable|array',
            'safari_style' => 'nullable|string|max:255',
            'accommodation_level' => 'nullable|string|max:255',
            'transport_type' => 'nullable|string|max:255',
            'is_private' => 'nullable|boolean',
            'highlights' => 'nullable|array',
            'currency' => 'required|string|max:10',
            'subtotal_price' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'price_per_person' => 'nullable|numeric|min:0',
            'adult_price' => 'nullable|numeric|min:0',
            'child_price' => 'nullable|numeric|min:0',
            'child_age_range' => 'nullable|string|max:100',
            'deposit_required' => 'nullable|numeric|min:0',
            'deposit_percentage' => 'nullable|numeric|min:0|max:100',
            'balance_amount' => 'nullable|numeric|min:0',
            'balance_due_date' => 'nullable|date',
            'payment_methods' => 'nullable|string',
            'payment_instructions' => 'nullable|string',
            'payment_terms' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'cancellation_policy' => 'nullable|string',
            'refund_policy' => 'nullable|string',
            'optional_extras' => 'nullable|array',
            'accommodations' => 'nullable|array',
            'status' => 'required|string',
        ]);

        $itinerary = $this->parseItineraryInput($request->input('itinerary', []));
        $inclusions = $this->parseListInput($request->input('inclusions'));
        $exclusions = $this->parseListInput($request->input('exclusions'));
        $highlights = $this->parseListInput($request->input('highlights'));
        $destinations = $this->parseListInput($request->input('destinations'));
        $internalCosting = $this->calculateInternalCosting($request->input('internal_costing', []), $validated['total_price']);

        $proposal->update([
            'client_name' => $validated['client_name'],
            'client_email' => $validated['client_email'],
            'client_phone' => $validated['client_phone'] ?? null,
            'country' => $validated['country'] ?? null,
            'tour_id' => $validated['tour_id'] ?? null,
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'welcome_message' => $validated['welcome_message'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'valid_until' => $validated['valid_until'] ?? now()->addDays(30)->toDateString(),
            'duration_days' => $validated['duration_days'],
            'duration_nights' => $validated['duration_nights'] ?? max(0, $validated['duration_days'] - 1),
            'adults' => $validated['adults'],
            'children' => $validated['children'] ?? 0,
            'start_location' => $validated['start_location'] ?? null,
            'end_location' => $validated['end_location'] ?? null,
            'route_summary' => $validated['route_summary'] ?? null,
            'destinations' => $destinations,
            'safari_style' => $validated['safari_style'] ?? null,
            'accommodation_level' => $validated['accommodation_level'] ?? null,
            'transport_type' => $validated['transport_type'] ?? null,
            'is_private' => $request->has('is_private') ? true : false,
            'highlights' => $highlights,
            'currency' => strtoupper($validated['currency']),
            'subtotal_price' => $validated['subtotal_price'] ?? $validated['total_price'],
            'discount_amount' => $validated['discount_amount'] ?? 0,
            'total_price' => $validated['total_price'],
            'price_per_person' => $validated['price_per_person'] ?? null,
            'adult_price' => $validated['adult_price'] ?? null,
            'child_price' => $validated['child_price'] ?? null,
            'child_age_range' => $validated['child_age_range'] ?? null,
            'deposit_required' => $validated['deposit_required'] ?? null,
            'deposit_percentage' => $validated['deposit_percentage'] ?? 30,
            'balance_amount' => $validated['balance_amount'] ?? max(0, $validated['total_price'] - ($validated['deposit_required'] ?? 0)),
            'balance_due_date' => $validated['balance_due_date'] ?? null,
            'payment_methods' => $validated['payment_methods'] ?? null,
            'payment_instructions' => $validated['payment_instructions'] ?? null,
            'optional_extras' => $request->input('optional_extras', []),
            'accommodations' => $request->input('accommodations', []),
            'payment_terms' => $validated['payment_terms'] ?? null,
            'terms_conditions' => $validated['terms_conditions'] ?? null,
            'cancellation_policy' => $validated['cancellation_policy'] ?? null,
            'refund_policy' => $validated['refund_policy'] ?? null,
            'itinerary' => $itinerary,
            'inclusions' => $inclusions,
            'exclusions' => $exclusions,
            'internal_costing' => $internalCosting,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.proposals.show', $proposal)
            ->with('success', 'Proposal ' . $proposal->full_reference . ' updated successfully.');
    }

    public function duplicateVersion(Proposal $proposal)
    {
        $newVersionNumber = $proposal->version + 1;

        $newProposal = $proposal->replicate();
        $newProposal->token = Str::random(32);
        $newProposal->version = $newVersionNumber;
        $newProposal->reference_code = $proposal->reference_code;
        $newProposal->status = Proposal::STATUS_DRAFT;
        $newProposal->sent_at = null;
        $newProposal->viewed_at = null;
        $newProposal->accepted_at = null;
        $newProposal->save();

        return redirect()->route('admin.proposals.edit', $newProposal)
            ->with('success', 'Created new proposal version: ' . $newProposal->full_reference);
    }

    public function destroy(Proposal $proposal)
    {
        $proposal->delete();
        return redirect()->route('admin.proposals.index')
            ->with('success', 'Proposal deleted successfully.');
    }

    public function send(Proposal $proposal)
    {
        try {
            Mail::to($proposal->client_email)->send(new ClientProposalMail($proposal));

            if ($proposal->status === Proposal::STATUS_DRAFT) {
                $proposal->status = Proposal::STATUS_SENT;
            }
            $proposal->sent_at = now();
            $proposal->save();

            return redirect()->back()->with('success', 'Proposal email sent successfully to ' . $proposal->client_email);
        } catch (\Exception $e) {
            if ($proposal->status === Proposal::STATUS_DRAFT) {
                $proposal->status = Proposal::STATUS_SENT;
            }
            $proposal->sent_at = now();
            $proposal->save();

            return redirect()->back()->with('success', 'Proposal status marked as sent. (Mail note: ' . $e->getMessage() . ')');
        }
    }

    public function downloadPdf(Proposal $proposal)
    {
        $pdf = Pdf::loadView('proposals.pdf', compact('proposal'));
        $filename = 'Safari-Proposal-' . $proposal->full_reference . '-' . Str::slug($proposal->client_name) . '.pdf';

        return $pdf->download($filename);
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
                'date' => trim($item['date'] ?? ''),
                'title' => trim($item['title'] ?? ''),
                'destination' => trim($item['destination'] ?? ''),
                'starting_point' => trim($item['starting_point'] ?? ''),
                'ending_point' => trim($item['ending_point'] ?? ''),
                'route' => trim($item['route'] ?? ''),
                'description' => trim($item['description'] ?? ''),
                'activities' => trim($item['activities'] ?? ''),
                'highlights' => trim($item['highlights'] ?? ''),
                'special_notes' => trim($item['special_notes'] ?? ''),
                'optional_activities' => trim($item['optional_activities'] ?? ''),
                'distance' => trim($item['distance'] ?? ''),
                'driving_time' => trim($item['driving_time'] ?? ''),
                'transport_type' => trim($item['transport_type'] ?? ''),
                'meals' => is_array($item['meals'] ?? null) ? implode(', ', $item['meals']) : trim($item['meals'] ?? ''),
                'accommodation_property' => trim($item['accommodation_property'] ?? ($item['accommodation'] ?? '')),
                'room_type' => trim($item['room_type'] ?? ''),
                'accommodation_location' => trim($item['accommodation_location'] ?? ''),
                'nights' => (int) ($item['nights'] ?? 1),
                'meal_plan' => trim($item['meal_plan'] ?? ''),
                'cover_image' => trim($item['cover_image'] ?? ($item['image'] ?? '')),
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

    private function calculateInternalCosting(array $costingInput, float $totalPrice): array
    {
        $accommodation = (float) ($costingInput['accommodation_cost'] ?? $costingInput['accommodation_costs'] ?? 0);
        $park = (float) ($costingInput['park_fees'] ?? 0);
        $vehicle = (float) ($costingInput['vehicle_cost'] ?? $costingInput['vehicle_costs'] ?? 0);
        $guide = (float) ($costingInput['guide_cost'] ?? $costingInput['guide_costs'] ?? 0);
        $meals = (float) ($costingInput['meals_cost'] ?? 0);
        $transfers = (float) ($costingInput['transfers_cost'] ?? 0);
        $flights = (float) ($costingInput['flights_cost'] ?? 0);
        $activities = (float) ($costingInput['activities_cost'] ?? $costingInput['activities_costs'] ?? 0);
        $government = (float) ($costingInput['government_fees'] ?? 0);
        $other = (float) ($costingInput['other_costs'] ?? $costingInput['other_expenses'] ?? 0);

        $totalCost = $accommodation + $park + $vehicle + $guide + $meals + $transfers + $flights + $activities + $government + $other;
        $profit = $totalPrice - $totalCost;
        $profitMargin = $totalPrice > 0 ? round(($profit / $totalPrice) * 100, 2) : 0.0;

        return [
            'accommodation_cost' => $accommodation,
            'park_fees' => $park,
            'vehicle_cost' => $vehicle,
            'guide_cost' => $guide,
            'meals_cost' => $meals,
            'transfers_cost' => $transfers,
            'flights_cost' => $flights,
            'activities_cost' => $activities,
            'government_fees' => $government,
            'other_costs' => $other,
            'total_cost' => $totalCost,
            'markup_type' => $costingInput['markup_type'] ?? 'fixed',
            'markup_value' => (float) ($costingInput['markup_value'] ?? 0),
            'profit' => $profit,
            'profit_margin' => $profitMargin,
        ];
    }
}
