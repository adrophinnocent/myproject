@php
    $siteName = \App\Models\Setting::get('site_name', 'Twina Safaris');
    $siteEmail = \App\Models\Setting::get('site_email', 'info@twinasafaris.com');
    $sitePhone = \App\Models\Setting::get('site_phone', '+255 754 000 000');
    $logo = \App\Models\Setting::get('logo');
@endphp
<footer class="bg-[#0a0703] pt-10 pb-6 border-t border-white/5 relative overflow-hidden">
    {{-- Subtle pattern background hint --}}
    <div class="absolute inset-0 opacity-[0.02] pointer-events-none">
        <svg class="w-full h-full" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none">
            <path d="M0 50 Q 25 40 50 50 T 100 50" stroke="white" fill="transparent" stroke-width="0.1"/>
        </svg>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-x-8 gap-y-10 items-start mb-8">

            {{-- Column 1: About --}}
            <div class="space-y-4">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    @if($logo)
                        <img src="{{ asset('storage/' . $logo) }}" alt="{{ $siteName }}" class="h-10 object-contain">
                    @else
                        <div class="w-10 h-10 bg-gradient-to-br from-gold-400 to-gold-600 rounded-lg flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-safari-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9-9c1.657 0 3 1.343 3 3s-1.343 3-3 3m0-6H7m10 0v6m0 6H7m10 0v-6M3 12a9 9 0 019-9m-9 9a9 9 0 009 9m-9-9c1.657 0-3 1.343-3 3s1.343 3 3 3m0-6h10"></path></svg>
                        </div>
                    @endif
                    <div class="flex flex-col whitespace-nowrap">
                        <span class="font-display text-2xl font-bold text-white leading-tight">{{ $siteName }}</span>
                        <span class="text-gold-400 text-[10px] tracking-[0.15em] uppercase font-bold mt-0.5">Est. 2009 · Tanzania</span>
                    </div>
                </a>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Crafting unforgettable safari experiences since 2009. Personalized itineraries across East Africa.
                </p>
                {{-- All Social Media Icons --}}
                <div class="flex gap-3 pt-1">
                    <a href="{{ \App\Models\Setting::get('facebook_url', '#') }}" class="text-gray-500 hover:text-gold-500 transition-colors"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
                    <a href="{{ \App\Models\Setting::get('instagram_url', '#') }}" class="text-gray-500 hover:text-gold-500 transition-colors"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                    <a href="{{ \App\Models\Setting::get('youtube_url', '#') }}" class="text-gray-500 hover:text-gold-500 transition-colors"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg></a>
                </div>
            </div>

            {{-- Column 2: Quick Links --}}
            <div>
                <h4 class="font-bold text-white mb-6 uppercase tracking-wider text-[11px]">Quick Links</h4>
                <ul class="space-y-4 text-sm">
                    <li><a href="{{ route('tours.index') }}" class="text-gray-500 hover:text-gold-400 transition-colors">All Tours</a></li>
                    <li><a href="{{ route('trip-plan.index') }}" class="text-gray-500 hover:text-gold-400 transition-colors">Plan Your Trip</a></li>
                    <li><a href="{{ route('blog.index') }}" class="text-gray-500 hover:text-gold-400 transition-colors">Blog Journal</a></li>
                    <li><a href="{{ route('contact.index') }}" class="text-gray-500 hover:text-gold-400 transition-colors">Contact Us</a></li>
                </ul>
            </div>

            {{-- Column 3: Popular --}}
            <div>
                <h4 class="font-bold text-white mb-6 uppercase tracking-wider text-[11px]">Popular</h4>
                <ul class="space-y-4 text-sm text-gray-500">
                    <li><a href="{{ route('tours.index', ['destination' => 'serengeti']) }}" class="hover:text-gold-400 transition-colors">Serengeti</a></li>
                    <li><a href="{{ route('tours.index', ['destination' => 'kilimanjaro']) }}" class="hover:text-gold-400 transition-colors">Kilimanjaro</a></li>
                    <li><a href="{{ route('tours.index', ['destination' => 'zanzibar']) }}" class="hover:text-gold-400 transition-colors">Zanzibar</a></li>
                    <li><a href="{{ route('tours.index', ['destination' => 'ngorongoro']) }}" class="hover:text-gold-400 transition-colors">Ngorongoro</a></li>
                </ul>
            </div>

            {{-- Column 4: Contact --}}
            <div class="space-y-4">
                <h4 class="font-bold text-white mb-6 uppercase tracking-wider text-[11px]">Get In Touch</h4>
                <div class="space-y-4 text-sm text-gray-500">
                    <p class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-gold-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Moshi, Tanzania</span>
                    </p>
                    <p class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-gold-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <a href="mailto:{{ $siteEmail }}" class="hover:text-gold-400 transition-colors">{{ $siteEmail }}</a>
                    </p>
                    <p class="flex items-start gap-2.5 text-white font-bold">
                        <svg class="w-4 h-4 text-gold-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <a href="tel:{{ $sitePhone }}" class="hover:text-gold-400 transition-colors">{{ $sitePhone }}</a>
                    </p>
                </div>
            </div>

            {{-- Column 5: Join --}}
            <div class="bg-white/5 p-6 rounded-3xl border border-white/10 shadow-2xl relative overflow-hidden group">
                <h4 class="font-bold text-white mb-2 text-base">Join the Journey</h4>
                <p class="text-gray-500 text-xs mb-5 italic">Safari deals in your inbox.</p>
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="space-y-3">
                    @csrf
                    <input type="text" name="name" required placeholder="Full Name" class="w-full bg-[#1a1209] border border-white/10 rounded-xl px-4 py-3 text-white text-xs outline-none transition-all placeholder:text-gray-600 focus:border-gold-500/50">
                    <input type="email" name="email" required placeholder="Email Address" class="w-full bg-[#1a1209] border border-white/10 rounded-xl px-4 py-3 text-white text-xs outline-none transition-all placeholder:text-gray-600 focus:border-gold-500/50">
                    <button type="submit" class="btn-gold w-full py-4 rounded-xl font-bold text-xs shadow-lg hover:shadow-gold-500/30 transition-all uppercase tracking-widest">Subscribe Now</button>
                </form>
            </div>

        </div>

        <!-- Bottom Bar -->
        <div class="pt-8 border-t border-white/5 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9-9c1.657 0 3 1.343 3 3s-1.343 3-3 3m0-6H7m10 0v6m0 6H7m10 0v-6M3 12a9 9 0 019-9m-9 9a9 9 0 009 9m-9-9c1.657 0-3 1.343-3 3s1.343 3 3 3m0-6h10"></path></svg>
                <p class="text-gray-600 text-xs font-medium uppercase tracking-widest">© {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
            </div>
            <div class="flex items-center gap-8 text-xs font-bold uppercase tracking-[0.2em]">
                <a href="#" class="text-gray-500 hover:text-gold-400 transition-colors">Privacy Policy</a>
                <span class="text-white/10">|</span>
                <a href="#" class="text-gray-500 hover:text-gold-400 transition-colors">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
