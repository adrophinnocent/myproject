@extends('public.layouts.app')

@section('title', \App\Models\Setting::get('site_name', 'Twinasafaris') . ' - Experience Africa\'s Wild Heart')
@section('meta_description', \App\Models\Setting::get('meta_description', 'Award-winning safari experiences across Tanzania and East Africa. Your dream adventure begins here.'))

@section('content')

{{-- ========== HERO SECTION ========== --}}
<section class="relative min-h-screen flex items-center justify-center overflow-hidden">
    {{-- Hero Background --}}
    <div class="absolute inset-0 z-0">
        @if(\App\Models\Setting::get('hero_video'))
            <video autoplay muted loop playsinline class="w-full h-full object-cover">
                <source src="{{ asset('storage/' . \App\Models\Setting::get('hero_video')) }}" type="video/mp4">
            </video>
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900/80 via-amber-900/30 to-slate-900/80"></div>
        @else
            {{-- Fallback Slider --}}
            <div class="w-full h-full bg-gradient-to-br from-slate-900 via-amber-900/20 to-slate-900"></div>
        @endif
    </div>

    {{-- Decorative Glows --}}
    <div class="absolute top-1/3 left-10 w-64 h-64 bg-yellow-500/10 rounded-full blur-3xl z-0"></div>
    <div class="absolute bottom-1/3 right-10 w-48 h-48 bg-amber-700/10 rounded-full blur-3xl z-0"></div>

    {{-- Hero Content --}}
    <div class="relative z-10 text-center px-4 max-w-5xl mx-auto">
        <div class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-500/20 border border-yellow-500/30 rounded-full text-yellow-300 text-sm mb-8 backdrop-blur-sm">
            <span class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></span>
            Award-winning Tanzania Safari Company Since 2009
        </div>

        <h1 class="font-display text-5xl md:text-7xl lg:text-8xl text-white font-bold leading-tight mb-6">
            {{ \App\Models\Setting::get('hero_title', 'Experience Africa\'s') }}
            <span class="block text-yellow-400 italic">Wild Heart</span>
        </h1>

        <p class="text-gray-200 text-lg md:text-xl max-w-2xl mx-auto mb-10 leading-relaxed">
            {{ \App\Models\Setting::get('hero_subtitle', 'Award-winning safari experiences across Tanzania and East Africa. Your dream adventure begins here.') }}
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16">
            <a href="{{ route('tours.index') }}" class="px-8 py-4 bg-yellow-500 text-slate-900 rounded-full font-semibold flex items-center gap-2 group hover:bg-yellow-400 transition-colors">
                Explore Our Safaris
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            <a href="{{ route('contact.index') }}" class="px-8 py-4 border border-yellow-400 text-yellow-400 rounded-full font-semibold hover:bg-yellow-400 hover:text-slate-900 transition-colors">
                Plan Custom Tour
            </a>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-3xl mx-auto">
            @foreach([
                ['value' => '10000', 'suffix' => '+', 'label' => 'Happy Clients'],
                ['value' => '500', 'suffix' => '+', 'label' => 'Safari Tours'],
                ['value' => '15', 'suffix' => '+', 'label' => 'Years Experience'],
                ['value' => '50', 'suffix' => '+', 'label' => 'Countries Served'],
            ] as $stat)
            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-4">
                <div class="text-3xl font-display font-bold text-yellow-400">
                    {{ number_format((int)$stat['value']) }}{{ $stat['suffix'] }}
                </div>
                <div class="text-gray-300 text-xs mt-1">{{ $stat['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Scroll Indicator --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 text-white/50 flex flex-col items-center gap-2 animate-bounce">
        <span class="text-xs tracking-widest uppercase">Scroll</span>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </div>
</section>

{{-- ========== FEATURED TOURS ========== --}}
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <span class="text-yellow-500 text-sm font-semibold uppercase tracking-widest">Handpicked Experiences</span>
            <h2 class="font-display text-4xl md:text-5xl text-gray-900 mt-3 mb-4">Featured Safari Tours</h2>
            <div class="w-16 h-0.5 bg-yellow-500 mx-auto my-4"></div>
            <p class="text-gray-500 max-w-2xl mx-auto mt-4">Carefully crafted safari packages that deliver unforgettable wildlife encounters, cultural immersion, and breathtaking landscapes.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($featuredTours as $tour)
            <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all">
                <div class="relative h-56">
                    <img src="{{ $tour->featured_image_url }}" alt="{{ $tour->title }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    <div class="absolute top-4 left-4 flex gap-2">
                        @if($tour->is_featured)
                            <span class="px-3 py-1 bg-yellow-500 text-slate-900 text-xs font-semibold rounded-full">⭐ Featured</span>
                        @endif
                        @if($tour->category)
                            <span class="px-3 py-1 bg-slate-900/70 text-white text-xs rounded-full backdrop-blur-sm">{{ $tour->category->name }}</span>
                        @endif
                    </div>
                    <div class="absolute bottom-4 right-4">
                        <span class="px-4 py-2 bg-yellow-500 text-slate-900 text-sm font-bold rounded-full">
                            From {{ $tour->formatted_price }}
                        </span>
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex items-center gap-3 text-gray-400 text-xs mb-3">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $tour->duration_text }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $tour->group_size_min }}-{{ $tour->group_size_max }} Pax
                        </span>
                        @if($tour->destination)
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                {{ $tour->destination->name }}
                            </span>
                        @endif
                    </div>

                    <h3 class="font-display text-xl font-semibold text-gray-900 mb-3 leading-snug">
                        <a href="{{ route('tours.show', $tour) }}" class="hover:text-yellow-600 transition-colors">{{ $tour->title }}</a>
                    </h3>
                    <p class="text-gray-500 text-sm leading-relaxed mb-5 line-clamp-2">{{ $tour->short_description }}</p>

                    <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                        <div class="flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            @endfor
                            <span class="text-gray-400 text-xs ml-1">({{ $tour->bookings()->count() }}+ bookings)</span>
                        </div>
                        <a href="{{ route('booking.create', $tour) }}" class="bg-yellow-500 text-slate-900 px-5 py-2 rounded-full text-xs font-semibold hover:bg-yellow-400 transition-colors">Book Now</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('tours.index') }}" class="border border-yellow-500 text-yellow-600 px-8 py-3 rounded-full font-semibold inline-flex items-center gap-2 hover:bg-yellow-50 transition-colors">
                View All {{ $allPublishedTours }}+ Tours
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- ========== WHY CHOOSE US ========== --}}
<section class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div>
                <span class="text-yellow-500 text-sm font-semibold uppercase tracking-widest">Why Travel With Us</span>
                <h2 class="font-display text-4xl md:text-5xl text-gray-900 mt-3 mb-6">Africa's Most Trusted Safari Company</h2>
                <p class="text-gray-500 leading-relaxed mb-8">With over 15 years of crafting extraordinary safari experiences, Twinasafaris has earned the trust of travelers from 50+ countries. Our commitment to excellence, sustainability, and authentic wildlife encounters sets us apart.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    @foreach([
                        ['icon' => '🏆', 'title' => 'Award-Winning Service', 'desc' => 'TripAdvisor Certificate of Excellence for 8 consecutive years'],
                        ['icon' => '🌿', 'title' => 'Eco-Responsible', 'desc' => 'Carbon-offset safaris supporting local conservation programs'],
                        ['icon' => '👨‍💼', 'title' => 'Expert Local Guides', 'desc' => 'Certified TANAPA guides with 10+ years of field experience'],
                        ['icon' => '🔒', 'title' => 'Safe & Secure', 'desc' => 'Fully insured, TATO-licensed, and TANAPA-registered operator'],
                        ['icon' => '✈️', 'title' => 'Seamless Experience', 'desc' => 'Door-to-door service from airport transfers to final departure'],
                        ['icon' => '💰', 'title' => 'Best Price Guarantee', 'desc' => 'Competitive pricing with no hidden fees or surprises'],
                    ] as $item)
                    <div class="flex items-start gap-3">
                        <div class="w-12 h-12 bg-yellow-50 border border-yellow-200 rounded-xl flex items-center justify-center text-2xl flex-shrink-0">{{ $item['icon'] }}</div>
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-1 text-sm">{{ $item['title'] }}</h4>
                            <p class="text-gray-500 text-xs leading-relaxed">{{ $item['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="relative">
                <div class="grid grid-cols-2 gap-4">
                    <div class="rounded-2xl overflow-hidden h-64 bg-gradient-to-br from-amber-700 to-amber-900"></div>
                    <div class="rounded-2xl overflow-hidden h-64 mt-8 bg-gradient-to-br from-slate-700 to-slate-900"></div>
                    <div class="rounded-2xl overflow-hidden h-48 bg-gradient-to-br from-emerald-700 to-emerald-900"></div>
                    <div class="rounded-2xl overflow-hidden h-48 mt-4 bg-gradient-to-br from-cyan-700 to-cyan-900"></div>
                </div>
                <div class="absolute -bottom-4 -left-4 bg-white rounded-2xl shadow-xl p-5 border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-yellow-500 rounded-xl flex items-center justify-center text-slate-900 text-xl font-bold font-display">15+</div>
                        <div>
                            <div class="text-gray-800 font-semibold text-sm">Years of Excellence</div>
                            <div class="text-gray-400 text-xs">Trusted by 10,000+ travelers</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========== POPULAR DESTINATIONS ========== --}}
<section class="py-24 bg-slate-900">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <span class="text-yellow-400 text-sm font-semibold uppercase tracking-widest">Explore East Africa</span>
            <h2 class="font-display text-4xl md:text-5xl text-white mt-3 mb-4">Popular Destinations</h2>
            <div class="w-16 h-0.5 bg-yellow-500 mx-auto my-4"></div>
            <p class="text-gray-400 max-w-xl mx-auto">From the endless plains of the Serengeti to the snow-capped peak of Kilimanjaro and the turquoise waters of Zanzibar.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($destinations as $destination)
            <a href="{{ route('tours.index', ['destination' => $destination->id]) }}" class="relative rounded-2xl overflow-hidden h-72 group block">
                <img src="{{ $destination->featured_image_url }}" alt="{{ $destination->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                <div class="absolute inset-0 flex flex-col justify-end p-6">
                    <div class="transform translate-y-2 group-hover:translate-y-0 transition-transform">
                        <h3 class="font-display text-xl text-white font-semibold">{{ $destination->name }}</h3>
                        <p class="text-yellow-400 text-sm">{{ $destination->country }}</p>
                        <p class="text-gray-300 text-xs mt-2 opacity-0 group-hover:opacity-100 transition-opacity leading-relaxed line-clamp-2">{{ $destination->description }}</p>
                        <span class="inline-block mt-3 px-4 py-1.5 bg-yellow-500 text-slate-900 text-xs font-semibold rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                            {{ $destination->tours()->count() }} Tours Available
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ========== TESTIMONIALS ========== --}}
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
            <span class="text-yellow-500 text-sm font-semibold uppercase tracking-widest">What Travelers Say</span>
            <h2 class="font-display text-4xl md:text-5xl text-gray-900 mt-3 mb-4">Guest Stories & Reviews</h2>
            <div class="w-16 h-0.5 bg-yellow-500 mx-auto my-4"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($testimonials as $testimonial)
            <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100 hover:shadow-lg transition-all relative">
                <div class="absolute top-6 right-6 text-6xl font-display text-yellow-100 leading-none">"</div>

                <div class="flex items-center gap-1 mb-4">
                    @for($i = 1; $i <= 5; $i++)
                    <svg class="w-4 h-4 {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    @endfor
                </div>

                <p class="text-gray-600 leading-relaxed mb-6 text-sm relative z-10">{{ $testimonial->content }}</p>

                <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                    <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center text-yellow-600 font-bold font-display">
                        {{ strtoupper(substr($testimonial->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-semibold text-gray-800 text-sm">{{ $testimonial->name }}</div>
                        <div class="text-gray-400 text-xs flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            {{ $testimonial->location }}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ========== CTA SECTION ========== --}}
<section class="py-24 bg-slate-900 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-amber-900/30 via-slate-900 to-slate-900"></div>
    <div class="relative z-10 max-w-4xl mx-auto px-4 text-center">
        <h2 class="font-display text-4xl md:text-6xl text-white font-bold mb-6">
            Ready for Your <span class="text-yellow-400 italic">African Adventure?</span>
        </h2>
        <p class="text-gray-300 text-lg mb-10 leading-relaxed">
            Let our safari experts create your perfect custom itinerary. From budget-friendly getaways to ultra-luxury expeditions — we craft experiences that last a lifetime.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('tours.index') }}" class="px-8 py-4 bg-yellow-500 text-slate-900 rounded-full text-base font-semibold hover:bg-yellow-400 transition-colors">Browse All Tours</a>
            <a href="{{ route('contact.index') }}" class="px-8 py-4 border border-yellow-400 text-yellow-400 rounded-full text-base font-semibold hover:bg-yellow-400 hover:text-slate-900 transition-colors">Get Custom Quote</a>
        </div>
        <p class="text-gray-400 text-sm mt-8">✓ Free consultation &nbsp; ✓ No booking fees &nbsp; ✓ Best price guarantee &nbsp; ✓ 24/7 support</p>
    </div>
</section>

@endsection
