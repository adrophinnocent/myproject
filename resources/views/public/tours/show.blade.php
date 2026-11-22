@extends('public.layouts.app')
@section('title', $tour->meta_title ?? $tour->title)
@section('meta_description', $tour->meta_description ?? $tour->short_description)

@section('schema')
<script type="application/ld+json">
{
  "@@context": "https://schema.org/",
  "@@type": "Product",
  "name": "{{ $tour->translate('title') }}",
  "description": "{{ $tour->translate('short_description') }}",
  "image": "{{ $tour->featured_image_url }}",
  "brand": {
    "@@type": "Brand",
    "name": "Twina Safaris"
  },
  "offers": {
    "@@type": "Offer",
    "priceCurrency": "USD",
    "price": "{{ $tour->price }}",
    "availability": "https://schema.org/InStock",
    "url": "{{ str_replace('://www.', '://', url()->current()) . (str_ends_with(url()->current(), '.html') ? '' : '.html') }}",
    "priceValidUntil": "{{ now()->addMonths(6)->format('Y-m-d') }}"
  },
  "aggregateRating": {
    "@@type": "AggregateRating",
    "ratingValue": "{{ $tour->average_rating }}",
    "reviewCount": "{{ $tour->review_count ?: 1 }}"
  },
  "mainEntity": {
    "@@type": "TouristTrip",
    "name": "{{ $tour->translate('title') }}",
    "description": "{{ $tour->translate('description') }}",
    "touristType": "Wildlife & Adventure",
    "duration": "{{ $tour->duration_text }}",
    "itinerary": [
      @if(is_array($tour->itinerary))
          @foreach($tour->itinerary as $index => $day)
          {
            "@@type": "City",
            "name": "{{ $day['title'] ?? '' }}",
            "description": "{{ $day['description'] ?? '' }}"
          }{{ !$loop->last ? ',' : '' }}
          @endforeach
      @endif
    ]
  }
}
</script>

@if(!empty($faqs) && is_array($faqs))
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "FAQPage",
  "mainEntity": [
    @foreach($faqs as $index => $faq)
    {
      "@@type": "Question",
      "name": "{{ $faq['question'] ?? '' }}",
      "acceptedAnswer": {
        "@@type": "Answer",
        "text": "{{ strip_tags($faq['answer'] ?? '') }}"
      }
    }{{ !$loop->last ? ',' : '' }}
    @endforeach
  ]
}
</script>
@endif
@endsection

@section('content')
{{-- TourRadar Style Light Header & Top Photo Gallery --}}
<div class="bg-stone-50 border-b border-stone-200/80 pt-28 md:pt-32 pb-8">
    <div class="max-w-7xl mx-auto px-4">
        {{-- Breadcrumbs --}}
        <nav class="flex items-center gap-2 text-xs text-stone-500 mb-4 font-medium overflow-x-auto whitespace-nowrap">
            <a href="{{ route('home') }}" class="hover:text-gold-600 transition-colors">{{ __('Home') }}</a>
            <span>›</span>
            <a href="{{ route('tours.index') }}" class="hover:text-gold-600 transition-colors">{{ __('Tours') }}</a>
            @if($tour->category)
                <span>›</span>
                <a href="{{ route('tours.index', ['category' => $tour->category->slug]) }}" class="hover:text-gold-600 transition-colors">{{ $tour->category->name }}</a>
            @endif
            <span>›</span>
            <span class="text-stone-800 font-bold truncate max-w-xs">{{ $tour->getTranslation('title') }}</span>
        </nav>

        {{-- Tour Title --}}
        <h1 class="font-display text-3xl md:text-5xl font-bold text-stone-900 leading-tight mb-4">
            {{ $tour->getTranslation('title') }}
        </h1>

        {{-- Meta Badges & Info Bar (Matching Screenshot) --}}
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6 text-xs md:text-sm font-medium text-stone-700">
            <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                @if($tour->category)
                    <span class="px-3 py-1 bg-stone-200/80 text-stone-800 font-bold rounded-md text-xs">
                        {{ $tour->category->name }}
                    </span>
                @endif

                {{-- Rating --}}
                <div class="flex items-center gap-1 font-bold text-stone-900">
                    <span class="text-amber-500">★ {{ number_format($tour->average_rating, 1) }}</span>
                    <span class="text-stone-500 font-normal">({{ $tour->review_count }} {{ __('reviews') }})</span>
                </div>

                <span class="text-stone-300">•</span>

                {{-- Duration --}}
                <span class="font-semibold text-stone-800">{{ $tour->duration_text }}</span>

                <span class="text-stone-300">•</span>

                {{-- Route --}}
                <span class="text-stone-600">
                    {{ __('From') }} <strong class="text-stone-900">{{ $tour->departure_location ?: 'Arusha' }}</strong> {{ __('to') }} <strong class="text-stone-900">{{ $tour->meeting_point ?: ($tour->destination->name ?? 'Moshi, Tanzania') }}</strong>
                </span>
            </div>

            {{-- Price Tag & Action Buttons (Share, Save, Book Now) --}}
            <div class="flex items-center gap-3.5 shrink-0">
                <button onclick="navigator.share ? navigator.share({title: '{{ $tour->title }}', url: window.location.href}) : navigator.clipboard.writeText(window.location.href)" class="hidden sm:flex items-center gap-1.5 text-xs font-bold text-stone-700 hover:text-amber-600 transition-colors bg-white px-3.5 py-2.5 rounded-full border border-stone-200/80 shadow-xs cursor-pointer">
                    <svg class="w-4 h-4 text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 100-2.684 3 3 0 000 2.684zm0 9a3 3 0 100-2.684 3 3 0 000 2.684z"/></svg>
                    <span>{{ __('Share') }}</span>
                </button>
                <button x-data="{ saved: false }" @click="saved = !saved" class="hidden sm:flex items-center gap-1.5 text-xs font-bold text-stone-700 hover:text-rose-600 transition-colors bg-white px-3.5 py-2.5 rounded-full border border-stone-200/80 shadow-xs cursor-pointer">
                    <svg class="w-4 h-4" :class="saved ? 'fill-rose-500 text-rose-500' : 'text-stone-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    <span x-text="saved ? 'Saved' : 'Save'">{{ __('Save') }}</span>
                </button>
                <div class="text-right ml-1">
                    <span class="text-2xl md:text-3xl font-black text-amber-600 leading-none">${{ number_format($tour->price) }}</span>
                    <span class="text-[10px] uppercase font-bold text-stone-400 tracking-wider block mt-0.5">{{ __('FROM USD') }}</span>
                </div>
                <a href="#booking-card" class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-stone-950 font-black px-7 py-3 rounded-2xl text-xs uppercase tracking-widest shadow-md transition-all hover:scale-105 inline-flex items-center gap-2">
                    {{ __('BOOK NOW') }}
                </a>
            </div>
        </div>

        {{-- TourRadar Style Top Photo Gallery Grid / Carousel --}}
        @php
            $topGallery = [];

            if (!empty($tour->featured_image)) {
                $topGallery[] = $tour->featured_image_url;
            }

            if (!empty($tour->images)) {
                foreach ($tour->images as $gImg) {
                    if (!empty($gImg->url) && !in_array($gImg->url, $topGallery)) {
                        $topGallery[] = $gImg->url;
                    }
                }
            }

            if (!empty($tour->itinerary) && is_array($tour->itinerary)) {
                foreach ($tour->itinerary as $d) {
                    $dayImgs = $d['images'] ?? [];
                    if (is_array($dayImgs)) {
                        foreach ($dayImgs as $dImg) {
                            $u = is_array($dImg) ? ($dImg['url'] ?? '') : $dImg;
                            if (!empty($u)) {
                                $fullU = str_starts_with($u, 'http') ? $u : asset(str_starts_with(ltrim($u, '/'), 'storage/') ? ltrim($u, '/') : 'storage/' . ltrim($u, '/'));
                                if (!in_array($fullU, $topGallery)) {
                                    $topGallery[] = $fullU;
                                }
                            }
                        }
                    }
                    $sImg = $d['image'] ?? $d['cover_image'] ?? null;
                    if (!empty($sImg) && !is_array($sImg)) {
                        $fullU = str_starts_with($sImg, 'http') ? $sImg : asset(str_starts_with(ltrim($sImg, '/'), 'storage/') ? ltrim($sImg, '/') : 'storage/' . ltrim($sImg, '/'));
                        if (!in_array($fullU, $topGallery)) {
                            $topGallery[] = $fullU;
                        }
                    }
                }
            }
        @endphp

        @if(!empty($topGallery))
            <div class="mt-4">
                {{-- Desktop Grid: Exact TourRadar 5-Slot Collage Layout (4 Photos + Review Quote Card) --}}
                @php
                    $gCount = count($topGallery);
                    $firstReview = $tour->reviews ? $tour->reviews->first() : null;
                    $revContent = $firstReview ? $firstReview->content : __('We saw many, many animals and were able to get so close to cheetahs and lions hunting it was like being in a documentary.');
                    $revName = $firstReview ? $firstReview->name : 'Sarah Mitchell';
                    $revCountry = $firstReview ? ($firstReview->country ?: __('Safari Traveler')) : __('Safari Traveler');
                    $revDate = $firstReview ? ($firstReview->trip_date ? $firstReview->trip_date->format('M Y') : $firstReview->created_at->format('M Y')) : 'Nov 2026';
                    $revRating = $firstReview ? $firstReview->rating : 5;
                    $revInitials = strtoupper(substr($revName, 0, 1));
                @endphp

                <div class="hidden md:grid md:grid-cols-3 md:grid-rows-2 md:gap-3 h-[420px] lg:h-[460px] rounded-3xl overflow-hidden border border-stone-200/80 bg-stone-100 p-1">
                    @if($gCount >= 4)
                        {{-- 1. Main Left Tall Big Photo (Col 1, Row 1-2) --}}
                        <div class="col-span-1 row-span-2 relative group cursor-pointer overflow-hidden rounded-2xl bg-stone-900" onclick="openLightbox('{{ $topGallery[0] }}')">
                            <img src="{{ $topGallery[0] }}" alt="{{ $tour->getTranslation('title') }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="eager">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                                <span class="text-white text-xs font-bold bg-black/60 px-3 py-1.5 rounded-lg backdrop-blur-sm">🔍 {{ __('Click to view photo') }}</span>
                            </div>
                        </div>

                        {{-- 2. Top-Middle Photo (Col 2, Row 1) --}}
                        <div class="col-span-1 row-span-1 relative group cursor-pointer overflow-hidden rounded-2xl bg-stone-900" onclick="openLightbox('{{ $topGallery[1] }}')">
                            <img src="{{ $topGallery[1] }}" alt="Photo 2" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
                        </div>

                        {{-- 3. Bottom-Middle Photo (Col 2, Row 2) --}}
                        <div class="col-span-1 row-span-1 relative group cursor-pointer overflow-hidden rounded-2xl bg-stone-900" onclick="openLightbox('{{ $topGallery[2] }}')">
                            <img src="{{ $topGallery[2] }}" alt="Photo 3" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
                        </div>

                        {{-- 4. Top-Right Photo (Col 3, Row 1) with View All Badge --}}
                        <div class="col-span-1 row-span-1 relative group cursor-pointer overflow-hidden rounded-2xl bg-stone-900" onclick="openLightbox('{{ $topGallery[3] }}')">
                            <img src="{{ $topGallery[3] }}" alt="Photo 4" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">

                            {{-- View All Badge Button --}}
                            <div class="absolute bottom-3 right-3 z-10">
                                <button type="button" onclick="event.stopPropagation(); openLightbox('{{ $topGallery[0] }}')" class="bg-stone-900/85 hover:bg-stone-950 text-white font-bold text-xs px-3.5 py-2 rounded-xl backdrop-blur-md shadow-xl border border-white/20 flex items-center gap-2 transition-all hover:scale-105">
                                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>{{ __('View all') }}</span>
                                    @if($gCount > 4)
                                        <span class="text-amber-400 text-[10px] font-black">({{ $gCount }})</span>
                                    @endif
                                </button>
                            </div>
                        </div>

                        {{-- 5. Bottom-Right Review Quote Box (Col 3, Row 2) --}}
                        <div class="col-span-1 row-span-1 bg-[#fffdfa] border border-[#f3e8d3] rounded-2xl p-4 flex flex-col justify-between shadow-xs relative overflow-hidden group">
                            <p class="text-stone-800 text-xs sm:text-sm font-medium leading-relaxed italic line-clamp-4">
                                "{{ $revContent }}"
                            </p>
                            <div class="flex items-center gap-3 pt-3 border-t border-[#f3e8d3] mt-2">
                                <div class="w-8 h-8 rounded-full bg-[#f4e8d3] text-[#8A6D0B] font-black text-xs flex items-center justify-center shrink-0">
                                    {{ $revInitials }}
                                </div>
                                <div class="truncate">
                                    <h5 class="font-bold text-stone-900 text-xs leading-tight truncate">{{ $revName }}</h5>
                                    <p class="text-[10px] text-stone-500 font-medium">
                                        {{ $revCountry }} • {{ $revDate }}
                                    </p>
                                </div>
                                <div class="ml-auto text-[#8A6D0B] text-xs font-bold shrink-0">
                                    ★ {{ number_format($revRating, 1) }}
                                </div>
                            </div>
                        </div>

                    @elseif($gCount >= 2)
                        {{-- Fallback for 2-3 photos --}}
                        <div class="col-span-2 row-span-2 relative group cursor-pointer overflow-hidden rounded-2xl bg-stone-900" onclick="openLightbox('{{ $topGallery[0] }}')">
                            <img src="{{ $topGallery[0] }}" alt="{{ $tour->getTranslation('title') }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="eager">
                        </div>
                        <div class="col-span-1 row-span-1 relative group cursor-pointer overflow-hidden rounded-2xl bg-stone-900" onclick="openLightbox('{{ $topGallery[1] }}')">
                            <img src="{{ $topGallery[1] }}" alt="Photo 2" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
                        </div>
                        <div class="col-span-1 row-span-1 bg-[#fffdfa] border border-[#f3e8d3] rounded-2xl p-4 flex flex-col justify-between shadow-xs">
                            <p class="text-stone-800 text-xs sm:text-sm font-medium leading-relaxed italic line-clamp-4">
                                "{{ $revContent }}"
                            </p>
                            <div class="flex items-center gap-3 pt-3 border-t border-[#f3e8d3] mt-2">
                                <div class="w-8 h-8 rounded-full bg-[#f4e8d3] text-[#8A6D0B] font-black text-xs flex items-center justify-center shrink-0">
                                    {{ $revInitials }}
                                </div>
                                <div class="truncate">
                                    <h5 class="font-bold text-stone-900 text-xs leading-tight truncate">{{ $revName }}</h5>
                                    <p class="text-[10px] text-stone-500 font-medium">{{ $revCountry }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- 1 Photo Fallback --}}
                        <div class="col-span-3 row-span-2 relative group cursor-pointer overflow-hidden rounded-2xl bg-stone-900" onclick="openLightbox('{{ $topGallery[0] }}')">
                            <img src="{{ $topGallery[0] }}" alt="{{ $tour->getTranslation('title') }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="eager">
                        </div>
                    @endif
                </div>

                {{-- Mobile Slider --}}
                <div class="md:hidden flex gap-3 overflow-x-auto snap-x snap-mandatory pb-2 no-scrollbar scroll-smooth">
                    @foreach($topGallery as $mIndex => $mUrl)
                        <div class="snap-start shrink-0 w-[85vw] h-64 rounded-2xl overflow-hidden bg-stone-900 border border-stone-200 shadow-md relative" onclick="openLightbox('{{ $mUrl }}')">
                            <img src="{{ $mUrl }}" alt="Photo {{ $mIndex + 1 }}" class="w-full h-full object-cover" loading="lazy">
                            <div class="absolute bottom-3 right-3 bg-black/70 text-white text-xs font-bold px-3 py-1 rounded-md backdrop-blur-sm">
                                {{ $mIndex + 1 }} / {{ $gCount }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-12">
    <div class="flex flex-col lg:flex-row gap-12">
        <div class="flex-1">
            {{-- TourRadar Style Quick Specs Grid (Replaces old 4-box layout) --}}
            <div class="bg-white rounded-3xl p-6 md:p-8 mb-10 shadow-sm border border-stone-200/80">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-6">
                    {{-- 1. Operator --}}
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-stone-100 border border-stone-200 flex items-center justify-center text-stone-800 shrink-0 mt-0.5 shadow-2xs">
                            <svg class="w-5 h-5 text-stone-800" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-stone-900 text-sm md:text-base leading-snug">
                                {{ __('Platinum Operator') }}
                            </h4>
                            <p class="text-stone-500 text-xs mt-0.5 leading-relaxed font-medium">
                                Twina Safaris • {{ __('World Adventure Tours') }}
                            </p>
                        </div>
                    </div>

                    {{-- 2. Safari / Category --}}
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-stone-100 border border-stone-200 flex items-center justify-center text-stone-800 shrink-0 mt-0.5 shadow-2xs">
                            <svg class="w-5 h-5 text-stone-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2 1m2-1l-2-1m2 1v2.5M4 7l2-1M4 7l2 1M4 7v2.5M12 21a9 9 0 100-18 9 9 0 000 18z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-stone-900 text-sm md:text-base leading-snug">
                                {{ $tour->category ? $tour->category->name : __('Safari') }}
                            </h4>
                            <p class="text-stone-500 text-xs mt-0.5 leading-relaxed font-medium">
                                {{ str_contains(strtolower($tour->category->name ?? ''), 'trek') ? __('Summit Africa\'s highest peaks with expert guides') : __('Track iconic wildlife on unforgettable game drives') }}
                            </p>
                        </div>
                    </div>

                    {{-- 3. Group Tour / Tour Type --}}
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-stone-100 border border-stone-200 flex items-center justify-center text-stone-800 shrink-0 mt-0.5 shadow-2xs">
                            <svg class="w-5 h-5 text-stone-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-stone-900 text-sm md:text-base leading-snug">
                                {{ $tour->tour_type ? ucfirst($tour->tour_type) . ' Tour' : __('Group Tour') }}
                            </h4>
                            <p class="text-stone-500 text-xs mt-0.5 leading-relaxed font-medium">
                                {{ $tour->tour_type == 'private' ? __('Private custom safari tailored for your group') : __('Join a group and forge lifelong friendships') }}
                            </p>
                        </div>
                    </div>

                    {{-- 4. Guiding --}}
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-stone-100 border border-stone-200 flex items-center justify-center text-stone-800 shrink-0 mt-0.5 shadow-2xs">
                            <svg class="w-5 h-5 text-stone-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-stone-900 text-sm md:text-base leading-snug">
                                {{ __('Fully Guided') }}
                            </h4>
                            <p class="text-stone-500 text-xs mt-0.5 leading-relaxed font-medium">
                                {{ $tour->assigned_guide ? __('Guided by') . ' ' . $tour->assigned_guide : __('An experienced guide will be with you for the entire tour') }}
                            </p>
                        </div>
                    </div>

                    {{-- 5. Physical Intensity / Difficulty --}}
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-stone-100 border border-stone-200 flex items-center justify-center text-stone-800 shrink-0 mt-0.5 shadow-2xs">
                            <svg class="w-5 h-5 text-stone-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-stone-900 text-sm md:text-base leading-snug">
                                {{ $tour->difficulty_level ? ucfirst($tour->difficulty_level) . ' Intensity' : __('Moderate Intensity') }}
                            </h4>
                            <p class="text-stone-500 text-xs mt-0.5 leading-relaxed font-medium">
                                {{ $tour->difficulty_level == 'hard' ? __('Travelers can expect strenuous physical activities and hiking') : ($tour->difficulty_level == 'low' ? __('Travelers can expect light physical activities') : __('Travelers can expect moderate physical activities')) }}
                            </p>
                        </div>
                    </div>

                    {{-- 6. Languages --}}
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-stone-100 border border-stone-200 flex items-center justify-center text-stone-800 shrink-0 mt-0.5 shadow-2xs">
                            <svg class="w-5 h-5 text-stone-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2h1.5a2.5 2.5 0 002.5-2.5V11.8M9 21a9 9 0 100-18 9 9 0 000 18z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-stone-900 text-sm md:text-base leading-snug">
                                {{ __('Guided in') }} {{ !empty($tour->languages_offered) && is_array($tour->languages_offered) ? (is_array($tour->languages_offered[0] ?? null) ? implode(', ', $tour->languages_offered[0]) : ($tour->languages_offered[0] ?? 'English')) : 'English' }}
                            </h4>
                            <p class="text-stone-500 text-xs mt-0.5 leading-relaxed font-medium">
                                @if(!empty($tour->languages_offered) && is_array($tour->languages_offered) && count($tour->languages_offered) > 1)
                                    {{ __('Other languages on request') }}: {{ implode(', ', array_filter(array_slice($tour->languages_offered, 1))) }}
                                @else
                                    {{ __('Other languages on request: German, French, Spanish') }}
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- 7. Group Size --}}
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-stone-100 border border-stone-200 flex items-center justify-center text-stone-800 shrink-0 mt-0.5 shadow-2xs">
                            <svg class="w-5 h-5 text-stone-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-stone-900 text-sm md:text-base leading-snug">
                                {{ __('Group Size') }} {{ $tour->group_size_min ?: '2' }} - {{ $tour->group_size_max ?: '6' }}
                            </h4>
                            <p class="text-stone-500 text-xs mt-0.5 leading-relaxed font-medium">
                                {{ __('Small group experience for maximum wildlife immersion') }}
                            </p>
                        </div>
                    </div>

                    {{-- 8. Age Range --}}
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-stone-100 border border-stone-200 flex items-center justify-center text-stone-800 shrink-0 mt-0.5 shadow-2xs">
                            <svg class="w-5 h-5 text-stone-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-stone-900 text-sm md:text-base leading-snug">
                                {{ ($tour->min_age || $tour->max_age) ? (__('Age') . ' ' . ($tour->min_age ?: '15') . ' - ' . ($tour->max_age ?: '70')) : __('Age 15 - 70') }}
                            </h4>
                            <p class="text-stone-500 text-xs mt-0.5 leading-relaxed font-medium">
                                {{ __('Suitable for families, couples, and solo travelers') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-10">
                <h2 class="font-display text-2xl font-semibold text-gray-900 mb-4">{{ __('Overview') }}</h2>
                <article class="prose prose-lg prose-gray max-w-none text-gray-600 leading-relaxed">{!! $tour->translate('description') !!}</article>
            </div>

            @if(!empty($tour->highlights) && is_array($tour->highlights))
            <div class="mb-10">
                <h2 class="font-display text-2xl font-semibold text-gray-900 mb-4">{{ __('Tour Highlights') }}</h2>
                <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($tour->translate('highlights') as $highlight)
                        @if($highlight)
                        <li class="flex items-center gap-3 text-gray-600">
                            <svg class="w-5 h-5 text-gold-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ is_array($highlight) ? implode(', ', $highlight) : __($highlight) }}</span>
                        </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            @endif

            @if($tour->video_url)
            <div class="mb-10">
                <h2 class="font-display text-2xl font-semibold text-gray-900 mb-4">{{ __('Tour Experience Video') }}</h2>
                <div class="aspect-video bg-gray-900 rounded-2xl overflow-hidden border border-gray-200 shadow-xl relative group">
                    <iframe
                        id="tour-video-iframe"
                        width="100%"
                        height="100%"
                        src="{{ $tour->youtube_embed_url }}"
                        title="{{ $tour->translate('title') }} Video"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
            @endif

            @php
                $rawMainItinerary = $tour->itinerary ?? [];
                $itinerary = $tour->translate('itinerary');
                if (!is_array($itinerary) || count($itinerary) === 0) {
                    $itinerary = is_array($rawMainItinerary) ? $rawMainItinerary : [];
                }
            @endphp
            @if(!empty($itinerary) && count($itinerary) > 0)
            {{-- Collapsible Dropdown Itinerary Summary --}}
            <div class="mb-10 bg-stone-50 border border-stone-200/80 rounded-3xl overflow-hidden shadow-xs transition-all" x-data="{ open: true }">
                {{-- Dropdown Header / Toggle Button --}}
                <button type="button" @click="open = !open" class="w-full flex items-center justify-between p-5 md:p-6 text-left bg-stone-100/80 hover:bg-stone-100 transition-colors focus:outline-none">
                    <div class="flex items-center gap-3">
                        <span class="text-[#e64a19] font-black text-xl">📋</span>
                        <div>
                            <h2 class="font-display text-2xl md:text-3xl font-black text-stone-900 uppercase tracking-tight leading-tight">
                                {{ __('Itinerary Summary') }}
                            </h2>
                            <p class="text-xs text-stone-500 font-medium mt-0.5">
                                {{ count($itinerary) }} {{ __('Days Overview') }} • {{ __('Click to') }} <span x-text="open ? '{{ __('collapse') }}' : '{{ __('expand') }}'"></span>
                            </p>
                        </div>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-white border border-stone-200 flex items-center justify-center text-stone-700 font-bold transition-transform duration-300 shadow-xs" :class="open ? 'rotate-180 bg-amber-500 text-stone-950 border-amber-500' : ''">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </button>

                {{-- Dropdown Body Content (Clean compact original line design) --}}
                <div x-show="open" x-collapse class="p-5 md:p-6 pt-4 border-t border-stone-200/60 bg-white">
                    <div class="space-y-3">
                        @foreach($itinerary as $index => $day)
                        <div class="flex items-start gap-3">
                            <span class="text-[#e64a19] font-black text-lg leading-tight shrink-0 mt-0.5">→</span>
                            <p class="font-bold text-sm md:text-base leading-snug text-stone-800">
                                <span class="text-stone-900 font-black">{{ __('Day') }} {{ $loop->iteration }}:</span>
                                {{ is_array($day['title'] ?? '') ? implode(', ', $day['title']) : ($day['title'] ?? '') }}
                            </p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mb-10">
                <h2 class="font-display text-2xl font-semibold text-gray-900 mb-6">{{ __('Day-by-Day Details') }}</h2>
                <div class="space-y-4">
                    @foreach($itinerary as $index => $day)
                    @php
                        $mainDay = is_array($rawMainItinerary) && isset($rawMainItinerary[$index]) ? $rawMainItinerary[$index] : [];

                        $allDayImages = [];
                        $seenUrls = [];

                        $normalizeUrl = function($raw) {
                            $raw = trim($raw ?? '');
                            if (empty($raw) || is_array($raw)) return '';
                            if (str_starts_with($raw, 'http://') || str_starts_with($raw, 'https://') || str_starts_with($raw, 'data:image/')) {
                                return $raw;
                            }
                            $cleanPath = ltrim($raw, '/');
                            if (str_starts_with($cleanPath, 'storage/')) {
                                return asset($cleanPath);
                            }
                            return asset('storage/' . $cleanPath);
                        };

                        $addImg = function($url, $caption = '') use (&$allDayImages, &$seenUrls, $normalizeUrl, $day) {
                            if (empty($url) || is_array($url)) return;
                            $fullUrl = $normalizeUrl($url);
                            if (!empty($fullUrl) && !in_array($fullUrl, $seenUrls)) {
                                $seenUrls[] = $fullUrl;
                                $allDayImages[] = [
                                    'url' => $fullUrl,
                                    'caption' => trim($caption) ?: ($day['title'] ?? ''),
                                ];
                            }
                        };

                        // 1. Process 'images' or 'day_images' array
                        $arrImgs = $day['images'] ?? $day['day_images'] ?? $mainDay['images'] ?? $mainDay['day_images'] ?? [];
                        if (is_string($arrImgs)) {
                            $decoded = json_decode($arrImgs, true);
                            $arrImgs = is_array($decoded) ? $decoded : [];
                        }
                        if (is_array($arrImgs)) {
                            foreach ($arrImgs as $item) {
                                $u = is_array($item) ? ($item['url'] ?? $item['path'] ?? '') : $item;
                                $c = is_array($item) ? ($item['caption'] ?? '') : '';
                                $addImg($u, $c);
                            }
                        }

                        // 2. Process 'gallery_images'
                        $galImgs = $day['gallery_images'] ?? $mainDay['gallery_images'] ?? [];
                        if (is_string($galImgs)) {
                            $decodedGal = json_decode($galImgs, true);
                            if (is_array($decodedGal)) {
                                $galImgs = $decodedGal;
                            } else {
                                $galImgs = array_map('trim', explode(',', $galImgs));
                            }
                        }
                        if (is_array($galImgs)) {
                            foreach ($galImgs as $gItem) {
                                $u = is_array($gItem) ? ($gItem['url'] ?? $gItem['path'] ?? '') : $gItem;
                                $addImg($u, '');
                            }
                        }

                        // 3. Process single 'cover_image' or 'image'
                        $singleCandidates = [
                            $day['cover_image'] ?? null,
                            $day['image'] ?? null,
                            $day['day_image'] ?? null,
                            $mainDay['cover_image'] ?? null,
                            $mainDay['image'] ?? null,
                            $mainDay['day_image'] ?? null,
                        ];

                        foreach ($singleCandidates as $sCandidate) {
                            if (!empty($sCandidate) && !is_array($sCandidate)) {
                                $addImg($sCandidate, '');
                            }
                        }
                    @endphp
                    <div class="relative pl-8">
                        <div class="absolute left-0 top-3 w-6 h-6 bg-gold-500 rounded-full flex items-center justify-center text-safari-dark text-xs font-bold">{{ is_numeric($index) ? $index : $loop->iteration }}</div>
                        @if(!$loop->last)
                        <div class="absolute left-3 top-9 bottom-0 w-0.5 bg-gray-200"></div>
                        @endif
                        <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm">
                            <h3 class="font-bold text-gray-800 mb-2">{{ __('Day') }} {{ is_numeric($index) ? $index : $loop->iteration }}: {{ $day['title'] ?? '' }}</h3>

                            @if(!empty($day['description']))
                            <p class="text-gray-500 text-sm leading-relaxed mb-4">{{ $day['description'] ?? '' }}</p>
                            @endif

                            @if(!empty($allDayImages) && count($allDayImages) > 0)
                                <div class="my-4 pt-1">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                        @foreach($allDayImages as $imgIndex => $img)
                                            @php
                                                $imgUrl = $img['url'] ?? '';
                                                $caption = $img['caption'] ?? '';
                                            @endphp
                                            @if(!empty($imgUrl))
                                                <div class="flex flex-col bg-stone-50 border border-stone-200/80 rounded-xl overflow-hidden shadow-sm hover:border-gold-500/60 transition-all">
                                                    <div class="h-44 sm:h-52 md:h-56 overflow-hidden bg-stone-900 relative">
                                                        <img src="{{ $imgUrl }}" alt="{{ $caption ?: ($day['title'] ?? 'Day Photo') }}"
                                                             class="w-full h-full object-cover cursor-pointer hover:scale-105 transition-transform duration-500"
                                                             loading="lazy"
                                                             onclick="openLightbox('{{ $imgUrl }}')">
                                                    </div>
                                                    @if(!empty($caption))
                                                    <div class="p-2.5 bg-white border-t border-stone-200/60">
                                                        <p class="text-[11px] font-medium text-stone-700 leading-snug">
                                                            {{ $caption }}
                                                        </p>
                                                    </div>
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            <div class="flex flex-wrap items-center gap-x-8 gap-y-3 mt-4 text-xs font-bold uppercase tracking-wider">
                                @if(!empty($day['accommodation']))
                                <div class="flex items-center gap-2 text-gray-600">
                                    <svg class="w-4 h-4 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    <span>{{ __('Stay') }}: {{ is_array($day['accommodation']) ? implode(', ', $day['accommodation']) : $day['accommodation'] }}</span>
                                </div>
                                @endif
                                @if(!empty($day['meals']))
                                <div class="flex items-center gap-2 text-gray-600">
                                    <svg class="w-4 h-4 text-gold-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    <span>{{ __('Meals') }}: {{ is_array($day['meals']) ? implode(', ', $day['meals']) : $day['meals'] }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- WHAT'S INCLUDED & NOT INCLUDED SECTION --}}
            @php
                $inclusions = $tour->translate('inclusions');
                $exclusions = $tour->translate('exclusions');
            @endphp
            @if((!empty($inclusions) && is_array($inclusions)) || (!empty($exclusions) && is_array($exclusions)))
            <div class="mb-10 pt-4">
                <h2 class="font-display text-3xl font-black text-stone-900 border-b-2 border-[#b8920d]/40 pb-3 mb-6">
                    {{ __("What's Included") }}
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- INCLUDED CARD --}}
                    @if(!empty($inclusions) && is_array($inclusions))
                    <div class="bg-[#fffdfa] border border-[#f3e8d3] rounded-2xl p-6 shadow-xs">
                        <h4 class="text-xs font-black text-[#8A6D0B] uppercase tracking-wider mb-5">INCLUDED</h4>
                        <ul class="space-y-3">
                            @foreach($inclusions as $item)
                            <li class="flex items-start gap-2.5 text-stone-700 text-xs sm:text-sm font-medium">
                                <span class="text-[#8A6D0B] font-black text-sm shrink-0">✓</span>
                                <span class="leading-snug">{{ is_array($item) ? implode(', ', $item) : __($item) }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    {{-- NOT INCLUDED CARD --}}
                    @if(!empty($exclusions) && is_array($exclusions))
                    <div class="bg-[#fffdfa] border border-[#f3e8d3] rounded-2xl p-6 shadow-xs">
                        <h4 class="text-xs font-black text-rose-800 uppercase tracking-wider mb-5">NOT INCLUDED</h4>
                        <ul class="space-y-3">
                            @foreach($exclusions as $item)
                            <li class="flex items-start gap-2.5 text-stone-700 text-xs sm:text-sm font-medium">
                                <span class="text-rose-600 font-bold text-sm shrink-0">×</span>
                                <span class="leading-snug">{{ is_array($item) ? implode(', ', $item) : __($item) }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- WHAT TO BRING SECTION --}}
            @php $packingList = $tour->translate('what_to_bring'); @endphp
            @if(!empty($packingList) && is_array($packingList) && count($packingList) > 0)
            <div class="mb-10">
                <h4 class="text-xs font-black text-[#8A6D0B] uppercase tracking-wider mb-4">{{ __("WHAT TO BRING") }}</h4>
                <div class="flex flex-wrap gap-2.5">
                    @foreach($packingList as $item)
                        @php $itemStr = is_array($item) ? implode(', ', $item) : __($item); @endphp
                        @if(!empty($itemStr))
                        <span class="px-4 py-2 bg-[#fbf7ee] text-stone-800 border border-[#f1e5cf] rounded-full text-xs font-semibold shadow-xs">
                            {{ $itemStr }}
                        </span>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif

            {{-- GOOD TO KNOW SECTION --}}
            @php $essentialInfo = $tour->translate('good_to_know'); @endphp
            @if(!empty($essentialInfo))
            <div class="mb-12">
                <h3 class="font-display text-2xl md:text-3xl font-black text-stone-900 border-b-2 border-[#b8920d] pb-3 mb-6">
                    {{ __("GOOD TO KNOW") }}
                </h3>
                <ul class="space-y-3.5 bg-white border border-[#e8dfcf] rounded-2xl p-6 md:p-8 shadow-xs">
                    @php
                        $infoList = is_array($essentialInfo) ? $essentialInfo : explode("\n", $essentialInfo);
                    @endphp
                    @foreach($infoList as $index => $infoItem)
                        @php
                            $itemText = '';
                            if (is_array($infoItem)) {
                                $t = $infoItem['title'] ?? $infoItem['question'] ?? '';
                                $d = $infoItem['description'] ?? $infoItem['answer'] ?? '';
                                $itemText = trim($t . ($t && $d ? ': ' : '') . $d);
                                if (empty($itemText)) {
                                    $itemText = implode(' - ', array_filter($infoItem));
                                }
                            } else {
                                $itemText = trim($infoItem);
                            }
                        @endphp
                        @if(!empty($itemText))
                        <li class="flex items-start gap-3.5 text-stone-800 text-sm sm:text-base leading-relaxed font-medium">
                            <span class="w-2.5 h-2.5 rounded-full bg-[#d4af37] shrink-0 mt-2 shadow-xs"></span>
                            <span>{{ $itemText }}</span>
                        </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            @endif

            <div id="reviews" class="pt-10 border-t border-gray-100 mb-12">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="font-display text-3xl font-bold text-gray-900">{{ __('Guest Reviews') }}</h2>
                    <button onclick="document.getElementById('review-form-container').classList.toggle('hidden')" class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-stone-950 px-6 py-3 rounded-full text-xs font-black uppercase tracking-widest transition-all shadow-md">
                        {{ __('Leave a Review') }}
                    </button>
                </div>

                {{-- Success Banner --}}
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 shadow-xs">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <p class="text-xs md:text-sm font-bold">{{ session('success') }}</p>
                    </div>
                @endif

                {{-- Validation Errors Banner --}}
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl text-xs space-y-1 shadow-xs">
                        <p class="font-bold mb-1">{{ __('Please correct the errors in your review:') }}</p>
                        @foreach($errors->all() as $error)
                            <p>• {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                {{-- Review Form Container --}}
                <form id="review-form-container" action="{{ route('reviews.store') }}" method="POST" class="{{ $errors->any() ? '' : 'hidden' }} bg-stone-50 border border-stone-200/80 rounded-3xl p-6 md:p-8 mb-8 shadow-sm">
                    @csrf
                    <input type="hidden" name="tour_id" value="{{ $tour->id }}">

                    <h3 class="text-lg md:text-xl font-black text-stone-900 mb-4">{{ __('Write a Review for') }} {{ $tour->title }}</h3>

                    {{-- Rating Selector --}}
                    <div class="mb-5" x-data="{ rating: {{ old('rating', 5) }} }">
                        <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-2">{{ __('Your Rating') }} *</label>
                        <div class="flex items-center gap-2">
                            <input type="hidden" name="rating" :value="rating">
                            <template x-for="star in [1, 2, 3, 4, 5]">
                                <button type="button" @click="rating = star" class="p-1 focus:outline-none transition-transform hover:scale-110">
                                    <svg class="w-8 h-8" :class="star <= rating ? 'text-amber-500 fill-amber-500' : 'text-stone-300 fill-stone-200'" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </button>
                            </template>
                            <span class="text-xs font-bold text-stone-600 ml-2" x-text="rating + ' / 5 {{ __('Stars') }}'"></span>
                        </div>
                    </div>

                    {{-- Name & Email --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">{{ __('Full Name') }} *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-white border border-stone-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 outline-none" placeholder="{{ __('Your Name') }}">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">{{ __('Email Address') }} *</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full bg-white border border-stone-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 outline-none" placeholder="{{ __('your@email.com') }}">
                        </div>
                    </div>

                    {{-- Title, Country, Date --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">{{ __('Review Headline') }}</label>
                            <input type="text" name="title" value="{{ old('title') }}" class="w-full bg-white border border-stone-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 outline-none" placeholder="{{ __('e.g. Life-changing Experience!') }}">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">{{ __('Country / Hometown') }}</label>
                            <input type="text" name="country" value="{{ old('country') }}" class="w-full bg-white border border-stone-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 outline-none" placeholder="{{ __('e.g. Germany') }}">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">{{ __('Date of Travel') }}</label>
                            <input type="date" name="trip_date" value="{{ old('trip_date') }}" class="w-full bg-white border border-stone-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 outline-none">
                        </div>
                    </div>

                    {{-- Review Content --}}
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">{{ __('Your Review') }} *</label>
                        <textarea name="content" rows="4" required minlength="10" class="w-full bg-white border border-stone-300 rounded-xl p-4 text-sm focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 outline-none" placeholder="{{ __('Write your experience, guide feedback, and highlights of the tour...') }}">{{ old('content') }}</textarea>
                        <p class="text-[10px] text-stone-400 mt-1">{{ __('Minimum 10 characters') }}</p>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-center gap-3">
                        <button type="submit" class="bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-stone-950 font-black px-8 py-3.5 rounded-xl text-xs uppercase tracking-widest shadow-md transition-all hover:scale-105">
                            {{ __('Submit Review') }}
                        </button>
                        <button type="button" onclick="document.getElementById('review-form-container').classList.add('hidden')" class="px-6 py-3.5 border border-stone-300 text-stone-600 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-stone-200/60 transition-all">
                            {{ __('Cancel') }}
                        </button>
                    </div>
                </form>

                <div class="space-y-6">
                    @forelse($tour->reviews as $review)
                        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-gold-100 rounded-full flex items-center justify-center font-bold text-gold-700">
                                        {{ substr($review->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900 text-sm">{{ $review->name }}</h4>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase">{{ $review->created_at->format('M Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex text-gold-500">
                                    @for($i=1; $i<=5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'fill-current' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                            </div>
                            <h5 class="font-bold text-gray-800 text-sm mb-2">{{ $review->title }}</h5>
                            <p class="text-gray-600 text-sm leading-relaxed">{{ $review->content }}</p>
                        </div>
                    @empty
                        <p class="text-gray-400 italic text-center py-10">{{ __('No reviews yet. Be the first to share your journey.') }}</p>
                    @endforelse
                </div>
            </div>

            {{-- FREQUENTLY ASKED QUESTIONS (Strictly Tour-Specific) --}}
            @php
                $faqs = $tour->translate('faqs');
                $allVisualFaqs = [];

                if (!empty($faqs) && is_array($faqs)) {
                    foreach ($faqs as $f) {
                        $q = is_array($f) ? ($f['question'] ?? $f['title'] ?? '') : $f;
                        $a = is_array($f) ? ($f['answer'] ?? $f['description'] ?? '') : '';
                        if (!empty($q) && !empty($a)) {
                            $allVisualFaqs[] = [
                                'question' => $q,
                                'answer' => $a,
                            ];
                        }
                    }
                }
            @endphp

            @if(count($allVisualFaqs) > 0)
            <div class="pt-8 border-t border-stone-200/80 mb-10">
                <h2 class="font-display text-2xl md:text-3xl font-black text-stone-900 border-b-2 border-[#b8920d] pb-3 mb-6">
                    {{ __('Frequently Asked Questions') }}
                </h2>

                <div class="space-y-3">
                    @foreach($allVisualFaqs as $vf)
                    <div x-data="{ open: false }" class="bg-white border border-[#e8dfcf] rounded-2xl overflow-hidden shadow-xs hover:border-[#b8920d]/60 transition-all">
                        <button type="button" @click="open = !open" class="w-full text-left p-5 md:p-6 font-bold text-stone-900 text-sm sm:text-base flex items-center justify-between cursor-pointer hover:bg-stone-50/50 transition-colors">
                            <span>{{ $vf['question'] }}</span>
                            <span class="text-[#8A6D0B] text-xl font-bold ml-2 shrink-0 transition-transform duration-200" :class="open ? 'rotate-45' : ''">+</span>
                        </button>
                        <div x-show="open" x-collapse x-cloak class="px-5 pb-5 pt-1 text-stone-600 text-xs sm:text-sm leading-relaxed border-t border-[#e8dfcf]/60 bg-[#fffdf9]">
                            {!! $vf['answer'] !!}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <aside class="lg:w-96 flex-shrink-0">
            <div class="sticky top-28 space-y-6">
                {{-- Card 1: TourRadar Style Booking Box --}}
                <div id="booking-card" class="bg-white rounded-3xl p-6 md:p-8 border border-stone-200/90 shadow-lg relative overflow-hidden">
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <span class="text-xs text-stone-400 font-semibold line-through">
                            From ${{ number_format($tour->price * 1.5) }}
                        </span>
                        <span class="px-2.5 py-1 bg-red-600 text-white font-black text-[10px] uppercase rounded-md tracking-wider">
                            50% off
                        </span>
                    </div>

                    <div class="mb-4">
                        <span class="text-xs font-bold text-stone-900 uppercase tracking-wider">US</span>
                        <span class="text-3xl md:text-4xl font-black text-stone-900 tracking-tight">${{ number_format($tour->price) }}</span>
                        <span class="text-xs font-semibold text-stone-500">per person</span>
                    </div>

                    <div class="space-y-1.5 mb-6 text-xs text-stone-600 font-medium">
                        <p class="flex items-center gap-1.5 text-amber-800 font-semibold">
                            <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <span>Sign up to unlock savings</span>
                        </p>
                        <p class="text-stone-400 text-[11px]">
                            Price based on Private Double Room
                        </p>
                    </div>

                    <div class="space-y-3 mb-6">
                        <div>
                            <label class="block text-[11px] font-bold text-stone-700 uppercase tracking-wider mb-1">{{ __('Departure Month') }}</label>
                            <select class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-2.5 text-xs font-semibold text-stone-800 outline-none focus:border-amber-500">
                                <option value="">Select departure month</option>
                                <option value="current">{{ now()->format('F Y') }}</option>
                                <option value="next1">{{ now()->addMonth()->format('F Y') }}</option>
                                <option value="next2">{{ now()->addMonths(2)->format('F Y') }}</option>
                                <option value="next3">{{ now()->addMonths(3)->format('F Y') }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-stone-700 uppercase tracking-wider mb-1">{{ __('Travelers') }}</label>
                            <select class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-2.5 text-xs font-semibold text-stone-800 outline-none focus:border-amber-500">
                                <option value="2">2 Adults</option>
                                <option value="1">1 Adult</option>
                                <option value="3">3 Adults</option>
                                <option value="4">4 Adults</option>
                                <option value="group">5+ Group</option>
                            </select>
                        </div>
                    </div>

                    @if(!empty($tour->extras) && is_array($tour->extras) && count($tour->extras) > 0)
                        <div class="mb-5 p-3.5 bg-[#fcfaf5] border border-[#e8dfcf] rounded-xl text-xs">
                            <div class="flex items-center justify-between font-bold text-stone-900 mb-1.5">
                                <span class="flex items-center gap-1.5 text-amber-800 uppercase text-[10px] tracking-wider">
                                    <svg class="w-3.5 h-3.5 text-[#b8920d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                    Optional Extras Available
                                </span>
                                <span class="text-[10px] bg-amber-100 text-amber-900 px-1.5 py-0.5 rounded font-black">{{ count($tour->extras) }}</span>
                            </div>
                            <ul class="space-y-1 text-[11px] text-stone-600">
                                @foreach(array_slice($tour->extras, 0, 3) as $ext)
                                    @php $exName = is_array($ext) ? ($ext['name'] ?? $ext['title'] ?? '') : $ext; @endphp
                                    @php $exPrice = is_array($ext) ? ($ext['price'] ?? 0) : 0; @endphp
                                    @if($exName)
                                        <li class="flex justify-between items-center">
                                            <span class="truncate pr-2">• {{ $exName }}</span>
                                            <span class="font-bold text-stone-900 shrink-0">+${{ number_format($exPrice, 0) }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                            <p class="text-[9px] text-stone-400 mt-1.5 italic">Select your preferred extras during checkout</p>
                        </div>
                    @endif

                    <a href="{{ route('booking.create', $tour->slug) }}" class="btn-gold w-full block text-center py-4 rounded-2xl font-black text-sm uppercase tracking-wider shadow-md transition-all mb-6">
                        See dates & prices
                    </a>

                    <div class="space-y-3 pt-4 border-t border-stone-100 text-xs text-stone-600 font-medium">
                        <div class="flex items-start gap-2.5">
                            <span class="text-stone-700 font-bold text-sm shrink-0">💳</span>
                            <p class="leading-tight">
                                <strong>Pay only 20% today.</strong> The rest is due 65 days before departure.
                            </p>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <span class="text-stone-700 font-bold text-sm shrink-0">🛡️</span>
                            <p class="leading-tight">
                                <strong>Best price guarantee</strong>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Plan Your Adventure Box --}}
                <div class="bg-white rounded-3xl p-6 border border-stone-200/90 shadow-xs space-y-3">
                    <h4 class="font-bold text-stone-900 text-xs uppercase tracking-wider mb-2">{{ __('Plan your adventure:') }}</h4>

                    @if(!empty($tour->itinerary))
                        <a href="{{ route('tours.download-pdf', $tour->slug) }}" class="w-full flex items-center gap-3 p-3.5 rounded-2xl bg-stone-50 hover:bg-stone-100 border border-stone-200 text-stone-800 text-xs font-bold transition-all">
                            <span class="text-amber-600 text-base">📥</span>
                            <span>{{ __('Download PDF Brochure') }}</span>
                        </a>
                    @endif

                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('site_whatsapp', '255795482197')) }}?text=I'm interested in: {{ urlencode($tour->title) }}"
                       target="_blank"
                       class="w-full flex items-center gap-3 p-3.5 rounded-2xl bg-stone-50 hover:bg-stone-100 border border-stone-200 text-stone-800 text-xs font-bold transition-all">
                        <span class="text-emerald-600 text-base">💬</span>
                        <span>{{ __('Contact Operator') }}</span>
                    </a>
                </div>
            </div>
        </aside>
    </div>
</div>

<div id="lightbox" class="fixed inset-0 bg-black/95 z-[100] hidden items-center justify-center p-4" onclick="closeLightbox()">
    <img id="lightbox-img" src="" alt="Gallery Preview" class="max-w-full max-h-full rounded-lg shadow-2xl">
    <button class="absolute top-6 right-6 text-white text-4xl font-light hover:text-gold-400 transition-colors" onclick="closeLightbox()">&times;</button>
</div>

<div class="fixed bottom-0 left-0 right-0 p-4 bg-white/95 backdrop-blur-md border-t border-gray-100 lg:hidden z-50 shadow-2xl transform transition-transform"
     x-data="{ show: true, lastScroll: 0 }"
     x-show="show"
     @@scroll.window="show = (window.pageYOffset < lastScroll || window.pageYOffset < 100); lastScroll = window.pageYOffset">
    <div class="flex justify-between items-center gap-4 max-w-lg mx-auto">
        <div>
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">{{ __('From') }}</p>
            <p class="text-xl font-black text-gold-600 leading-none">{{ $tour->formatted_price }}</p>
        </div>
        <a href="{{ route('booking.create', $tour->slug) }}" class="flex-1 btn-gold py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-lg">
            {{ __('Book Now') }}
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openLightbox(src) {
    const lb = document.getElementById('lightbox');
    document.getElementById('lightbox-img').src = src;
    lb.classList.remove('hidden');
    lb.classList.add('flex');
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    const lb = document.getElementById('lightbox');
    lb.classList.add('hidden');
    lb.classList.remove('flex');
    document.body.style.overflow = '';
}
</script>
@endsection
