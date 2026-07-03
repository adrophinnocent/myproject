@extends('admin.layouts.app')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <h3 class="text-lg font-semibold text-gray-900 mb-6">General Settings</h3>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Site Logo (Header)</label>
            @if(\App\Models\Setting::get('logo'))
                <img src="{{ asset('storage/' . \App\Models\Setting::get('logo')) }}" alt="Logo" class="h-16 mb-4">
            @endif
            <input type="file" name="logo" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Favicon (Browser Icon)</label>
            @if(\App\Models\Setting::get('favicon'))
                <img src="{{ asset('storage/' . \App\Models\Setting::get('favicon')) }}" alt="Favicon" class="w-8 h-8 mb-4">
            @endif
            <input type="file" name="favicon" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
            <p class="text-xs text-gray-500 mt-1">Best size: 32x32 pixels. This icon appears on the browser tab.</p>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Footer Logo</label>
            @if(\App\Models\Setting::get('footer_logo'))
                <img src="{{ asset('storage/' . \App\Models\Setting::get('footer_logo')) }}" alt="Footer Logo" class="h-16 mb-4 bg-[#0a0703] p-2 rounded">
            @endif
            <input type="file" name="footer_logo" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
            <p class="text-xs text-gray-500 mt-1">Recommended size: 512x512 pixels. This logo will appear on the dark footer.</p>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Site Name</label>
            <input type="text" name="site_name" value="{{ \App\Models\Setting::get('site_name', 'Twinasafaris') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Site Email</label>
            <input type="email" name="site_email" value="{{ \App\Models\Setting::get('site_email', 'info@twinasafaris.com') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Site Phone</label>
            <input type="text" name="site_phone" value="{{ \App\Models\Setting::get('site_phone', '+255 754 000 000') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">WhatsApp Number</label>
            <input type="text" name="whatsapp_number" value="{{ \App\Models\Setting::get('whatsapp_number', '+255 754 000 000') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Featured Image (About Us Section)</label>
            @if(\App\Models\Setting::get('featured_image'))
                <img src="{{ asset('storage/' . \App\Models\Setting::get('featured_image')) }}" alt="Featured Image" class="h-32 mb-4 rounded-xl shadow-md">
            @endif
            <input type="file" name="featured_image" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
            <p class="text-xs text-gray-500 mt-1">This image will replace the video embed in the "Our Story" section on the Home and About pages.</p>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Hero Video (Background File)</label>
            @if(\App\Models\Setting::get('hero_video'))
                <div class="mb-4 text-xs text-green-600">Current video: {{ \App\Models\Setting::get('hero_video') }}</div>
            @endif
            <input type="file" name="hero_video" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
            <p class="text-xs text-gray-500 mt-1">Recommended: MP4 format, under 20MB. If uploaded, this will replace the hero slider.</p>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Hero Eyebrow (Tagline)</label>
            <input type="text" name="hero_eyebrow" value="{{ \App\Models\Setting::get('hero_eyebrow', 'Tanzania\'s #1 Boutique Safari Operator') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Hero Title</label>
            <input type="text" name="hero_title" value="{{ \App\Models\Setting::get('hero_title', 'Experience Africa\'s') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Hero Subtitle (Italic Text)</label>
            <input type="text" name="hero_subtitle" value="{{ \App\Models\Setting::get('hero_subtitle', '') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Hero Main Description</label>
            <textarea name="hero_description" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ \App\Models\Setting::get('hero_description', 'Unforgettable luxury safaris, Kilimanjaro summits, and Zanzibar escapes designed specifically for you.') }}</textarea>
        </div>

        <h3 class="text-lg font-semibold text-gray-900 mb-6 mt-8">Season Indicator Content</h3>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Good Season Description</label>
            <textarea name="season_good_text" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ \App\Models\Setting::get('season_good_text', 'June-October: Excellent wildlife viewing with long dry days.') }}</textarea>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Moderate Season Description</label>
            <textarea name="season_moderate_text" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ \App\Models\Setting::get('season_moderate_text', 'Jan-Feb & Nov-Dec: Green landscapes, fewer crowds, great value.') }}</textarea>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Low Season Description</label>
            <textarea name="season_low_text" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ \App\Models\Setting::get('season_low_text', 'March-May: Long rains, incredible birdwatching, amazing deals.') }}</textarea>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Travel Conditions Indicator</label>
            <select name="current_season" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                <option value="peak" {{ \App\Models\Setting::get('current_season', 'peak') === 'peak' ? 'selected' : '' }}>🟢 Peak Season — Steady Green</option>
                <option value="shoulder" {{ \App\Models\Setting::get('current_season', 'peak') === 'shoulder' ? 'selected' : '' }}>🟡 Green Season — Slow Yellow Flashing</option>
                <option value="low" {{ \App\Models\Setting::get('current_season', 'peak') === 'low' ? 'selected' : '' }}>🔴 Low Season — Fast Red Flashing</option>
            </select>
            <p class="text-xs text-gray-500 mt-2">If set, this will override the automatic season calculation based on the current month.</p>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
            <textarea name="meta_description" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ \App\Models\Setting::get('meta_description') }}</textarea>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
            <textarea name="address" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ \App\Models\Setting::get('address') }}</textarea>
        </div>

        <h3 class="text-lg font-semibold text-gray-900 mb-6 mt-8">Page Banners</h3>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Gallery Page Banner</label>
            @if(\App\Models\Setting::get('gallery_banner'))
                <img src="{{ asset('storage/' . \App\Models\Setting::get('gallery_banner')) }}" class="h-20 mb-4 rounded shadow-sm">
            @endif
            <input type="file" name="gallery_banner" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Blog Page Banner</label>
            @if(\App\Models\Setting::get('blog_banner'))
                <img src="{{ asset('storage/' . \App\Models\Setting::get('blog_banner')) }}" class="h-20 mb-4 rounded shadow-sm">
            @endif
            <input type="file" name="blog_banner" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Page Banner</label>
            @if(\App\Models\Setting::get('contact_banner'))
                <img src="{{ asset('storage/' . \App\Models\Setting::get('contact_banner')) }}" class="h-20 mb-4 rounded shadow-sm">
            @endif
            <input type="file" name="contact_banner" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Home Footer Banner (Joins with Footer)</label>
            @if(\App\Models\Setting::get('home_footer_banner'))
                <img src="{{ asset('storage/' . \App\Models\Setting::get('home_footer_banner')) }}" class="h-20 mb-4 rounded shadow-sm">
            @endif
            <input type="file" name="home_footer_banner" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
            <p class="text-xs text-gray-500 mt-1">High-resolution landscape image. This will appear above the footer on the Home page.</p>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Interactive Safari Map Background</label>
            @if(\App\Models\Setting::get('map_background'))
                <img src="{{ asset('storage/' . \App\Models\Setting::get('map_background')) }}" class="h-20 mb-4 rounded shadow-sm">
            @endif
            <input type="file" name="map_background" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
            <p class="text-xs text-gray-500 mt-1">High-resolution landscape image of Africa with wildlife (Big 5). Recommended size: 1920x1080 pixels.</p>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Mount Kilimanjaro Section Background</label>
            @if(\App\Models\Setting::get('kilimanjaro_home_bg'))
                <img src="{{ asset('storage/' . \App\Models\Setting::get('kilimanjaro_home_bg')) }}" class="h-20 mb-4 rounded shadow-sm">
            @endif
            <input type="file" name="kilimanjaro_home_bg" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
            <p class="text-xs text-gray-500 mt-1">Landscape photo of Kilimanjaro. This will appear as a darkened background behind the "Conquer Mt. Kilimanjaro" text.</p>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Safari Highlights Section Image</label>
            @if(\App\Models\Setting::get('safari_highlights_img'))
                <img src="{{ asset('storage/' . \App\Models\Setting::get('safari_highlights_img')) }}" class="h-32 mb-4 rounded shadow-sm">
            @endif
            <input type="file" name="safari_highlights_img" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm">
            <p class="text-xs text-gray-500 mt-1">Image for the "Unrivaled Adventures / Iconic Safari Journeys" section.</p>
        </div>

        <h3 class="text-lg font-semibold text-gray-900 mb-6 mt-8">Social Media</h3>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Facebook URL</label>
            <input type="url" name="facebook_url" value="{{ \App\Models\Setting::get('facebook_url') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Instagram URL</label>
            <input type="url" name="instagram_url" value="{{ \App\Models\Setting::get('instagram_url') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Twitter/X URL</label>
            <input type="url" name="twitter_url" value="{{ \App\Models\Setting::get('twitter_url') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">YouTube URL</label>
            <input type="url" name="youtube_url" value="{{ \App\Models\Setting::get('youtube_url') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Google Maps Business Link</label>
            <input type="url" name="google_maps_url" value="{{ \App\Models\Setting::get('google_maps_url') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]" placeholder="https://maps.app.goo.gl/...">
            <p class="text-xs text-gray-500 mt-1">This link allows customers to leave reviews and find your office location.</p>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-[#D4AF37] hover:bg-[#b8920d] text-[#1a1209] font-semibold px-6 py-2 rounded-lg transition-colors">Save Changes</button>
        </div>
    </form>
</div>
@endsection
