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
                    @if(\App\Models\Setting::get('footer_logo') || \App\Models\Setting::get('logo'))
                        <img src="{{ asset('storage/' . (\App\Models\Setting::get('footer_logo') ?: \App\Models\Setting::get('logo'))) }}" width="180" height="48" alt="{{ \App\Helpers\AssetHelper::asString($siteName) }}" class="h-12 w-auto object-contain" loading="lazy">
                    @else
                        <div class="w-10 h-10 bg-gradient-to-br from-gold-400 to-gold-600 rounded-lg flex items-center justify-center shadow-lg transition-all">
                            <svg class="w-6 h-6 text-safari-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9-9c1.657 0 3 1.343 3 3s-1.343 3-3 3m0-6H7m10 0v6m0 6H7m10 0v-6M3 12a9 9 0 019-9m-9 9a9 9 0 009 9m-9-9c1.657 0-3 1.343-3 3s1.343 3 3 3m0-6h10"></path></svg>
                        </div>
                    @endif
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
                        <a href="mailto:twinasafaris@gmail.com" class="hover:text-gold-400 transition-colors">twinasafaris@gmail.com</a>
                    </li>
                    <li class="flex items-center gap-3 text-white font-bold group animate-phone-breathe">
                        <svg class="w-4 h-4 text-gold-500 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.17 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
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
                    {{-- Visa --}}
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 p-4 rounded-2xl shadow-xl hover:bg-white/10 hover:scale-110 transition-all duration-500 group">
                        <svg class="h-5 w-auto" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.825 32.55H23.5125L26.4375 15.45H21.75L18.825 32.55ZM40.0875 15.8625C39.2625 15.5625 37.9125 15.2625 36.3 15.2625C31.575 15.2625 28.2 17.775 28.1625 21.375C28.125 24.0375 30.525 25.5375 32.325 26.4375C34.2 27.3375 34.8 27.9 34.7625 28.725C34.7625 29.9625 33.3 30.525 31.875 30.525C29.625 30.525 28.3125 29.925 27.2625 29.4375L26.625 33.7875C27.825 34.35 29.85 34.8 31.9875 34.8375C37.05 34.8375 40.3125 32.325 40.3875 28.425C40.425 26.2125 39.1125 24.6375 36.075 23.175C34.2375 22.3125 33.15 21.7125 33.15 20.8125C33.15 20.025 34.0125 19.1625 35.8125 19.1625C37.275 19.125 38.475 19.5 39.2625 19.9125L39.75 15.8625H40.0875ZM47.25 15.45H43.65C42.4125 15.45 41.625 16.125 41.2125 17.25L35.25 32.55H40.2375L41.25 29.85H47.25L47.775 32.55H52.5L48.6 15.45H47.25ZM42.525 26.175L44.8875 19.725L46.2 26.175H42.525ZM15.0375 15.45L10.0875 27.15L9.4875 24.1125C8.3625 19.3875 5.1 16.125 1.05 15.45L0.9 15.4125V32.55H5.85L5.8875 19.2375L13.1625 32.55H18.45L26.0625 15.45H15.0375Z" fill="#2563EB"/>
                            <path d="M10.125 27.1125L5.85 19.2L5.8125 32.55H10.125V27.1125Z" fill="#F59E0B"/>
                        </svg>
                    </div>
                    {{-- Mastercard --}}
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 p-4 rounded-2xl shadow-xl hover:bg-white/10 hover:scale-110 transition-all duration-500 group">
                        <svg class="h-6 w-auto" viewBox="0 0 24 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="7" cy="9" r="7" fill="#EB001B"/>
                            <circle cx="17" cy="9" r="7" fill="#F79E1B"/>
                            <path d="M12 1.95605C10.1172 3.56836 8.92871 5.96191 8.92871 8.64355C8.92871 11.3252 10.1172 13.7188 12 15.3311C13.8828 13.7188 15.0713 11.3252 15.0713 8.64355C15.0713 5.96191 13.8828 3.56836 12 1.95605Z" fill="#FF5F00"/>
                        </svg>
                    </div>
                    {{-- PayPal --}}
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 p-4 rounded-2xl shadow-xl hover:bg-white/10 hover:scale-110 transition-all duration-500 group">
                        <svg class="h-5 w-auto" viewBox="0 0 24 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19.921 7.214C19.643 3.57 17.387 1.488 13.435 1.488H4.664C3.89 1.488 3.238 2.051 3.125 2.809L0.016 23.149C-0.05 23.585 0.286 23.985 0.727 23.985H5.412L6.643 15.93C6.755 15.172 7.406 14.61 8.18 14.61H10.127C15.158 14.61 18.064 12.148 19.043 7.644C19.055 7.591 19.066 7.538 19.077 7.485C19.387 7.394 19.667 7.303 19.921 7.214Z" fill="#253B80"/>
                            <path d="M23.237 8.026C22.618 13.226 19.123 16.038 14.092 16.038H10.669L9.438 24.094C9.325 24.851 8.673 25.414 7.899 25.414H3.454L2.342 26.685C2.127 26.814 1.956 27.027 1.907 27.288L1.758 28.261C1.69 28.697 2.028 29.097 2.469 29.097H6.945C7.72 29.097 8.371 28.535 8.484 27.777L9.845 20.354L10.231 17.433C10.344 16.675 10.995 16.113 11.769 16.113H13.716C18.747 16.113 22.062 13.652 23.041 9.148C23.14 8.694 23.206 8.307 23.237 8.026Z" fill="#179BD7"/>
                        </svg>
                    </div>
                    {{-- Apple Pay --}}
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 p-4 rounded-2xl shadow-xl hover:bg-white/10 hover:scale-110 transition-all duration-500 group">
                        <svg class="h-6 w-auto" viewBox="0 0 50 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.82 7.37c-.55 0-1.55.51-2.14 1.15-1.02 1.12-1.74 2.87-1.74 4.63 0 2.86 1.94 6.7 4.23 6.7.67 0 1.25-.26 1.83-.58.55-.31 1.2-.62 1.98-.62.7 0 1.26.31 1.86.62.59.32 1.26.61 1.94.61 2.37 0 4.14-3.61 4.14-5.32-.01-.04-1.89-.78-1.91-3.04 0-1.89 1.44-2.81 1.54-2.88-1.05-1.54-2.61-1.72-3.17-1.76-.75-.05-2.07.45-2.79.45-.72 0-1.87-.45-2.77-.45V7.37zm2.46-1.56c1.23-.1 2.31-.86 2.81-2.01.1-.23.18-.5.21-.76-.02-.02-.04-.02-.06-.02-1.29 0-2.52.8-3.08 1.99-.18.39-.32.86-.32 1.34 0 .09.02.19.04.28.02 0 .04.01.06.01.12.1.23.16.34.17z" fill="white"/>
                            <path d="M30.68 18.66h2.46v-1.3c0-1.84.99-2.74 2.89-2.74h.61v-2h-.91c-2.31 0-3.66.97-4.13 2.15h-.06l-.21-1.84h-2.18v10.15h2.46v-4.42h.07c.45.89 1.62 1.48 2.8 1.48 2.45 0 4.41-2.09 4.41-5.11 0-2.93-2.01-5.06-4.51-5.06-1.32 0-2.45.62-3.1 1.48h-.06l-.08-1.18h-2.15l.66 4.39zm3.8 2.11c-1.39 0-2.44-.99-2.44-2.46s1.05-2.43 2.44-2.43c1.39 0 2.44.99 2.44 2.43s-1.05 2.46-2.44 2.46zM46.72 12.63l-2.66 5.86h-.06l-2.65-5.86h-2.65l4.08 8.13-1.4 3.03h-2.51v1.98h1.22c1.47 0 2.45-.63 3.02-1.92l5.06-11.22h-2.45zM22.84 10.35h-2.61l-3.9 12.14h2.52l.93-3.14h3.6l.93 3.14h2.53l-4-12.14zm-.31 7.02h-2.18l1.09-3.77h.05l1.04 3.77z" fill="white"/>
                        </svg>
                    </div>
                    {{-- Amex --}}
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 p-4 rounded-2xl shadow-xl hover:bg-white/10 hover:scale-110 transition-all duration-500 group">
                        <svg class="h-5 w-auto" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="24" height="24" rx="2" fill="#0070CE"/>
                            <path d="M4 14.5L5.5 10L7 14.5H4ZM5.1 13.5H5.9L5.5 11.2L5.1 13.5Z" fill="white"/>
                            <path d="M8.5 14.5V10H10L11 12.5L12 10H13.5V14.5H12.5V11.2L11.5 13.5H10.5L9.5 11.2V14.5H8.5Z" fill="white"/>
                            <path d="M15 14.5V10H18V10.8H16V11.8H17.5V12.5H16V13.7H18V14.5H15Z" fill="white"/>
                            <path d="M19.5 14.5L20.5 12.2L21.5 14.5H23L21.2 11.2L23 10H21.5L20.5 11.5L19.5 10H18L19.8 12.2L18 14.5H19.5Z" fill="white"/>
                        </svg>
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
