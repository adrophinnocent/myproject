<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\NewsletterConfirmationMail;
use App\Models\AdminNotification;
use App\Models\NewsletterSubscriber;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        // reCaptcha v3 Verification (required whenever a secret key is configured)
        $recaptchaSecret = Setting::get('recaptcha_secret_key');
        if ($recaptchaSecret) {
            if (! $request->filled('g-recaptcha-response')) {
                return back()->withErrors(['captcha' => 'ReCaptcha verification failed. Please try again.']);
            }

            $response = Http::asForm()->timeout(10)->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $recaptchaSecret,
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip(),
            ]);

            if (! $response->json('success') || $response->json('score') < 0.5) {
                return back()->withErrors(['captcha' => 'ReCaptcha verification failed. Please try again.']);
            }
        }

        $subscriber = NewsletterSubscriber::where('email', $request->email)->first();

        // Already confirmed: nothing to do.
        if ($subscriber && $subscriber->isConfirmed()) {
            return back()->with('success', __('You are already subscribed to our newsletter. Asante!'));
        }

        // Double opt-in: create (or refresh) a pending subscription and email
        // a confirmation link. The subscriber only becomes active after clicking it.
        if (! $subscriber) {
            $subscriber = new NewsletterSubscriber(['email' => $request->email]);
        }

        $subscriber->fill([
            'name' => $request->name,
            'active' => false,
            'subscribed_at' => now(),
            'confirmation_token' => Str::random(48),
        ])->save();

        try {
            Mail::to($subscriber->email)->send(new NewsletterConfirmationMail($subscriber));
        } catch (\Throwable $e) {
            Log::error('Failed to send newsletter confirmation email: '.$e->getMessage());
        }

        return back()->with('success', __('Almost there! Please check your email and click the confirmation link to activate your subscription.'));
    }

    public function confirm(string $token)
    {
        $subscriber = NewsletterSubscriber::where('confirmation_token', $token)->first();

        if (! $subscriber) {
            return redirect()->route('home')->with('error', __('This confirmation link is invalid or has already been used.'));
        }

        $subscriber->update([
            'active' => true,
            'confirmed_at' => now(),
            'confirmation_token' => null,
        ]);

        AdminNotification::create([
            'type' => 'newsletter',
            'title' => 'New Newsletter Subscriber',
            'message' => $subscriber->name.' ('.$subscriber->email.') confirmed their subscription.',
            'link' => route('admin.email-campaigns.index'),
            'is_read' => false,
        ]);

        return response()->view('public.newsletter.confirmed', compact('subscriber'))
            ->header('X-Robots-Tag', 'noindex, nofollow, noarchive, nosnippet');
    }

    public function unsubscribe(string $token)
    {
        $subscriber = NewsletterSubscriber::where('unsubscribe_token', $token)->first();

        if (! $subscriber) {
            return redirect()->route('home')->with('error', __('This unsubscribe link is invalid.'));
        }

        $subscriber->update(['active' => false]);

        return response()->view('public.newsletter.unsubscribed', compact('subscriber'))
            ->header('X-Robots-Tag', 'noindex, nofollow, noarchive, nosnippet');
    }
}
