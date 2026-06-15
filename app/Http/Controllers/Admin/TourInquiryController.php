<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TourInquiry;
use Illuminate\Http\Request;

class TourInquiryController extends Controller
{
    public function index()
    {
        $inquiries = TourInquiry::with('tour')->latest()->paginate(15);
        return view('admin.inquiries.index', compact('inquiries'));
    }

    public function show(TourInquiry $inquiry)
    {
        $inquiry->update(['status' => 'read']);
        return view('admin.inquiries.show', compact('inquiry'));
    }

    public function destroy(TourInquiry $inquiry)
    {
        $inquiry->delete();
        return redirect()->route('admin.inquiries.index')->with('success', 'Inquiry deleted.');
    }
}
