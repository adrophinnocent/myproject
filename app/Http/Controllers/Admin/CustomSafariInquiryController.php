<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomSafariInquiry;
use App\Models\Proposal;
use Illuminate\Http\Request;

class CustomSafariInquiryController extends Controller
{
    public function index(Request $request)
    {
        $query = CustomSafariInquiry::latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $inquiries = $query->paginate(15);

        return view('admin.custom-inquiries.index', compact('inquiries'));
    }

    public function show(CustomSafariInquiry $inquiry)
    {
        if ($inquiry->status === CustomSafariInquiry::STATUS_NEW) {
            $inquiry->update(['status' => CustomSafariInquiry::STATUS_REVIEWED]);
        }

        return view('admin.custom-inquiries.show', compact('inquiry'));
    }

    public function convertToProposal(CustomSafariInquiry $inquiry)
    {
        // Redirect directly to proposal creation page with inquiry ID pre-filled
        return redirect()->route('admin.proposals.create', [
            'inquiry_id' => $inquiry->id,
        ])->with('success', 'Converting inquiry from ' . $inquiry->full_name . ' into a personalized proposal.');
    }

    public function destroy(CustomSafariInquiry $inquiry)
    {
        $inquiry->delete();
        return redirect()->route('admin.custom-inquiries.index')
            ->with('success', 'Inquiry deleted successfully.');
    }
}
