@extends('public.layouts.app')

@section('content')

{{-- ========== SEASON STATUS INDICATOR STYLES ========== --}}
<style>
    @keyframes alert-blink {
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
            slidesCount: {{ $sliders->count() > 0 ? $sliders->count() : 1 }},
            next() { this.activeSlide = (this.activeSlide + 1) % this.slidesCount }
         }"
         x-init="if(slidesCount > 1) setInterval(() => next(), 8000)">

    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-black/40 z-10"></div>
        @if($sliders && $sliders->count() > 0)
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
        <div class="w-full max-w-5xl">
            @if($sliders && $sliders->count() > 0)
                @foreach($sliders as $index => $slide)
                    <div x-show="activeSlide === {{ $index }}" x-cloak
                         x-transition:enter="transition ease-out duration-1000"
                         x-transition:enter-start="opacity-0 translate-y-8"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="space-y-10">
                        <div class="space-y-4">
                            @if($slide->subtitle)
                                <span class="inline-block text-gold-400 text-sm md:text-lg font-bold uppercase tracking-[0.4em] animate-pulse">
                                    {{ $slide->subtitle }}
                                </span>
                            @endif
                            @if($slide->title)
                                <h1 class="font-display text-3xl sm:text-4xl md:text-8xl lg:text-9xl text-white font-black leading-[0.85] drop-shadow-2xl">
                                    {{ $slide->title }}
                                </h1>
                            @endif
                        </div>
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 md:gap-6 relative z-50 pt-4 md:pt-0">
                            @if($slide->cta_text)
                                <a href="{{ $slide->cta_url ?: '#' }}" class="btn-gold px-12 py-5 rounded-full text-base font-black shadow-2xl transition-all hover:scale-105 active:scale-95 min-w-[220px]">
                                    {{ $slide->cta_text }}
                                </a>
                            @endif
                            <a href="{{ route('tours.index', ['tour_type' => 'kilimanjaro-trekking']) }}" class="bg-white/5 backdrop-blur-md border border-white/10 text-white hover:bg-gold-500 hover:text-safari-dark px-10 py-4 rounded-full text-sm font-black transition-all min-w-[220px]">
                                Climb Kilimanjaro
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
                            <option value="" class="text-gray-900">Where to?</option>
                            @foreach(\App\Models\Destination::where('is_active', true)->get() as $dest)
                            <option value="{{ $dest->id }}" class="text-gray-900">{{ $dest->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gold-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                    <div class="relative flex-1">
                        <select name="category" class="w-full bg-white/10 md:bg-transparent border-0 md:border-r border-white/10 rounded-2xl md:rounded-none px-6 py-4 text-white text-sm font-bold focus:ring-0 appearance-none cursor-pointer">
                            <option value="" class="text-gray-900">Adventure Type</option>
                            @foreach(\App\Models\Category::where('is_active', true)->get() as $cat)
                            <option value="{{ $cat->id }}" class="text-gray-900">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gold-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                    <div class="relative flex-1">
                        <select name="duration" class="w-full bg-white/10 md:bg-transparent border-0 rounded-2xl md:rounded-none px-6 py-4 text-white text-sm font-bold focus:ring-0 appearance-none cursor-pointer">
                            <option value="" class="text-gray-900">How long?</option>
                            <option value="1-3" class="text-gray-900">1-3 Days</option>
                            <option value="4-7" class="text-gray-900">4-7 Days</option>
                            <option value="8-14" class="text-gray-900">8-14 Days</option>
                            <option value="15+" class="text-gray-900">15+ Days</option>
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gold-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                    <div class="md:w-48">
                        <button type="submit" class="w-full h-full bg-gold-500 hover:bg-gold-600 text-safari-dark py-4 px-8 rounded-2xl md:rounded-full font-black text-xs uppercase tracking-widest flex items-center justify-center gap-2 shadow-xl transition-all active:scale-95 group">
                            <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            SEARCH
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="max-w-4xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-4 px-2">
            <div class="flex flex-col md:flex-row items-center justify-center gap-2 md:gap-3 md:border-r border-white/10 group">
                <div class="text-gold-400 transition-transform group-hover:scale-110">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                </div>
                <div class="text-center md:text-left">
                    <div class="text-white text-[9px] md:text-[11px] font-black uppercase tracking-widest leading-tight">Best Rated</div>
                    <div class="text-gray-400 text-[8px] md:text-[9px] font-bold">TripAdvisor 2024</div>
                </div>
            </div>
            <div class="flex flex-col md:flex-row items-center justify-center gap-2 md:gap-3 md:border-r border-white/10 group">
                <div class="text-gold-400 transition-transform group-hover:scale-110">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div class="text-center md:text-left">
                    <div class="text-white text-[9px] md:text-[11px] font-black uppercase tracking-widest whitespace-nowrap">Safe & Secure</div>
                    <div class="text-gray-400 text-[8px] md:text-[9px] font-bold">Certified Operator</div>
                </div>
            </div>
            <div class="flex flex-col md:flex-row items-center justify-center gap-2 md:gap-3 md:border-r border-white/10 group">
                <div class="text-gold-400 transition-transform group-hover:scale-110">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.347 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="text-center md:text-left">
                    <div class="text-white text-[9px] md:text-[11px] font-black uppercase tracking-widest leading-tight">Affordable</div>
                    <div class="text-gray-400 text-[8px] md:text-[9px] font-bold">Direct Pricing</div>
                </div>
            </div>
            <div class="flex flex-col md:flex-row items-center justify-center gap-2 md:gap-3 group">
                <div class="text-gold-400 transition-transform group-hover:scale-110">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div class="text-center md:text-left">
                    <div class="text-white text-[9px] md:text-[11px] font-black uppercase tracking-widest whitespace-nowrap">24/7 Support</div>
                    <div class="text-gray-400 text-[8px] md:text-[9px] font-bold">Expert Assistance</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========== 2. FEATURED PACKAGE (KILIMANJARO DYNAMIC) ========== --}}
@if($heroTour)
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
                    <span class="text-safari-dark text-xl font-black">${{ number_format($heroTour->price) }}</span>
                    <span class="text-safari-dark text-[8px] font-bold uppercase">Per Person</span>
                </div>
            </div>
            <div class="lg:w-1/2 space-y-8">
                <div>
                    <span class="text-gold-600 text-sm font-black uppercase tracking-[0.3em] mb-4 block">Signature Expedition</span>
                    <h2 class="font-display text-4xl md:text-5xl font-black text-safari-dark leading-tight text-center md:text-left">
                        {{ \App\Helpers\AssetHelper::asString($heroTour->title) }}
                    </h2>
                    <div class="w-20 h-1.5 bg-[#e64a19] rounded-full mt-6 mx-auto md:mx-0"></div>
                </div>
                <div class="flex justify-center md:justify-start">
                    <a href="{{ route('booking.create', $heroTour->slug) }}" class="btn-gold px-12 py-5 rounded-full text-base font-black shadow-2xl hover:scale-105 transition-all text-center uppercase tracking-widest">
                        BOOK THIS TOUR
                    </a>
                </div>
                <p class="text-gray-600 text-lg leading-relaxed font-light">{{ $heroTour->short_description }}</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-5 gap-x-8">
                    @php
                        $inclusions = is_array($heroTour->inclusions) ? $heroTour->inclusions : ['Professional Guides', 'Airport Transfers', 'Hotel Stay', 'Park Fees', 'Camping Gear', 'Fresh Meals', 'Emergency Oxygen', 'Summit Certificate'];
                    @endphp
                    @foreach($inclusions as $inc)
                    <div class="flex items-start gap-3 group">
                        <div class="w-6 h-6 shrink-0 rounded-full bg-gold-500/10 flex items-center justify-center mt-0.5 group-hover:bg-gold-500 transition-colors duration-300">
                            <svg class="w-3 h-3 text-gold-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="text-gray-700 text-sm font-black uppercase tracking-tight leading-tight">{{ is_array($inc) ? implode(', ', $inc) : $inc }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100" x-data="{ openItinerary: false }">
                    <div class="flex items-center justify-between mb-10">
                        <h2 class="font-display text-3xl font-black text-[#e64a19] uppercase tracking-tight">Route summary</h2>
                        <button @click="openItinerary = !openItinerary" class="text-[10px] font-black uppercase text-gold-600 hover:text-gold-700 transition-colors flex items-center gap-1.5">
                            <span x-text="openItinerary ? 'Hide Details' : 'View Day-by-Day'"></span>
                            <svg class="w-3 h-3 transition-transform duration-300" :class="openItinerary ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                    </div>
                    <div class="space-y-6">
                        @if($heroTour->itinerary && is_array($heroTour->itinerary))
                            @foreach($heroTour->itinerary as $index => $day)
                            <div class="flex flex-col gap-2">
                                <div class="flex items-start gap-4">
                                    <span class="text-[#e64a19] font-black text-2xl leading-none">→</span>
                                    <h5 class="text-gray-800 font-bold text-lg md:text-xl leading-tight">
                                        <span class="text-gray-400">Day {{ $loop->iteration }}:</span> {{ $day['title'] ?? '' }}
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
                        EXPLORE ALL TOURS
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- ========== 3. MOUNT KILIMANJARO (STATIC) ========== --}}
<section class="py-24 bg-safari-dark relative overflow-hidden border-b border-white/5">
    <div class="absolute inset-0 z-0 opacity-20 pointer-events-none">
        <img src="{{ \App\Helpers\AssetHelper::getBannerUrl('kilimanjaro_bg') }}" width="1920" height="800" class="w-full h-full object-cover" alt="Kilimanjaro Background" loading="lazy" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-b from-safari-dark via-transparent to-safari-dark"></div>
    </div>
    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <div class="text-center mb-16">
            <span class="inline-block text-gold-400 text-sm font-black uppercase tracking-[0.4em] mb-4">The Roof of Africa</span>
            <h2 class="font-display text-4xl md:text-8xl font-black text-white leading-tight mb-8">
                Conquer <span class="italic text-gold-500">Mt. Kilimanjaro</span>: The Ultimate Bucket-List Trek
            </h2>
            <p class="text-gray-300 text-lg md:text-2xl max-w-5xl mx-auto font-light leading-relaxed">
                Towering at an awe-inspiring <span class="text-white font-bold">5,895 meters</span> (19,341 feet), Mount Kilimanjaro is a beacon for adventurers worldwide. As one of the legendary Seven Summits, it demands respect, preparation, and passion. But the reward is entirely unmatched.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20">
            <div class="bg-white/5 backdrop-blur-md p-8 rounded-[2rem] border border-white/10 hover:border-gold-500/50 transition-all group">
                <h3 class="text-white font-bold text-xl mb-3">The Route</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Journey from equatorial heat to arctic cold, witnessing changing landscapes that feel like traveling from the equator to the North Pole in just a few days.</p>
            </div>
            <div class="bg-white/5 backdrop-blur-md p-8 rounded-[2rem] border border-white/10 hover:border-gold-500/50 transition-all group">
                <h3 class="text-white font-bold text-xl mb-3">The Experience</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Walk alongside expert local guides, bond with a team of fellow adventurers, and sleep beneath a canopy of stars untouched by city lights.</p>
            </div>
            <div class="bg-white/5 backdrop-blur-md p-8 rounded-[2rem] border border-white/10 hover:border-gold-500/50 transition-all group">
                <h3 class="text-white font-bold text-xl mb-3">The Summit</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Test your grit during the final, challenging night-climb, and reach the top just as the African sun breaks over the horizon.</p>
            </div>
        </div>

        <div class="max-w-4xl mx-auto text-center">
            <p class="text-gold-400 text-xl md:text-2xl font-display italic mb-10">
                "This is your chance to do something extraordinary. Lace up your boots, pack your determination, and let’s claim the Roof of Africa together."
            </p>
            <div class="flex flex-wrap justify-center gap-6 mt-10">
                <div class="flex items-center gap-3 px-6 py-3 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-md">
                    <span class="text-gold-400 font-black text-xl">5,895m</span>
                    <span class="text-gray-400 text-[9px] font-black uppercase tracking-widest leading-tight">Peak Elevation</span>
                </div>
                <div class="flex items-center gap-3 px-6 py-3 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-md">
                    <span class="text-gold-400 font-black text-xl">98%</span>
                    <span class="text-gray-400 text-[9px] font-black uppercase tracking-widest leading-tight">Success Rate</span>
                </div>
            </div>
        </div>

        <div class="mt-20 bg-white/5 border border-white/5 rounded-[3rem] p-8 md:p-16 backdrop-blur-md max-w-5xl mx-auto">
            <h3 class="font-display text-2xl font-black text-[#e64a19] mb-10 text-center uppercase tracking-widest">Route Options</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 text-left">
                @php $routes = [['n' => 'Machame', 'a' => 'Whiskey Route'], ['n' => 'Marangu', 'a' => 'Coca-Cola Route'], ['n' => 'Lemosho', 'a' => 'Scenic Route'], ['n' => 'Rongai', 'a' => 'Northern Route']]; @endphp
                @foreach($routes as $r)
                <div class="flex items-center gap-4 group cursor-default">
                    <span class="text-gold-500 font-black text-xl">→</span>
                    <div class="flex flex-col">
                        <span class="text-white font-bold text-lg leading-tight">{{ $r['n'] }}</span>
                        <span class="text-gray-500 text-[9px] font-black uppercase tracking-widest mt-1">{{ $r['a'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-16 text-center">
                <a href="{{ route('tours.index', ['tour_type' => 'kilimanjaro-trekking']) }}" class="btn-gold px-12 py-5 rounded-full text-sm font-black uppercase tracking-widest transition-all hover:scale-105 inline-flex items-center gap-4">
                    Explore All Packages
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ========== 3.5 WHY CHOOSE US SECTION (COMPACT UNIFIED BOX) ========== --}}
<section class="py-16 bg-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <div class="bg-[#fcfaf7] rounded-[3rem] p-10 md:p-16 border border-gray-100 shadow-2xl shadow-gold-500/5 relative overflow-hidden group">
            {{-- Decorative background accent --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-gold-500/5 rounded-full -mr-32 -mt-32 transition-transform duration-1000 group-hover:scale-110"></div>

            <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-20">
                {{-- Title Part --}}
                <div class="lg:w-1/3 text-center lg:text-left relative z-10">
                    <span class="text-gold-600 text-[10px] font-black uppercase tracking-[0.5em] mb-3 block">The Twina Advantage</span>
                    <h2 class="font-display text-4xl md:text-5xl font-black text-safari-dark leading-[1.1]">Why Journey <br class="hidden lg:block"> With Us?</h2>
                    <div class="w-16 h-1.5 bg-gold-500 mt-6 mx-auto lg:mx-0 rounded-full"></div>
                </div>

                {{-- Features Part --}}
                <div class="lg:w-2/3 grid grid-cols-1 sm:grid-cols-2 gap-x-12 gap-y-12 relative z-10">
                    {{-- USP 1 --}}
                    <div class="flex items-start gap-5 group/item">
                        <div class="w-14 h-14 shrink-0 bg-white rounded-2xl shadow-sm flex items-center justify-center text-2xl group-hover/item:bg-gold-500 group-hover/item:text-white group-hover/item:scale-110 transition-all duration-500 border border-gray-100">🛡️</div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-lg mb-1 transition-colors group-hover/item:text-gold-600">Expert Local Guides</h4>
                            <p class="text-gray-500 text-sm leading-relaxed font-light">Born and raised in Tanzania, offering deep cultural insights and tracking skills.</p>
                        </div>
                    </div>

                    {{-- USP 2 --}}
                    <div class="flex items-start gap-5 group/item">
                        <div class="w-14 h-14 shrink-0 bg-white rounded-2xl shadow-sm flex items-center justify-center text-2xl group-hover/item:bg-gold-500 group-hover/item:text-white group-hover/item:scale-110 transition-all duration-500 border border-gray-100">✨</div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-lg mb-1 transition-colors group-hover/item:text-gold-600">Tailor-Made Safaris</h4>
                            <p class="text-gray-500 text-sm leading-relaxed font-light">Every itinerary is custom-crafted to match your rhythm, budget, and dreams.</p>
                        </div>
                    </div>

                    {{-- USP 3 --}}
                    <div class="flex items-start gap-5 group/item">
                        <div class="w-14 h-14 shrink-0 bg-white rounded-2xl shadow-sm flex items-center justify-center text-2xl group-hover/item:bg-gold-500 group-hover/item:text-white group-hover/item:scale-110 transition-all duration-500 border border-gray-100">💎</div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-lg mb-1 transition-colors group-hover/item:text-gold-600">Direct Local Pricing</h4>
                            <p class="text-gray-500 text-sm leading-relaxed font-light">No middlemen, ensuring the best value while supporting the local economy.</p>
                        </div>
                    </div>

                    {{-- USP 4 --}}
                    <div class="flex items-start gap-5 group/item">
                        <div class="w-14 h-14 shrink-0 bg-white rounded-2xl shadow-sm flex items-center justify-center text-2xl group-hover/item:bg-gold-500 group-hover/item:text-white group-hover/item:scale-110 transition-all duration-500 border border-gray-100">📞</div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-lg mb-1 transition-colors group-hover/item:text-gold-600">24/7 Ground Support</h4>
                            <p class="text-gray-500 text-sm leading-relaxed font-light">Our team is on standby 24/7 to ensure your total safety and comfort.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========== 3.7 OUR IMPACT IN NUMBERS (STATISTICS) ========== --}}
<section class="py-12 bg-safari-dark border-y border-white/5 relative overflow-hidden" x-data="{
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
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-64 h-64 bg-gold-500/20 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 bg-gold-500/20 rounded-full blur-3xl"></div>
    </div>
    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 md:gap-12">
            <div class="text-center group" x-data="{ current: 0, triggered: false }" x-intersect="if(!triggered) { triggered = true; startCount(500, 2000, (val) => current = val) }">
                <div class="text-3xl md:text-5xl font-display font-black text-gold-500 mb-2 group-hover:scale-110 transition-transform duration-500"><span x-text="current">0</span>+</div>
                <p class="text-[10px] md:text-xs text-gray-400 font-black uppercase tracking-[0.3em]">Happy Travelers</p>
            </div>
            <div class="text-center group" x-data="{ current: 0, triggered: false }" x-intersect="if(!triggered) { triggered = true; startCount(150, 2000, (val) => current = val) }">
                <div class="text-3xl md:text-5xl font-display font-black text-gold-500 mb-2 group-hover:scale-110 transition-transform duration-500"><span x-text="current">0</span>+</div>
                <p class="text-[10px] md:text-xs text-gray-400 font-black uppercase tracking-[0.3em]">Safari Packages</p>
            </div>
            <div class="text-center group" x-data="{ current: 0, triggered: false }" x-intersect="if(!triggered) { triggered = true; startCount(10, 2000, (val) => current = val) }">
                <div class="text-3xl md:text-5xl font-display font-black text-gold-500 mb-2 group-hover:scale-110 transition-transform duration-500"><span x-text="current">0</span>+</div>
                <p class="text-[10px] md:text-xs text-gray-400 font-black uppercase tracking-[0.3em]">Years Experience</p>
            </div>
            <div class="text-center group" x-data="{ current: 0, triggered: false }" x-intersect="if(!triggered) { triggered = true; startCount(100, 2000, (val) => current = val) }">
                <div class="text-3xl md:text-5xl font-display font-black text-gold-500 mb-2 group-hover:scale-110 transition-transform duration-500"><span x-text="current">0</span>%</div>
                <p class="text-[10px] md:text-xs text-gray-400 font-black uppercase tracking-[0.3em]">Safety Record</p>
            </div>
        </div>
    </div>
</section>

{{-- ========== 4. FEATURED TOURS ========== --}}
<section class="py-24 bg-white" x-data="{
    scrollBy(distance) {
        const slider = document.getElementById('tours-slider');
        if (slider) { slider.scrollBy({ left: distance, behavior: 'smooth' }); }
    }
}">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <span class="text-gold-600 text-sm font-semibold uppercase tracking-[0.3em] mb-3 block">Handpicked Experiences</span>
            <h2 class="font-display text-4xl md:text-6xl font-black text-gray-900 mb-6">Featured Safari Tours</h2>
            <p class="text-gray-500 text-lg md:text-xl max-w-3xl mx-auto leading-relaxed font-light">
                Discover our curated collection of <span class="text-gold-600 font-bold">extraordinary journeys</span>. From the vast plains of the Serengeti to the hidden gems of the Ngorongoro Crater, each tour is designed to immerse you in the raw beauty and majestic wildlife of Tanzania.
            </p>
            <div class="w-16 h-1.5 bg-gold-500 mt-8 rounded-full mx-auto"></div>
        </div>
        <div class="relative group">
            <div id="tours-slider" class="flex gap-6 overflow-x-auto pb-6 snap-x snap-mandatory no-scrollbar scroll-smooth">
                @forelse($featuredTours as $tour)
                    <div class="snap-start shrink-0 w-[85vw] sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)]">
                        <div class="bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-md hover:shadow-xl transition-all h-full">
                            <div class="relative h-56"><img src="{{ $tour->featured_image_url }}" width="600" height="400" alt="{{ \App\Helpers\AssetHelper::asString($tour->title) }}" class="w-full h-full object-cover" loading="lazy" decoding="async"></div>
                            <div class="p-6 flex flex-col justify-between h-[calc(100%-14rem)]">
                                <div class="flex items-center gap-3 text-gray-600 text-xs mb-3 font-semibold"><span>{{ $tour->duration_text }}</span> • <span>{{ $tour->destination->name ?? 'Tanzania' }}</span></div>
                                <h3 class="font-display text-xl font-semibold text-gray-900 mb-1"><a href="{{ route('tours.show', ['type' => $tour->item_type, 'slug' => $tour->slug]) }}" class="hover:text-gold-600">{{ \App\Helpers\AssetHelper::asString($tour->title) }}</a></h3>
                                <p class="text-gray-700 text-sm mb-5 line-clamp-2 leading-relaxed">{{ $tour->short_description }}</p>
                                <div class="flex items-center justify-between pt-4 border-t border-gray-100 mt-auto">
                                    <div class="text-2xl font-display font-bold text-gold-600">{{ $tour->formatted_price }}</div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('tours.show', ['type' => $tour->item_type, 'slug' => $tour->slug]) }}" class="px-4 py-2 border border-gray-200 text-gray-600 text-[10px] font-black uppercase tracking-widest rounded-full hover:bg-gray-50 transition-colors">Details</a>
                                        <a href="{{ route('booking.create', $tour->slug) }}" class="btn-gold px-5 py-2 rounded-full text-[10px] font-black uppercase tracking-widest">Book Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="w-full text-center py-10 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200"><h3 class="text-lg font-bold text-gray-500 uppercase tracking-widest">Tours Coming Soon</h3></div>
                @endforelse
            </div>
        </div>
        <div class="text-center mt-12"><a href="{{ route('tours.index') }}" class="btn-gold px-8 py-3 rounded-full text-base font-semibold inline-flex items-center gap-2 group">View All Tours <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg></a></div>
    </div>
</section>

{{-- ========== 5. GUEST STORIES ========== --}}
<section class="py-24 bg-[#fcfaf7] overflow-hidden border-t border-gray-100" x-data="{
    scrollBy(distance) {
        const slider = document.getElementById('testimonials-slider');
        if (slider) { slider.scrollBy({ left: distance, behavior: 'smooth' }); }
    }
}">
    <div class="max-w-7xl mx-auto px-4 mb-16 text-center">
        <span class="text-gold-600 text-sm font-black uppercase tracking-[0.3em] mb-4 block">Guest Stories</span>
        <h2 class="font-display text-4xl md:text-6xl font-black text-safari-dark">What Travelers Say</h2>
        <div class="w-16 h-1.5 bg-gold-500 mt-6 rounded-full mx-auto"></div>
    </div>
    <div class="relative max-w-6xl mx-auto">
        <div id="testimonials-slider" class="flex gap-8 overflow-x-auto pb-12 px-4 snap-x snap-mandatory no-scrollbar scroll-smooth">
            @php $customTestimonials = [['name' => 'Sarah Mitchell', 'title' => 'Safari Traveler', 'content' => 'Absolutely life-changing experience! Our guide knew every animal\'s behavior.', 'initials' => 'SA'], ['name' => 'Marco & Julia', 'title' => 'Honeymoon Couple', 'content' => 'Perfect honeymoon! Safari followed by Zanzibar beach time.', 'initials' => 'MA'], ['name' => 'David Chen', 'title' => 'Mountain Climber', 'content' => 'Summiting Kilimanjaro was the toughest but most rewarding thing I\'ve ever done.', 'initials' => 'DA']]; @endphp
            @foreach($customTestimonials as $t)
            <div class="snap-center shrink-0 w-[85vw] md:w-[calc(50%-16px)] lg:w-[calc(33.333%-22px)]">
                <div class="bg-white rounded-[2.5rem] p-10 border border-gray-100 shadow-xl hover:shadow-2xl transition-all duration-500 h-full flex flex-col relative group">
                    <div class="absolute top-6 right-8 text-gold-100 group-hover:text-gold-200 transition-colors duration-500"><svg class="w-16 h-16 fill-current" viewBox="0 0 32 32"><path d="M10 8v8H6v6h6V8h-2zm12 0v8h-4v6h6V8h-2z"/></svg></div>
                    <div class="flex text-gold-500 mb-8 gap-1 relative z-10">@for($i=0; $i<5; $i++) <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> @endfor</div>
                    <p class="text-gray-700 text-lg leading-relaxed mb-10 flex-grow italic font-light relative z-10">"{{ $t['content'] }}"</p>
                    <div class="flex items-center gap-5 pt-8 border-t border-gray-50 relative z-10"><div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-gold-400 to-gold-600 flex items-center justify-center text-white font-black text-xl shadow-lg transform -rotate-3 group-hover:rotate-0 transition-transform duration-300">{{ $t['initials'] }}</div><div><h4 class="font-black text-safari-dark text-lg leading-tight">{{ $t['name'] }}</h4><p class="text-gold-600 text-xs font-bold uppercase tracking-widest mt-1">{{ $t['title'] }}</p></div></div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="flex justify-center gap-4 mt-8">
            <button @click="scrollBy(-400)" class="w-12 h-12 rounded-full border-2 border-gold-200 flex items-center justify-center text-gold-600 hover:bg-gold-600 hover:text-white transition-all"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></button>
            <button @click="scrollBy(400)" class="w-12 h-12 rounded-full border-2 border-gold-200 flex items-center justify-center text-gold-600 hover:bg-gold-600 hover:text-white transition-all"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
        </div>
    </div>
</section>

{{-- ========== 6. SAFARI JOURNAL (BLOG) ========== --}}
@if(isset($latestPosts) && $latestPosts->count() > 0)
<section id="blog" class="py-24 bg-white border-t border-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <span class="text-gold-600 text-sm font-black uppercase tracking-[0.3em] mb-4 block">Safari Journal</span>
            <h2 class="font-display text-4xl md:text-6xl font-black text-gray-900 leading-tight">Latest Stories & Insights</h2>
            <p class="text-gray-500 text-lg mt-6 max-w-3xl mx-auto leading-relaxed font-light">Explore expert travel guides and breathtaking photography.</p>
            <div class="w-16 h-1.5 bg-gold-500 mt-8 rounded-full mx-auto"></div>
        </div>
        <div class="relative group">
            <div id="blog-slider" class="flex gap-8 overflow-x-auto pb-12 snap-x snap-mandatory no-scrollbar scroll-smooth">
                @foreach($latestPosts as $post)
                <div class="snap-start shrink-0 w-[85vw] sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)]">
                    <div class="bg-white rounded-[2rem] overflow-hidden border border-gray-100 shadow-lg hover:shadow-2xl transition-all duration-500 h-full flex flex-col group">
                        <div class="relative h-64 overflow-hidden">
                            <img src="{{ $post->featured_image_url }}" width="600" height="400" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-all duration-700" loading="lazy" decoding="async">
                            <div class="absolute top-6 left-6 bg-gold-500 text-safari-dark text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-full shadow-lg">New Post</div>
                        </div>
                        <div class="p-10 flex flex-col flex-grow">
                            <h3 class="font-display text-2xl font-bold text-gray-900 mb-4 group-hover:text-gold-600 transition-colors"><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h3>
                            <p class="text-gray-600 text-base line-clamp-3 mb-8 leading-relaxed font-light">{{ $post->excerpt }}</p>
                            <div class="mt-auto pt-6 border-t border-gray-50"><a href="{{ route('blog.show', $post->slug) }}" class="text-gold-600 font-black text-sm uppercase tracking-widest flex items-center gap-3 group/link">Read Full Story <svg class="w-5 h-5 transform transition-transform group-hover/link:translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg></a></div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="text-center mt-12"><a href="{{ route('blog.index') }}" class="btn-gold px-12 py-5 rounded-full text-base font-black uppercase tracking-widest shadow-2xl transition-all hover:scale-105 active:scale-95 inline-flex items-center gap-4">View All Stories <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg></a></div>
    </div>
</section>
@endif

{{-- ========== 8. TRUST & AFFILIATIONS STRIP ========== --}}
<section class="py-16 bg-white border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-8">
            {{-- TripAdvisor --}}
            <div class="bg-[#fcfaf7] border border-gray-100 rounded-2xl p-6 flex flex-col items-center justify-center gap-3 transition-all duration-500 hover:shadow-xl hover:shadow-gold-500/10 group cursor-help h-32">
                <div class="w-12 h-12 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg viewBox="0 0 24 24" class="w-10 h-10 text-[#34E0A1]" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.5 14H15v-1h1.5v1zm-3-2H12v-1h1.5v1zm0-2H12V9h1.5v1zm3 0H15V9h1.5v1zM9 16H7.5v-1H9v1zm0-2H7.5v-1H9v1zm0-2H7.5V9H9v1zm3 4h-1.5v-1h1.5v1z"/></svg>
                </div>
                <span class="text-[9px] font-black uppercase tracking-widest text-gray-900 text-center">TripAdvisor Excellence</span>
            </div>

            {{-- Google --}}
            <div class="bg-[#fcfaf7] border border-gray-100 rounded-2xl p-6 flex flex-col items-center justify-center gap-3 transition-all duration-500 hover:shadow-xl hover:shadow-gold-500/10 group cursor-help h-32">
                <div class="w-12 h-12 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg viewBox="0 0 24 24" class="w-9 h-9" fill="currentColor">
                        <path d="M12.48 10.92v3.28h7.84c-.24 1.84-.92 3.32-2.08 4.44-1.12 1.12-2.8 2.32-5.76 2.32-4.68 0-8.24-3.8-8.24-8.48s3.56-8.48 8.24-8.48c2.52 0 4.24 1 5.56 2.24l2.32-2.32C18.48 2.08 15.8 0 12.48 0 5.48 0 0 5.48 0 12.48S5.48 24.96 12.48 24.96c3.76 0 6.6-1.24 8.84-3.6 2.32-2.32 3.04-5.56 3.04-8.12 0-.76-.08-1.52-.2-2.32h-11.68z" fill="#4285F4"/>
                    </svg>
                </div>
                <span class="text-[9px] font-black uppercase tracking-widest text-gray-900 text-center">Verified Google Business</span>
            </div>

            {{-- Licensed --}}
            <div class="bg-safari-dark border border-gold-500/30 rounded-2xl p-6 flex flex-col items-center justify-center gap-3 transition-all duration-500 hover:shadow-xl hover:shadow-gold-500/20 group cursor-help h-32">
                <div class="w-12 h-12 bg-gold-500 rounded-full flex items-center justify-center group-hover:rotate-12 transition-all">
                    <svg class="w-7 h-7 text-safari-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <span class="text-[9px] font-black uppercase tracking-widest text-gold-500 text-center">Licensed Safari Operator</span>
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
