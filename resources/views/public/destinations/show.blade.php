@extends('public.layouts.app')

@section('title', $title)
@section('meta_description', $metaDescription)
@section('og_image', $destination->featured_image_url)
@section('og_image_alt', 'Wildlife and landscapes in '.$name)

@php
    $canonical = \App\Helpers\SeoHelper::canonicalUrl(request());
    $siteUrl = \App\Helpers\SeoHelper::siteUrl();
    $description = $destination->translate('description') ?: $destination->description;

    $items = [];
    $position = 1;
    foreach ($tours as $tour) {
        $items[] = [
            '@type' => 'TouristTrip',
            'name' => $tour->translate('title') ?: $tour->title,
            'url' => $siteUrl.'/tours/tour/'.$tour->slug.'.html',
        ];
        $position++;
    }
    foreach ($safaris as $safari) {
        $items[] = [
            '@type' => 'TouristTrip',
            'name' => $safari->title,
            'url' => $siteUrl.'/tours/safari/'.$safari->slug.'.html',
        ];
    }

    $graph = [
        [
            '@type' => 'TouristDestination',
            '@id' => $canonical.'#destination',
            'name' => $name,
            'description' => strip_tags((string) $description),
            'url' => $canonical,
            'image' => $destination->featured_image_url,
            'touristType' => ['Wildlife', 'Safari', 'Trekking'],
        ],
        [
            '@type' => 'ItemList',
            '@id' => $canonical.'#tours',
            'name' => 'Safari itineraries in '.$name,
            'numberOfItems' => count($items),
            'itemListElement' => array_map(fn ($item, $index) => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'item' => $item,
            ], $items, array_keys($items)),
        ],
        [
            '@type' => 'BreadcrumbList',
            '@id' => $canonical.'#breadcrumb',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $siteUrl.'/'],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Destinations', 'item' => $siteUrl.'/destinations'],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $name, 'item' => $canonical],
            ],
        ],
    ];

    if ($facts['geo'] !== null) {
        $graph[0]['geo'] = [
            '@type' => 'GeoCoordinates',
            'latitude' => $facts['geo']['latitude'],
            'longitude' => $facts['geo']['longitude'],
        ];
    }

    if ($faqs->isNotEmpty()) {
        $graph[] = [
            '@type' => 'FAQPage',
            '@id' => $canonical.'#faq',
            'mainEntity' => $faqs->map(fn ($faq) => [
                '@type' => 'Question',
                'name' => $faq->question,
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => strip_tags((string) $faq->answer)],
            ])->all(),
        ];
    }

    $schema = ['@context' => 'https://schema.org', '@graph' => $graph];
@endphp

@section('content')
<script type="application/ld+json">
@json($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
</script>

<div class="relative pt-32 pb-20 bg-safari-dark">
    <div class="absolute inset-0 z-0">
        <img src="{{ $destination->featured_image_url }}" alt="" width="1920" height="800" class="w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 bg-gradient-to-t from-safari-dark to-transparent"></div>
    </div>
    <div class="relative z-10 max-w-7xl mx-auto px-4">
        <nav aria-label="{{ __('Breadcrumb') }}" class="flex flex-wrap items-center gap-2 text-xs font-bold uppercase tracking-widest text-gold-400 mb-6">
            <a href="{{ route('home') }}" class="hover:text-white">{{ __('Home') }}</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('destinations.index') }}" class="hover:text-white">{{ __('Destinations') }}</a>
            <span aria-hidden="true">/</span>
            <span class="text-gray-300" aria-current="page">{{ $name }}</span>
        </nav>
        <span class="text-gold-400 text-sm uppercase tracking-widest font-semibold">{{ $destination->country }}</span>
        <h1 class="font-display text-4xl md:text-6xl text-white font-bold mt-3">{{ $name }}</h1>
        @if($description)
            <p class="text-gray-300 max-w-3xl mx-auto md:mx-0 mt-4 text-lg leading-relaxed">{{ strip_tags((string) $description) }}</p>
        @endif
    </div>
</div>

<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 lg:grid-cols-3 gap-10">
        <div class="lg:col-span-2 space-y-10">
            <div>
                <h2 class="font-display text-3xl text-safari-dark font-bold mb-4">{{ __('Why visit') }}</h2>
                <p class="text-gray-600 leading-relaxed">{{ $facts['summary'] }}</p>
            </div>
            <div>
                <h2 class="font-display text-3xl text-safari-dark font-bold mb-4">{{ __('Best time to visit') }}</h2>
                <p class="text-gray-600 leading-relaxed">{{ $facts['best_time'] }}</p>
                <p class="mt-3">
                    <a href="{{ $facts['guide']['url'] }}" class="text-gold-700 font-bold hover:underline">
                        {{ $facts['guide']['label'] }}
                    </a>
                </p>
            </div>
            <div>
                <h2 class="font-display text-3xl text-safari-dark font-bold mb-4">{{ __('Highlights') }}</h2>
                <ul class="grid sm:grid-cols-2 gap-3">
                    @foreach($facts['highlights'] as $highlight)
                        <li class="flex items-start gap-3 text-gray-600">
                            <span aria-hidden="true" class="text-gold-500 mt-1">✦</span>
                            <span>{{ $highlight }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h2 class="font-display text-3xl text-safari-dark font-bold mb-4">{{ __('Getting there') }}</h2>
                <p class="text-gray-600 leading-relaxed">{{ $facts['getting_there'] }}</p>
            </div>

            @if($faqs->isNotEmpty())
                <div>
                    <h2 class="font-display text-3xl text-safari-dark font-bold mb-4">{{ __('Frequently asked questions') }}</h2>
                    <div class="space-y-3">
                        @foreach($faqs as $faq)
                            <details class="border border-gray-200 rounded-2xl p-5">
                                <summary class="font-bold text-safari-dark cursor-pointer">{{ $faq->question }}</summary>
                                <div class="mt-3 text-gray-600 leading-relaxed">{!! $faq->answer !!}</div>
                            </details>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <aside class="space-y-6">
            <div class="rounded-3xl border border-gray-100 shadow-lg p-6">
                <h2 class="font-display text-2xl text-safari-dark font-bold mb-4">{{ __('Safari help') }}</h2>
                <p class="text-gray-600 text-sm mb-5">{{ __('Tell us your dates and budget. We will match you with the right route and accommodation level.') }}</p>
                <a href="{{ route('custom-safari.create') }}" class="btn-gold inline-block px-6 py-3 rounded-full text-xs font-black uppercase tracking-widest">
                    {{ __('Request a tailor-made safari') }}
                </a>
            </div>
            <div class="rounded-3xl bg-gray-50 p-6">
                <h2 class="font-display text-2xl text-safari-dark font-bold mb-4">{{ __('Talk to a local expert') }}</h2>
                <p class="text-gray-600 text-sm mb-4">{{ \App\Models\Setting::get('site_phone') }}</p>
                <a href="{{ route('contact.index') }}" class="text-gold-700 font-bold hover:underline">{{ __('Contact Twina Safaris') }}</a>
            </div>
        </aside>
    </div>
</section>

@if($tours->isNotEmpty() || $safaris->isNotEmpty())
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="font-display text-3xl md:text-4xl text-safari-dark font-bold mb-10">{{ __('Itineraries visiting this destination') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($tours as $tour)
                    <a href="{{ route('tours.show', ['type' => 'tour', 'slug' => $tour->slug]) }}"
                       class="group bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-shadow">
                        <img src="{{ $tour->featured_image_url }}"
                             alt="{{ $tour->translate('title') ?: $tour->title }} safari itinerary"
                             width="1200" height="800" loading="lazy" decoding="async"
                             class="w-full h-56 object-cover">
                        <div class="p-6">
                            <h3 class="font-display text-xl text-safari-dark font-bold mb-2">{{ $tour->translate('title') ?: $tour->title }}</h3>
                            <p class="text-gray-600 text-sm line-clamp-2 mb-4">{{ $tour->translate('short_description') ?: $tour->short_description }}</p>
                            <span class="text-gold-700 font-bold text-sm">{{ $tour->formatted_price }} · {{ trim($tour->duration_text) }}</span>
                        </div>
                    </a>
                @endforeach
                @foreach($safaris as $safari)
                    <a href="{{ route('tours.show', ['type' => 'safari', 'slug' => $safari->slug]) }}"
                       class="group bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-shadow">
                        <img src="{{ $safari->featured_image_url }}"
                             alt="{{ $safari->title }} safari itinerary"
                             width="1200" height="800" loading="lazy" decoding="async"
                             class="w-full h-56 object-cover">
                        <div class="p-6">
                            <h3 class="font-display text-xl text-safari-dark font-bold mb-2">{{ $safari->title }}</h3>
                            <p class="text-gray-600 text-sm line-clamp-2 mb-4">{{ $safari->short_description }}</p>
                            <span class="text-gold-700 font-bold text-sm">{{ $safari->formatted_price }} · {{ trim($safari->duration_text) }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif
@endsection
