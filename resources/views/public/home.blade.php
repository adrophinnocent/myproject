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

{{-- ========== VIDEO HERO SECTION ========== --}}
<section class="relative min-h-screen lg:h-screen flex flex-col overflow-hidden bg-safari-dark">
    {{-- Background Video/Image --}}
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-black/40 z-10"></div>

        @if($sliders && $sliders->count() > 0)
            <div x-data="{
                activeSlide: 0,
                slidesCount: {{ $sliders->count() }},
                next() { this.activeSlide = (this.activeSlide + 1) % this.slidesCount }
            }" x-init="setInterval(() => next(), 8000)" class="w-full h-full relative">
                @foreach($sliders as $index => $slide)
                    <div x-show="activeSlide === {{ $index }}"
                         x-transition:enter="transition ease-out duration-1000"
                         x-transition:enter-start="opacity-0 scale-105"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-1000"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="absolute inset-0 w-full h-full">
                        @if($slide->type === 'video')
                            <video autoplay muted loop playsinline class="w-full h-full object-cover">
                                <source src="{{ $slide->image_url }}" type="video/mp4">
                            </video>
                        @else
                            <img src="{{ $slide->image_url }}" width="1920" height="1080" class="w-full h-full object-cover" alt="{{ $slide->title }}" loading="eager">
                        @endif

                        {{-- Overlay Content for each slide --}}
                        <div class="absolute inset-0 flex items-center justify-center text-center px-4 z-20">
                            <div class="max-w-5xl">
                                @if($slide->subtitle)
                                    <span class="inline-block text-gold-400 text-sm md:text-lg font-bold uppercase tracking-[0.4em] mb-6 animate-pulse">
                                        {{ $slide->subtitle }}
                                    </span>
                                @endif
                                @if($slide->title)
                                    <h1 class="font-display text-4xl md:text-8xl lg:text-9xl text-white font-bold leading-[0.85] mb-8 drop-shadow-2xl">
                                        {{ $slide->title }}
                                    </h1>
                                @endif
                                @if($slide->cta_text)
                                    <div class="mt-10">
                                        <a href="{{ $slide->cta_url ?: '#' }}" class="btn-gold px-12 py-5 rounded-full text-lg font-black shadow-2xl transition-all hover:scale-105 pointer-events-auto">
                                            {{ $slide->cta_text }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Original Fallback if no sliders defined --}}
            @if(\App\Models\Setting::get('hero_video'))
                <video autoplay muted loop playsinline class="w-full h-full object-cover">
                    <source src="{{ asset('storage/' . \App\Models\Setting::get('hero_video')) }}" type="video/mp4">
                </video>
            @else
                <picture>
                    <source srcset="{{ \App\Helpers\AssetHelper::getBannerUrl('hero_fallback') }}" type="image/webp">
                    <img src="{{ \App\Helpers\AssetHelper::getBannerUrl('hero_fallback') }}"
                         width="1920" height="1080"
                         class="w-full h-full object-cover"
                         alt="Tanzania Safari"
                         loading="eager"
                         fetchpriority="high">
                </picture>
            @endif
        @endif
    </div>

    <!-- Season Status Indicator -->
    <div class="absolute top-24 right-6 md:top-32 md:right-10 z-40">
        <div class="flex flex-col gap-4 items-center">
            <div class="season-light relative group cursor-help" id="green-container" style="display:none;">
                <div class="relative">
                    <div class="absolute -inset-2 bg-green-500/20 rounded-full blur-lg"></div>
                    <div id="green-light" class="relative w-5 h-5 rounded-full bg-gradient-to-br from-green-400 to-green-600 border-2 border-white/50 shadow-lg"></div>
                </div>
                <div class="season-tooltip absolute right-full top-0 mr-4 w-72 bg-white/95 backdrop-blur-md rounded-2xl p-5 shadow-2xl border border-green-100">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        <h5 class="font-black text-gray-900 uppercase tracking-widest text-xs">Peak Travel Season</h5>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed font-light">
                        {{ \App\Helpers\AssetHelper::asString(\App\Models\Setting::get('season_good_text'), 'June to October: The absolute best time for wildlife viewing.') }}
                    </p>
                </div>
            </div>
            <div class="season-light relative group cursor-help" id="yellow-container" style="display:none;">
                <div class="relative">
                    <div class="absolute -inset-2 bg-yellow-500/20 rounded-full blur-lg"></div>
                    <div id="yellow-light" class="relative w-5 h-5 rounded-full bg-gradient-to-br from-yellow-400 to-yellow-600 border-2 border-white/50 shadow-lg"></div>
                </div>
                <div class="season-tooltip absolute right-full top-0 mr-4 w-72 bg-white/95 backdrop-blur-md rounded-2xl p-5 shadow-2xl border border-yellow-100">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-2 h-2 rounded-full bg-yellow-500 animate-pulse"></span>
                        <h5 class="font-black text-gray-900 uppercase tracking-widest text-xs">Shoulder Season</h5>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed font-light">
                        {{ \App\Helpers\AssetHelper::asString(\App\Models\Setting::get('season_moderate_text'), 'Jan-Feb & Nov-Dec.') }}
                    </p>
                </div>
            </div>
            <div class="season-light relative group cursor-help" id="red-container" style="display:none;">
                <div class="relative">
                    <div class="absolute -inset-2 bg-red-500/20 rounded-full blur-lg"></div>
                    <div id="red-light" class="relative w-5 h-5 rounded-full bg-gradient-to-br from-red-400 to-red-600 border-2 border-white/50 shadow-lg"></div>
                </div>
                <div class="season-tooltip absolute right-full top-0 mr-4 w-72 bg-white/95 backdrop-blur-md rounded-2xl p-5 shadow-2xl border border-red-100">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                        <h5 class="font-black text-gray-900 uppercase tracking-widest text-xs">Rainy Season</h5>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed font-light">
                        {{ \App\Helpers\AssetHelper::asString(\App\Models\Setting::get('season_low_text'), 'March to May: Long rains.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    @if(!$sliders || $sliders->count() === 0)
    <!-- Main Content Container (Search Only if no Sliders) -->
    <div class="relative z-20 flex-grow flex items-center justify-center py-24 lg:py-0">
        <div class="w-full max-w-6xl mx-auto px-4 text-center">

            <div class="mt-20">
                {{-- SEARCH BAR --}}
                <div class="max-w-5xl mx-auto">
                    <div class="bg-black/30 backdrop-blur-3xl rounded-3xl md:rounded-full p-2 md:p-1.5 border-2 border-white/20 shadow-[0_0_50px_-12px_rgba(212,175,55,0.3)]">
                        <form action="{{ route('tours.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-2 md:gap-1">
                        <div class="relative">
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
                        <div class="relative">
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
                        <div class="relative">
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
                        <button type="submit" class="w-full bg-gold-500 hover:bg-gold-600 text-safari-dark py-4 rounded-2xl md:rounded-full font-black text-sm uppercase tracking-widest flex items-center justify-center gap-2 shadow-xl transition-all active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            Search
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Trust Strip --}}
    <div class="relative z-30 bg-black/40 backdrop-blur-xl border-t border-white/10 py-8 lg:py-5">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-2 lg:grid-cols-4 gap-y-8 gap-x-4">
            <div class="flex items-center justify-center gap-3 lg:border-r border-white/10">
                <div class="text-gold-400">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                </div>
                <div class="text-left">
                    <div class="text-white text-xs font-black uppercase tracking-widest leading-tight">Best Rated</div>
                    <div class="text-gray-400 text-[10px] font-bold">TripAdvisor 2024</div>
                </div>
            </div>
            <div class="flex items-center justify-center gap-3 lg:border-r border-white/10">
                <div class="text-gold-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div class="text-left">
                    <div class="text-white text-xs font-black uppercase tracking-widest leading-tight">Safe & Secure</div>
                    <div class="text-gray-400 text-[10px] font-bold">Certified Operator</div>
                </div>
            </div>
            <div class="flex items-center justify-center gap-3 lg:border-r border-white/10">
                <div class="text-gold-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.347 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="text-left">
                    <div class="text-white text-xs font-black uppercase tracking-widest leading-tight">Affordable</div>
                    <div class="text-gray-400 text-[10px] font-bold">Direct Pricing</div>
                </div>
            </div>
            <div class="flex items-center justify-center gap-3">
                <div class="text-gold-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div class="text-left">
                    <div class="text-white text-xs font-black uppercase tracking-widest leading-tight">24/7 Support</div>
                    <div class="text-gray-400 text-[10px] font-bold">Expert Assistance</div>
                </div>
            </div>
        </div>
    </div>
    @endif
</section>

{{-- ========== FEATURED PACKAGE: DYNAMIC HERO TOUR ========== --}}
@if($heroTour)
<section class="py-24 bg-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <div class="flex flex-col lg:flex-row gap-16 items-center">
            {{-- Visual Side: Moving Image Slider --}}
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
                             x-transition:leave="transition ease-in duration-1000"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="absolute inset-0 w-full h-full">
                            <img :src="slide" width="800" height="700" class="w-full h-full object-cover" alt="{{ $heroTour->title }}" loading="lazy">
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
                {{-- Decorative badge --}}
                <div class="absolute -top-6 -right-6 w-32 h-32 bg-gold-500 rounded-full flex flex-col items-center justify-center shadow-2xl z-20 transform rotate-12">
                    <span class="text-safari-dark text-[10px] font-black uppercase">From</span>
                    <span class="text-safari-dark text-xl font-black">${{ number_format($heroTour->price) }}</span>
                    <span class="text-safari-dark text-[8px] font-bold uppercase">Per Person</span>
                </div>
            </div>

            {{-- Info Side --}}
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

                <p class="text-gray-600 text-lg leading-relaxed font-light">
                    {{ $heroTour->short_description }}
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-5 gap-x-8">
                    @php
                        $inclusions = is_array($heroTour->inclusions) ? $heroTour->inclusions : [
                            'Professional Guides & Porters', 'Airport Transfers Included',
                            'Hotel Stay Before/After', 'All Park Fees & Permits',
                            'Quality Camping Gear', 'Fresh Meals & Safe Water',
                            'Emergency Oxygen & Safety', 'Summit Certificate'
                        ];
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
                                        <span class="text-gray-400">Day {{ is_numeric($index) ? $index : $loop->iteration }}:</span> {{ is_array($day['title'] ?? '') ? implode(', ', $day['title']) : ($day['title'] ?? '') }}
                                    </h5>
                                </div>
                                <div x-show="openItinerary" x-collapse>
                                    <div class="pl-9 space-y-4">
                                        <p class="text-gray-600 text-sm md:text-base leading-relaxed font-medium">
                                            {{ is_array($day['description'] ?? '') ? implode("\n", $day['description']) : ($day['description'] ?? '') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="flex items-start gap-4">
                                <span class="text-[#e64a19] font-black text-2xl">→</span>
                                <p class="text-gray-800 font-bold text-lg leading-tight">
                                    <span class="text-gray-900">Itinerary for {{ $heroTour->duration_text }}:</span>
                                    {{ $heroTour->short_description }}
                                </p>
                            </div>
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
