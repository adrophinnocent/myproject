@extends('public.layouts.app')
@section('title', 'Safari Tour Packages - Tanzania & East Africa')
@section('meta_description', 'Browse our complete collection of Tanzania safari packages, Kilimanjaro climbs, Zanzibar beach holidays and East Africa tours.')

@section('content')
<div class="relative pt-32 pb-16 bg-safari-dark">
    <div class="absolute inset-0 z-0">
        <img src="{{ \App\Helpers\AssetHelper::getBannerUrl('safari_highlights') }}" class="w-full h-full object-cover opacity-20" alt="{{ __('Safari Packages') }}">
    </div>
    <div class="absolute inset-0 bg-gradient-to-br from-slate-800 via-amber-900/20 to-slate-800 opacity-60"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 text-center">
        <span class="text-gold-400 text-sm uppercase tracking-widest font-semibold">{{ __('Explore Africa') }}</span>
        <h1 class="font-display text-4xl md:text-6xl text-white font-bold mt-3">{{ __('Safari Tour Packages') }}</h1>
        <p class="text-gray-300 max-w-2xl mx-auto mt-4">{{ __('Extraordinary experiences awaiting you across East Africa') }}</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-16">
    <div class="flex flex-col lg:flex-row gap-10">

        <aside class="lg:w-72 flex-shrink-0">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-24">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-semibold text-gray-800">{{ __('Filter Tours') }}</h3>
                    <a href="{{ route('tours.index') }}" class="text-xs text-gold-500 hover:text-gold-600">{{ __('Clear All') }}</a>
                </div>
                <form method="GET" action="{{ route('tours.index') }}" id="filter-form">
                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">{{ __('Search') }}</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('Search tours...') }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-gold-500">
                    </div>
                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">{{ __('Tour Type') }}</label>
                        <div class="space-y-2">
                            @foreach($categories as $cat)
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="category" value="{{ $cat->id }}"
                                       {{ (request('category') == $cat->id || request('tour_type') == $cat->slug) ? 'checked' : '' }}
                                       onchange="this.form.submit()" class="text-gold-500 focus:ring-gold-500">
                                <span class="text-sm text-gray-600 group-hover:text-gold-600 transition-colors">{{ __($cat->name) }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">{{ __('Destination') }}</label>
                        <select name="destination" onchange="this.form.submit()"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-gold-500">
                            <option value="">{{ __('All Destinations') }}</option>
                            @foreach($destinations as $dest)
                            <option value="{{ $dest->id }}" {{ (request('destination') == $dest->id || request('tour_type') == $dest->slug) ? 'selected' : '' }}>
                                {{ $dest->translate('name') }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">{{ __('Duration') }}</label>
                        <select name="duration" onchange="this.form.submit()"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-gold-500">
                            <option value="">{{ __('Any Duration') }}</option>
                            @foreach(['1-3'=>'1-3 Days','4-7'=>'4-7 Days','8-14'=>'8-14 Days','15+'=>'15+ Days'] as $val=>$label)
                            <option value="{{ $val }}" {{ request('duration') === $val ? 'selected' : '' }}>{{ __($label) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">{{ __('Difficulty') }}</label>
                        <select name="difficulty" onchange="this.form.submit()"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-gold-500">
                            <option value="">{{ __('Any Level') }}</option>
                            <option value="easy" {{ request('difficulty')==='easy'?'selected':'' }}>{{ __('Easy') }}</option>
                            <option value="moderate" {{ request('difficulty')==='moderate'?'selected':'' }}>{{ __('Moderate') }}</option>
                            <option value="challenging" {{ request('difficulty')==='challenging'?'selected':'' }}>{{ __('Challenging') }}</option>
                        </select>
                    </div>
                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">{{ __('Sort By') }}</label>
                        <select name="sort" onchange="this.form.submit()"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-gold-500">
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

        <div class="flex-1">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($tours as $tour)
                    @php
                        $rawTitle = $tour->translate('title') ?: $tour->title;
                        $titleStr = \App\Helpers\AssetHelper::asString($rawTitle);

                        $rawDesc = $tour->translate('short_description') ?: $tour->short_description;
                        $descStr = \App\Helpers\AssetHelper::asString($rawDesc);
                    @endphp
                    <a href="{{ route('tours.show', ['type' => $tour->item_type ?? 'tour', 'slug' => $tour->slug ?? 'default']) }}"
                       class="group relative block w-full rounded-lg overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 h-64 bg-stone-900 border border-stone-800/50">

                        {{-- Background Cover Image with Zoom Effect --}}
                        <img src="{{ $tour->featured_image_url }}"
                             alt="{{ $titleStr }}"
                             class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out"
                             loading="lazy" decoding="async">

                        {{-- Dark Overlay Gradient for Readable Text --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/50 to-black/20 group-hover:from-black/90 transition-all pointer-events-none"></div>

                        {{-- Price Tag Badge at TOP RIGHT --}}
                        <div class="absolute top-2.5 right-2.5 z-10">
                            <span class="inline-block bg-[#f8b218] text-stone-950 font-black text-xs px-2.5 py-1 rounded shadow-md uppercase tracking-wider">
                                From ${{ number_format($tour->price) }} USD*
                            </span>
                        </div>

                        {{-- Centered Text Content (Title & Subtitle) --}}
                        <div class="absolute inset-x-0 bottom-0 top-0 p-4 pb-5 flex flex-col justify-end items-center text-center z-10 pointer-events-none">
                            <h3 class="font-black text-white text-sm md:text-base leading-tight uppercase tracking-tight group-hover:text-amber-300 transition-colors drop-shadow-md max-w-[90%]">
                                {{ $titleStr }}
                            </h3>
                            @if($descStr)
                            <p class="text-stone-200 text-xs font-normal leading-snug line-clamp-2 max-w-[92%] mt-1 drop-shadow-xs">
                                {{ $descStr }}
                            </p>
                            @endif
                        </div>

                        {{-- White Circle Arrow Button at Bottom Right --}}
                        <div class="absolute bottom-2.5 right-2.5 w-8 h-8 rounded-full bg-white text-stone-950 flex items-center justify-center shrink-0 shadow-lg group-hover:scale-110 group-hover:bg-[#f8b218] transition-all duration-300 z-10">
                            <svg class="w-4 h-4 text-stone-950" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center py-24 bg-gray-50 rounded-2xl">
                        <div class="text-6xl mb-4">🦁</div>
                        <h3 class="font-display text-xl text-gray-700 mb-2">{{ __('No Tours Found') }}</h3>
                        <p class="text-gray-400 text-sm mb-6">{{ __('Try adjusting your filters or search term.') }}</p>
                        <a href="{{ route('tours.index') }}" class="btn-gold px-6 py-3 rounded-full text-sm font-semibold">{{ __('Clear Filters') }}</a>
                    </div>
                @endforelse
            </div>

            @if($tours->hasPages())
            <div class="mt-10">
                {{ $tours->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
