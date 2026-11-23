@extends('public.layouts.app')

@section('title', 'Twina Safaris | Tanzania Safaris, Kilimanjaro & Zanzibar')
@section('meta_description', 'Plan Tanzania safaris, Kilimanjaro treks and Zanzibar beach holidays with Twina Safaris. Explore Serengeti and Ngorongoro itineraries, prices and expert local guides.')

@section('content')

{{-- ========== SEASON STATUS INDICATOR STYLES ========== --}}
<style>
    @@keyframes alert-blink {
        0%, 100% { opacity: 1; box-shadow: 0 0 15px currentColor, 0 0 30px currentColor; }
        50% { opacity: 0.3; box-shadow: 0 0 0 currentColor; }
    }
    .blink-green { animation: alert-blink 0.6s ease-in-out infinite; color: #22c55e; }
    .blink-yellow { animation: alert-blink 0.6s ease-in-out infinite; color: #eab308; }
    .blink-red { animation: alert-blink 0.6s ease-in-out infinite; color: #ef4444; }

    .season-tooltip {
        opacity: 0;
        transform: translateX(10px);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: none;
        visibility: hidden;
    }
    .season-light:hover .season-tooltip {
        opacity: 1;
        transform: translateX(0);
        pointer-events: auto;
        visibility: visible;
    }
</style>

{{-- ========== 1. HERO SECTION ========== --}}
<section class="relative h-screen bg-safari-dark overflow-hidden flex flex-col"
         x-data="{
            activeSlide: 0,
            slidesCount: {{ (isset($sliders) && $sliders->count() > 0) ? $sliders->count() : 1 }},
            next() { this.activeSlide = (this.activeSlide + 1) % this.slidesCount }
         }"
         x-init="if(slidesCount > 1) setInterval(() => next(), 8000)">

    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-black/40 z-10"></div>
        @if(isset($sliders) && $sliders->count() > 0)
            @foreach($sliders as $index => $slide)
                <div x-show="activeSlide === {{ $index }}"
                     x-transition:enter="transition ease-out duration-1000"
                     x-transition:enter-start="opacity-0 scale-105"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute inset-0 w-full h-full">
                    @if($slide->type === 'video')
                        <video autoplay muted loop playsinline class="w-full h-full object-cover">
                            <source src="{{ $slide->image_url }}" type="video/mp4">
                        </video>
                    @else
                        <img src="{{ $slide->image_url }}" width="1920" height="1080" class="w-full h-full object-cover" alt="{{ $slide->title }}" loading="eager" fetchpriority="high">
                    @endif
                </div>
            @endforeach
        @else
            <img src="{{ asset('images/banners/hero_fallback.webp') }}" width="1920" height="1080" class="w-full h-full object-cover opacity-60" alt="Tanzania Safari" loading="eager" fetchpriority="high">
        @endif
    </div>

    <div class="relative z-20 flex-grow flex flex-col items-center justify-center text-center px-4 pt-20">
        {{-- One stable, keyword-focused H1 for the page; slider headlines are H2s. --}}
        <h1 class="sr-only">{{ __('Tanzania safaris, Kilimanjaro treks and Zanzibar holidays with Twina Safaris') }}</h1>
        <div class="w-full max-w-5xl">
            @if(isset($sliders) && $sliders->count() > 0)
                @foreach($sliders as $index => $slide)
                    <div x-show="activeSlide === {{ $index }}" x-cloak
                         x-transition:enter="transition ease-out duration-1000"
                         x-transition:enter-start="opacity-0 translate-y-8"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="space-y-10">
                        <div class="space-y-4">
                            @if($slide->subtitle)
                                <span class="inline-block text-gold-400 text-sm md:text-lg font-bold uppercase tracking-[0.4em] animate-pulse">
                                    {{ __($slide->subtitle) }}
                                </span>
                            @endif
                            @if($slide->title)
                                <h2 class="font-display text-3xl sm:text-4xl md:text-8xl lg:text-9xl text-white font-black leading-[0.85] drop-shadow-2xl">
                                    {{ __($slide->title) }}
                                </h2>
                            @endif
                        </div>
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 md:gap-6 relative z-50 pt-4 md:pt-0">
                            @if($slide->cta_text)
                                <a href="{{ $slide->cta_url ?: '#' }}" class="btn-gold px-12 py-5 rounded-full text-base font-black shadow-2xl transition-all hover:scale-105 active:scale-95 min-w-[220px]">
                                    {{ __($slide->cta_text) }}
                                </a>
                            @endif
                            <a href="{{ route('tours.index', ['tour_type' => 'kilimanjaro-trekking']) }}" class="bg-white/5 backdrop-blur-md border border-white/10 text-white hover:bg-gold-500 hover:text-safari-dark px-10 py-4 rounded-full text-sm font-black transition-all min-w-[220px]">
                                {{ __('Climb Kilimanjaro') }}
                            </a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <div class="absolute top-24 right-6 md:top-32 md:right-10 z-40">
        <div class="flex flex-col gap-4 items-center">
            <!-- Green Light: Peak Season (June-Oct) -->
            <div class="season-light relative group cursor-help" id="green-container" style="display:none;">
                <div id="green-light" class="w-5 h-5 rounded-full bg-green-500 border-2 border-white/50 shadow-lg blink-green"></div>
                <div class="season-tooltip absolute right-full mr-4 top-1/2 -translate-y-1/2 bg-safari-dark/90 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest whitespace-nowrap border border-white/10 backdrop-blur-md shadow-2xl">
                    <span class="text-green-400">●</span> Peak Safari Season
                </div>
            </div>
            <!-- Yellow Light: High Season (Jan, Feb, Nov, Dec) -->
            <div class="season-light relative group cursor-help" id="yellow-container" style="display:none;">
                <div id="yellow-light" class="w-5 h-5 rounded-full bg-yellow-500 border-2 border-white/50 shadow-lg blink-yellow"></div>
                <div class="season-tooltip absolute right-full mr-4 top-1/2 -translate-y-1/2 bg-safari-dark/90 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest whitespace-nowrap border border-white/10 backdrop-blur-md shadow-2xl">
                    <span class="text-yellow-400">●</span> High Safari Season
                </div>
            </div>
            <!-- Red Light: Green/Wet Season (Mar, Apr, May) -->
            <div class="season-light relative group cursor-help" id="red-container" style="display:none;">
                <div id="red-light" class="w-5 h-5 rounded-full bg-red-500 border-2 border-white/50 shadow-lg blink-red"></div>
                <div class="season-tooltip absolute right-full mr-4 top-1/2 -translate-y-1/2 bg-safari-dark/90 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest whitespace-nowrap border border-white/10 backdrop-blur-md shadow-2xl">
                    <span class="text-red-400">●</span> Lush Wet Season
                </div>
            </div>
        </div>
    </div>

    <div class="relative z-50 w-full max-w-6xl mx-auto text-center pb-12 md:pb-20 px-4 mt-auto">
        <div class="max-w-5xl mx-auto mb-10">
            <div class="bg-black/50 backdrop-blur-3xl rounded-[2rem] md:rounded-full p-3 md:p-1.5 border-2 border-white/20 shadow-[0_20px_50px_-12px_rgba(212,175,55,0.4)]">
                <form action="{{ route('tours.index') }}" method="GET" class="flex flex-col md:flex-row gap-3 md:gap-0">
                    <div class="relative flex-1">
                        <select name="destination" class="w-full bg-white/10 md:bg-transparent border-0 md:border-r border-white/10 rounded-2xl md:rounded-none px-6 py-4 text-white text-sm font-bold focus:ring-0 appearance-none cursor-pointer">
                            <option value="" class="text-gray-900">{{ __('Where to?') }}</option>
                            @foreach(\App\Models\Destination::where('is_active', true)->get() as $dest)
                            <option value="{{ $dest->id }}" class="text-gray-900">{{ __($dest->name) }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gold-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                    <div class="relative flex-1">
                        <select name="category" class="w-full bg-white/10 md:bg-transparent border-0 md:border-r border-white/10 rounded-2xl md:rounded-none px-6 py-4 text-white text-sm font-bold focus:ring-0 appearance-none cursor-pointer">
                            <option value="" class="text-gray-900">{{ __('Adventure Type') }}</option>
                            @foreach(\App\Models\Category::where('is_active', true)->get() as $cat)
                            <option value="{{ $cat->id }}" class="text-gray-900">{{ __($cat->name) }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gold-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                    <div class="relative flex-1">
                        <select name="duration" class="w-full bg-white/10 md:bg-transparent border-0 rounded-2xl md:rounded-none px-6 py-4 text-white text-sm font-bold focus:ring-0 appearance-none cursor-pointer">
                            <option value="" class="text-gray-900">{{ __('How long?') }}</option>
                            <option value="1-3" class="text-gray-900">1-3 {{ __('Days') }}</option>
                            <option value="4-7" class="text-gray-900">4-7 {{ __('Days') }}</option>
                            <option value="8-14" class="text-gray-900">8-14 {{ __('Days') }}</option>
                            <option value="15+" class="text-gray-900">15+ {{ __('Days') }}</option>
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gold-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                    <div class="md:w-48">
                        <button type="submit" class="w-full h-full bg-gold-500 hover:bg-gold-600 text-safari-dark py-4 px-8 rounded-2xl md:rounded-full font-black text-xs uppercase tracking-widest flex items-center justify-center gap-2 shadow-xl transition-all active:scale-95 group">
                            <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            {{ __('SEARCH') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

{{-- ========== 1.5 IMPACT IN NUMBERS (STATISTICS - WHITE BG) ========== --}}
<section class="py-12 bg-white border-b border-gray-100 relative overflow-hidden" x-data="{
    startCount(target, duration, callback) {
        let start = 0;
        const increment = target / (duration / 16);
        const timer = setInterval(() => {
            start += increment;
            if (start >= target) {
                callback(target);
                clearInterval(timer);
            } else {
                callback(Math.floor(start));
            }
        }, 16);
    }
}">
    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-12">
            <div class="text-center group">
                <div class="text-3xl md:text-5xl font-display font-black text-gold-600 mb-1 group-hover:scale-110 transition-transform duration-500">500+</div>
                <p class="text-[9px] md:text-[10px] text-gray-600 font-black uppercase tracking-[0.4em]">{{ __('Happy Travelers') }}</p>
            </div>
            <div class="text-center group">
                <div class="text-3xl md:text-5xl font-display font-black text-gold-600 mb-1 group-hover:scale-110 transition-transform duration-500">150+</div>
                <p class="text-[9px] md:text-[10px] text-gray-600 font-black uppercase tracking-[0.4em]">{{ __('Safari Packages') }}</p>
            </div>
            <div class="text-center group">
                <div class="text-3xl md:text-5xl font-display font-black text-gold-600 mb-1 group-hover:scale-110 transition-transform duration-500">10+</div>
                <p class="text-[9px] md:text-[10px] text-gray-600 font-black uppercase tracking-[0.4em]">{{ __('Years Experience') }}</p>
            </div>
            <div class="text-center group">
                <div class="text-3xl md:text-5xl font-display font-black text-gold-600 mb-1 group-hover:scale-110 transition-transform duration-500">100%</div>
                <p class="text-[9px] md:text-[10px] text-gray-600 font-black uppercase tracking-[0.4em]">{{ __('Safety Record') }}</p>
            </div>
        </div>
    </div>
</section>

{{-- ========== 2. FEATURED PACKAGE (KILIMANJARO DYNAMIC) ========== --}}
@if(isset($heroTour) && $heroTour)
<section class="py-24 bg-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <div class="flex flex-col lg:flex-row gap-16 items-center">
            <div class="w-full lg:w-1/2 relative" x-data="{
                activeSlide: 0,
                slides: [
                    '{{ \App\Helpers\AssetHelper::getBannerUrl('kili-1') }}',
                    '{{ \App\Helpers\AssetHelper::getBannerUrl('kili-2') }}',
                    '{{ \App\Helpers\AssetHelper::getBannerUrl('kili-3') }}',
                    '{{ \App\Helpers\AssetHelper::getBannerUrl('kili-4') }}',
                    '{{ \App\Helpers\AssetHelper::getBannerUrl('kili-5') }}'
                ],
                init() {
                    setInterval(() => {
                        this.activeSlide = (this.activeSlide + 1) % this.slides.length;
                    }, 5000);
                }
            }">
                <div class="relative rounded-[2rem] md:rounded-[3rem] overflow-hidden shadow-2xl h-[450px] md:h-[700px]">
                    <template x-for="(slide, index) in slides" :key="index">
                        <div x-show="activeSlide === index"
                             x-transition:enter="transition ease-out duration-1000"
                             x-transition:enter-start="opacity-0 scale-110"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="absolute inset-0 w-full h-full">
                            <img :src="slide" width="800" height="700" class="w-full h-full object-cover" alt="{{ $heroTour->title }}" loading="lazy" decoding="async">
                        </div>
                    </template>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent pointer-events-none"></div>
                    <div class="absolute bottom-10 left-10 right-10">
                        <div class="bg-white/10 backdrop-blur-md border border-white/20 p-6 rounded-3xl">
                            <p class="text-white text-lg font-bold mb-2">🏔️ {{ $heroTour->title }}</p>
                            <p class="text-gold-400 text-sm font-black uppercase tracking-widest">The Ultimate Experience</p>
                        </div>
                    </div>
                </div>
                <div class="absolute -top-6 -right-6 w-32 h-32 bg-gold-500 rounded-full flex flex-col items-center justify-center shadow-2xl z-20 transform rotate-12">
                    <span class="text-safari-dark text-[10px] font-black uppercase">From</span>
                    <span class="text-safari-dark text-xl font-black">{{ $heroTour->display_price_formatted }}</span>
                    <span class="text-safari-dark text-[8px] font-bold uppercase">Per Person</span>
                </div>
            </div>
            <div class="lg:w-1/2 space-y-8">
                <div>
                    <span class="text-gold-600 text-sm font-black uppercase tracking-[0.3em] mb-4 block">{{ __('Signature Expedition') }}</span>
                    <h2 class="font-display text-4xl md:text-5xl font-black text-safari-dark leading-tight text-center md:text-left">
                        {{ $heroTour->translate('title') }}
                    </h2>
                    <div class="w-20 h-1.5 bg-[#e64a19] rounded-full mt-6 mx-auto md:mx-0"></div>
                </div>
                <div class="flex justify-center md:justify-start">
                    <a href="{{ route('booking.create', $heroTour->slug) }}" class="btn-gold px-12 py-5 rounded-full text-base font-black shadow-2xl hover:scale-105 transition-all text-center uppercase tracking-widest">
                        {{ __('BOOK THIS TOUR') }}
                    </a>
                </div>
                <p class="text-gray-600 text-lg leading-relaxed font-light">{{ $heroTour->translate('short_description') }}</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-5 gap-x-8">
                    @php
                        $inclusions = $heroTour->translate('inclusions');
                        if (!is_array($inclusions)) {
                            $inclusions = ['Professional Guides', 'Airport Transfers', 'Hotel Stay', 'Park Fees', 'Camping Gear', 'Fresh Meals', 'Emergency Oxygen', 'Summit Certificate'];
                        }
                    @endphp
                    @foreach($inclusions as $inc)
                    <div class="flex items-start gap-3 group">
                        <div class="w-6 h-6 shrink-0 rounded-full bg-gold-500/10 flex items-center justify-center mt-0.5 group-hover:bg-gold-500 transition-colors duration-300">
                            <svg class="w-3 h-3 text-gold-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="text-gray-700 text-sm font-black uppercase tracking-tight leading-tight">{{ is_array($inc) ? implode(', ', $inc) : __($inc) }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100" x-data="{ openItinerary: false }">
                    <div class="flex items-center justify-between mb-10">
                        <h2 class="font-display text-3xl font-black text-[#e64a19] uppercase tracking-tight">{{ __('Route summary') }}</h2>
                        <button @@click="openItinerary = !openItinerary" class="text-[10px] font-black uppercase text-gold-600 hover:text-gold-700 transition-colors flex items-center gap-1.5">
                            <span x-text="openItinerary ? '{{ __('Hide Details') }}' : '{{ __('View Day-by-Day') }}'"></span>
                            <svg class="w-3 h-3 transition-transform duration-300" :class="openItinerary ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>
                    <div class="space-y-6">
                        @php $itinerary = $heroTour->translate('itinerary'); @endphp
                        @if($itinerary && is_array($itinerary))
                            @foreach($itinerary as $index => $day)
                            <div class="flex flex-col gap-2">
                                <div class="flex items-start gap-4">
                                    <span class="text-[#e64a19] font-black text-2xl leading-none">→</span>
                                    <h5 class="text-gray-800 font-bold text-lg md:text-xl leading-tight">
                                        <span class="text-gray-400">{{ __('Day') }} {{ $loop->iteration }}:</span> {{ $day['title'] ?? '' }}
                                    </h5>
                                </div>
                                <div x-show="openItinerary" x-collapse>
                                    <div class="pl-9"><p class="text-gray-600 text-sm md:text-base leading-relaxed font-medium">{{ $day['description'] ?? '' }}</p></div>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="pt-6 flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('tours.index') }}" class="flex-1 px-10 py-5 rounded-full border-2 border-safari-dark text-safari-dark text-sm font-black uppercase tracking-widest hover:bg-safari-dark hover:text-white transition-all text-center">
                        {{ __('EXPLORE ALL TOURS') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- ========== 3. MOUNT KILIMANJARO (REDESIGNED COMPACT 2-COLUMN) ========== --}}
<section class="py-12 bg-safari-dark relative overflow-hidden border-b border-white/5">
    <div class="absolute inset-0 z-0 opacity-20 pointer-events-none">
        <img src="{{ \App\Helpers\AssetHelper::getBannerUrl('kilimanjaro_bg') }}" width="1920" height="800" class="w-full h-full object-cover" alt="Kilimanjaro Background" loading="lazy" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-b from-safari-dark via-transparent to-safari-dark"></div>
    </div>
    <div class="max-w-7xl mx-auto px-4 relative z-10">
        @php
            $kilimanjaroTours = \App\Models\Tour::published()
                ->where(function($q) {
                    $q->whereHas('category', function($catQ) {
                        $catQ->where('slug', 'like', '%kilimanjaro%');
                    })->orWhere('tour_type', 'kilimanjaro-trekking')
                      ->orWhere('title', 'like', '%Kilimanjaro%')
                      ->orWhere('title', 'like', '%Machame%')
                      ->orWhere('title', 'like', '%Marangu%')
                      ->orWhere('title', 'like', '%Lemosho%')
                      ->orWhere('title', 'like', '%Rongai%');
                })->get();

            $routesDef = [
                [
                    'key' => 'machame',
                    'name' => __('Machame Route'),
                    'alias' => __('Whiskey Route'),
                    'default_days' => '7 ' . __('Days'),
                    'default_difficulty' => __('Challenging'),
                    'badge_color' => 'bg-amber-500/20 text-amber-300 border-amber-500/30',
                ],
                [
                    'key' => 'marangu',
                    'name' => __('Marangu Route'),
                    'alias' => __('Coca-Cola Route'),
                    'default_days' => '6 ' . __('Days'),
                    'default_difficulty' => __('Moderate'),
                    'badge_color' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                ],
                [
                    'key' => 'lemosho',
                    'name' => __('Lemosho Route'),
                    'alias' => __('Scenic Route'),
                    'default_days' => '8 ' . __('Days'),
                    'default_difficulty' => __('High Success'),
                    'badge_color' => 'bg-blue-500/20 text-blue-300 border-blue-500/30',
                ],
                [
                    'key' => 'rongai',
                    'name' => __('Rongai Route'),
                    'alias' => __('Northern Route'),
                    'default_days' => '7 ' . __('Days'),
                    'default_difficulty' => __('Moderate'),
                    'badge_color' => 'bg-purple-500/20 text-purple-300 border-purple-500/30',
                ],
            ];

            $fallbackUrl = route('tours.index', ['tour_type' => 'kilimanjaro-trekking']);

            $routes = array_map(function($r) use ($kilimanjaroTours, $fallbackUrl) {
                $matched = $kilimanjaroTours->first(function($t) use ($r) {
                    return stripos($t->title, $r['key']) !== false || stripos($t->slug, $r['key']) !== false;
                });

                if ($matched) {
                    $url = route('tours.show', ['type' => $matched->item_type ?? 'tour', 'slug' => $matched->slug]);
                    $days = $matched->duration_days ? ($matched->duration_days . ' ' . __('Days')) : $r['default_days'];
                    $difficulty = $matched->difficulty_level ? ucfirst(__($matched->difficulty_level)) : $r['default_difficulty'];
                } else {
                    $url = $fallbackUrl;
                    $days = $r['default_days'];
                    $difficulty = $r['default_difficulty'];
                }

                return array_merge($r, [
                    'url' => $url,
                    'days' => $days,
                    'difficulty' => $difficulty,
                ]);
            }, $routesDef);
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
            {{-- Left Column: Text, Stats, and CTA --}}
            <div class="lg:col-span-5 flex flex-col text-left">
                <span class="inline-block text-gold-400 text-xs md:text-sm font-black uppercase tracking-[0.4em] mb-3">
                    {{ __('The Roof of Africa') }}
                </span>
                <h2 class="font-display text-3xl md:text-5xl font-black text-white leading-tight mb-4">
                    {{ __('Conquer') }} <span class="italic text-gold-500">{{ __('Mt. Kilimanjaro') }}</span>: {{ __('The Ultimate Bucket-List Trek') }}
                </h2>
                <p class="text-gold-300 text-sm md:text-base font-display italic mb-6 leading-relaxed">
                    "{{ __('This is your chance to do something extraordinary. Lace up your boots, pack your determination, and let’s claim the Roof of Africa together.') }}"
                </p>

                {{-- Stats --}}
                <div class="flex flex-wrap items-center gap-4 mb-8">
                    <div class="flex items-center gap-3 px-5 py-2.5 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-md">
                        <span class="text-gold-400 font-black text-lg md:text-xl">5,895m</span>
                        <span class="text-gray-300 text-[9px] font-black uppercase tracking-widest leading-tight">{{ __('Peak Elevation') }}</span>
                    </div>
                    <div class="flex items-center gap-3 px-5 py-2.5 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-md">
                        <span class="text-gold-400 font-black text-lg md:text-xl">98%</span>
                        <span class="text-gray-300 text-[9px] font-black uppercase tracking-widest leading-tight">{{ __('Success Rate') }}</span>
                    </div>
                </div>

                {{-- CTA Button --}}
                <div>
                    <a href="{{ route('tours.index', ['tour_type' => 'kilimanjaro-trekking']) }}" class="btn-gold w-full sm:w-auto px-8 py-4 rounded-full text-xs md:text-sm font-black uppercase tracking-widest transition-all hover:scale-105 inline-flex items-center justify-center gap-3 shadow-lg">
                        {{ __('Explore All Packages') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>

            {{-- Right Column: 4 Route Cards (2x2 Grid on desktop, horizontal scroll on mobile) --}}
            <div class="lg:col-span-7">
                <div class="flex overflow-x-auto snap-x snap-mandatory gap-4 pb-4 pt-1 -mx-4 px-4 sm:mx-0 sm:px-0 sm:grid sm:grid-cols-2 lg:gap-5 scrollbar-none">
                    @foreach($routes as $r)
                    <a href="{{ $r['url'] }}" class="snap-start shrink-0 w-[82%] sm:w-auto bg-white/5 hover:bg-white/10 border border-white/10 hover:border-gold-500/50 rounded-2xl p-5 md:p-6 backdrop-blur-md transition-all duration-300 transform hover:-translate-y-1 group flex flex-col justify-between shadow-lg">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="px-2.5 py-1 text-[10px] font-black uppercase tracking-wider rounded-full border {{ $r['badge_color'] }}">
                                    {{ $r['difficulty'] }}
                                </span>
                                <span class="text-xs font-bold text-gold-400 flex items-center gap-1.5 shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $r['days'] }}
                                </span>
                            </div>
                            <h3 class="text-white font-bold text-lg leading-tight group-hover:text-gold-400 transition-colors">
                                {{ $r['name'] }}
                            </h3>
                            <p class="text-gray-400 text-xs font-medium tracking-wide mt-1">
                                {{ $r['alias'] }}
                            </p>
                        </div>
                        <div class="mt-5 pt-3 border-t border-white/5 flex items-center justify-between text-xs font-semibold text-gray-300 group-hover:text-white transition-colors">
                            <span>{{ __('Explore Route') }}</span>
                            <svg class="w-4 h-4 text-gold-400 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========== 3.5 WHY CHOOSE US SECTION (COMPACT UNIFIED BOX) ========== --}}
<section class="pt-10 pb-6 md:pt-12 md:pb-8 bg-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <div class="bg-[#fcfaf7] rounded-[2rem] p-8 md:p-12 border border-stone-200/70 shadow-md relative overflow-hidden group">
            {{-- Decorative background accent --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-gold-500/5 rounded-full -mr-32 -mt-32 transition-transform duration-1000 group-hover:scale-110"></div>

            <div class="flex flex-col lg:flex-row items-center gap-10 lg:gap-16">
                {{-- Title Part --}}
                <div class="lg:w-1/3 text-center lg:text-left relative z-10">
                    <h2 class="font-display text-4xl md:text-5xl font-black text-safari-dark leading-[1.1]">Why Journey <br class="hidden lg:block"> With Us?</h2>
                    <div class="w-16 h-1.5 bg-gold-500 mt-5 mx-auto lg:mx-0 rounded-full"></div>
                </div>

                {{-- Features Part --}}
                <div class="lg:w-2/3 grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-8 relative z-10">
                    {{-- USP 1 --}}
                    <div class="group/item border-l-2 border-gold-500/30 pl-6 hover:border-gold-500 transition-all duration-500 bg-white/50 p-5 rounded-r-2xl hover:bg-white hover:shadow-md">
                        <span class="text-[10px] font-black text-gold-600 uppercase tracking-[0.3em] mb-2 block">{{ __('Excellence') }}</span>
                        <h4 class="font-black text-gray-900 text-lg mb-2 uppercase tracking-tighter">{{ __('Expert Local Guides') }}</h4>
                        <p class="text-gray-600 text-xs leading-relaxed font-medium">
                            {{ __('Our guides are born and raised in the heart of the Tanzanian wilderness, possessing an instinctive understanding of wildlife behavior and migratory patterns. With over a decade of field experience, they offer deep insights into the delicate balance of our ecosystem, ensuring every sighting is not just seen, but understood and ethically managed.') }}
                        </p>
                    </div>

                    {{-- USP 2 --}}
                    <div class="group/item border-l-2 border-gold-500/30 pl-6 hover:border-gold-500 transition-all duration-500 bg-white/50 p-5 rounded-r-2xl hover:bg-white hover:shadow-md">
                        <span class="text-[10px] font-black text-gold-600 uppercase tracking-[0.3em] mb-2 block">{{ __('Bespoke') }}</span>
                        <h4 class="font-black text-gray-900 text-lg mb-2 uppercase tracking-tighter">{{ __('Tailor-Made Safaris') }}</h4>
                        <p class="text-gray-600 text-xs leading-relaxed font-medium">
                            {{ __('We believe no two travelers are the same, and neither should their journeys be. Our expert consultants work tirelessly with you to craft bespoke itineraries that match your specific pace, personal interests, and budget. From private photography expeditions to multi-generational family adventures, we personalize every detail to create your dream escape.') }}
                        </p>
                    </div>

                    {{-- USP 3 --}}
                    <div class="group/item border-l-2 border-gold-500/30 pl-6 hover:border-gold-500 transition-all duration-500 bg-white/50 p-6 rounded-r-2xl hover:bg-white hover:shadow-md">
                        <span class="text-[10px] font-black text-gold-600 uppercase tracking-[0.3em] mb-2 block">{{ __('Value') }}</span>
                        <h4 class="font-black text-gray-900 text-lg mb-2 uppercase tracking-tighter">{{ __('Direct Local Pricing') }}</h4>
                        <p class="text-gray-600 text-xs leading-relaxed font-medium">
                            {{ __('By operating directly on the ground from our headquarters in Tanzania, we eliminate unnecessary third-party commissions and middleman fees. This allows us to offer ultra-premium experiences at exceptionally competitive rates while ensuring that a larger portion of your travel investment goes directly into local conservation efforts and community upliftment.') }}
                        </p>
                    </div>

                    {{-- USP 4 --}}
                    <div class="group/item border-l-2 border-gold-500/30 pl-6 hover:border-gold-500 transition-all duration-500 bg-white/50 p-5 rounded-r-2xl hover:bg-white hover:shadow-md">
                        <span class="text-[10px] font-black text-gold-600 uppercase tracking-[0.3em] mb-2 block">{{ __('Reliability') }}</span>
                        <h4 class="font-black text-gray-900 text-lg mb-2 uppercase tracking-tighter">{{ __('24/7 Ground Support') }}</h4>
                        <p class="text-gray-600 text-xs leading-relaxed font-medium">
                            {{ __('Your safety and comfort are our non-negotiable priorities. From the moment you step off the plane until your final departure, our dedicated logistics team is available around the clock. We handle every detail, from flight transfers to medical contingencies, giving you the absolute peace of mind to fully immerse yourself in the majestic beauty of the African wilderness.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========== 4. FEATURED TOURS (SPECIALIST 4-COLUMN CARD GRID) ========== --}}
@if(isset($featuredTours) && $featuredTours->count() > 0)
<section class="pt-6 pb-12 md:pt-8 md:pb-16 bg-stone-100/70 border-t border-stone-200/80">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-10 md:mb-14">
            <span class="text-gold-600 text-xs font-black uppercase tracking-[0.3em] mb-2 block">Handpicked Journeys</span>
            <h2 class="font-display text-3xl md:text-5xl font-black text-gray-900 mb-3">Featured Safari Packages</h2>
            <p class="text-gray-600 text-sm md:text-base max-w-2xl mx-auto font-normal">
                Explore our top-rated Tanzania safari itineraries and Kilimanjaro trekking packages crafted by local experts.
            </p>
            <div class="w-16 h-1 bg-gold-500 mt-5 rounded-full mx-auto"></div>
        </div>

        {{-- 4 Columns Specialist Cards Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 md:gap-4">
            @foreach($featuredTours as $tour)
                @php
                    $rawTitle = $tour->translate('title') ?: $tour->title;
                    $titleStr = \App\Helpers\AssetHelper::asString($rawTitle);

                    $rawDesc = $tour->translate('short_description') ?: $tour->short_description;
                    $descStr = \App\Helpers\AssetHelper::asString($rawDesc);
                @endphp
                <a href="{{ route('tours.show', ['type' => $tour->item_type ?? 'tour', 'slug' => $tour->slug]) }}"
                   class="group relative block w-full rounded-md md:rounded-lg overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 h-56 sm:h-60 md:h-64 bg-stone-900 border border-stone-800/50">

                    {{-- Background Cover Image with Zoom Effect --}}
                    <img src="{{ $tour->featured_image_url }}"
                         alt="{{ $titleStr }}"
                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out"
                         loading="lazy" decoding="async">

                    {{-- Dark Overlay Gradient for Readable Text --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/50 to-black/20 group-hover:from-black/90 transition-all pointer-events-none"></div>

                    {{-- Price Tag Badge at TOP RIGHT --}}
                    <div class="absolute top-2.5 right-2.5 z-10">
                        <span class="inline-block bg-[#f8b218] text-stone-950 font-black text-[10px] sm:text-[11px] px-2.5 py-1 rounded shadow-md uppercase tracking-wider">
                            From {{ $tour->display_price_formatted }}*
                        </span>
                    </div>

                    {{-- Centered Text Content (Title & Subtitle) --}}
                    <div class="absolute inset-x-0 bottom-0 top-0 p-4 pb-5 flex flex-col justify-end items-center text-center z-10 pointer-events-none">
                        <h3 class="font-black text-white text-xs sm:text-sm md:text-base leading-tight uppercase tracking-tight group-hover:text-amber-300 transition-colors drop-shadow-md max-w-[88%]">
                            {{ $titleStr }}
                        </h3>
                        @if($descStr)
                        <p class="text-stone-200 text-[11px] sm:text-xs font-normal leading-snug line-clamp-2 max-w-[92%] mt-1 drop-shadow-xs">
                            {{ $descStr }}
                        </p>
                        @endif
                    </div>

                    {{-- White Circle Arrow Button at Bottom Right --}}
                    <div class="absolute bottom-2.5 right-2.5 w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-white text-stone-950 flex items-center justify-center shrink-0 shadow-lg group-hover:scale-110 group-hover:bg-[#f8b218] transition-all duration-300 z-10">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-stone-950" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('tours.index') }}" class="btn-gold px-8 py-3.5 rounded-full text-xs font-black uppercase tracking-widest inline-flex items-center gap-2 group shadow-lg">
                View All Packages
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>
@endif

{{-- ========== 5. WHAT TRAVELERS SAY (CUSTOM LUXURY CARD GRID) ========== --}}
<section class="py-10 px-4 md:px-8 bg-[#12110e] text-[#faf0d9] border-t border-white/5 relative">
    <div class="max-w-7xl mx-auto">
        {{-- Section Header --}}
        <div class="flex items-baseline justify-between mb-2">
            <h2 class="font-display text-2xl md:text-4xl font-bold text-[#faf0d9] tracking-tight">
                {{ __('What Travelers Say') }}
            </h2>
            <div class="text-xs md:text-sm font-semibold text-[#e5b842] flex items-center gap-1.5 shrink-0">
                <span class="text-[#e5b842]">★</span> 5.0 {{ __('from guest reviews') }}
            </div>
        </div>

        {{-- Golden Divider Line --}}
        <div class="w-full border-b border-[#d4af37]/70 mt-2 mb-6"></div>

        {{-- 3-Column Card Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4.5 md:gap-5">
            @php
                $reviewsList = [
                    [
                        'name' => 'Sarah Mitchell',
                        'role' => __('Safari traveler'),
                        'content' => __('"Absolutely life-changing experience! Our guide knew every animal\'s behavior."'),
                        'initials' => 'SA',
                    ],
                    [
                        'name' => 'Marco & Julia',
                        'role' => __('Honeymoon couple'),
                        'content' => __('"Perfect honeymoon! Safari followed by Zanzibar beach time."'),
                        'initials' => 'MA',
                    ],
                    [
                        'name' => 'David Chen',
                        'role' => __('Mountain climber'),
                        'content' => __('"Summiting Kilimanjaro was the toughest but most rewarding thing I\'ve ever done."'),
                        'initials' => 'DA',
                    ],
                ];
            @endphp

            @foreach($reviewsList as $rev)
            <div class="bg-[#1a1915] border border-white/10 border-l-[3px] border-l-[#d4af37] rounded-xl p-[18px] flex flex-col justify-between shadow-lg hover:border-white/20 transition-all duration-300">
                <div>
                    {{-- 5 Gold Stars --}}
                    <div class="flex text-[#e5b842] gap-1 mb-3">
                        @for($s=0; $s<5; $s++)
                            <svg class="w-3.5 h-3.5 fill-current"><use href="#star"/></svg>
                        @endfor
                    </div>

                    {{-- Review Content --}}
                    <p class="text-[#e2ded6] text-[0.92rem] leading-relaxed mb-4 font-normal">
                        {{ $rev['content'] }}
                    </p>
                </div>

                {{-- Author Profile --}}
                <div class="flex items-center gap-3 pt-2">
                    <div class="w-9 h-9 rounded-full bg-[#2a2416] border border-[#d4af37]/80 flex items-center justify-center text-[#e5b842] font-bold text-xs shrink-0">
                        {{ $rev['initials'] }}
                    </div>
                    <div>
                        <h4 class="font-bold text-[#faf0d9] text-sm leading-tight block">
                            {{ $rev['name'] }}
                        </h4>
                        <span class="text-[#d4af37] text-xs font-medium block mt-0.5">
                            {{ $rev['role'] }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- SVG Star Definition Symbol --}}
    <svg style="display:none">
        <symbol id="star" viewBox="0 0 24 24">
            <path fill="currentColor" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
        </symbol>
    </svg>
</section>

{{-- ========== 6. SAFARI JOURNAL (BLOG) ========== --}}
@if(isset($latestPosts) && $latestPosts->count() > 0)
<section id="blog" class="py-20 bg-stone-100/70 border-t border-stone-200/80">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <span class="text-gold-600 text-xs font-black uppercase tracking-[0.3em] mb-2 block">Safari Journal</span>
            <h2 class="font-display text-3xl md:text-5xl font-black text-gray-900 mb-3">Latest Stories & Insights</h2>
            <p class="text-gray-600 text-sm md:text-base max-w-2xl mx-auto font-normal">Explore expert travel guides, safari tips, and breathtaking photography from Tanzania.</p>
            <div class="w-16 h-1 bg-gold-500 mt-5 rounded-full mx-auto"></div>
        </div>

        {{-- Specialist Cards Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-5">
            @foreach($latestPosts as $post)
                @php
                    $rawTitle = $post->translate('title') ?: $post->title;
                    $titleStr = \App\Helpers\AssetHelper::asString($rawTitle);

                    $rawExcerpt = $post->translate('excerpt') ?: $post->excerpt;
                    $excerptStr = \App\Helpers\AssetHelper::asString($rawExcerpt);
                @endphp
                <a href="{{ route('blog.show', $post->slug) }}"
                   class="group relative block w-full rounded-md md:rounded-lg overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 h-60 sm:h-64 bg-stone-900 border border-stone-800/50">

                    {{-- Background Cover Image with Zoom Effect --}}
                    <img src="{{ $post->featured_image_url }}"
                         alt="{{ $titleStr }}"
                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out"
                         loading="lazy" decoding="async">

                    {{-- Dark Overlay Gradient for Readable Text --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/50 to-black/20 group-hover:from-black/90 transition-all pointer-events-none"></div>

                    {{-- Category Badge at TOP RIGHT --}}
                    <div class="absolute top-2.5 right-2.5 z-10">
                        <span class="inline-block bg-[#f8b218] text-stone-950 font-black text-[10px] sm:text-[11px] px-2.5 py-1 rounded shadow-md uppercase tracking-wider">
                            {{ $post->category->name ?? 'BLOG' }}
                        </span>
                    </div>

                    {{-- Centered Text Content (Title & Excerpt) --}}
                    <div class="absolute inset-x-0 bottom-0 top-0 p-4 pb-5 flex flex-col justify-end items-center text-center z-10 pointer-events-none">
                        <h3 class="font-black text-white text-xs sm:text-sm md:text-base leading-tight uppercase tracking-tight group-hover:text-amber-300 transition-colors drop-shadow-md max-w-[88%]">
                            {{ $titleStr }}
                        </h3>
                        @if($excerptStr)
                        <p class="text-stone-200 text-[11px] sm:text-xs font-normal leading-snug line-clamp-2 max-w-[92%] mt-1 drop-shadow-xs">
                            {{ $excerptStr }}
                        </p>
                        @endif
                    </div>

                    {{-- White Circle Arrow Button at Bottom Right --}}
                    <div class="absolute bottom-2.5 right-2.5 w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-white text-stone-950 flex items-center justify-center shrink-0 shadow-lg group-hover:scale-110 group-hover:bg-[#f8b218] transition-all duration-300 z-10">
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-stone-950" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('blog.index') }}" class="btn-gold px-8 py-3.5 rounded-full text-xs font-black uppercase tracking-widest inline-flex items-center gap-2 group shadow-lg">
                View All Stories
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>
@endif

{{-- ========== 8. TRUST & AFFILIATIONS STRIP ========== --}}
<section class="py-16 bg-white border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-8">
            {{-- TripAdvisor --}}
            <div class="bg-[#fcfaf7] border border-gray-100 rounded-2xl p-6 flex flex-col items-center justify-center gap-3 transition-all duration-500 hover:shadow-xl hover:shadow-gold-500/10 group cursor-help h-32">
                <div class="w-full flex items-center justify-center group-hover:scale-105 transition-transform">
                    <svg viewBox="0 0 24 24" class="h-10 w-10 text-[#34E0A1]" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M12 0C5.385 0 0 5.385 0 12s5.385 12 12 12 12-5.385 12-12S18.615 0 12 0zm0 21.6c-5.302 0-9.6-4.298-9.6-9.6S6.698 2.4 12 2.4s9.6 4.298 9.6 9.6-4.298 9.6-9.6 9.6zm4.183-14.4c-.663 0-1.2.537-1.2 1.2s.537 1.2 1.2 1.2 1.2-.537 1.2-1.2-.537-1.2-1.2-1.2zm-8.366 0c-.663 0-1.2.537-1.2 1.2s.537 1.2 1.2 1.2 1.2-.537 1.2-1.2-.537-1.2-1.2-1.2zm4.183 9.6c-1.988 0-3.6-1.612-3.6-3.6h7.2c0 1.988-1.612 3.6-3.6 3.6z"/></svg>
                </div>
                <span class="text-[9px] font-black uppercase tracking-widest text-gray-900 text-center">TripAdvisor Excellence</span>
            </div>

            {{-- Google --}}
            <div class="bg-[#fcfaf7] border border-gray-100 rounded-2xl p-6 flex flex-col items-center justify-center gap-3 transition-all duration-500 hover:shadow-xl hover:shadow-gold-500/10 group cursor-help h-32">
                <div class="w-full flex items-center justify-center group-hover:scale-105 transition-transform">
                    <svg viewBox="0 0 24 24" class="h-10 w-10" xmlns="http://www.w3.org/2000/svg"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c1.61-1.48 2.54-3.67 2.54-6.09z" fill="#4285F4"/><path d="M12 23c3.11 0 5.71-1.03 7.62-2.79l-3.57-2.77c-1.01.68-2.3 1.08-4.05 1.08-3.11 0-5.75-2.1-6.7-4.91H1.05v2.85C2.94 19.46 7.18 23 12 23z" fill="#34A853"/><path d="M5.3 13.61c-.24-.68-.37-1.42-.37-2.18s.13-1.5-.37-2.18V6.4H1.05c-.68 1.4-1.05 2.96-1.05 4.6s.37 3.2 1.05 4.6l4.25-3.39z" fill="#FBBC05"/><path d="M12 4.75c1.69 0 3.2.58 4.39 1.71l3.3-3.3C17.7 1.19 15.11 0 12 0 7.18 0 2.94 3.54 1.05 8.12l4.25 3.39C6.25 8.65 8.89 6.55 12 6.55z" fill="#EA4335"/></svg>
                </div>
                <span class="text-[9px] font-black uppercase tracking-widest text-gray-900 text-center">Verified Google Reviews</span>
            </div>

            {{-- Sustainable --}}
            <div class="bg-[#fcfaf7] border border-gray-100 rounded-2xl p-6 flex flex-col items-center justify-center gap-3 transition-all duration-500 hover:shadow-xl hover:shadow-gold-500/10 group cursor-help h-32">
                <div class="w-12 h-12 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <span class="text-3xl">🌿</span>
                </div>
                <span class="text-[9px] font-black uppercase tracking-widest text-gray-900 text-center">Sustainable Tourism Partner</span>
            </div>
        </div>
    </div>
</section>

{{-- ========== 8.5 FREQUENTLY ASKED QUESTIONS (GLOBAL Q&A BASE) ========== --}}
@if(isset($faqs) && $faqs->count() > 0)
<section class="py-16 md:py-20 bg-stone-100/70 border-t border-stone-200/80">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-10 md:mb-12">
            <span class="text-gold-600 text-xs font-black uppercase tracking-[0.3em] mb-2 block">{{ __('Help & Knowledge Base') }}</span>
            <h2 class="font-display text-3xl md:text-5xl font-black text-stone-900 mb-3">{{ __('Frequently Asked Questions') }}</h2>
            <p class="text-stone-600 text-sm md:text-base max-w-2xl mx-auto font-normal">{{ __('Find answers to common questions about safari bookings, travel advice, and Tanzania adventures.') }}</p>
            <div class="w-16 h-1 bg-gold-500 mt-5 rounded-full mx-auto"></div>
        </div>

        <div class="space-y-3">
            @foreach($faqs as $faq)
            <div x-data="{ open: false }" class="bg-white border border-[#e8dfcf] rounded-2xl overflow-hidden shadow-xs hover:border-[#b8920d]/60 transition-all">
                <button type="button" @click="open = !open" class="w-full text-left p-5 md:p-6 font-bold text-stone-900 text-sm sm:text-base flex items-center justify-between cursor-pointer hover:bg-stone-50/50 transition-colors">
                    <span>{{ $faq->translate('question') ?: $faq->question }}</span>
                    <span class="text-[#8A6D0B] text-xl font-bold ml-2 shrink-0 transition-transform duration-200" :class="open ? 'rotate-45' : ''">+</span>
                </button>
                <div x-show="open" x-collapse x-cloak class="px-5 pb-5 pt-1 text-stone-600 text-xs sm:text-sm leading-relaxed border-t border-[#e8dfcf]/60 bg-[#fffdf9]">
                    {!! $faq->translate('answer') ?: $faq->answer !!}
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-10">
            <a href="{{ route('faqs.index') }}" class="btn-gold px-8 py-3.5 rounded-full text-xs font-black uppercase tracking-widest inline-flex items-center gap-2 group shadow-lg">
                {{ __('View All FAQs') }}
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>
@endif

{{-- ========== 7. HOME FOOTER BANNER ========== --}}
<section class="relative h-[60vh] min-h-[500px] flex items-center overflow-hidden bg-safari-dark">
    <img src="{{ \App\Helpers\AssetHelper::getBannerUrl('home_footer_banner') }}" width="1920" height="600" class="absolute inset-0 w-full h-full object-cover opacity-60" alt="Twina Safaris Africa" loading="lazy" decoding="async">
    <div class="absolute inset-0 bg-gradient-to-t from-safari-dark via-transparent to-transparent"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 text-center">
        <h2 class="font-display text-5xl md:text-8xl text-white font-black mb-10 leading-tight drop-shadow-2xl">Start Your <span class="text-gold-400 italic">Legacy</span> Today</h2>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-8">
            <a href="{{ route('tours.index') }}" class="btn-gold px-14 py-6 rounded-full text-xl font-black shadow-2xl transition-all hover:scale-110 active:scale-95">Explore All Tours</a>
            <a href="{{ route('trip-plan.index') }}" class="bg-white/10 backdrop-blur-lg border-2 border-white/20 text-white hover:bg-white hover:text-safari-dark px-14 py-6 rounded-full text-xl font-black transition-all">Plan Custom Trip</a>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function initSeasonIndicator() {
        const containers = [document.getElementById('green-container'), document.getElementById('yellow-container'), document.getElementById('red-container')];
        const month = new Date().getMonth() + 1;
        let activeIdx = [6,7,8,9,10].includes(month) ? 0 : ([1,2,11,12].includes(month) ? 1 : 2);
        containers.forEach((c, i) => { if(c) c.style.display = (i === activeIdx) ? 'block' : 'none'; });
    }
    initSeasonIndicator();
});
</script>
@endsection
