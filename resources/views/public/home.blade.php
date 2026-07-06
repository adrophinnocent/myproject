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

{{-- ========== HERO SECTION (FIXED & FUNCTIONAL) ========== --}}
<section class="relative h-screen bg-safari-dark overflow-hidden"
         x-data="{
            activeSlide: 0,
            slidesCount: {{ $sliders->count() > 0 ? $sliders->count() : 1 }},
            next() { this.activeSlide = (this.activeSlide + 1) % this.slidesCount }
         }"
         x-init="if(slidesCount > 1) setInterval(() => next(), 8000)">

    {{-- 1. BACKGROUND MEDIA (Visuals Only) --}}
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-black/40 z-10"></div>
        @if($sliders && $sliders->count() > 0)
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
                        <img src="{{ $slide->image_url }}" width="1920" height="1080" class="w-full h-full object-cover" alt="{{ $slide->title }}">
                    @endif
                </div>
            @endforeach
        @else
            <img src="{{ asset('images/banners/hero_fallback.webp') }}" width="1920" height="1080" class="w-full h-full object-cover opacity-60" alt="Tanzania Safari">
        @endif
    </div>

    {{-- 2. SEASON INDICATOR --}}
    <div class="absolute top-24 right-6 md:top-32 md:right-10 z-50">
        <div class="flex flex-col gap-4 items-center">
            <div class="season-light relative group cursor-help" id="green-container" style="display:none;">
                <div id="green-light" class="w-5 h-5 rounded-full bg-green-500 border-2 border-white/50 shadow-lg"></div>
            </div>
            <div class="season-light relative group cursor-help" id="yellow-container" style="display:none;">
                <div id="yellow-light" class="w-5 h-5 rounded-full bg-yellow-500 border-2 border-white/50 shadow-lg"></div>
            </div>
            <div class="season-light relative group cursor-help" id="red-container" style="display:none;">
                <div id="red-light" class="w-5 h-5 rounded-full bg-red-500 border-2 border-white/50 shadow-lg"></div>
            </div>
        </div>
    </div>

    {{-- 3. INTERACTIVE OVERLAY (The Fix) --}}
    <div class="relative z-40 h-full flex flex-col">

        {{-- A. MIDDLE CONTENT (Slider Text & The 3 Buttons) --}}
        <div class="flex-grow flex flex-col items-center justify-center text-center px-4 pt-20">
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
                                    <h1 class="font-display text-4xl md:text-8xl lg:text-9xl text-white font-black leading-[0.85] drop-shadow-2xl">
                                        {{ $slide->title }}
                                    </h1>
                                @endif
                            </div>

                            {{-- THE 3 BUTTONS (FULLY CLICKABLE) --}}
                            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 md:gap-6 relative z-50">
                                {{-- Main Explorer Button --}}
                                @if($slide->cta_text)
                                    <a href="{{ $slide->cta_url ?: '#' }}" class="btn-gold px-12 py-5 rounded-full text-base font-black shadow-2xl transition-all hover:scale-105 active:scale-95 min-w-[220px]">
                                        {{ $slide->cta_text }}
                                    </a>
                                @endif

                                {{-- Restored Button 1 --}}
                                <a href="{{ route('tours.index') }}" class="bg-white/10 backdrop-blur-md border border-white/20 text-white hover:bg-white hover:text-safari-dark px-10 py-4 rounded-full text-sm font-black transition-all min-w-[220px]">
                                    Plan Your Safari
                                </a>

                                {{-- Restored Button 2 --}}
                                <a href="{{ route('tours.index', ['tour_type' => 'kilimanjaro-trekking']) }}" class="bg-white/5 backdrop-blur-md border border-white/10 text-white hover:bg-gold-500 hover:text-safari-dark px-10 py-4 rounded-full text-sm font-black transition-all min-w-[220px]">
                                    Climb Kilimanjaro
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- B. BOTTOM CONTENT (Search Bar Extended Below) --}}
        <div class="w-full max-w-6xl mx-auto text-center pb-12 md:pb-20 px-4">

            {{-- Search Bar --}}
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

            {{-- Trust Strip --}}
            <div class="max-w-4xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-4 px-2">
                <div class="flex flex-col md:flex-row items-center justify-center gap-2 md:gap-3 md:border-r border-white/10 group">
                    <div class="text-gold-400 transition-transform group-hover:scale-110">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
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
                        <div class="text-white text-[9px] md:text-[11px] font-black uppercase tracking-widest leading-tight whitespace-nowrap">Safe & Secure</div>
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
                        <div class="text-white text-[9px] md:text-[11px] font-black uppercase tracking-widest leading-tight whitespace-nowrap">24/7 Support</div>
                        <div class="text-gray-400 text-[8px] md:text-[9px] font-bold">Expert Assistance</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========== FEATURED TOURS SECTION ========== --}}
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
            <p class="text-gray-600 text-lg mb-4 max-w-2xl mx-auto">Explore our most popular and highly recommended safari packages.</p>
            <div class="section-divider"></div>
        </div>

        <div class="relative group">
            <div id="tours-slider" class="flex gap-6 overflow-x-auto pb-6 snap-x snap-mandatory no-scrollbar scroll-smooth">
                @forelse($featuredTours as $tour)
                    <div class="snap-start shrink-0 w-full sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)]">
                        <div class="bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-md hover:shadow-xl transition-all h-full">
                            <div class="relative h-56">
                                <img src="{{ $tour->featured_image_url }}"
                                     width="400" height="224"
                                     alt="{{ \App\Helpers\AssetHelper::asString($tour->title) }}"
                                     class="w-full h-full object-cover"
                                     loading="lazy">
                            </div>
                            <div class="p-6 flex flex-col justify-between h-[calc(100%-14rem)]">
                                <div class="flex items-center gap-3 text-gray-600 text-xs mb-3 font-semibold">
                                    <span>{{ $tour->duration_text }}</span> • <span>{{ $tour->destination->name ?? 'Tanzania' }}</span>
                                </div>
                                <h3 class="font-display text-xl font-semibold text-gray-900 mb-1">
                                    <a href="{{ route('tours.show', ['type' => $tour->item_type, 'slug' => $tour->slug]) }}" class="hover:text-gold-600">{{ \App\Helpers\AssetHelper::asString($tour->title) }}</a>
                                </h3>
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
                        <h3 class="text-lg font-bold text-gray-500 uppercase tracking-widest">Tours Coming Soon</h3>
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
        <div id="testimonials-slider" class="flex gap-5 overflow-x-auto pb-8 px-4 snap-x snap-mandatory no-scrollbar scroll-smooth">
            @php
                $customTestimonials = [
                    ['name' => 'Sarah Mitchell', 'title' => 'Safari Traveler', 'content' => 'Absolutely life-changing experience! Our guide knew every animal\'s behavior and we saw all the Big Five in just three days.', 'initials' => 'SA'],
                    ['name' => 'Marco & Julia', 'title' => 'Honeymoon Couple', 'content' => 'Perfect honeymoon! Safari followed by Zanzibar beach time. The lodges were incredible and every detail was handled with care.', 'initials' => 'MA'],
                    ['name' => 'David Chen', 'title' => 'Mountain Climber', 'content' => 'Summiting Kilimanjaro was the toughest but most rewarding thing I\'ve ever done. The guides were supportive and highly professional.', 'initials' => 'DA'],
                    ['name' => 'Elena Rossi', 'title' => 'Beach Holiday', 'content' => 'Pure paradise! The beaches were stunning and the tours through Stone Town were fascinating. Twina Safaris made our stay effortless.', 'initials' => 'EL'],
                    ['name' => 'Kim & Family', 'title' => 'Family Adventure', 'content' => 'Family-friendly, professional, and so much fun! Our kids loved every minute, especially the wildlife sightings in the Serengeti.', 'initials' => 'KI']
                ];
            @endphp
            @foreach($customTestimonials as $t)
            <div class="snap-start shrink-0 w-full sm:w-[calc(50%-10px)] lg:w-[calc(33.333%-13px)] xl:w-[calc(25%-15px)]">
                <div class="bg-white rounded-3xl p-6 md:p-8 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 h-full flex flex-col">
                    <div class="flex text-gold-500 mb-4 gap-0.5">
                        @for($i=0; $i<5; $i++) <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> @endfor
                    </div>
                    <p class="text-gray-700 text-base leading-relaxed mb-6 flex-grow">{{ $t['content'] }}</p>
                    <div class="flex items-center gap-4 pt-4 border-t border-gray-100">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-gold-400 to-gold-600 flex items-center justify-center text-white font-black text-lg shadow-md">{{ $t['initials'] }}</div>
                        <div>
                            <h4 class="font-black text-safari-dark text-sm">{{ $t['name'] }}</h4>
                            <p class="text-gold-600 text-xs font-medium">{{ $t['title'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========== LATEST BLOG SECTION ========== --}}
@if(isset($latestPosts) && $latestPosts->count() > 0)
<section id="blog" class="py-24 bg-gray-50 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <span class="text-gold-600 text-sm font-semibold uppercase tracking-widest">Safari Journal</span>
            <h2 class="font-display text-4xl md:text-5xl text-gray-900 mt-3 mb-4">Latest From The Blog</h2>
            <p class="text-gray-600 text-lg mb-8 max-w-4xl mx-auto leading-relaxed">Inspiring safari stories and destination highlights.</p>
            <div class="section-divider mx-auto mb-8"></div>
        </div>
        <div class="relative group">
            <div id="blog-slider" class="flex gap-6 overflow-x-auto pb-6 snap-x snap-mandatory no-scrollbar scroll-smooth">
                @foreach($latestPosts as $post)
                <div class="snap-start shrink-0 w-full sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)]">
                    <div class="bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all h-full">
                        <div class="relative h-64 overflow-hidden">
                            <img src="{{ $post->featured_image_url }}" width="400" height="256" alt="{{ $post->title }}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-8">
                            <h3 class="font-display text-2xl font-bold text-gray-900 mb-4 group-hover:text-gold-600 transition-colors">
                                <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                            </h3>
                            <p class="text-gray-600 text-sm line-clamp-3 mb-6">{{ $post->excerpt }}</p>
                            <a href="{{ route('blog.show', $post->slug) }}" class="text-gold-600 font-bold text-sm flex items-center gap-2">Read Story →</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

{{-- ========== HOME FOOTER BANNER ========== --}}
<section class="relative h-[60vh] min-h-[400px] flex items-center overflow-hidden bg-safari-dark">
    <img src="{{ \App\Helpers\AssetHelper::getBannerUrl('home_footer_banner') }}" width="1920" height="800" class="absolute inset-0 w-full h-full object-cover opacity-60" alt="Twina Safaris Africa">
    <div class="absolute inset-0 bg-gradient-to-t from-safari-dark via-transparent to-transparent"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 text-center">
        <h2 class="font-display text-5xl md:text-7xl text-white font-black mb-8 leading-tight">Start Your <span class="text-gold-400 italic">Legacy</span> Today</h2>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
            <a href="{{ route('tours.index') }}" class="btn-gold px-12 py-5 rounded-full text-lg font-black transition-all hover:scale-105">Explore All Tours</a>
            <a href="{{ route('trip-plan.index') }}" class="bg-white/10 backdrop-blur-md border border-white/20 text-white hover:bg-white hover:text-safari-dark px-12 py-5 rounded-full text-lg font-black transition-all">Plan Custom Trip</a>
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
