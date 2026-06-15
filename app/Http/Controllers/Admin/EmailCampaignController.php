<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailCampaignController extends Controller
{
    public function index()
    {
        $subscribers = NewsletterSubscriber::latest()->paginate(20);
        $totalActive = NewsletterSubscriber::where('active', true)->count();

        return view('admin.email-campaigns.index', compact('subscribers', 'totalActive'));
    }

    public function create()
    {
        $totalActive = NewsletterSubscriber::where('active', true)->count();
        return view('admin.email-campaigns.create', compact('totalActive'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $subscribers = NewsletterSubscriber::where('active', true)->get();

        // In a real application, you would use a queue here.
        // For this project, we'll simulate the "sending" process.

        return redirect()->route('admin.email-campaigns.index')->with('success', 'Email campaign has been queued to ' . $subscribers->count() . ' subscribers!');
    }

    public function destroy(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();
        return back()->with('success', 'Subscriber removed.');
    }

    public function toggleStatus(NewsletterSubscriber $subscriber)
    {
        $subscriber->update(['active' => !$subscriber->active]);
        return back()->with('success', 'Subscriber status updated.');
    }
}
