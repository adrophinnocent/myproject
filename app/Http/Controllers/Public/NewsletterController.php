<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use App\Models\Setting;
use App\Models\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        // reCaptcha v3 Verification (Only if configured)
        $recaptchaSecret = Setting::get('recaptcha_secret_key');
        if ($recaptchaSecret && $request->has('g-recaptcha-response')) {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => $recaptchaSecret,
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip(),
            ]);

            if (!$response->json('success') || $response->json('score') < 0.5) {
                return back()->withErrors(['captcha' => 'ReCaptcha verification failed. Please try again.']);
            }
        }

        $subscriber = NewsletterSubscriber::updateOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->name,
                'active' => true,
                'subscribed_at' => now()
            ]
        );

        // Create Admin Notification
        AdminNotification::create([
            'type' => 'newsletter',
            'title' => 'New Newsletter Subscriber',
            'message' => $request->name . ' (' . $request->email . ') just subscribed.',
            'link' => null,
            'is_read' => false
        ]);

        return back()->with('success', 'Thank you ' . $request->name . ', you have successfully joined our newsletter!');
    }
}
