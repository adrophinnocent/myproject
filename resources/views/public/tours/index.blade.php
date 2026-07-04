@extends('public.layouts.app')
@section('title', 'Safari Tour Packages - Tanzania & East Africa')
@section('meta_description', 'Browse our complete collection of Tanzania safari packages, Kilimanjaro climbs, Zanzibar beach holidays and East Africa tours.')

@section('content')
<div class="relative pt-32 pb-16 bg-safari-dark">
    <div class="absolute inset-0 z-0">
        <img src="{{ \App\Helpers\AssetHelper::getBannerUrl('safari_highlights') }}" class="w-full h-full object-cover opacity-20" alt="Safari Packages">
    </div>
    <div class="absolute inset-0 bg-gradient-to-br from-slate-800 via-amber-900/20 to-slate-800 opacity-60"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 text-center">
        <span class="text-gold-400 text-sm uppercase tracking-widest font-semibold">Explore Africa</span>
        <h1 class="font-display text-4xl md:text-6xl text-white font-bold mt-3">Safari Tour Packages</h1>
        <p class="text-gray-300 max-w-2xl mx-auto mt-4">Extraordinary experiences awaiting you across East Africa</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-16">
    <div class="flex flex-col lg:flex-row gap-10">

        <aside class="lg:w-72 flex-shrink-0">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-24">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-semibold text-gray-800">Filter Tours</h3>
                    <a href="{{ route('tours.index') }}" class="text-xs text-gold-500 hover:text-gold-600">Clear All</a>
                </div>
                <form method="GET" action="{{ route('tours.index') }}" id="filter-form">
                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tours..."
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-gold-500">
                    </div>
                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Tour Type</label>
                        <div class="space-y-2">
                            @foreach($categories as $cat)
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="radio" name="category" value="{{ $cat->id }}"
                                       {{ (request('category') == $cat->id || request('tour_type') == $cat->slug) ? 'checked' : '' }}
                                       onchange="this.form.submit()" class="text-gold-500 focus:ring-gold-500">
                                <span class="text-sm text-gray-600 group-hover:text-gold-600 transition-colors">{{ $cat->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Destination</label>
                        <select name="destination" onchange="this.form.submit()"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-gold-500">
                            <option value="">All Destinations</option>
                            @foreach($destinations as $dest)
                            <option value="{{ $dest->id }}" {{ (request('destination') == $dest->id || request('tour_type') == $dest->slug) ? 'selected' : '' }}>
                                {{ $dest->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Duration</label>
                        <select name="duration" onchange="this.form.submit()"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-gold-500">
                            <option value="">Any Duration</option>
                            @foreach(['1-3'=>'1-3 Days','4-7'=>'4-7 Days','8-14'=>'8-14 Days','15+'=>'15+ Days'] as $val=>$label)
                            <option value="{{ $val }}" {{ request('duration') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Difficulty</label>
                        <select name="difficulty" onchange="this.form.submit()"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-gold-500">
                            <option value="">Any Level</option>
                            <option value="easy" {{ request('difficulty')==='easy'?'selected':'' }}>Easy</option>
                            <option value="moderate" {{ request('difficulty')==='moderate'?'selected':'' }}>Moderate</option>
                            <option value="challenging" {{ request('difficulty')==='challenging'?'selected':'' }}>Challenging</option>
                        </select>
                    </div>
                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Sort By</label>
                        <select name="sort" onchange="this.form.submit()"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:border-gold-500">
                            <option value="featured" {{ request('sort','featured')==='featured'?'selected':'' }}>Featured First</option>
                            <option value="price_asc" {{ request('sort')==='price_asc'?'selected':'' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort')==='price_desc'?'selected':'' }}>Price: High to Low</option>
                            <option value="duration" {{ request('sort')==='duration'?'selected':'' }}>Shortest First</option>
                            <option value="newest" {{ request('sort')==='newest'?'selected':'' }}>Newest</option>
                        </select>
                    </div>
                </form>
            </div>
        </aside>

        <div class="flex-1">
            <div class="mb-8">
            </div>

            @forelse($tours as $tour)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg transition-shadow mb-6 overflow-hidden flex flex-col md:flex-row">
                <div class="md:w-64 flex-shrink-0 img-zoom h-56 md:h-auto">
                    <img src="{{ $tour->featured_image_url }}" alt="{{ $tour->title }}" class="w-full h-full object-cover">
                </div>
                <div class="flex-1 p-6">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex flex-wrap gap-2">
                            @if($tour->category)<span class="px-3 py-1 bg-gold-50 text-gold-600 text-xs font-medium rounded-full">{{ $tour->category->name }}</span>@endif
                            @if($tour->destination)<span class="px-3 py-1 bg-blue-50 text-blue-600 text-xs rounded-full">{{ $tour->destination->name }}</span>@endif
                            <span class="px-3 py-1 bg-gray-100 text-gray-500 text-xs rounded-full capitalize">{{ $tour->difficulty_level }}</span>
                        </div>
                        @if($tour->is_featured)<span class="text-gold-500 text-sm">⭐ Featured</span>@endif
                    </div>
                    <h3 class="font-display text-xl font-semibold text-gray-900 mb-1">
                        <a href="{{ route('tours.show', ['type' => $tour->item_type, 'slug' => $tour->slug]) }}" class="hover:text-gold-600 transition-colors">{{ $tour->getTranslation('title') }}</a>
                    </h3>
                    <div class="text-[10px] font-mono text-gray-400 mb-3 tracking-tighter">{{ $tour->slug }}.html</div>
                    <p class="text-gray-500 text-sm leading-relaxed mb-4 line-clamp-2">{{ $tour->getTranslation('short_description') }}</p>
                    <div class="flex flex-wrap gap-4 text-sm text-gray-400 mb-4">
                        <span class="flex items-center gap-1.5">{{ $tour->duration_text }}</span>
                        <span class="flex items-center gap-1.5">{{ $tour->group_size_min ?? '1' }}-{{ $tour->group_size_max ?? '12' }} People</span>
                        @if($tour->departure_location)<span class="flex items-center gap-1.5">{{ $tour->departure_location }}</span>@endif
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <div>
                            <div class="text-2xl font-display font-bold text-gold-600">{{ $tour->formatted_price }}</div>
                            <div class="text-xs text-gray-400">{{ $tour->price_note ?? 'per person' }}</div>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('tours.show', ['type' => $tour->item_type, 'slug' => $tour->slug]) }}" class="btn-outline-gold px-5 py-2.5 rounded-full text-sm font-semibold">View Details</a>
                            <a href="{{ route('booking.create', $tour) }}" class="btn-gold px-5 py-2.5 rounded-full text-sm font-semibold">Book Now</a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-24 bg-gray-50 rounded-2xl">
                <div class="text-6xl mb-4">🦁</div>
                <h3 class="font-display text-xl text-gray-700 mb-2">No Tours Found</h3>
                <p class="text-gray-400 text-sm mb-6">Try adjusting your filters or search term.</p>
                <a href="{{ route('tours.index') }}" class="btn-gold px-6 py-3 rounded-full text-sm font-semibold">Clear Filters</a>
            </div>
            @endforelse

            @if($tours->hasPages())
            <div class="mt-10">
                {{ $tours->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
