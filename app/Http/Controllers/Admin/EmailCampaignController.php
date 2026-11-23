<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailCampaign;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

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
            'content' => 'required|string|max:65000',
        ]);

        $totalActive = NewsletterSubscriber::where('active', true)->count();

        if ($totalActive === 0) {
            return back()->with('error', 'There are no active subscribers to send this campaign to.');
        }

        SendEmailCampaign::dispatch($request->subject, $request->content);

        return redirect()->route('admin.email-campaigns.index')->with('success', 'Email campaign has been queued to '.$totalActive.' subscribers!');
    }

    public function destroy(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();

        return back()->with('success', 'Subscriber removed.');
    }

    public function toggleStatus(NewsletterSubscriber $subscriber)
    {
        $subscriber->update(['active' => ! $subscriber->active]);

        return back()->with('success', 'Subscriber status updated.');
    }
}
