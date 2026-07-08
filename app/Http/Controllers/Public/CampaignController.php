<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignStat;
use App\Models\Lead;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function show(Request $request, $slug)
    {
        $campaign = Campaign::where('slug', $slug)->with(['tour.images', 'tour.category'])->firstOrFail();

        // Fetch related tours from the actual tours table
        $relatedTours = \App\Models\Tour::published()
            ->with(['category', 'destination'])
            ->latest()
            ->take(6)
            ->get();

        // Advanced Tracking with Source Support
        $platform = $request->query('source') ?? $request->query('utm_source');
        if (!$platform) {
            $platform = $this->detectPlatform($request->userAgent(), $request->header('referer'));
        }

        CampaignStat::create([
            'campaign_id' => $campaign->id,
            'type' => 'visit',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->header('referer'),
            'platform' => $platform
        ]);

        return view('public.campaigns.landing', compact('campaign', 'relatedTours'));
    }

    public function trackAction(Request $request, Campaign $campaign)
    {
        CampaignStat::create([
            'campaign_id' => $campaign->id,
            'type' => $request->type ?? 'click',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'platform' => $this->detectPlatform($request->userAgent())
        ]);

        return response()->json(['status' => 'success']);
    }

    public function submitLead(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'country' => 'nullable|string',
            'travel_date' => 'nullable|date',
            'travelers_count' => 'required|integer|min:1',
        ]);

        $lead = Lead::create([
            'campaign_id' => $campaign->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'country' => $validated['country'] ?? null,
            'travel_date' => $validated['travel_date'] ?? null,
            'travelers_count' => $validated['travelers_count'],
        ]);

        // Track conversion
        CampaignStat::create([
            'campaign_id' => $campaign->id,
            'type' => 'conversion',
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Thank you! Our expert will contact you shortly regarding your ' . $campaign->title . ' inquiry.');
    }

    private function detectPlatform($ua, $ref = null)
    {
        $ua = strtolower($ua);
        if (str_contains($ua, 'facebook') || str_contains($ref, 'facebook.com')) return 'facebook';
        if (str_contains($ua, 'instagram') || str_contains($ref, 'instagram.com')) return 'instagram';
        if (str_contains($ua, 'whatsapp')) return 'whatsapp';
        if (str_contains($ua, 'tiktok')) return 'tiktok';
        if (str_contains($ua, 'twitter') || str_contains($ua, 'x.com')) return 'x';
        return 'direct';
    }
}
