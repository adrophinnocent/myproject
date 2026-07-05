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
            'featured_image_upload' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'season_good_text' => 'nullable|string',
            'season_moderate_text' => 'nullable|string',
            'season_low_text' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'address' => 'nullable|string',
            'whatsapp_number' => 'nullable|string',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'tiktok_url' => 'nullable|url',
            'google_maps_url' => 'nullable|url',
            'current_season' => 'required|in:peak,shoulder,low',
            'logo_upload' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
            'favicon_upload' => 'nullable|image|mimes:jpg,jpeg,png,ico|max:1024',
            'footer_logo_upload' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
            'hero_video_upload' => 'nullable|mimes:mp4,mov,ogg,qt|max:20480',
            'gallery_banner_upload' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'blog_banner_upload' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'contact_banner_upload' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'home_footer_banner_upload' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'map_background_upload' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:10240',
            'kilimanjaro_home_bg_upload' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'safari_highlights_img_upload' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $fileKeys = ['logo', 'favicon', 'footer_logo', 'hero_video', 'gallery_banner', 'blog_banner', 'contact_banner', 'about_banner', 'home_footer_banner', 'map_background', 'featured_image', 'kilimanjaro_home_bg', 'safari_highlights'];

        foreach ($request->all() as $key => $value) {
            if (in_array($key, $fileKeys)) {
                $uploadKey = $key . '_upload';
                if ($request->hasFile($uploadKey)) {
                    $file = $request->file($uploadKey);
                    $folder = ($key === 'hero_video') ? 'videos' : ($key === 'footer_logo' || $key === 'logo' || $key === 'favicon' ? 'logos' : 'banners');

                    if (str_contains($file->getMimeType(), 'image') && $key !== 'favicon') {
                        $filename = $key . '_' . time() . '.webp';
                        $img = imagecreatefromstring(file_get_contents($file->getRealPath()));
                        ob_start();
                        imagewebp($img, null, 80);
                        $content = ob_get_clean();
                        Storage::disk('public')->put($folder . '/' . $filename, $content);
                        Setting::set($key, $folder . '/' . $filename);
                        imagedestroy($img);
                    } else {
                        Setting::set($key, $file->store($folder, 'public'));
                    }
                } elseif ($request->filled($key)) {
                    // Selected from library or existing value
                    Setting::set($key, $request->input($key));
                }
            } elseif ($key !== '_token' && $key !== '_method' && !str_ends_with($key, '_upload')) {
                Setting::set($key, $value);
            }
        }

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully!');
    }
}
