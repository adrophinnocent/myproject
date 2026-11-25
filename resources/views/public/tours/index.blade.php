@extends('public.layouts.app')
@section('title', 'Tanzania Safari Packages 2026 | Prices, Routes & Itineraries | Twina Safaris')
@section('meta_description', 'Browse Tanzania safari packages for Serengeti, Ngorongoro and Tarangire. Compare listed prices, day-by-day routes, inclusions and guide support.')

@php
    $packageFaqs = [
        [
            'question' => 'How many days do I need for a Tanzania safari?',
            'answer' => 'Three days works for a short Serengeti and Ngorongoro trip, five days gives a more relaxed northern circuit, and seven or more days allows a slower pace with more parks.',
        ],
        [
            'question' => 'Are park fees included in Tanzania safari packages?',
            'answer' => 'It depends on the itinerary. Check the inclusion list on each tour page and ask for current park or conservation fee information before comparing prices.',
        ],
        [
            'question' => 'Can I combine a safari with Kilimanjaro or Zanzibar?',
            'answer' => 'Yes. Many travellers trek Kilimanjaro first, go on safari, then finish on Zanzibar. Ask for flight days and rest days to be built into the itinerary.',
        ],
        [
            'question' => 'What is usually included in a safari package?',
            'answer' => 'Most packages include a guided vehicle, driver-guide, accommodation and meals as listed. Flights, visas, insurance, optional activities and personal costs are commonly excluded.',
        ],
        [
            'question' => 'Can I customise a published itinerary?',
            'answer' => 'Yes. Use the custom safari request form to change parks, accommodation, dates, group size and pace.',
        ],
    ];

    $packageFaqSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => collect($packageFaqs)->map(fn (array $faq) => [
            '@type' => 'Question',
            'name' => $faq['question'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer']],
        ])->values()->all(),
    ];
@endphp

@section('schema')
<script type="application/ld+json">@json($packageFaqSchema)</script>
@endsection

@section('content')
<div class="relative pt-32 pb-16 bg-safari-dark">
    <div class="absolute inset-0 z-0">
        <img src="{{ \App\Helpers\AssetHelper::getBannerUrl('safari_highlights') }}" class="w-full h-full object-cover opacity-20" alt="{{ __('Safari Packages') }}">
    </div>
    <div class="absolute inset-0 bg-gradient-to-br from-slate-800 via-amber-900/20 to-slate-800 opacity-60"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 text-center">
        <span class="text-gold-400 text-sm uppercase tracking-widest font-semibold">{{ __('Explore Africa') }}</span>
        <h1 class="font-display text-4xl md:text-6xl text-white font-bold mt-3">{{ __('Tanzania Safari Packages') }}</h1>
        <p class="text-gray-300 max-w-2xl mx-auto mt-4">
            {{ __('Compare routes, durations and inclusions for Serengeti, Ngorongoro, Tarangire and Kilimanjaro journeys, then ask for a tailored itinerary.') }}
        </p>
    </div>
</div>

<section class="bg-white border-b border-stone-200/80 py-10">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-3">Choose your safari with the full picture</h2>
        <p class="text-stone-600 max-w-4xl leading-relaxed mb-6 text-sm md:text-base">
            A Tanzania safari package should show the route, number of days, accommodation style, vehicle, guide and exactly what is included in the price. Use the filters below to browse current itineraries, or start with the planning guides if you are still deciding when and where to go.
        </p>
        <div class="grid sm:grid-cols-3 gap-4 text-xs md:text-sm">
            <a href="{{ route('guides.safari-cost') }}" class="p-4 rounded-2xl border border-stone-200 bg-stone-50 hover:border-[#b8920d] transition-colors">
                <h3 class="font-bold text-stone-900 mb-1">Understand safari costs</h3>
                <p class="text-stone-600 text-xs">See what drives price by season, group size, park fees and accommodation.</p>
            </a>
            <a href="{{ route('guides.best-time') }}" class="p-4 rounded-2xl border border-stone-200 bg-stone-50 hover:border-[#b8920d] transition-colors">
                <h3 class="font-bold text-stone-900 mb-1">Pick the right season</h3>
                <p class="text-stone-600 text-xs">Compare dry season, calving season, migration timing and green-season value.</p>
            </a>
            <a href="{{ route('custom-safari.create') }}" class="p-4 rounded-2xl border border-stone-200 bg-stone-50 hover:border-[#b8920d] transition-colors">
                <h3 class="font-bold text-stone-900 mb-1">Build your own itinerary</h3>
                <p class="text-stone-600 text-xs">Tell us your dates, parks, pace and budget for a tailor-made plan.</p>
            </a>
        </div>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 py-12">
    {{-- 2-Column Main Layout: Left Sidebar + Right Tour Grid --}}
    <div class="flex flex-col lg:flex-row gap-8 items-start">

        {{-- Left Column: Filter Sidebar --}}
        <aside class="w-full lg:w-72 shrink-0">
            <div class="bg-white rounded-3xl border border-stone-200/90 shadow-sm p-6 sticky top-24">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="font-bold text-stone-900 text-base">{{ __('Filter Tours') }}</h3>
                    <a href="{{ route('tours.index') }}" class="text-xs font-bold text-amber-800 hover:underline">{{ __('Clear All') }}</a>
                </div>

                <form method="GET" action="{{ route('tours.index') }}" id="filter-form" class="space-y-5 text-xs">
                    <div>
                        <label class="block font-bold text-stone-500 uppercase tracking-wider mb-2">{{ __('Search') }}</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search tours...') }}"
                               class="w-full bg-stone-50 border border-stone-200 rounded-xl px-4 py-2.5 font-semibold text-stone-800 focus:outline-none focus:border-amber-500">
                    </div>

                    <div>
                        <label class="block font-bold text-stone-500 uppercase tracking-wider mb-2">{{ __('Tour Type') }}</label>
                        <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                            @foreach($categories as $cat)
                            <label class="flex items-center gap-2.5 cursor-pointer group">
                                <input type="radio" name="category" value="{{ $cat->id }}"
                                       {{ (request('category') == $cat->id || request('tour_type') == $cat->slug) ? 'checked' : '' }}
                                       onchange="this.form.submit()" class="text-amber-600 focus:ring-amber-500">
                                <span class="text-stone-700 font-medium group-hover:text-amber-800 transition-colors">{{ __($cat->name) }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-stone-500 uppercase tracking-wider mb-2">{{ __('Destination') }}</label>
                        <select name="destination" onchange="this.form.submit()"
                                class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3 py-2.5 font-semibold text-stone-800 focus:outline-none focus:border-amber-500">
                            <option value="">{{ __('All Destinations') }}</option>
                            @foreach($destinations as $dest)
                            <option value="{{ $dest->id }}" {{ (request('destination') == $dest->id || request('tour_type') == $dest->slug) ? 'selected' : '' }}>
                                {{ $dest->translate('name') }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-stone-500 uppercase tracking-wider mb-2">{{ __('Duration') }}</label>
                        <select name="duration" onchange="this.form.submit()"
                                class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3 py-2.5 font-semibold text-stone-800 focus:outline-none focus:border-amber-500">
                            <option value="">{{ __('Any Duration') }}</option>
                            @foreach(['1-3'=>'1-3 Days','4-7'=>'4-7 Days','8-14'=>'8-14 Days','15+'=>'15+ Days'] as $val=>$label)
                            <option value="{{ $val }}" {{ request('duration') === $val ? 'selected' : '' }}>{{ __($label) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-stone-500 uppercase tracking-wider mb-2">{{ __('Difficulty') }}</label>
                        <select name="difficulty" onchange="this.form.submit()"
                                class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3 py-2.5 font-semibold text-stone-800 focus:outline-none focus:border-amber-500">
                            <option value="">{{ __('Any Level') }}</option>
                            <option value="easy" {{ request('difficulty')==='easy'?'selected':'' }}>{{ __('Easy') }}</option>
                            <option value="moderate" {{ request('difficulty')==='moderate'?'selected':'' }}>{{ __('Moderate') }}</option>
                            <option value="challenging" {{ request('difficulty')==='challenging'?'selected':'' }}>{{ __('Challenging') }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-stone-500 uppercase tracking-wider mb-2">{{ __('Sort By') }}</label>
                        <select name="sort" onchange="this.form.submit()"
                                class="w-full bg-stone-50 border border-stone-200 rounded-xl px-3 py-2.5 font-semibold text-stone-800 focus:outline-none focus:border-amber-500">
                            <option value="featured" {{ request('sort','featured')==='featured'?'selected':'' }}>{{ __('Featured First') }}</option>
                            <option value="price_asc" {{ request('sort')==='price_asc'?'selected':'' }}>{{ __('Price: Low to High') }}</option>
                            <option value="price_desc" {{ request('sort')==='price_desc'?'selected':'' }}>{{ __('Price: High to Low') }}</option>
                            <option value="duration" {{ request('sort')==='duration'?'selected':'' }}>{{ __('Shortest First') }}</option>
                            <option value="newest" {{ request('sort')==='newest'?'selected':'' }}>{{ __('Newest') }}</option>
                        </select>
                    </div>
                </form>
            </div>
        </aside>

        {{-- Right Column: Tour Cards Grid (FULL 100% Remaining Width) --}}
        <main class="flex-1 w-full min-w-0">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse($tours as $tour)
                    @php
                        $rawTitle = $tour->translate('title') ?: $tour->title;
                        $titleStr = \App\Helpers\AssetHelper::asString($rawTitle);

                        $rawDesc = $tour->translate('short_description') ?: $tour->short_description;
                        $descStr = \App\Helpers\AssetHelper::asString($rawDesc);
                    @endphp

                    {{-- TourRadar Style Tour Card --}}
                    <div class="bg-white border border-[#e8dfcf] rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group hover:border-[#b8920d]/60">
                        <div>
                            {{-- Top Image Widescreen Banner --}}
                            <div class="relative h-52 overflow-hidden bg-stone-900">
                                <img src="{{ $tour->featured_image_url }}"
                                     alt="{{ $titleStr }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out"
                                     loading="lazy" decoding="async">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent pointer-events-none"></div>

                                {{-- Top Left Badge --}}
                                <div class="absolute top-3 left-3 z-10">
                                    <span class="px-2.5 py-1 bg-stone-900/80 backdrop-blur-md text-amber-400 font-bold text-[10px] uppercase rounded-lg border border-white/20">
                                        {{ $tour->duration_text }}
                                    </span>
                                </div>

                                {{-- Top Right Price Badge (NO ASTERISK *) --}}
                                <div class="absolute top-3 right-3 z-10">
                                    <span class="px-3 py-1 bg-[#d4af37] text-stone-950 font-black text-xs uppercase rounded-lg shadow-md tracking-wider">
                                        From {{ $tour->display_price_formatted }}
                                    </span>
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-5 space-y-3">
                                <div class="flex items-center gap-2 text-stone-500 text-[11px] font-semibold">
                                    <span>{{ $tour->destination?->name ?? 'Tanzania' }}</span>
                                    <span>•</span>
                                    <span class="text-amber-800 font-bold">★ 5.0 (Certified Guides)</span>
                                </div>

                                <h3 class="font-display font-black text-stone-900 text-lg leading-tight group-hover:text-amber-800 transition-colors line-clamp-2">
                                    <a href="{{ route('tours.show', ['type' => $tour->item_type ?? 'tour', 'slug' => $tour->slug ?? 'default']) }}">
                                        {{ $titleStr }}
                                    </a>
                                </h3>

                                @if($descStr)
                                <p class="text-stone-600 text-xs leading-relaxed line-clamp-2">
                                    {{ $descStr }}
                                </p>
                                @endif
                            </div>
                        </div>

                        {{-- Card Footer & CTA Button --}}
                        <div class="p-5 pt-0 border-t border-stone-100 flex items-center justify-between gap-2">
                            <div>
                                <span class="text-[10px] text-stone-400 font-bold uppercase block">Starting From</span>
                                <span class="text-stone-900 font-black text-base">{{ $tour->display_price_formatted }}</span>
                            </div>

                            <a href="{{ route('tours.show', ['type' => $tour->item_type ?? 'tour', 'slug' => $tour->slug ?? 'default']) }}"
                               class="btn-gold px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider shadow-sm flex items-center gap-1.5 group-hover:scale-105 transition-transform">
                                <span>VIEW TOUR</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-20 bg-stone-50 rounded-3xl border border-stone-200">
                        <div class="text-6xl mb-3">🦁</div>
                        <h3 class="font-display text-xl text-stone-800 font-bold mb-1">{{ __('No Tours Found') }}</h3>
                        <p class="text-stone-500 text-xs mb-5">{{ __('Try adjusting your filters or search term.') }}</p>
                        <a href="{{ route('tours.index') }}" class="btn-gold px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider">{{ __('Clear Filters') }}</a>
                    </div>
                @endforelse
            </div>

            @if($tours->hasPages())
            <div class="mt-10">
                {{ $tours->links() }}
            </div>
            @endif
        </main>
    </div>

    {{-- Safari Package Questions (FAQ) Section - FULL WIDTH STACKED BELOW FLEX ROW --}}
    <section class="mt-20 pt-12 border-t border-stone-200">
        <div class="text-center mb-8">
            <span class="text-amber-800 text-xs font-black uppercase tracking-widest block mb-1">TRAVELER FAQ</span>
            <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900">Safari package questions</h2>
            <div class="w-12 h-1 bg-amber-500 rounded-full mx-auto mt-2"></div>
        </div>

        <div class="grid sm:grid-cols-2 gap-4 max-w-5xl mx-auto">
            @foreach($packageFaqs as $faq)
            <details class="group bg-white border border-stone-200 rounded-2xl shadow-xs overflow-hidden">
                <summary class="cursor-pointer list-none p-5 font-bold text-stone-900 text-xs sm:text-sm flex items-start justify-between gap-3 bg-stone-50/50 group-open:bg-amber-50/30">
                    <span>{{ $faq['question'] }}</span>
                    <span class="text-amber-800 font-black text-lg leading-none shrink-0" aria-hidden="true">+</span>
                </summary>
                <div class="px-5 pb-5 text-stone-600 text-xs sm:text-sm leading-relaxed border-t border-stone-100 pt-3 bg-white">{{ $faq['answer'] }}</div>
            </details>
            @endforeach
        </div>
    </section>
</div>
@endsection
