@extends('public.layouts.app')
@section('title', $tour->meta_title ?? $tour->title)
@section('meta_description', $tour->meta_description ?? $tour->short_description)

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org/",
  "@@type": "Product",
  "name": "{{ $tour->title }}",
  "image": "{{ $tour->featured_image_url }}",
  "description": "{{ $tour->short_description }}",
  "brand": {
    "@@type": "Brand",
    "name": "{{ \App\Models\Setting::get('site_name', 'Twina Safaris') }}"
  },
  "offers": {
    "@@type": "Offer",
    "priceCurrency": "USD",
    "price": "{{ $tour->price }}",
    "availability": "https://schema.org/InStock"
  },
  "aggregateRating": {
    "@@type": "AggregateRating",
    "ratingValue": "{{ $tour->average_rating }}",
    "reviewCount": "{{ $tour->review_count }}"
  }
}
</script>
@endsection

@section('content')
<div class="relative h-[70vh] min-h-[500px]">
    <img src="{{ $tour->featured_image_url }}" alt="{{ $tour->getTranslation('title') }} Adventure in {{ $tour->destination->name ?? 'Tanzania' }}" class="w-full h-full object-cover">
    <div class="hero-overlay absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
    <div class="absolute inset-0 flex items-end">
        <div class="max-w-7xl mx-auto px-4 pb-16 w-full">
            <div class="flex flex-wrap gap-3 mb-6">
                @if($tour->category)
                <span class="px-4 py-1.5 bg-gold-500 text-safari-dark text-xs font-bold rounded-full uppercase tracking-widest shadow-lg">{{ $tour->category->name }}</span>
                @endif
                @if($tour->destination)
                <span class="px-4 py-1.5 bg-white/20 text-white text-xs font-bold rounded-full backdrop-blur-md border border-white/30">{{ $tour->destination->name }}</span>
                @endif
                <div class="px-4 py-1.5 bg-gold-500/10 text-gold-400 text-[10px] font-mono rounded-full backdrop-blur-md border border-gold-500/20 uppercase tracking-widest flex items-center gap-2">
                    <span class="opacity-50">Package:</span>
                    <span class="font-bold">{{ $tour->slug }}</span>
                </div>
            </div>
            <h1 class="font-display text-5xl md:text-7xl text-white font-bold max-w-4xl leading-tight drop-shadow-2xl">{{ $tour->getTranslation('title') }}</h1>
            <div class="flex items-center gap-6 mt-6 text-white/90">
                <div class="flex items-center gap-2">
                    <span class="text-gold-400 font-bold">
                        <svg class="w-4 h-4 inline-block mb-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        {{ $tour->average_rating }}
                    </span>
                    <span class="text-sm">({{ $tour->review_count }} Reviews)</span>
                </div>
                <div class="h-4 w-px bg-white/30"></div>
                <div class="text-sm font-medium">Safe & Certified Operator</div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-12">
    <div class="flex flex-col lg:flex-row gap-12">
        <div class="flex-1">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 bg-white rounded-3xl p-8 mb-12 shadow-sm border border-gray-100">
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 bg-gold-50 rounded-2xl flex items-center justify-center text-gold-600 text-2xl mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="font-bold text-gray-900 text-base">{{ $tour->duration_text }}</div>
                    <div class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Duration</div>
                </div>
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 text-2xl mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div class="font-bold text-gray-900 text-base">
                        {{ $tour->group_size_min ?: '1' }}-{{ $tour->group_size_max ?: '12' }}
                    </div>
                    <div class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Group Size</div>
                </div>
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center text-green-600 text-2xl mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div class="font-bold text-gray-900 text-base">{{ $tour->accommodation_type ?: 'Luxury' }}</div>
                    <div class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Accommodation</div>
                </div>
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600 text-2xl mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div class="font-bold text-gray-900 text-base">{{ $tour->meeting_point ?: ($tour->departure_location ?: 'JRO Airport') }}</div>
                    <div class="text-gray-400 text-xs font-semibold uppercase tracking-wider">Arrival Point</div>
                </div>
            </div>

            <div class="mb-10">
                <h2 class="font-display text-2xl font-semibold text-gray-900 mb-4">Overview</h2>
                <div class="prose prose-gray max-w-none text-gray-600 leading-relaxed">{!! $tour->getTranslation('description') !!}</div>
            </div>

            @if(!empty($tour->highlights) && is_array($tour->highlights))
            <div class="mb-10">
                <h2 class="font-display text-2xl font-semibold text-gray-900 mb-4">Tour Highlights</h2>
                <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($tour->highlights as $highlight)
                        @if($highlight)
                        <li class="flex items-center gap-3 text-gray-600">
                            <svg class="w-5 h-5 text-gold-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ $highlight }}</span>
                        </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            @endif

            @if(!empty($tour->images) && $tour->images->count() > 0)
            <div class="mb-10">
                <h2 class="font-display text-2xl font-semibold text-gray-900 mb-4">Tour Gallery</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($tour->images as $image)
                    <div class="img-zoom rounded-xl overflow-hidden h-40">
                        <img src="{{ $image->url }}" alt="{{ $image->alt_text ?? $tour->title }}" class="w-full h-full object-cover cursor-pointer" onclick="openLightbox('{{ $image->url }}')" loading="lazy">
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if($tour->video_url)
            <div class="mb-10">
                <h2 class="font-display text-2xl font-semibold text-gray-900 mb-4">Tour Video</h2>
                <div class="aspect-video bg-gray-900 rounded-2xl overflow-hidden border border-gray-200 shadow-xl relative group">
                    <iframe
                        id="tour-video-iframe"
                        width="100%"
                        height="100%"
                        src="{{ $tour->youtube_embed_url }}"
                        title="Tour Video"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen>
                    </iframe>

                    {{-- Backup Button if YouTube blocks local playback --}}
                    <div class="absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="{{ $tour->video_url }}" target="_blank" class="bg-red-600 text-white px-4 py-2 rounded-lg text-xs font-bold flex items-center gap-2 shadow-lg">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l6.393 4-6.393 4z"/></svg>
                            Open on YouTube
                        </a>
                    </div>
                </div>
            </div>
            @endif

            @if(!empty($tour->itinerary) && is_array($tour->itinerary) && count($tour->itinerary) > 0)
            {{-- Route Summary (Image Style) --}}
            <div class="mb-12 bg-gray-200/50 rounded-3xl p-8 md:p-12 border border-gray-200">
                <h2 class="font-display text-3xl font-black text-[#e64a19] mb-8 uppercase tracking-tight">Route summary</h2>
                <div class="space-y-4">
                    @foreach($tour->itinerary as $index => $day)
                    <div class="flex items-start gap-4">
                        <span class="text-[#e64a19] font-black text-xl leading-none">→</span>
                        <p class="text-gray-800 font-bold text-lg md:text-xl leading-tight">
                            <span class="text-gray-900">Day {{ is_numeric($index) ? $index : $loop->iteration }}:</span>
                            {{ $day['title'] ?? '' }}
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-10">
                <h2 class="font-display text-2xl font-semibold text-gray-900 mb-6">Detailed Daily Itinerary</h2>
                <div class="space-y-4">
                    @foreach($tour->itinerary as $index => $day)
                    <div class="relative pl-8">
                        <div class="absolute left-0 top-3 w-6 h-6 bg-gold-500 rounded-full flex items-center justify-center text-safari-dark text-xs font-bold">{{ is_numeric($index) ? $index : $loop->iteration }}</div>
                        @if(!$loop->last)
                        <div class="absolute left-3 top-9 bottom-0 w-0.5 bg-gray-200"></div>
                        @endif
                        <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm">
                            <h4 class="font-semibold text-gray-800 mb-2">Day {{ is_numeric($index) ? $index : $loop->iteration }}: {{ $day['title'] ?? '' }}</h4>
                            @if(!empty($day['description']))
                            <p class="text-gray-500 text-sm leading-relaxed mb-3">{{ $day['description'] ?? '' }}</p>
                            @endif
                            <div class="flex flex-wrap items-center gap-x-8 gap-y-3 mt-4">
                                @if(!empty($day['accommodation']))
                                <div class="flex items-center gap-2 text-gray-600 whitespace-nowrap min-w-fit">
                                    <svg class="w-4 h-4 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    <span class="text-sm"><span class="font-bold text-gray-900">Stay:</span> {{ is_array($day['accommodation'] ?? '') ? implode(', ', $day['accommodation']) : ($day['accommodation'] ?? '') }}</span>
                                </div>
                                @endif
                                @if(!empty($day['meals']))
                                <div class="flex items-center gap-2 text-gray-600 whitespace-nowrap min-w-fit">
                                    <svg class="w-4 h-4 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    <span class="text-sm"><span class="font-bold text-gray-900">Meals:</span> {{ is_array($day['meals'] ?? '') ? implode(', ', $day['meals']) : ($day['meals'] ?? '') }}</span>
                                </div>
                                @endif
                                @if(!empty($day['distance']))
                                <div class="flex items-center gap-2 text-gray-600 whitespace-nowrap min-w-fit">
                                    <svg class="w-4 h-4 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                    <span class="text-sm"><span class="font-bold text-gray-900">Distance:</span> {{ is_array($day['distance'] ?? '') ? implode(', ', $day['distance']) : ($day['distance'] ?? '') }}</span>
                                </div>
                                @endif
                                @if(!empty($day['hiking_time']))
                                <div class="flex items-center gap-2 text-gray-600 whitespace-nowrap min-w-fit">
                                    <svg class="w-4 h-4 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span class="text-sm"><span class="font-bold text-gray-900">Time:</span> {{ is_array($day['hiking_time'] ?? '') ? implode(', ', $day['hiking_time']) : ($day['hiking_time'] ?? '') }}</span>
                                </div>
                                @endif
                                @if(!empty($day['habitat']))
                                <div class="flex items-center gap-2 text-gray-600 whitespace-nowrap min-w-fit">
                                    <svg class="w-4 h-4 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z"/></svg>
                                    <span class="text-sm"><span class="font-bold text-gray-900">Habitat:</span> {{ is_array($day['habitat'] ?? '') ? implode(', ', $day['habitat']) : ($day['habitat'] ?? '') }}</span>
                                </div>
                                @endif
                                @if(!empty($day['elevation']))
                                <div class="flex items-center gap-2 text-gray-600 whitespace-nowrap min-w-fit">
                                    <svg class="w-4 h-4 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                    <span class="text-sm"><span class="font-bold text-gray-900">Elevation:</span> {{ is_array($day['elevation'] ?? '') ? implode(', ', $day['elevation']) : ($day['elevation'] ?? '') }}</span>
                                </div>
                                @endif
                                @if(!empty($day['activities']))
                                <div class="flex items-start gap-2 text-gray-600 w-full mt-1">
                                    <svg class="w-4 h-4 text-gold-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    <span class="text-sm"><span class="font-bold text-gray-900">Activities:</span> {{ is_array($day['activities']) ? implode(', ', $day['activities']) : $day['activities'] }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                @if(!empty($tour->inclusions) && is_array($tour->inclusions))
                <div class="bg-green-50 border border-green-200 rounded-2xl p-6">
                    <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2"><span class="text-green-500">✓</span> What's Included</h3>
                    <ul class="space-y-2">
                        @foreach($tour->inclusions as $item)
                        <li class="flex items-start gap-3 text-gray-600 text-sm">
                            <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @if(!empty($tour->exclusions) && is_array($tour->exclusions))
                <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
                    <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2"><span class="text-red-400">✗</span> Not Included</h3>
                    <ul class="space-y-2">
                        @foreach($tour->exclusions as $item)
                        <li class="flex items-start gap-3 text-gray-600 text-sm">
                            <svg class="w-4 h-4 text-red-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            {{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>

            @if(!empty($tour->what_to_bring) && is_array($tour->what_to_bring))
            <div class="mb-10">
                <h2 class="font-display text-2xl font-semibold text-gray-900 mb-4">What to Bring</h2>
                <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($tour->what_to_bring as $item)
                        @if($item)
                        <li class="flex items-center gap-3 text-gray-600">
                            <span class="w-2 h-2 bg-gold-500 rounded-full flex-shrink-0"></span>
                            <span>{{ $item }}</span>
                        </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            @endif

            @if(!empty($tour->good_to_know) && is_array($tour->good_to_know))
            <div class="mb-10">
                <h2 class="font-display text-2xl font-semibold text-gray-900 mb-4">Good to Know</h2>
                <ul class="space-y-3">
                    @foreach($tour->good_to_know as $item)
                        @if($item)
                        <li class="flex items-start gap-3 text-gray-600">
                            <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>{{ $item }}</span>
                        </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            @endif

            @if(!empty($tour->faqs) && is_array($tour->faqs))
            <div class="mb-10">
                <h2 class="font-display text-2xl font-semibold text-gray-900 mb-6">Frequently Asked Questions</h2>
                <div class="space-y-3">
                    @foreach($tour->faqs as $index => $faq)
                    <div x-data="{ open: false }" class="border border-gray-200 rounded-xl overflow-hidden">
                        <button @click="open = !open"
                                class="w-full text-left px-6 py-4 font-semibold text-gray-800 text-sm flex items-center justify-between hover:bg-gray-50 transition-colors">
                            {{ $faq['question'] ?? '' }}
                            <svg :class="open ? 'rotate-180' : ''" class="w-5 h-5 text-gold-500 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div x-show="open" x-collapse class="px-6 pb-4 text-gray-500 text-sm leading-relaxed">{{ $faq['answer'] ?? '' }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Multi-Location Pickup Map Section --}}
            <div class="mb-16 bg-gray-50 rounded-[2.5rem] p-8 md:p-12 border border-gray-100 shadow-sm" x-data="{
                activePoint: 0,
                points: {{ json_encode($tour->pickup_locations ?? [['name' => $tour->meeting_point ?? 'Arrival Point', 'lat' => $tour->latitude ?? '-3.4294', 'lng' => $tour->longitude ?? '37.0745']]) }},
                get currentMapUrl() {
                    let p = this.points[this.activePoint];
                    return `https://www.openstreetmap.org/export/embed.html?bbox=${parseFloat(p.lng)-0.005}%2C${parseFloat(p.lat)-0.005}%2C${parseFloat(p.lng)+0.005}%2C${parseFloat(p.lat)+0.005}&layer=mapnik&marker=${p.lat}%2C${p.lng}`;
                }
            }">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                    <div>
                        <h2 class="font-display text-3xl font-black text-safari-dark uppercase tracking-tight">Meeting Points</h2>
                        <p class="text-gray-500 text-sm mt-2 font-medium">We offer flexible arrival options. Explore our meeting points below; you can confirm your choice during booking.</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="(p, index) in points" :key="index">
                            <button @click="activePoint = index"
                                    :class="activePoint === index ? 'bg-safari-dark text-white shadow-lg scale-105' : 'bg-white text-gray-400 border-gray-200 hover:text-gold-600'"
                                    class="px-5 py-3 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all border outline-none flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full" :class="activePoint === index ? 'bg-gold-500' : 'bg-gray-200'"></span>
                                <span x-text="p.name"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <div class="bg-white p-2 rounded-[2rem] border border-gray-200 shadow-inner overflow-hidden h-[400px] relative">
                    <iframe :src="currentMapUrl" class="w-full h-full rounded-[1.8rem]" frameborder="0"></iframe>
                    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 bg-safari-dark/90 backdrop-blur-md px-6 py-3 rounded-full border border-white/10 shadow-2xl">
                        <p class="text-[10px] font-black text-white uppercase tracking-[0.2em] whitespace-nowrap">
                            Viewing Location: <span class="text-gold-400" x-text="points[activePoint].name"></span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Reviews Section --}}
            <div id="reviews" class="mb-10 pt-10 border-t border-gray-100">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="font-display text-3xl font-bold text-gray-900">Guest Reviews</h2>
                        <div class="flex items-center gap-2 mt-2">
                            <div class="flex text-gold-500">
                                @for($i=1; $i<=5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= round($tour->average_rating) ? 'fill-current' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                            <span class="font-bold text-gray-900">{{ $tour->average_rating }} / 5.0</span>
                            <span class="text-gray-500 text-sm">({{ $tour->review_count }} verified reviews)</span>
                        </div>
                    </div>
                    <button onclick="document.getElementById('review-form-container').classList.toggle('hidden')" class="btn-outline-gold px-6 py-3 rounded-full text-sm font-bold transition-all">
                        Write a Review
                    </button>
                </div>

                {{-- Review Form (Hidden by default) --}}
                <div id="review-form-container" class="hidden mb-12 bg-gray-50 rounded-3xl p-8 border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Share Your Experience</h3>
                    <form action="{{ route('reviews.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="{{ isset($tour->item_type) && $tour->item_type == 'safari' ? 'safari_id' : 'tour_id' }}" value="{{ $tour->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Full Name</label>
                                <input type="text" name="name" required class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-gold-500/20 outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Email Address</label>
                                <input type="email" name="email" required class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-gold-500/20 outline-none">
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Rating</label>
                            <div class="flex gap-2">
                                @for($i=1; $i<=5; $i++)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="rating" value="{{ $i }}" class="hidden peer" required>
                                        <svg class="w-8 h-8 text-gray-300 peer-checked:text-gold-500 hover:text-gold-400 transition-colors" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    </label>
                                @endfor
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Review Title</label>
                            <input type="text" name="title" placeholder="Summarize your experience" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-gold-500/20 outline-none">
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Your Review</label>
                            <textarea name="content" rows="5" required placeholder="What did you love most about this trip?" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-gold-500/20 outline-none"></textarea>
                        </div>

                        <button type="submit" class="w-full btn-gold py-4 rounded-xl font-bold shadow-lg">Submit Review</button>
                    </form>
                </div>

                {{-- Display Reviews --}}
                <div class="space-y-6">
                    @forelse($tour->reviews as $review)
                        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-gold-100 rounded-full flex items-center justify-center font-bold text-gold-700">
                                        {{ substr($review->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900">{{ $review->name }}</h4>
                                        <p class="text-xs text-gray-400">{{ $review->country ?? 'Verified Traveler' }} · {{ $review->created_at->format('M Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex text-gold-500">
                                    @for($i=1; $i<=5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'fill-current' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                            </div>
                            <h5 class="font-bold text-gray-800 mb-2">{{ $review->title }}</h5>
                            <p class="text-gray-600 text-sm leading-relaxed">{{ $review->content }}</p>
                        </div>
                    @empty
                        <div class="text-center py-10 bg-gray-50 rounded-3xl border border-dashed border-gray-200">
                            <p class="text-gray-500">No reviews yet. Be the first to share your experience!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="lg:w-80 flex-shrink-0">
            <div class="sticky top-24 space-y-4">
                <div class="bg-safari-dark text-white rounded-2xl p-6 shadow-xl">
                    <div class="text-center mb-6">
                        <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Starting from</div>
                        <div class="font-display text-4xl font-bold text-gold-400">{{ $tour->formatted_price }}</div>
                        <div class="text-gray-400 text-sm">{{ $tour->price_note ?? 'per person (sharing)' }}</div>
                    </div>
                    <a href="{{ route('booking.create', $tour->slug) }}" class="btn-gold w-full block text-center py-4 rounded-xl font-bold text-base mb-4">
                        Book This Tour
                    </a>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('site_whatsapp', '255795482197')) }}?text=I'm interested in: {{ urlencode($tour->title) }}"
                       target="_blank"
                       class="w-full block text-center py-3 rounded-xl bg-green-500 hover:bg-green-600 text-white font-semibold text-sm transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        WhatsApp Enquiry
                    </a>
                    <div class="mt-4 pt-4 border-t border-white/10 space-y-3 text-sm">
                        <div class="flex items-center gap-3 text-gray-300">
                            <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            <span>Free cancellation (30 days)</span>
                        </div>
                        <div class="flex items-center gap-3 text-gray-300">
                            <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span>24/7 Support</span>
                        </div>
                        <div class="flex items-center gap-3 text-gray-300">
                            <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.347 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Best price guarantee</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 p-6">
                    <h4 class="font-semibold text-gray-800 mb-4 uppercase tracking-wider text-xs">Tour Details</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm border-b border-gray-50 pb-2"><span class="text-gray-400">Duration</span><span class="font-bold text-gray-700">{{ $tour->duration_text }}</span></div>
                        <div class="flex justify-between text-sm border-b border-gray-50 pb-2"><span class="text-gray-400">Group Size</span><span class="font-bold text-gray-700">{{ $tour->group_size_min ? $tour->group_size_min : '1' }}-{{ $tour->group_size_max ? $tour->group_size_max : '12' }} people</span></div>
                        @if($tour->difficulty_level)
                        <div class="flex justify-between text-sm border-b border-gray-50 pb-2"><span class="text-gray-400">Difficulty</span><span class="font-bold text-gray-700 capitalize">{{ $tour->difficulty_level }}</span></div>
                        @endif
                        @if($tour->accommodation_type)
                        <div class="flex justify-between text-sm border-b border-gray-50 pb-2"><span class="text-gray-400">Accommodation</span><span class="font-bold text-gray-700">{{ $tour->accommodation_type }}</span></div>
                        @endif
                        @if($tour->departure_location)
                        <div class="flex justify-between text-sm border-b border-gray-50 pb-2"><span class="text-gray-400">Departure</span><span class="font-bold text-gray-700">{{ $tour->departure_location }}</span></div>
                        @endif
                        @if($tour->departure_time)
                        <div class="flex justify-between text-sm border-b border-gray-50 pb-2"><span class="text-gray-400">Departure Time</span><span class="font-bold text-gray-700">{{ $tour->departure_time }}</span></div>
                        @endif
                        @if($tour->min_age)
                        <div class="flex justify-between text-sm border-b border-gray-50 pb-2"><span class="text-gray-400">Min Age</span><span class="font-bold text-gray-700">{{ $tour->min_age }}+ years</span></div>
                        @endif
                        @if(!empty($tour->languages_offered) && is_array($tour->languages_offered))
                        <div class="flex justify-between text-sm border-b border-gray-50 pb-2"><span class="text-gray-400">Languages</span><span class="font-bold text-gray-700">{{ implode(', ', $tour->languages_offered) }}</span></div>
                        @endif
                    </div>

                    <div class="mt-6 space-y-2">
                        @if($tour->pickup_included)
                        <div class="flex items-center gap-2 text-xs font-bold text-green-600 bg-green-50 px-3 py-2 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Hotel Pickup Included
                        </div>
                        @endif
                        @if($tour->airport_pickup)
                        <div class="flex items-center gap-2 text-xs font-bold text-blue-600 bg-blue-50 px-3 py-2 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Airport Pickup Included
                        </div>
                        @endif
                    </div>
                </div>

                <div class="bg-gold-50 border border-gold-200 rounded-2xl p-5 mb-4">
                    <p class="text-sm text-gray-600 mb-3">Need help planning? Talk to our safari experts.</p>
                    <a href="tel:{{ \App\Models\Setting::get('site_phone', '255795482197') }}" class="text-gold-600 font-semibold text-sm flex items-center gap-2 mb-4">
                        {{ \App\Models\Setting::get('site_phone', '+255 795 482 197') }}
                    </a>
                </div>

                {{-- Tour Inquiry Card --}}
                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                    <h4 class="font-bold text-gray-900 mb-4">Ask about this Tour</h4>
                    <form action="{{ route('tours.inquiry', $tour->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="text" name="name" required placeholder="Your Name" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-gold-500">
                        <input type="email" name="email" required placeholder="Email Address" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-gold-500">
                        <textarea name="message" rows="3" required placeholder="What would you like to know?" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-gold-500 resize-none"></textarea>
                        <button type="submit" class="w-full bg-safari-dark hover:bg-black text-white font-bold py-3 rounded-xl transition-all text-sm">
                            Send Inquiry
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($relatedTours) && $relatedTours->count() > 0)
    <div class="mt-16">
        <h2 class="font-display text-3xl font-semibold text-gray-900 mb-8">You Might Also Like</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($relatedTours as $related)
            <div class="tour-card bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm">
                <div class="img-zoom h-44"><img src="{{ $related->featured_image_url }}" alt="{{ $related->title }}" class="w-full h-full object-cover" loading="lazy"></div>
                <div class="p-4">
                    <h4 class="font-semibold text-gray-800 text-sm mb-1 line-clamp-2"><a href="{{ route('tours.show', ['type' => $related->item_type ?? 'tour', 'slug' => $related->slug]) }}" class="hover:text-gold-600 transition-colors">{{ $related->title }}</a></h4>
                    <div class="flex items-center justify-between mt-3">
                        <span class="text-gold-500 font-bold text-sm">{{ $related->formatted_price }}</span>
                        <span class="text-gray-400 text-xs">{{ $related->duration_text }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<div id="lightbox" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center p-4" onclick="closeLightbox()">
    <img id="lightbox-img" src="" alt="" class="max-w-full max-h-full rounded-lg">
    <button class="absolute top-4 right-4 text-white text-3xl" onclick="closeLightbox()">×</button>
</div>
@endsection

@section('scripts')
<script>
function openLightbox(src) {
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox').classList.remove('hidden');
    document.getElementById('lightbox').classList.add('flex');
}
function closeLightbox() {
    document.getElementById('lightbox').classList.add('hidden');
    document.getElementById('lightbox').classList.remove('flex');
}
</script>
@endsection
