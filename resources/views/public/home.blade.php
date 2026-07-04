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
        @if(\App\Models\Setting::get('hero_video'))
            <video autoplay muted loop playsinline class="w-full h-full object-cover">
                <source src="{{ asset('storage/' . \App\Models\Setting::get('hero_video')) }}" type="video/mp4">
            </video>
        @else
            <img src="{{ \App\Helpers\AssetHelper::getBannerUrl('hero_fallback') }}" class="w-full h-full object-cover" alt="Tanzania Safari">
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
                        {{ \App\Models\Setting::get('season_good_text', 'June to October: The absolute best time for wildlife viewing.') }}
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
                        {{ \App\Models\Setting::get('season_moderate_text', 'Jan-Feb & Nov-Dec.') }}
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
                        {{ \App\Models\Setting::get('season_low_text', 'March to May: Long rains.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="relative z-20 flex-grow flex items-center justify-center py-24 lg:py-0">
        <div class="w-full max-w-6xl mx-auto px-4 text-center">
            <span class="inline-block text-gold-400 text-sm md:text-lg font-bold uppercase tracking-[0.4em] mb-6 animate-pulse">
                {{ \App\Models\Setting::get('hero_eyebrow', 'Tanzania\'s #1 Boutique Safari Operator') }}
            </span>
            <h1 class="font-display text-4xl md:text-8xl lg:text-9xl text-white font-bold leading-[0.85] mb-8 drop-shadow-2xl">
                {{ \App\Models\Setting::get('hero_title', 'Explore Tanzania') }} <br>
                <span class="italic text-gold-500">{{ \App\Models\Setting::get('hero_subtitle', 'Beyond Expectations') }}</span>
            </h1>
            <p class="text-gray-100 text-lg md:text-2xl mb-12 max-w-3xl mx-auto font-light leading-relaxed drop-shadow-md">
                {{ \App\Models\Setting::get('hero_description', 'Unforgettable luxury safaris designed specifically for you.') }}
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 md:gap-6 mb-16">
                <a href="{{ route('tours.index') }}" class="btn-gold px-12 py-5 rounded-full text-base font-bold flex items-center gap-3 group min-w-[240px] justify-center shadow-2xl transition-all hover:scale-105">
                    Plan Your Safari
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="{{ route('tours.index', ['tour_type' => 'kilimanjaro-trekking']) }}" class="btn-outline-gold px-12 py-5 rounded-full text-base font-bold border-white text-white hover:bg-white hover:text-safari-dark transition-all min-w-[240px] justify-center backdrop-blur-md hover:scale-105">
                    Climb Kilimanjaro
                </a>
            </div>

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
                    '{{ \App\Helpers\AssetHelper::getBannerUrl('kilimanjaro_1') }}',
                    '{{ \App\Helpers\AssetHelper::getBannerUrl('kilimanjaro_2') }}',
                    '{{ \App\Helpers\AssetHelper::getBannerUrl('kilimanjaro_3') }}',
                    '{{ \App\Helpers\AssetHelper::getBannerUrl('kilimanjaro_4') }}'
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
                            <img :src="slide" class="w-full h-full object-cover" alt="{{ $heroTour->title }}">
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
                        {{ $heroTour->title }}
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

{{-- ========== MOUNT KILIMANJARO SECTION ========== --}}
<section class="py-24 bg-safari-dark relative overflow-hidden">
    <div class="absolute inset-0 z-0 opacity-20 pointer-events-none">
        <img src="{{ \App\Helpers\AssetHelper::getBannerUrl('kilimanjaro_bg') }}"
             class="w-full h-full object-cover" alt="Kilimanjaro Background">
        <div class="absolute inset-0 bg-gradient-to-b from-safari-dark via-transparent to-safari-dark"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 relative z-10">
        {{-- Full Width Header --}}
        <div class="text-center mb-16">
            <span class="inline-block text-gold-400 text-sm font-black uppercase tracking-[0.4em] mb-4">The Roof of Africa</span>
            <h2 class="font-display text-4xl md:text-7xl font-black text-white leading-tight mb-8">
                Conquer <span class="italic text-gold-500">Mt. Kilimanjaro</span>
            </h2>
            <p class="text-gray-300 text-lg max-w-4xl mx-auto font-light leading-relaxed">
                Standing at <span class="text-white font-bold">5,895 meters</span> above sea level, Mount Kilimanjaro is not just Africa's highest peak, but the tallest free-standing mountain on Earth. Embark on a legendary journey through five distinct climate zones—from lush tropical rainforests to the near-arctic summit. Our expert-led treks provide the safety, equipment, and deep local knowledge required to successfully conquer the <span class="text-gold-400 font-bold">'Roof of Africa'</span> and witness a sunrise unlike any other.
            </p>

            <div class="flex flex-wrap justify-center gap-6 mt-10">
                <div class="flex items-center gap-3 px-6 py-3 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-md">
                    <span class="text-gold-400 font-black text-xl">5,895m</span>
                    <span class="text-gray-400 text-[9px] font-black uppercase tracking-widest leading-tight">Peak<br>Elevation</span>
                </div>
                <div class="flex items-center gap-3 px-6 py-3 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-md">
                    <span class="text-gold-400 font-black text-xl">98%</span>
                    <span class="text-gray-400 text-[9px] font-black uppercase tracking-widest leading-tight">Success<br>Rate</span>
                </div>
            </div>
        </div>

        {{-- Route Summary Style List --}}
        <div class="max-w-4xl mx-auto mt-16 bg-white/5 border border-white/5 rounded-[3rem] p-8 md:p-16 backdrop-blur-md">
            <h3 class="font-display text-2xl font-black text-[#e64a19] mb-10 uppercase tracking-widest text-center">Route Options</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                @php
                    $routes = [
                        ['n' => 'Machame', 'a' => 'Whiskey Route'],
                        ['n' => 'Marangu', 'a' => 'Coca-Cola Route'],
                        ['n' => 'Lemosho', 'a' => 'The Scenic Route'],
                        ['n' => 'Rongai', 'a' => 'Northern Route'],
                        ['n' => 'Northern Circuit', 'a' => 'Grand Traverse'],
                        ['n' => 'Umbwe', 'a' => 'Extreme Route'],
                        ['n' => 'Shira', 'a' => 'Plateau Route'],
                    ];
                @endphp

                @foreach($routes as $r)
                <div class="flex items-center gap-5 group cursor-default">
                    <span class="text-[#e64a19] font-black text-2xl leading-none transform transition-transform group-hover:translate-x-2">→</span>
                    <div class="flex flex-col">
                        <span class="text-white font-bold text-xl leading-tight">{{ $r['n'] }}</span>
                        <span class="text-gold-500/60 text-[10px] font-black uppercase tracking-widest mt-1">{{ $r['a'] }}</span>
                    </div>
                </div>
                @endforeach

                <div class="flex items-center pt-6 md:pt-0">
                    <a href="{{ route('tours.index', ['tour_type' => 'kilimanjaro-trekking']) }}" class="btn-gold px-8 py-4 rounded-full text-[10px] font-black uppercase tracking-widest shadow-2xl flex items-center gap-3 transition-all hover:scale-105 active:scale-95">
                        Explore All Packages
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========== FEATURED TOURS ========== --}}
<section class="py-24 bg-white" x-data="{
    scrollBy(distance) {
        const slider = document.getElementById('tours-slider');
        if (slider) {
            slider.scrollBy({ left: distance, behavior: 'smooth' });
        }
    }
}">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <span class="text-gold-600 text-sm font-semibold uppercase tracking-widest">Handpicked Experiences</span>
            <h2 class="font-display text-4xl md:text-5xl text-gray-900 mt-3 mb-4">Featured Safari Tours</h2>
            <p class="text-gray-600 text-lg mb-4 max-w-2xl mx-auto">Explore our most popular and highly recommended safari packages, carefully designed to suit every type of traveler. Whether you are seeking adventure, luxury, or a relaxing escape into nature, our tours are crafted to deliver unforgettable experiences across Tanzania’s most iconic destinations.</p>
            <div class="section-divider"></div>
        </div>

        <div class="relative group">
            <div id="tours-slider" class="flex gap-6 overflow-x-auto pb-6 snap-x snap-mandatory no-scrollbar scroll-smooth">
                @forelse($featuredTours as $tour)
                    <div class="snap-start shrink-0 w-full sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)]">
                        <div class="bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-md hover:shadow-xl transition-all h-full">
                            <div class="relative h-56">
                                <img src="{{ $tour->featured_image_url }}" alt="{{ $tour->getTranslation('title') }} in {{ $tour->destination->name ?? 'Tanzania' }}" class="w-full h-full object-cover" loading="lazy">
                            </div>
                            <div class="p-6 flex flex-col justify-between h-[calc(100%-14rem)]">
                                <div class="flex items-center gap-3 text-gray-600 text-xs mb-3 font-semibold">
                                    <span>{{ $tour->duration_text }}</span> • <span>{{ $tour->destination->name ?? 'Tanzania' }}</span>
                                </div>
                                <h3 class="font-display text-xl font-semibold text-gray-900 mb-1">
                                    <a href="{{ route('tours.show', ['type' => $tour->item_type, 'slug' => $tour->slug]) }}" class="hover:text-gold-600">{{ $tour->title }}</a>
                                </h3>
                                <div class="text-[10px] font-mono text-gray-400 mb-4 tracking-tighter">{{ $tour->slug }}</div>
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
                    <div class="w-full text-center py-10 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                        <div class="text-4xl mb-4">🌍</div>
                        <h3 class="text-lg font-bold text-gray-500 uppercase tracking-widest">Exciting Tours Coming Soon</h3>
                        <p class="text-gray-400 text-sm">We are preparing amazing safari experiences for you. Stay tuned!</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('tours.index') }}" class="btn-gold px-8 py-3 rounded-full text-base font-semibold inline-flex items-center gap-2 group">
                View All Tours
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- ========== POPULAR DESTINATIONS ========== --}}
<section class="py-16 md:py-24 bg-white border-t border-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <span class="text-gold-600 text-sm font-semibold uppercase tracking-widest">Explore East Africa</span>
            <h2 class="font-display text-3xl md:text-5xl text-gray-900 mt-3 mb-4">Our Destinations</h2>
            <p class="text-gray-600 max-w-2xl mx-auto text-lg">Explore the most breathtaking safari destinations in Tanzania, from endless plains to iconic wildlife parks.</p>
            <div class="section-divider"></div>
        </div>

        @if(isset($destinations))
        <div>
            {{-- Slider Container --}}
            <div id="dest-slider"
                 class="flex gap-5 overflow-x-auto pb-4 snap-x snap-mandatory no-scrollbar scroll-smooth">
                @foreach($destinations as $destination)
                <a href="{{ route('tours.index', ['destination' => $destination->id]) }}"
                   class="snap-start shrink-0 w-[280px] md:w-[320px] lg:w-[340px] relative group rounded-2xl overflow-hidden h-56 block shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300">
                    <img src="{{ $destination->featured_image_url }}"
                         alt="{{ $destination->name }}"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                         loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>
                    <div class="absolute inset-0 flex flex-col justify-end p-6">
                        <h3 class="font-display text-xl md:text-2xl text-white font-bold leading-tight">{{ $destination->name }}</h3>
                        <p class="text-gold-400 text-sm md:text-base font-medium mt-1">{{ $destination->country }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

{{-- ========== POPULAR SAFARI HIGHLIGHTS SECTION ========== --}}
<section class="py-24 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col lg:flex-row items-center gap-20">
            {{-- Image Side --}}
            <div class="lg:w-1/2 relative">
                <div class="relative z-10 rounded-[3rem] overflow-hidden shadow-2xl">
                    <img src="{{ \App\Helpers\AssetHelper::getBannerUrl('safari_highlights') }}"
                         class="w-full h-[600px] object-cover" alt="Tanzania Safari Adventure">
                </div>
                {{-- Decorative Elements --}}
                <div class="absolute -bottom-10 -right-10 w-64 h-64 bg-gold-500/10 rounded-full blur-3xl -z-0"></div>
                <div class="absolute top-10 -left-10 p-8 bg-safari-dark rounded-3xl shadow-2xl z-20 hidden md:block">
                    <div class="text-gold-400 text-4xl font-black mb-1">#1</div>
                    <div class="text-white text-xs font-bold uppercase tracking-widest">Safari Destination</div>
                </div>
            </div>

            {{-- Content Side --}}
            <div class="lg:w-1/2 space-y-10">
                <div>
                    <span class="text-gold-600 text-sm font-black uppercase tracking-[0.3em] mb-4 block">Unrivaled Adventures</span>
                    <h2 class="font-display text-4xl md:text-6xl font-black text-safari-dark leading-tight">
                        Tanzania's Iconic <br> <span class="italic text-gold-600">Safari Journeys</span>
                    </h2>
                    <div class="w-20 h-1.5 bg-gold-500 rounded-full mt-6"></div>
                </div>

                <p class="text-gray-600 text-lg leading-relaxed font-light">
                    Venture into the heart of the wild. Our signature safaris offer unprecedented access to the Serengeti plains, the Ngorongoro Crater, and the hidden wilderness of the south, designed to deliver the ultimate African experience.
                </p>

                <div class="space-y-8">
                    <div class="flex gap-6 group">
                        <div class="w-14 h-14 shrink-0 bg-gold-50 rounded-2xl flex items-center justify-center text-gold-600 group-hover:bg-gold-500 group-hover:text-white transition-all duration-500 shadow-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                        <div>
                            <h4 class="text-gray-900 font-black text-sm uppercase tracking-wider mb-2">The Great Migration</h4>
                            <p class="text-gray-500 text-sm leading-relaxed">Witness the world's most spectacular wildlife movement across the vast Serengeti ecosystem.</p>
                        </div>
                    </div>

                    <div class="flex gap-6 group">
                        <div class="w-14 h-14 shrink-0 bg-gold-50 rounded-2xl flex items-center justify-center text-gold-600 group-hover:bg-gold-500 group-hover:text-white transition-all duration-500 shadow-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div>
                            <h4 class="text-gray-900 font-black text-sm uppercase tracking-wider mb-2">Big Five Tracking</h4>
                            <p class="text-gray-500 text-sm leading-relaxed">Embark on private game drives to find lions, leopards, elephants, buffaloes, and the rare rhino.</p>
                        </div>
                    </div>

                    <div class="flex gap-6 group">
                        <div class="w-14 h-14 shrink-0 bg-gold-50 rounded-2xl flex items-center justify-center text-gold-600 group-hover:bg-gold-500 group-hover:text-white transition-all duration-500 shadow-sm">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <div>
                            <h4 class="text-gray-900 font-black text-sm uppercase tracking-wider mb-2">Handpicked Luxury Camps</h4>
                            <p class="text-gray-500 text-sm leading-relaxed">Stay in the most exclusive lodges and tented camps that blend comfort with the raw beauty of the bush.</p>
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <a href="{{ route('tours.index', ['tour_type' => 'safari-tours']) }}" class="btn-gold px-10 py-4 rounded-full text-xs font-black uppercase tracking-widest shadow-xl inline-flex items-center gap-3">
                        Explore Safari Tours
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========== TESTIMONIALS SECTION (HORIZONTAL CAROUSEL) ========== --}}
<section class="py-16 md:py-24 bg-[#fcfaf7] overflow-hidden border-t border-gray-100" x-data="{
    scrollLeft: 0,
    scrollBy(distance) {
        const slider = document.getElementById('testimonials-slider');
        if (slider) {
            slider.scrollBy({ left: distance, behavior: 'smooth' });
        }
    }
}">
    <div class="max-w-7xl mx-auto px-4 mb-10 md:mb-16 text-center">
        <span class="text-gold-600 text-[10px] md:text-sm font-black uppercase tracking-[0.3em] mb-2 block">Guest Stories</span>
        <h2 class="font-display text-3xl md:text-5xl font-black text-safari-dark">What Travelers Say</h2>
        <div class="w-12 h-1 bg-gold-500 mt-4 rounded-full mx-auto"></div>
    </div>

    <div class="relative">
        <!-- Slider Container -->
        <div id="testimonials-slider"
             class="flex gap-5 overflow-x-auto pb-8 px-4 snap-x snap-mandatory no-scrollbar scroll-smooth"
             style="scroll-behavior: smooth;">

            @if(isset($testimonials) && $testimonials->count() > 0)
                @foreach($testimonials as $testimonial)
                <div class="snap-start shrink-0 w-full sm:w-[calc(50%-10px)] lg:w-[calc(33.333%-13px)] xl:w-[calc(25%-15px)]">
                    <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 h-full flex flex-col">
                        <!-- Rating -->
                        <div class="flex text-gold-500 mb-4 gap-0.5">
                            @for($i=0; $i<5; $i++)
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>

                        <!-- Quote -->
                        <p class="text-gray-700 text-base leading-relaxed mb-6 flex-grow">
                            \"{{ $testimonial->content ?? $testimonial->testimonial ?? 'Excellent experience with Twina Safaris!' }}\"
                        </p>

                        <!-- Author -->
                        <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-gold-400 to-gold-600 flex items-center justify-center text-white font-black text-lg shadow-md">
                                {{ strtoupper(substr(($testimonial->name ?? $testimonial->author ?? 'Guest'), 0, 2)) }}
                            </div>
                            <div>
                                <h4 class="font-black text-safari-dark text-sm">
                                    {{ $testimonial->name ?? $testimonial->author ?? 'Guest' }}
                                </h4>
                                <p class="text-gold-600 text-xs font-medium">
                                    {{ $testimonial->title ?? $testimonial->trip_type ?? 'Safari Traveler' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <!-- Hardcoded testimonials if no database ones -->
                <div class="snap-start shrink-0 w-full sm:w-[calc(50%-10px)] lg:w-[calc(33.333%-13px)] xl:w-[calc(25%-15px)]">
                    <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 h-full flex flex-col">
                        <div class="flex text-gold-500 mb-4 gap-0.5">
                            @for($i=0; $i<5; $i++) <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> @endfor
                        </div>
                        <p class="text-gray-700 text-base leading-relaxed mb-6 flex-grow">
                            "Perfect honeymoon! Safari followed by Zanzibar beach time. The lodges were incredible and every detail was handled with care."
                        </p>
                        <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-gold-400 to-gold-600 flex items-center justify-center text-white font-black text-lg shadow-md">MJ</div>
                            <div>
                                <h4 class="font-black text-safari-dark text-sm">Marco & Julia</h4>
                                <p class="text-gold-600 text-xs font-medium">Honeymoon Safari</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="snap-start shrink-0 w-full sm:w-[calc(50%-10px)] lg:w-[calc(33.333%-13px)] xl:w-[calc(25%-15px)]">
                    <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 h-full flex flex-col">
                        <div class="flex text-gold-500 mb-4 gap-0.5">
                            @for($i=0; $i<5; $i++) <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> @endfor
                        </div>
                        <p class="text-gray-700 text-base leading-relaxed mb-6 flex-grow">
                            "Family-friendly, professional, and so much fun! Our kids loved every minute, especially the wildlife sightings in the Serengeti."
                        </p>
                        <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-black text-lg shadow-md">KF</div>
                            <div>
                                <h4 class="font-black text-safari-dark text-sm">Kim & Family</h4>
                                <p class="text-gold-600 text-xs font-medium">Family Adventure</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="snap-start shrink-0 w-full sm:w-[calc(50%-10px)] lg:w-[calc(33.333%-13px)] xl:w-[calc(25%-15px)]">
                    <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 h-full flex flex-col">
                        <div class="flex text-gold-500 mb-4 gap-0.5">
                            @for($i=0; $i<5; $i++) <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> @endfor
                        </div>
                        <p class="text-gray-700 text-base leading-relaxed mb-6 flex-grow">
                            "Absolutely life-changing experience! Our guide was so knowledgeable and passionate. We will definitely be back with Twina Safaris!"
                        </p>
                        <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white font-black text-lg shadow-md">SM</div>
                            <div>
                                <h4 class="font-black text-safari-dark text-sm">Sarah Mitchell</h4>
                                <p class="text-gold-600 text-xs font-medium">Cultural Explorer</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- ========== LATEST BLOG SECTION (HORIZONTAL CAROUSEL) ========== --}}
@if(isset($latestPosts) && $latestPosts->count() > 0)
<section id="blog" class="py-24 bg-gray-50 border-t border-gray-100" x-data="{
    scrollBy(distance) {
        const slider = document.getElementById('blog-slider');
        if (slider) {
            slider.scrollBy({ left: distance, behavior: 'smooth' });
        }
    }
}">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <span class="text-gold-600 text-sm font-semibold uppercase tracking-widest">Safari Journal</span>
            <h2 class="font-display text-4xl md:text-5xl text-gray-900 mt-3 mb-4">Latest From The Blog</h2>
            <p class="text-gray-600 text-lg mb-8 max-w-4xl mx-auto leading-relaxed font-light">
                Discover inspiring safari stories, unforgettable travel experiences, breathtaking wildlife encounters, and hidden gems from across Tanzania and East Africa. Stay updated with expert travel tips, real guest adventures, and destination highlights that bring the spirit of Africa to life.
                <br><br>
                From the vast plains of the <strong>Serengeti to the peaks of Mount Kilimanjaro and the beaches of Zanzibar</strong>, our blog shares real moments, practical guides, and stories that spark your next journey.
            </p>
            <div class="section-divider mx-auto mb-8"></div>

            <div class="flex justify-center gap-3">
                <button @click="scrollBy(-380)" class="w-12 h-12 rounded-full bg-white shadow-lg border border-gray-200 flex items-center justify-center text-gray-700 hover:bg-gold-500 hover:text-white hover:border-gold-500 transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button @click="scrollBy(380)" class="w-12 h-12 rounded-full bg-white shadow-lg border border-gray-200 flex items-center justify-center text-gray-700 hover:bg-gold-500 hover:text-white hover:border-gold-500 transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
        <div class="relative group">
            <div id="blog-slider" class="flex gap-6 overflow-x-auto pb-6 snap-x snap-mandatory no-scrollbar scroll-smooth">
                @foreach($latestPosts as $post)
                <div class="snap-start shrink-0 w-full sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)]">
                    <div class="bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all group h-full">
                        <div class="relative h-64 overflow-hidden">
                            <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-all duration-500">
                        </div>
                        <div class="p-8 flex-grow flex flex-col">
                            <h3 class="font-display text-2xl font-bold text-gray-900 mb-4 group-hover:text-gold-600 transition-colors">
                                <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                            </h3>
                            <p class="text-gray-600 text-sm line-clamp-3 leading-relaxed mb-6">{{ $post->excerpt }}</p>

                            <div class="mt-auto">
                                <a href="{{ route('blog.show', $post->slug) }}" class="text-gold-600 font-bold text-sm flex items-center gap-2 hover:gap-3 transition-all">
                                    Read Story <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="text-center mt-16">
            <a href="{{ route('blog.index') }}" class="btn-gold px-8 py-3 rounded-full text-base font-semibold inline-flex items-center gap-2 group">
                View All Blog
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>
@endif

{{-- ========== HOME FOOTER BANNER (JOINED WITH FOOTER) ========== --}}
<section class="relative h-[60vh] min-h-[400px] flex items-center overflow-hidden bg-safari-dark">
    <img src="{{ \App\Helpers\AssetHelper::getBannerUrl('home_footer_banner') }}"
         class="absolute inset-0 w-full h-full object-cover opacity-60" alt="Twina Safaris Africa">

    {{-- Gradient that blends into the dark footer --}}
    <div class="absolute inset-0 bg-gradient-to-t from-safari-dark via-transparent to-transparent"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 text-center">
        <h2 class="font-display text-5xl md:text-7xl text-white font-black mb-8 leading-tight drop-shadow-2xl">
            Start Your <span class="text-gold-400 italic">Legacy</span> Today
        </h2>
        <p class="text-xl text-gray-200 mb-12 max-w-2xl mx-auto font-light leading-relaxed">
            From the heart of the Serengeti to the peaks of Kilimanjaro, your African masterpiece is waiting to be written.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
            <a href="{{ route('tours.index') }}" class="btn-gold px-12 py-5 rounded-full text-lg font-black shadow-2xl transition-all hover:scale-105">
                Explore All Tours
            </a>
            <a href="{{ route('trip-plan.index') }}" class="bg-white/10 backdrop-blur-md border border-white/20 text-white hover:bg-white hover:text-safari-dark px-12 py-5 rounded-full text-lg font-black transition-all">
                Plan Custom Trip
            </a>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Season Indicator
    function initSeasonIndicator() {
        const containers = [
            document.getElementById('green-container'),
            document.getElementById('yellow-container'),
            document.getElementById('red-container')
        ];
        const lights = [
            document.getElementById('green-light'),
            document.getElementById('yellow-light'),
            document.getElementById('red-light')
        ];
        const classes = ['blink-green', 'blink-yellow', 'blink-red'];
        const month = new Date().getMonth() + 1;
        let activeIdx = [6,7,8,9,10].includes(month) ? 0 : ([1,2,11,12].includes(month) ? 1 : 2);

        containers.forEach((c, i) => {
            if(c) c.style.display = (i === activeIdx) ? 'block' : 'none';
        });
        if(lights[activeIdx]) lights[activeIdx].classList.add(classes[activeIdx]);
    }
    initSeasonIndicator();
});
</script>
@endsection
