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
        $plans = $query->orderBy('created_at', 'desc')->paginate(20);
        $counts = TripPlan::selectRaw('status, count(*) as c')->groupBy('status')->pluck('c', 'status');

        return view('admin.trip-plans.index', compact('plans', 'counts'));
    }

    public function show(TripPlan $tripPlan)
    {
        return view('admin.trip-plans.show', compact('tripPlan'));
    }

    public function updateStatus(Request $request, TripPlan $tripPlan)
    {
        $request->validate(['status' => 'required|in:new,reviewing,sent,closed']);
        $tripPlan->update(['status' => $request->status, 'admin_notes' => $request->admin_notes]);

        return back()->with('success', 'Status updated!');
    }

    public function destroy(TripPlan $tripPlan)
    {
        $tripPlan->delete();

        return redirect()->route('admin.trip-plans.index')->with('success', 'Trip plan deleted.');
    }
}
