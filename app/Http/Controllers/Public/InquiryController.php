<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\TourInquiryMail;
use App\Models\Tour;
use App\Models\TourInquiry;
use App\Models\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InquiryController extends Controller
{
    public function store(Request $request, Tour $tour)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        $inquiry = new TourInquiry($validated);
        $inquiry->tour_id = $tour->id;
        $inquiry->status = 'unread';
        $inquiry->save();

        // 1. Send notification to admin
        $adminEmail = \App\Models\Setting::get('site_email', 'info@twinasafaris.com');
        Mail::to($adminEmail)->send(new TourInquiryMail($inquiry));

        // 2. Create Admin Dashboard Notification
        AdminNotification::create([
            'type' => 'inquiry',
            'title' => 'Tour Inquiry: ' . $tour->title,
            'message' => 'New inquiry from ' . $validated['name'],
            'link' => null, // Assuming no admin inquiry view yet
            'is_read' => false
        ]);

        return redirect()->back()->with('success', 'Your inquiry has been sent! We will contact you regarding ' . $tour->title . ' shortly.');
    }
}
