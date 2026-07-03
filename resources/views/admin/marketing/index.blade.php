@extends('admin.layouts.app')

@section('title', 'Marketing & SEO')
@section('page-title', 'Marketing & SEO Engine')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 space-y-6">
            <form method="POST" action="{{ route('admin.marketing.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- SEO SECTION --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-[#166534] to-[#14532D] p-4 text-white flex items-center gap-3">
                        <svg class="w-6 h-6 text-[#F59E0B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <h3 class="font-bold">Search Engine Optimization (SEO)</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Global Meta Title</label>
                            <input type="text" name="seo_title" value="{{ \App\Models\Setting::get('seo_title') }}" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-amber-500/20" placeholder="e.g. Twinasafaris | Best Luxury Safaris in Tanzania">
                            <p class="text-[10px] text-gray-400 mt-1">Appears in Google search results. Keep under 60 characters.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Global Meta Description</label>
                            <textarea name="meta_description" rows="3" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-amber-500/20" placeholder="Describe your business for Google search results...">{{ \App\Models\Setting::get('meta_description') }}</textarea>
                            <p class="text-[10px] text-gray-400 mt-1">Provide a brief summary of your site (150-160 characters) for search engines.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Meta Keywords</label>
                            <textarea name="meta_keywords" rows="2" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-amber-500/20" placeholder="safari, tanzania, serengeti, zanzibar, kilimanjaro">{{ \App\Models\Setting::get('meta_keywords') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Google Search Console Verification</label>
                            <input type="text" name="google_search_console" value="{{ \App\Models\Setting::get('google_search_console') }}" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-amber-500/20" placeholder="<meta name='google-site-verification' content='...'>">
                        </div>
                    </div>
                </div>

                {{-- TRACKING CODES SECTION --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mt-6">
                    <div class="bg-gradient-to-r from-blue-900 to-blue-800 p-4 text-white flex items-center gap-3">
                        <svg class="w-6 h-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        <h3 class="font-bold">Ads & Analytics (Boost Millions of Views)</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Google Analytics ID (GA4)</label>
                                <input type="text" name="google_analytics_id" value="{{ \App\Models\Setting::get('google_analytics_id') }}" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500/20" placeholder="G-XXXXXXXXXX">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Facebook Pixel ID</label>
                                <input type="text" name="facebook_pixel_id" value="{{ \App\Models\Setting::get('facebook_pixel_id') }}" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500/20" placeholder="1234567890">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">reCaptcha Site Key (v3)</label>
                                <input type="text" name="recaptcha_site_key" value="{{ \App\Models\Setting::get('recaptcha_site_key') }}" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500/20">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">reCaptcha Secret Key (v3)</label>
                                <input type="password" name="recaptcha_secret_key" value="{{ \App\Models\Setting::get('recaptcha_secret_key') }}" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500/20">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Custom Header Scripts (Head)</label>
                            <textarea name="header_scripts" rows="4" class="w-full font-mono text-xs bg-gray-50 border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500/20" placeholder="<!-- Paste TikTok Pixel, Pinterest, or Hotjar here -->">{{ \App\Models\Setting::get('header_scripts') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Custom Footer Scripts (Body)</label>
                            <textarea name="footer_scripts" rows="3" class="w-full font-mono text-xs bg-gray-50 border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500/20">{{ \App\Models\Setting::get('footer_scripts') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- SOCIAL MEDIA SHARE SECTION --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mt-6">
                    <div class="bg-gradient-to-r from-pink-600 to-rose-600 p-4 text-white flex items-center gap-3">
                        <svg class="w-6 h-6 text-pink-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                        <h3 class="font-bold">Social Media Viral Marketing</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Default Share Image (Open Graph)</label>
                            @if(\App\Models\Setting::get('og_image'))
                                <img src="{{ asset('storage/' . \App\Models\Setting::get('og_image')) }}" class="w-full h-40 object-cover rounded-xl mb-4 border border-gray-200">
                            @endif
                            <input type="file" name="og_image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100">
                            <p class="text-[10px] text-gray-400 mt-2">Recommended: 1200x630px. This image shows when someone shares your link on FB, Insta, or WhatsApp.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">WhatsApp Auto-Marketing Message</label>
                            <textarea name="whatsapp_marketing_msg" rows="2" class="w-full border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-pink-500/20" placeholder="Hello! I found your amazing safari packages and want to book one...">{{ \App\Models\Setting::get('whatsapp_marketing_msg') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-[#F59E0B] hover:bg-[#d98506] text-[#1a1209] font-bold px-10 py-4 rounded-2xl shadow-lg transition-all hover:scale-105 active:scale-95 flex items-center gap-2">
                        🚀 Boost Website Performance
                    </button>
                </div>
            </form>
        </div>

        {{-- SIDEBAR TIPS --}}
        <div class="space-y-6">
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6">
                <h4 class="text-amber-800 font-bold flex items-center gap-2 mb-4">
                    💡 Marketing Tips
                </h4>
                <ul class="text-xs text-amber-700 space-y-4">
                    <li class="flex gap-2">
                        <span class="font-bold text-amber-500">01</span>
                        <span>Install the <b>Facebook Pixel</b> to retarget people who visited your site but didn't book.</span>
                    </li>
                    <li class="flex gap-2">
                        <span class="font-bold text-amber-500">02</span>
                        <span>Use high-quality photos for the <b>Share Image</b> to get 300% more clicks on social media.</span>
                    </li>
                    <li class="flex gap-2">
                        <span class="font-bold text-amber-500">03</span>
                        <span>Verify your site with <b>Google Search Console</b> to start appearing in top search results.</span>
                    </li>
                </ul>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 text-center">
                <div class="text-3xl mb-2">🔥</div>
                <h4 class="text-blue-900 font-bold mb-1">Need More Traffic?</h4>
                <p class="text-xs text-blue-700 mb-4">Ensure your Tour titles contain keywords like "Best Tanzania Safari" or "Kilimanjaro Trekking".</p>
                <div class="text-[10px] text-blue-500 uppercase tracking-widest font-bold">SEO Optimization Active</div>
            </div>
        </div>

    </div>
</div>
@endsection
