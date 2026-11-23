<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\TourInquiryMail;
use App\Models\AdminNotification;
use App\Models\Setting;
use App\Models\Tour;
use App\Models\TourInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class InquiryController extends Controller
{
    public function store(Request $request, Tour $tour)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $inquiry = new TourInquiry($validated);
        $inquiry->tour_id = $tour->id;
        $inquiry->status = 'unread';
        $inquiry->save();

        // 1. Send notification to admin
        try {
            $adminEmail = Setting::publicEmail();
            Mail::to($adminEmail)->send(new TourInquiryMail($inquiry));
        } catch (\Throwable $e) {
            Log::error('Failed to send tour inquiry notification: '.$e->getMessage());
        }

        // 2. Create Admin Dashboard Notification
        AdminNotification::create([
            'type' => 'inquiry',
            'title' => 'Tour Inquiry: '.$tour->title,
            'message' => 'New inquiry from '.$validated['name'],
            'link' => route('admin.inquiries.show', $inquiry),
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'Your inquiry has been sent! We will contact you regarding '.$tour->title.' shortly.');
    }
}
