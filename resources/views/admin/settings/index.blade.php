@extends('admin.layouts.app')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <h3 class="text-lg font-semibold text-gray-900 mb-6">General Settings</h3>

        <div class="mb-6 p-4 border border-gray-100 rounded-xl bg-gray-50">
            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Site Logo (Header)</label>
            <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-2xl p-6 bg-white mb-4">
                @if(\App\Models\Setting::get('logo'))
                    <img id="logo-preview" src="{{ asset('storage/' . \App\Models\Setting::get('logo')) }}" alt="Logo" class="h-16 mb-4 object-contain">
                @else
                    <img id="logo-preview" src="" class="hidden h-16 mb-4 object-contain">
                    <div id="logo-placeholder" class="text-2xl mb-2">🖼️</div>
                @endif
                <input type="hidden" name="logo" id="logo_path" value="{{ \App\Models\Setting::get('logo') }}">
                <div class="flex gap-3">
                    <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-media-picker', {detail: {targetId: 'logo_path', previewId: 'logo-preview'}}))" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg font-bold text-[10px] uppercase tracking-widest transition-all">Choose Library</button>
                    <input type="file" name="logo_upload" class="text-[10px] file:bg-gray-100 file:border-none file:px-3 file:py-1.5 file:rounded-md file:font-black file:uppercase">
                </div>
            </div>
        </div>

        <div class="mb-6 p-4 border border-gray-100 rounded-xl bg-gray-50">
            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Favicon (Browser Icon)</label>
            <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-2xl p-6 bg-white mb-4">
                @if(\App\Models\Setting::get('favicon'))
                    <img id="favicon-preview" src="{{ asset('storage/' . \App\Models\Setting::get('favicon')) }}" alt="Favicon" class="w-8 h-8 mb-4 object-contain">
                @else
                    <img id="favicon-preview" src="" class="hidden w-8 h-8 mb-4 object-contain">
                    <div id="favicon-placeholder" class="text-2xl mb-2">📑</div>
                @endif
                <input type="hidden" name="favicon" id="favicon_path" value="{{ \App\Models\Setting::get('favicon') }}">
                <div class="flex gap-3">
                    <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-media-picker', {detail: {targetId: 'favicon_path', previewId: 'favicon-preview'}}))" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg font-bold text-[10px] uppercase tracking-widest transition-all">Choose Library</button>
                    <input type="file" name="favicon_upload" class="text-[10px] file:bg-gray-100 file:border-none file:px-3 file:py-1.5 file:rounded-md file:font-black file:uppercase">
                </div>
            </div>
            <p class="text-[10px] text-gray-500 mt-1">Best size: 32x32 pixels. This icon appears on the browser tab.</p>
        </div>

        <div class="mb-6 p-4 border border-gray-100 rounded-xl bg-gray-50">
            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Footer Logo</label>
            <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-2xl p-6 bg-white mb-4">
                @if(\App\Models\Setting::get('footer_logo'))
                    <img id="footer-logo-preview" src="{{ asset('storage/' . \App\Models\Setting::get('footer_logo')) }}" alt="Footer Logo" class="h-12 mb-4 bg-[#0a0703] p-2 rounded object-contain">
                @else
                    <img id="footer-logo-preview" src="" class="hidden h-12 mb-4 bg-[#0a0703] p-2 rounded object-contain">
                    <div id="footer-logo-placeholder" class="text-2xl mb-2">🖼️</div>
                @endif
                <input type="hidden" name="footer_logo" id="footer_logo_path" value="{{ \App\Models\Setting::get('footer_logo') }}">
                <div class="flex gap-3">
                    <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-media-picker', {detail: {targetId: 'footer_logo_path', previewId: 'footer-logo-preview'}}))" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg font-bold text-[10px] uppercase tracking-widest transition-all">Choose Library</button>
                    <input type="file" name="footer_logo_upload" class="text-[10px] file:bg-gray-100 file:border-none file:px-3 file:py-1.5 file:rounded-md file:font-black file:uppercase">
                </div>
            </div>
            <p class="text-[10px] text-gray-500 mt-1">Recommended size: 512x512 pixels. This logo will appear on the dark footer.</p>
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

        <div class="mb-6 p-4 border border-gray-100 rounded-xl bg-gray-50">
            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Featured Image (About Us Section)</label>
            <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-2xl p-6 bg-white mb-4">
                @if(\App\Models\Setting::get('featured_image'))
                    <img id="featured-preview" src="{{ asset('storage/' . \App\Models\Setting::get('featured_image')) }}" alt="Featured" class="h-32 mb-4 rounded-xl shadow-md object-cover">
                @else
                    <img id="featured-preview" src="" class="hidden h-32 mb-4 rounded-xl shadow-md object-cover">
                    <div id="featured-placeholder" class="text-2xl mb-2">📸</div>
                @endif
                <input type="hidden" name="featured_image" id="featured_image_path" value="{{ \App\Models\Setting::get('featured_image') }}">
                <div class="flex gap-3">
                    <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-media-picker', {detail: {targetId: 'featured_image_path', previewId: 'featured-preview'}}))" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg font-bold text-[10px] uppercase tracking-widest transition-all">Choose Library</button>
                    <input type="file" name="featured_image_upload" class="text-[10px] file:bg-gray-100 file:border-none file:px-3 file:py-1.5 file:rounded-md file:font-black file:uppercase">
                </div>
            </div>
        </div>

        <div class="mb-6 p-4 border border-gray-100 rounded-xl bg-gray-50">
            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Hero Video (Background File)</label>
            <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-200 rounded-2xl p-6 bg-white mb-4">
                @if(\App\Models\Setting::get('hero_video'))
                    <div id="video-preview-container" class="mb-4">
                        <video id="hero-video-preview" src="{{ asset('storage/' . \App\Models\Setting::get('hero_video')) }}" class="h-32 rounded-lg shadow-md" controls muted></video>
                    </div>
                @else
                    <div id="video-preview-container" class="hidden mb-4">
                        <video id="hero-video-preview" src="" class="h-32 rounded-lg shadow-md" controls muted></video>
                    </div>
                    <div id="video-placeholder" class="text-2xl mb-2">🎬</div>
                @endif
                <input type="hidden" name="hero_video" id="hero_video_path" value="{{ \App\Models\Setting::get('hero_video') }}">
                <div class="flex gap-3">
                    <button type="button" onclick="window.dispatchEvent(new CustomEvent('open-media-picker', {detail: {targetId: 'hero_video_path', previewId: 'hero-video-preview', type: 'video'}}))" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg font-bold text-[10px] uppercase tracking-widest transition-all">Choose Library</button>
                    <input type="file" name="hero_video_upload" class="text-[10px] file:bg-gray-100 file:border-none file:px-3 file:py-1.5 file:rounded-md file:font-black file:uppercase">
                </div>
            </div>
            <p class="text-[10px] text-gray-500 mt-1">Recommended: MP4 format. If set, this replaces the home slider.</p>
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
            <label class="block text-sm font-medium text-gray-700 mb-2">TikTok URL</label>
            <input type="url" name="tiktok_url" value="{{ \App\Models\Setting::get('tiktok_url') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
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
