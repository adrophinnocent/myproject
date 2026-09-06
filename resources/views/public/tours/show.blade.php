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
<div class="relative h-[70vh] min-h-[500px]">
    <img src="{{ $tour->featured_image_url }}" width="1920" height="1080" alt="{{ $tour->getTranslation('title') }} - {{ $tour->destination->name ?? 'Tanzania' }} Safari" class="w-full h-full object-cover" loading="eager">
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
                <div class="text-sm font-medium">{{ __('Certified Safari Operator') }}</div>
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
                    <div class="text-gray-400 text-xs font-semibold uppercase tracking-wider">{{ __('Duration') }}</div>
                </div>
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 text-2xl mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div class="font-bold text-gray-900 text-base">
                        {{ $tour->group_size_min ?: '1' }}-{{ $tour->group_size_max ?: '12' }}
                    </div>
                    <div class="text-gray-400 text-xs font-semibold uppercase tracking-wider">{{ __('Group Size') }}</div>
                </div>
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center text-green-600 text-2xl mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div class="font-bold text-gray-900 text-base">{{ __($tour->accommodation_type ?: 'Luxury') }}</div>
                    <div class="text-gray-400 text-xs font-semibold uppercase tracking-wider">{{ __('Accommodation') }}</div>
                </div>
                <div class="flex flex-col items-center text-center">
                    <div class="w-12 h-12 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600 text-2xl mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div class="font-bold text-gray-900 text-base">{{ __($tour->meeting_point ?: ($tour->departure_location ?: 'JRO Airport')) }}</div>
                    <div class="text-gray-400 text-xs font-semibold uppercase tracking-wider">{{ __('Arrival Point') }}</div>
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

            @if(!empty($tour->images) && $tour->images->count() > 0)
            <div class="mb-10">
                <h2 class="font-display text-2xl font-semibold text-gray-900 mb-4">{{ __('Tour Gallery') }}</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($tour->images as $image)
                    <div class="img-zoom rounded-xl overflow-hidden h-40">
                        <img src="{{ $image->url }}" width="400" height="300" alt="{{ $image->alt_text ?? $tour->translate('title') }}" class="w-full h-full object-cover cursor-pointer" onclick="openLightbox('{{ $image->url }}')" loading="lazy">
                    </div>
                    @endforeach
                </div>
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

            @php $itinerary = $tour->translate('itinerary'); @endphp
            @if(!empty($itinerary) && is_array($itinerary) && count($itinerary) > 0)
            <div class="mb-12 bg-gray-200/50 rounded-3xl p-8 md:p-12 border border-gray-200">
                <h2 class="font-display text-3xl font-black text-[#e64a19] mb-8 uppercase tracking-tight">{{ __('Itinerary Summary') }}</h2>
                <div class="space-y-4">
                    @foreach($itinerary as $index => $day)
                    <div class="flex items-start gap-4">
                        <span class="text-[#e64a19] font-black text-xl leading-none">→</span>
                        <p class="text-gray-800 font-bold text-lg md:text-xl leading-tight">
                            <span class="text-gray-900">{{ __('Day') }} {{ is_numeric($index) ? $index : $loop->iteration }}:</span>
                            {{ $day['title'] ?? '' }}
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-10">
                <h2 class="font-display text-2xl font-semibold text-gray-900 mb-6">{{ __('Day-by-Day Details') }}</h2>
                <div class="space-y-4">
                    @foreach($itinerary as $index => $day)
                    <div class="relative pl-8">
                        <div class="absolute left-0 top-3 w-6 h-6 bg-gold-500 rounded-full flex items-center justify-center text-safari-dark text-xs font-bold">{{ is_numeric($index) ? $index : $loop->iteration }}</div>
                        @if(!$loop->last)
                        <div class="absolute left-3 top-9 bottom-0 w-0.5 bg-gray-200"></div>
                        @endif
                        <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm">
                            <h3 class="font-bold text-gray-800 mb-2">{{ __('Day') }} {{ is_numeric($index) ? $index : $loop->iteration }}: {{ $day['title'] ?? '' }}</h3>
                            @if(!empty($day['description']))
                            <p class="text-gray-500 text-sm leading-relaxed mb-3">{{ $day['description'] ?? '' }}</p>
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                @php $inclusions = $tour->translate('inclusions'); @endphp
                @if(!empty($inclusions) && is_array($inclusions))
                <div class="bg-green-50 border border-green-200 rounded-2xl p-6">
                    <h3 class="font-bold text-green-800 mb-4 flex items-center gap-2"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg> {{ __("What's Included") }}</h3>
                    <ul class="space-y-2">
                        @foreach($inclusions as $item)
                        <li class="text-green-700 text-sm font-medium">• {{ is_array($item) ? implode(', ', $item) : __($item) }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @php $exclusions = $tour->translate('exclusions'); @endphp
                @if(!empty($exclusions) && is_array($exclusions))
                <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
                    <h3 class="font-bold text-red-800 mb-4 flex items-center gap-2"><svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg> {{ __('Not Included') }}</h3>
                    <ul class="space-y-2">
                        @foreach($exclusions as $item)
                        <li class="text-red-700 text-sm font-medium">• {{ is_array($item) ? implode(', ', $item) : __($item) }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>

            <div id="reviews" class="pt-10 border-t border-gray-100">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="font-display text-3xl font-bold text-gray-900">{{ __('Guest Reviews') }}</h2>
                    <button onclick="document.getElementById('review-form-container').classList.toggle('hidden')" class="btn-outline-gold px-6 py-3 rounded-full text-sm font-bold transition-all">
                        {{ __('Leave a Review') }}
                    </button>
                </div>

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
        </div>

        <aside class="lg:w-80 flex-shrink-0">
            <div class="sticky top-24 space-y-6">
                <div class="bg-safari-dark text-white rounded-3xl p-8 shadow-2xl">
                    <div class="text-center mb-8">
                        <div class="text-xs text-gold-400 font-black uppercase tracking-widest mb-2">{{ __('Starting from') }}</div>
                        <div class="font-display text-5xl font-bold text-white">{{ $tour->formatted_price }}</div>
                        <p class="text-white/40 text-[10px] uppercase font-bold mt-2">{{ __($tour->price_note ?? 'per person sharing') }}</p>
                    </div>

                    <div class="space-y-3">
                        <a href="{{ route('booking.create', $tour->slug) }}" class="btn-gold w-full block text-center py-5 rounded-2xl font-black uppercase tracking-widest text-xs shadow-xl">
                            {{ __('Book My Spot') }}
                        </a>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('site_whatsapp', '255795482197')) }}?text=I'm interested in: {{ urlencode($tour->title) }}"
                           target="_blank"
                           class="w-full block text-center py-4 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-black uppercase tracking-widest text-[10px] transition-all flex items-center justify-center gap-2">
                           <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                           {{ __('Inquire via WhatsApp') }}
                        </a>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100">
                    <h4 class="font-bold text-gray-900 mb-4 uppercase tracking-widest text-[10px]">{{ __('Tour Specs') }}</h4>
                    <ul class="space-y-4">
                        <li class="flex justify-between items-center text-sm">
                            <span class="text-gray-400 font-medium">{{ __('Difficulty') }}</span>
                            <span class="text-gray-800 font-bold capitalize">{{ __($tour->difficulty_level) }}</span>
                        </li>
                        <li class="flex justify-between items-center text-sm">
                            <span class="text-gray-400 font-medium">{{ __('Min Age') }}</span>
                            <span class="text-gray-800 font-bold">{{ $tour->min_age }}+ yrs</span>
                        </li>
                        <li class="flex justify-between items-center text-sm">
                            <span class="text-gray-400 font-medium">{{ __('Type') }}</span>
                            <span class="text-gray-800 font-bold">{{ $tour->category->name ?? 'Safari' }}</span>
                        </li>
                    </ul>
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
