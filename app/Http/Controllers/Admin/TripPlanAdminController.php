<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TripPlan;
use Illuminate\Http\Request;

class TripPlanAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = TripPlan::query();
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
        }

        $plans = $query->orderBy('created_at', 'desc')->paginate(20);
        $counts = TripPlan::selectRaw('status, count(*) as c')->groupBy('status')->pluck('c', 'status');

        return view('admin.trip-plans.index', compact('plans', 'counts'));
    }

    public function show(TripPlan $tripPlan)
    {
        $tripPlan->load('messages', 'booking');
        return view('admin.trip-plans.workspace', compact('tripPlan'));
    }

    public function updateStatus(Request $request, TripPlan $tripPlan)
    {
        $validStatuses = [
            'new', 'reviewing', 'preparing_plan', 'sent', 'viewed',
            'changes_requested', 'accepted', 'booking', 'confirmed',
            'closed', 'declined', 'cancelled', 'expired'
        ];

        $request->validate(['status' => 'required|in:' . implode(',', $validStatuses)]);

        $tripPlan->update(['status' => $request->status]);

        if ($request->status === 'sent') {
            $tripPlan->update(['sent_at' => now()]);
            // Logic to send email would go here
        }

        return back()->with('success', 'Status updated to ' . ucfirst(str_replace('_', ' ', $request->status)));
    }

    public function saveItinerary(Request $request, TripPlan $tripPlan)
    {
        $validated = $request->validate([
            'trip_title' => 'required|string|max:255',
            'travel_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:travel_date',
            'itinerary_data' => 'nullable|array',
            'inclusions_data' => 'nullable|array',
            'exclusions_data' => 'nullable|array',
            'terms_conditions' => 'nullable|string',
        ]);

        $tripPlan->update($validated);

        if ($tripPlan->status === 'reviewing') {
            $tripPlan->update(['status' => 'preparing_plan']);
        }

        return back()->with('success', 'Itinerary saved successfully.');
    }

    public function savePricing(Request $request, TripPlan $tripPlan)
    {
        $validated = $request->validate([
            'price_per_person' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'currency' => 'required|string|size:3',
        ]);

        $groupSize = $tripPlan->group_size ?: ($tripPlan->adults + $tripPlan->children);
        $totalBase = $validated['price_per_person'] * $groupSize;
        $totalPrice = $totalBase - ($validated['discount_amount'] ?? 0) + ($validated['tax_amount'] ?? 0);

        $validated['total_price'] = $totalPrice;
        $validated['balance_amount'] = $totalPrice - ($validated['deposit_amount'] ?? 0);

        $tripPlan->update($validated);

        return back()->with('success', 'Pricing updated.');
    }

    public function sendMessage(Request $request, TripPlan $tripPlan)
    {
        $request->validate(['message' => 'required|string']);

        $tripPlan->messages()->create([
            'sender_type' => 'admin',
            'message' => $request->message,
        ]);

        // Logic to notify customer via email/whatsapp

        return back()->with('success', 'Message sent to customer.');
    }

    public function convertToBooking(TripPlan $tripPlan)
    {
        if ($tripPlan->status !== 'accepted' && $tripPlan->status !== 'booking') {
            return back()->with('error', 'Trip plan must be accepted before converting to booking.');
        }

        // Create actual booking
        $booking = \App\Models\Booking::create([
            'first_name' => explode(' ', $tripPlan->name)[0],
            'last_name' => explode(' ', $tripPlan->name)[1] ?? '',
            'email' => $tripPlan->email,
            'phone' => $tripPlan->phone,
            'nationality' => $tripPlan->nationality,
            'number_of_adults' => $tripPlan->adults,
            'number_of_children' => $tripPlan->children,
            'travel_date' => $tripPlan->travel_date,
            'total_price' => $tripPlan->total_price,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'notes' => "Converted from Trip Plan #{$tripPlan->id}",
        ]);

        $tripPlan->update([
            'booking_id' => $booking->id,
            'status' => 'booking'
        ]);

        return redirect()->route('admin.bookings.show', $booking)->with('success', 'Trip plan successfully converted to booking.');
    }

    public function destroy(TripPlan $tripPlan)
    {
        $tripPlan->delete();
        return redirect()->route('admin.trip-plans.index')->with('success', 'Trip plan deleted.');
    }
}
