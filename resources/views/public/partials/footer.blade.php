@php
    $siteName = \App\Models\Setting::get('site_name', 'Twina Safaris');
    $siteEmail = \App\Models\Setting::get('site_email', 'twinasafaris@gmail.com');
    $sitePhone = \App\Models\Setting::get('site_phone', '+255 754 000 000');
    $logo = \App\Models\Setting::get('logo');
    $footerLogo = \App\Models\Setting::get('footer_logo');
@endphp
<footer class="bg-[#0a0703] pt-20 pb-10 border-t border-white/5 relative overflow-hidden">
    {{-- Subtle pattern background hint --}}
    <div class="absolute inset-0 opacity-[0.02] pointer-events-none">
        <svg class="w-full h-full" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none">
            <path d="M0 50 Q 25 40 50 50 T 100 50" stroke="white" fill="transparent" stroke-width="0.1"/>
        </svg>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">

            {{-- Column 1: Brand & About --}}
            <div class="space-y-6">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <img src="{{ \App\Helpers\AssetHelper::getLogoUrl('footer_logo') ?: \App\Helpers\AssetHelper::getLogoUrl() }}" alt="{{ \App\Helpers\AssetHelper::asString($siteName) }}" class="h-12 w-auto object-contain">
                    <div class="flex flex-col">
                        <span class="font-display text-2xl font-bold text-white leading-tight">{{ \App\Helpers\AssetHelper::asString($siteName) }}</span>
                        <span class="text-gold-400 text-[10px] tracking-widest uppercase font-bold">Est. 2009 · Tanzania</span>
                    </div>
                </a>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Crafting unforgettable safari experiences since 2010. We specialize in personalized itineraries, luxury lodges, and authentic adventures.
                </p>
                <div class="flex gap-4 pt-2">
                    <a href="{{ \App\Models\Setting::get('facebook_url', '#') }}" class="w-10 h-10 bg-white/5 hover:bg-gold-500 text-white hover:text-safari-dark rounded-full flex items-center justify-center transition-all border border-white/5 shadow-sm">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a href="{{ \App\Models\Setting::get('instagram_url', '#') }}" class="w-10 h-10 bg-white/5 hover:bg-gold-500 text-white hover:text-safari-dark rounded-full flex items-center justify-center transition-all border border-white/5 shadow-sm">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="{{ \App\Models\Setting::get('tiktok_url', '#') }}" class="w-10 h-10 bg-white/5 hover:bg-gold-500 text-white hover:text-safari-dark rounded-full flex items-center justify-center transition-all border border-white/5 shadow-sm">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 448 512"><path d="M448 209.9a210.1 210.1 0 0 1 -122.8-39.3V349.4A162.6 162.6 0 1 1 185 188.3V278.2a74.6 74.6 0 1 0 52.2 71.2V0l88 0a121.2 121.2 0 0 0 1.9 22.2h0A122.2 122.2 0 0 0 381 102.4a121.4 121.4 0 0 0 67 20.1z"/></svg>
                    </a>
                    @if(\App\Models\Setting::get('google_maps_url'))
                    <a href="{{ \App\Models\Setting::get('google_maps_url') }}" target="_blank" class="w-10 h-10 bg-white/5 hover:bg-gold-500 text-white hover:text-safari-dark rounded-full flex items-center justify-center transition-all border border-white/5 shadow-sm" title="Find us on Google Maps">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </a>
                    @endif
                </div>
            </div>

            {{-- Column 2: Quick Links & Popular --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-6">
                    <h4 class="font-bold text-white uppercase tracking-wider text-[11px]">Quick Links</h4>
                    <ul class="space-y-4 text-sm">
                        <li><a href="{{ route('tours.index') }}" class="text-gray-500 hover:text-gold-400 transition-colors">All Tours</a></li>
                        <li><a href="{{ route('trip-plan.index') }}" class="text-gray-500 hover:text-gold-400 transition-colors">Plan Your Trip</a></li>
                        <li><a href="{{ route('about') }}" class="text-gray-500 hover:text-gold-400 transition-colors">About Us</a></li>
                        <li><a href="{{ route('contact.index') }}" class="text-gray-500 hover:text-gold-400 transition-colors">Contact</a></li>
                    </ul>
                </div>
                <div class="space-y-6">
                    <h4 class="font-bold text-white uppercase tracking-wider text-[11px] flex items-center gap-2">
                        <svg class="w-3 h-3 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        Popular
                    </h4>
                    <ul class="space-y-4 text-sm">
                        <li>
                            <a href="{{ route('tours.index', ['destination' => 'serengeti']) }}" class="flex items-center gap-2.5 text-gray-500 hover:text-gold-400 transition-colors group">
                                <svg class="w-4 h-4 text-gold-500/50 group-hover:text-gold-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2h1.5a2.5 2.5 0 012.5 2.5V14a2 2 0 002 2h.105M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Serengeti
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tours.index', ['destination' => 'kilimanjaro']) }}" class="flex items-center gap-2.5 text-gray-500 hover:text-gold-400 transition-colors group">
                                <svg class="w-4 h-4 text-gold-500/50 group-hover:text-gold-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2L2 22h20L12 2zm0 4.5L17.5 18H6.5L12 6.5z"/></svg>
                                Kilimanjaro
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tours.index', ['destination' => 'zanzibar']) }}" class="flex items-center gap-2.5 text-gray-500 hover:text-gold-400 transition-colors group">
                                <svg class="w-4 h-4 text-gold-500/50 group-hover:text-gold-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.5 11l.5-1m3 1l-.5-1M12 11l.5-1m3 1l-.5-1m-7 5l-.5 1m3-1l.5 1M12 16l-.5 1m3-1l.5 1M4.5 5.5l.5-1m14 1l-.5-1M7.5 4h10M5 20h14a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v14a1 1 0 001 1z"/></svg>
                                Zanzibar
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('tours.index', ['destination' => 'ngorongoro']) }}" class="flex items-center gap-2.5 text-gray-500 hover:text-gold-400 transition-colors group">
                                <svg class="w-4 h-4 text-gold-500/50 group-hover:text-gold-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Ngorongoro
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="space-y-6">
                <h4 class="font-bold text-white uppercase tracking-wider text-[11px] flex items-center gap-2">
                    <svg class="w-3 h-3 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Get In Touch
                </h4>
                <ul class="space-y-4 text-sm">
                    <li class="flex items-start gap-3 text-gray-500 group">
                        <svg class="w-4 h-4 text-gold-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Moshi, Kilimanjaro</span>
                    </li>
                    <li class="flex items-center gap-3 text-gray-500 group">
                        <svg class="w-4 h-4 text-gold-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <a href="mailto:{{ $siteEmail }}" class="hover:text-gold-400 transition-colors">{{ $siteEmail }}</a>
                    </li>
                    <li class="flex items-center gap-3 text-white font-bold group">
                        <svg class="w-4 h-4 text-gold-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <a href="tel:{{ $sitePhone }}" class="hover:text-gold-400 transition-colors">{{ $sitePhone }}</a>
                    </li>
                </ul>
            </div>

            {{-- Column 4: Integrated Newsletter --}}
            <div class="bg-white/5 p-8 rounded-[2.5rem] border border-white/10 shadow-2xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gold-500/5 rounded-full -mr-12 -mt-12 transition-transform duration-700 group-hover:scale-150"></div>

                <h4 class="font-bold text-white mb-3 text-lg leading-tight">Subscribe to <br> Newsletter</h4>
                <p class="text-gray-500 text-xs mb-6 italic">Get exclusive safari deals in your inbox.</p>

                <form action="{{ route('newsletter.subscribe') }}" method="POST" id="newsletter-form-footer" class="space-y-4">
                    @csrf
                    <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response-footer">

                    <div class="space-y-3">
                        <input type="text" name="name" required placeholder="Your Full Name"
                               class="w-full bg-[#1a1209] border border-white/10 rounded-2xl px-5 py-3.5 text-white text-xs outline-none transition-all placeholder:text-gray-600 focus:border-gold-500/50">

                        <input type="email" name="email" required placeholder="Email Address"
                               class="w-full bg-[#1a1209] border border-white/10 rounded-2xl px-5 py-3.5 text-white text-xs outline-none transition-all placeholder:text-gray-600 focus:border-gold-500/50">
                    </div>

                    <button type="submit" class="btn-gold w-full py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg hover:shadow-gold-500/30 transition-all active:scale-95">
                        Join the Journey
                    </button>

                    <p class="text-[9px] text-gray-600 text-center mt-3 italic">
                        No spam. Unsubscribe anytime.
                    </p>
                </form>
            </div>

        </div>

        <!-- Payment Methods -->
        <div class="mt-16 pt-10 border-t border-white/5">
            <div class="max-w-xl mx-auto">
                <div class="flex items-center gap-4 mb-8">
                    <div class="h-px flex-1 bg-white/10"></div>
                    <span class="text-gray-500 text-[10px] font-black uppercase tracking-[0.4em] whitespace-nowrap">Secured Payments</span>
                    <div class="h-px flex-1 bg-white/10"></div>
                </div>
                <div class="flex flex-wrap items-center justify-center gap-4 md:gap-8">
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 p-4 rounded-2xl shadow-xl hover:bg-white hover:scale-110 transition-all duration-500 group">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/d/d6/Visa_2021.svg" alt="Visa" class="h-4 grayscale group-hover:grayscale-0 transition-all">
                    </div>
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 p-4 rounded-2xl shadow-xl hover:bg-white hover:scale-110 transition-all duration-500 group">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard" class="h-6 grayscale group-hover:grayscale-0 transition-all">
                    </div>
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 p-4 rounded-2xl shadow-xl hover:bg-white hover:scale-110 transition-all duration-500 group">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" alt="PayPal" class="h-4 grayscale group-hover:grayscale-0 transition-all">
                    </div>
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 p-4 rounded-2xl shadow-xl hover:bg-white hover:scale-110 transition-all duration-500 group">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/b/b0/Apple_Pay_logo.svg" alt="Apple Pay" class="h-5 grayscale group-hover:grayscale-0 transition-all">
                    </div>
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 p-4 rounded-2xl shadow-xl hover:bg-white hover:scale-110 transition-all duration-500 group">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/f/fa/American_Express_logo_%282018%29.svg" alt="Amex" class="h-5 grayscale group-hover:grayscale-0 transition-all">
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="pt-10 border-t border-white/5 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9-9c1.657 0 3 1.343 3 3s-1.343 3-3 3m0-6H7m10 0v6m0 6H7m10 0v-6M3 12a9 9 0 019-9m-9 9a9 9 0 009 9m-9-9c1.657 0-3 1.343-3 3s1.343 3 3 3m0-6h10"></path></svg>
                <p class="text-gray-600 text-[11px] font-bold uppercase tracking-[0.2em]">© {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
            </div>
            <div class="flex items-center gap-8 text-[11px] font-bold uppercase tracking-[0.2em]">
                <a href="#" class="text-gray-600 hover:text-gold-400 transition-colors">Privacy Policy</a>
                <span class="text-white/10 hidden md:block">|</span>
                <a href="#" class="text-gray-600 hover:text-gold-400 transition-colors">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<script>
// Logic to handle reCaptcha for the footer form specifically if needed
document.addEventListener('DOMContentLoaded', function() {
    const footerForm = document.getElementById('newsletter-form-footer');
    const siteKey = '{{ \App\Models\Setting::get('recaptcha_site_key') }}';

    if (footerForm && siteKey) {
        footerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            grecaptcha.ready(function() {
                grecaptcha.execute(siteKey, {action: 'subscribe'}).then(function(token) {
                    document.getElementById('g-recaptcha-response-footer').value = token;
                    footerForm.submit();
                });
            });
        });
    }
});
</script>
