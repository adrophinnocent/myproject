<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class MarketingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.marketing.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'seo_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string',
            'google_analytics_id' => 'nullable|string|max:50',
            'facebook_pixel_id' => 'nullable|string|max:50',
            'google_search_console' => 'nullable|string',
            'header_scripts' => 'nullable|string',
            'footer_scripts' => 'nullable|string',
            'og_image' => 'nullable|image|max:5120',
            'whatsapp_marketing_msg' => 'nullable|string',
            'recaptcha_site_key' => 'nullable|string|max:100',
            'recaptcha_secret_key' => 'nullable|string|max:100',
        ]);

        foreach ($request->except(['_token', '_method', 'og_image']) as $key => $value) {
            Setting::set($key, $value);
        }

        if ($request->hasFile('og_image')) {
            $path = $request->file('og_image')->store('marketing', 'public');
            Setting::set('og_image', $path);
        }

        return redirect()->route('admin.marketing.index')->with('success', 'Marketing and SEO settings updated successfully!');
    }
}
