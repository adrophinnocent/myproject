<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_email' => 'required|email',
            'site_phone' => 'required|string|max:20',
            'hero_eyebrow' => 'nullable|string',
            'hero_title' => 'nullable|string',
            'hero_subtitle' => 'nullable|string',
            'hero_description' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'season_good_text' => 'nullable|string',
            'season_moderate_text' => 'nullable|string',
            'season_low_text' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'address' => 'nullable|string',
            'whatsapp_number' => 'nullable|string',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'current_season' => 'required|in:peak,shoulder,low',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
            'footer_logo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
            'hero_video' => 'nullable|mimes:mp4,mov,ogg,qt|max:20480',
            'gallery_banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'blog_banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'contact_banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'home_footer_banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'map_background' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:10240',
            'kilimanjaro_home_bg' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'safari_highlights_img' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $fileKeys = ['logo', 'footer_logo', 'hero_video', 'gallery_banner', 'blog_banner', 'contact_banner', 'home_footer_banner', 'map_background', 'featured_image', 'kilimanjaro_home_bg', 'safari_highlights_img'];

        foreach ($validated as $key => $value) {
            if (in_array($key, $fileKeys) && $request->hasFile($key)) {
                $folder = ($key === 'hero_video') ? 'videos' : ($key === 'footer_logo' || $key === 'logo' ? 'logos' : 'banners');
                $path = $request->file($key)->store($folder, 'public');
                Setting::set($key, $path);
            } elseif (!in_array($key, $fileKeys)) {
                Setting::set($key, $value);
            }
        }

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully!');
    }
}
