@php
     $siteName = \App\Models\Setting::get('site_name','Twinasafaris');
     $phone    = \App\Models\Setting::get('site_phone','+255 795 482 197');
     $email    = \App\Models\Setting::get('site_email','info@twinasafaris.com');
     $logo     = \App\Models\Setting::get('logo');

     // Categories with custom descriptions for the Mega Menu
     $categoriesWithMeta = [
        'safari-tours' => ['desc' => __('Explore Africa')],
        'kilimanjaro-trekking' => ['desc' => __('Explore Africa')],
        'zanzibar-holidays' => ['desc' => __('Explore Africa')],
        'luxury-safaris' => ['desc' => __('Ultra-premium escapes')],
        'family-safaris' => ['desc' => __('Memories for all ages')],
        'honeymoon-safaris' => ['desc' => __('Explore Africa')],
        'day-trips' => ['desc' => __('Short but thrilling')],
        'group-tours' => ['desc' => __('Shared adventures')],
     ];

     $navCategories = \App\Models\Category::where('is_active', true)->get();
@endphp

<style>
    @keyframes phone-breathe {
        0%, 100% { transform: translateY(0) scale(1); }
        50% { transform: translateY(-3px) scale(1.03); }
    }
    .animate-phone-breathe {
        display: inline-flex;
        animation: phone-breathe 2s ease-in-out infinite;
    }
</style>

<!-- Top bar -->
<div class="hidden lg:block bg-[#0a0703] border-b border-gold-500/10 text-xs py-2">
    <div class="max-w-7xl mx-auto px-4 flex items-center justify-between">
        <div class="flex items-center gap-6 text-gray-400">
            <a href="tel:{{ $phone }}" class="flex items-center gap-2 hover:text-gold-400 transition-colors animate-phone-breathe">
                <svg class="w-3 h-3 text-gold-500" fill="currentColor" viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.17 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                <span class="font-bold">{{ $phone }}</span>
            </a>
            <span class="text-gray-600">|</span>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 text-[#34E0A1]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.5 14H15v-1h1.5v1zm-3-2H12v-1h1.5v1zm0-2H12V9h1.5v1zm3 0H15V9h1.5v1zM9 16H7.5v-1H9v1zm0-2H7.5v-1H9v1zm0-2H7.5V9H9v1zm3 4h-1.5v-1h1.5v1z"/></svg>
                    <span class="text-gray-500"><span class="text-white font-bold">4.9/5</span> TripAdvisor</span>
                </div>
                <span class="text-gray-700">·</span>
                <div class="flex items-center gap-1.5">
                    <svg class="w-3 h-3 text-[#4285F4]" fill="currentColor" viewBox="0 0 24 24"><path d="M12.48 10.92v3.28h7.84c-.24 1.84-.92 3.32-2.08 4.44-1.12 1.12-2.8 2.32-5.76 2.32-4.68 0-8.24-3.8-8.24-8.48s3.56-8.48 8.24-8.48c2.52 0 4.24 1 5.56 2.24l2.32-2.32C18.48 2.08 15.8 0 12.48 0 5.48 0 0 5.48 0 12.48S5.48 24.96 12.48 24.96c3.76 0 6.6-1.24 8.84-3.6 2.32-2.32 3.04-5.56 3.04-8.12 0-.76-.08-1.52-.2-2.32h-11.68z"/></svg>
                    <span class="text-gray-500"><span class="text-white font-bold">5.0/5</span> Google Reviews</span>
                </div>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <!-- Currency Switcher -->
            <div class="relative" x-data="{open: false}">
                <button x-on:click="open = !open" x-on:click.outside="open = false" class="flex items-center gap-1.5 text-gray-400 hover:text-gold-400 transition-colors">
                    {{ session('currency', 'USD') }}
                    <svg class="w-3 h-3 transition-transform" x-bind:class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="absolute right-0 top-full mt-2 w-32 bg-[#0a0703] border border-gold-500/20 rounded-xl shadow-2xl z-50 overflow-hidden" x-cloak>
                    @foreach(['USD'=>'USD ($)','EUR'=>'EUR (€)','GBP'=>'GBP (£)','TZS'=>'TZS (Sh)'] as $code=>$label)
                    <a href="{{ route('currency.switch', $code) }}" class="block px-4 py-2.5 text-sm text-gray-300 hover:text-gold-400 hover:bg-white/5 transition-colors {{ session('currency', 'USD')===$code?'text-gold-400 bg-white/5':'' }}">{{ $label }}</a>
                    @endforeach
                </div>
            </div>
            <!-- Language Switcher -->
            <div class="relative" x-data="{open: false}">
                <button x-on:click="open = !open" x-on:click.outside="open = false" class="flex items-center gap-1.5 text-gray-400 hover:text-gold-400 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9-9c1.657 0 3 1.343 3 3s-1.343 3-3 3m0-6H7m10 0v6m0 6H7m10 0v-6M3 12a9 9 0 019-9m-9 9a9 9 0 009 9m-9-9c1.657 0-3 1.343-3 3s1.343 3 3 3m0-6h10"></path></svg>
                    <span class="uppercase">{{ app()->getLocale() }}</span>
                    <svg class="w-3 h-3 transition-transform" x-bind:class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="absolute right-0 top-full mt-2 w-44 bg-[#0a0703] border border-gold-500/20 rounded-xl shadow-2xl z-50 overflow-hidden" x-cloak>
                    @foreach(['en'=>'🇬🇧 English','de'=>'🇩🇪 Deutsch','fr'=>'🇫🇷 Français','es'=>'🇪🇸 Español','it'=>'🇮🇹 Italiano','zh'=>'🇨🇳 中文','nl'=>'🇳🇱 Nederlands'] as $code=>$label)
                    <a href="{{ route('lang.switch', $code) }}" class="block px-4 py-2.5 text-sm text-gray-300 hover:text-gold-400 hover:bg-white/5 transition-colors {{ app()->getLocale() === $code ? 'text-gold-400 bg-white/5 font-bold' : '' }}">{{ $label }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Nav -->
<nav id="main-nav"
     x-data="{ mobileMenuOpen: false, activeAccordion: null }"
     class="nav-transparent fixed top-0 lg:top-9 left-0 right-0 z-40 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between py-3">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                @if(\App\Models\Setting::get('logo'))
                    <img src="{{ asset('storage/' . \App\Models\Setting::get('logo')) }}" width="180" height="48" alt="{{ \App\Helpers\AssetHelper::asString($siteName) }}" class="h-12 w-auto object-contain" fetchpriority="high">
                @else
                    <div class="w-11 h-11 bg-gradient-to-br from-gold-400 to-gold-600 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-gold-500/40 transition-all">
                        <svg class="w-7 h-7 text-safari-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9-9c1.657 0 3 1.343 3 3s-1.343 3-3 3m0-6H7m10 0v6m0 6H7m10 0v-6M3 12a9 9 0 019-9m-9 9a9 9 0 009 9m-9-9c1.657 0-3 1.343-3 3s1.343 3 3 3m0-6h10"></path></svg>
                    </div>
                @endif
                <div class="hidden sm:block">
                    <span class="font-display text-white text-lg font-semibold leading-tight block">{{ \App\Helpers\AssetHelper::asString($siteName) }}</span>
                    <span class="text-gold-400 text-[10px] tracking-widest uppercase font-bold">Tanzania</span>
                </div>
            </a>

            <!-- Desktop Nav -->
            <div class="hidden lg:flex items-center gap-1">
                <a href="{{ route('home') }}" class="px-3 py-2 text-sm font-medium text-white/90 hover:text-gold-400 transition-colors rounded-lg {{ request()->routeIs('home')?'text-gold-400':'' }}">{{ __('Home') }}</a>
                <a href="{{ route('about') }}" class="px-3 py-2 text-sm font-medium text-white/90 hover:text-gold-400 transition-colors rounded-lg {{ request()->routeIs('about')?'text-gold-400':'' }}">{{ __('About') }}</a>

                <!-- Tours Mega Menu -->
                <div class="relative" x-data="{open: false}" x-on:mouseenter="open=true" x-on:mouseleave="open=false">
                    <button class="flex items-center gap-1 px-3 py-2 text-sm font-medium text-white/90 hover:text-gold-400 transition-colors rounded-lg {{ request()->routeIs('tours.*')?'text-gold-400':'' }}">
                        {{ __('Safaris') }} <svg class="w-3.5 h-3.5 transition-transform" x-bind:class="open?'rotate-180':''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute top-full left-0 mt-2 w-[720px] bg-safari-dark/95 backdrop-blur-2xl border border-white/10 rounded-2xl shadow-2xl overflow-hidden z-50" x-cloak>
                        <div class="p-6 grid grid-cols-3 gap-2">
                            @foreach($navCategories as $cat)
                            <a href="{{ route('tours.index', ['tour_type' => $cat->slug]) }}" class="flex items-start gap-3 p-3 rounded-xl hover:bg-white/10 hover:shadow-lg transition-all group">
                                <div class="w-1.5 h-1.5 rounded-full bg-gold-500 mt-2 group-hover:scale-125 transition-transform"></div>
                                <div>
                                    <div class="text-white text-sm font-semibold group-hover:text-gold-400 transition-colors">{{ $cat->name }}</div>
                                    <div class="text-gray-400 text-[10px] mt-0.5 leading-tight">{{ $categoriesWithMeta[$cat->slug]['desc'] ?? __('Explore Africa') }}</div>
                                </div>
                            </a>
                            @endforeach
                        </div>
                        <div class="bg-white/5 border-t border-white/10 px-6 py-4 flex justify-between items-center">
                            <span class="text-gray-300 text-xs">{{ __('Looking for something unique?') }}</span>
                            <a href="{{ route('trip-plan.index') }}" class="text-gold-400 text-sm font-bold hover:text-gold-300 flex items-center gap-1 transition-colors">
                                {{ __('Plan Custom Safari') }} <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <a href="{{ route('tours.index', ['view'=>'destinations']) }}" class="px-3 py-2 text-sm font-medium text-white/90 hover:text-gold-400 transition-colors rounded-lg">{{ __('Destinations') }}</a>
                <a href="{{ route('gallery.index') }}" class="px-3 py-2 text-sm font-medium text-white/90 hover:text-gold-400 transition-colors rounded-lg {{ request()->routeIs('gallery.*')?'text-gold-400':'' }}">{{ __('Gallery') }}</a>
                <a href="{{ route('blog.index') }}" class="px-3 py-2 text-sm font-medium text-white/90 hover:text-gold-400 transition-colors rounded-lg {{ request()->routeIs('blog.*')?'text-gold-400':'' }}">{{ __('Blog') }}</a>
                <a href="{{ route('contact.index') }}" class="px-3 py-2 text-sm font-medium text-white/90 hover:text-gold-400 transition-colors rounded-lg {{ request()->routeIs('contact.*')?'text-gold-400':'' }}">{{ __('Contact') }}</a>
            </div>

            <!-- CTAs -->
            <div class="flex items-center gap-3">
                <a href="{{ route('trip-plan.index') }}" class="hidden lg:block btn-outline-gold px-4 py-2 rounded-full text-sm font-semibold"><span>{{ __('Plan My Trip') }}</span></a>
                <a href="{{ route('tours.index') }}" class="btn-gold px-5 py-2.5 rounded-full text-sm font-semibold hidden sm:block">{{ __('Book Now') }}</a>
                <button x-on:click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-colors focus:outline-none">
                    <svg x-show="!mobileMenuOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu"
         x-show="mobileMenuOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="lg:hidden bg-safari-dark/98 border-t border-gold-500/20 backdrop-blur-2xl max-h-[calc(100vh-80px)] overflow-y-auto shadow-2xl"
         x-cloak>
        <div class="max-w-7xl mx-auto px-4 py-6 space-y-4">
            <div class="space-y-1">
                <a href="{{ route('home') }}" class="flex items-center px-4 py-3 text-gray-200 hover:text-gold-400 hover:bg-white/5 rounded-xl transition-colors text-base font-medium {{ request()->routeIs('home')?'text-gold-400 bg-white/5':'' }}">{{ __('Home') }}</a>
                <a href="{{ route('about') }}" class="flex items-center px-4 py-3 text-gray-200 hover:text-gold-400 hover:bg-white/5 rounded-xl transition-colors text-base font-medium {{ request()->routeIs('about')?'text-gold-400 bg-white/5':'' }}">{{ __('About') }}</a>

                <!-- Mobile Accordion for Safaris -->
                <div class="space-y-1">
                    <button x-on:click="activeAccordion = activeAccordion === 'safaris' ? null : 'safaris'"
                            class="w-full flex items-center justify-between px-4 py-3 text-gray-200 hover:text-gold-400 hover:bg-white/5 rounded-xl transition-colors text-base font-medium">
                        <span>{{ __('Safaris') }}</span>
                        <svg class="w-4 h-4 transition-transform duration-300" x-bind:class="activeAccordion === 'safaris' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="activeAccordion === 'safaris'"
                         x-collapse
                         class="pl-4 space-y-1">
                        @foreach($navCategories as $cat)
                        <a href="{{ route('tours.index', ['tour_type' => $cat->slug]) }}" class="flex flex-col px-4 py-3 text-gray-400 hover:text-gold-400 hover:bg-white/5 rounded-xl transition-colors">
                            <span class="text-sm font-semibold">{{ $cat->name }}</span>
                            <span class="text-[10px] opacity-60 leading-tight">{{ $categoriesWithMeta[$cat->slug]['desc'] ?? __('Explore Africa') }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('tours.index', ['view'=>'destinations']) }}" class="flex items-center px-4 py-3 text-gray-200 hover:text-gold-400 hover:bg-white/5 rounded-xl transition-colors text-base font-medium">{{ __('Destinations') }}</a>
                <a href="{{ route('gallery.index') }}" class="flex items-center px-4 py-3 text-gray-200 hover:text-gold-400 hover:bg-white/5 rounded-xl transition-colors text-base font-medium {{ request()->routeIs('gallery.*')?'text-gold-400 bg-white/5':'' }}">{{ __('Gallery') }}</a>
                <a href="{{ route('blog.index') }}" class="flex items-center px-4 py-3 text-gray-200 hover:text-gold-400 hover:bg-white/5 rounded-xl transition-colors text-base font-medium {{ request()->routeIs('blog.*')?'text-gold-400 bg-white/5':'' }}">{{ __('Blog') }}</a>
                <a href="{{ route('contact.index') }}" class="flex items-center px-4 py-3 text-gray-200 hover:text-gold-400 hover:bg-white/5 rounded-xl transition-colors text-base font-medium {{ request()->routeIs('contact.*')?'text-gold-400 bg-white/5':'' }}">{{ __('Contact') }}</a>
            </div>

            <div class="pt-4 border-t border-white/10 space-y-4">
                <div class="grid grid-cols-2 gap-3 px-2">
                    <a href="{{ route('trip-plan.index') }}" class="btn-outline-gold text-center py-3 rounded-xl font-semibold text-sm"><span>{{ __('Plan My Trip') }}</span></a>
                    <a href="{{ route('tours.index') }}" class="btn-gold text-center py-3 rounded-xl font-semibold text-sm">{{ __('Book Now') }}</a>
                </div>

                <div class="flex flex-col gap-3 px-4 pt-2">
                    <a href="tel:{{ $phone }}" class="flex items-center gap-3 text-gray-400 text-sm hover:text-gold-400 transition-colors animate-phone-breathe">
                        <span class="w-9 h-9 rounded-lg bg-white/5 flex items-center justify-center text-lg">
                            <svg class="w-5 h-5 text-gold-500" fill="currentColor" viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.17 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                        </span>
                        <span class="font-bold">{{ $phone }}</span>
                    </a>
                    <a href="mailto:{{ $email }}" class="flex items-center gap-3 text-gray-400 text-sm hover:text-gold-400 transition-colors">
                        <span class="w-9 h-9 rounded-lg bg-white/5 flex items-center justify-center text-lg text-gold-500">✉️</span>
                        {{ $email }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
