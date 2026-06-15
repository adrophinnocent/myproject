<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\ContactNotification;
use App\Mail\ContactAcknowledgmentMail;
use App\Models\Contact;
use App\Models\Slider;
use App\Models\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        $banner = Slider::where('page', 'contact')->active()->first();
        return view('public.contact', compact('banner'));
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $contact = Contact::create($validated);

        // 1. Send contact notification to admin
        $adminEmail = \App\Models\Setting::get('site_email', 'info@twinasafaris.com');
        Mail::to($adminEmail)->send(new ContactNotification($validated));

        // 2. Send automatic acknowledgment to customer
        Mail::to($validated['email'])->send(new ContactAcknowledgmentMail($validated));

        // 3. Create Admin Dashboard Notification
        AdminNotification::create([
            'type' => 'contact',
            'title' => 'New Contact Message',
            'message' => 'Message from ' . $validated['name'] . ': ' . $validated['subject'],
            'link' => null, // Assuming no admin contact view yet, or link to a general list
            'is_read' => false
        ]);

        return redirect()->back()->with('success', 'Thank you for your message! We have received your inquiry and will get back to you soon.');
    }
}
