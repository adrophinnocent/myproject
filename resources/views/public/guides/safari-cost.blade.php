@extends('public.layouts.app')

@section('title', 'Tanzania Safari Cost 2026 | Prices per Day & What Affects Them | Twina Safaris')
@section('meta_description', 'How much does a Tanzania safari cost? Compare published itinerary prices, per-day ranges, park fees, inclusions and practical ways to save with Twina Safaris.')

@php
    $pageTitle = 'Tanzania Safari Cost';
    $pagePath = '/tanzania-safari-cost';
    $faqs = [
        [
            'question' => 'How much does a Tanzania safari cost per person?',
            'answer' => 'It depends on season, group size, route and accommodation. Published itineraries can range from short shared trips to private luxury expeditions. Use the table on this page as a starting point, then check each itinerary inclusion list.',
        ],
        [
            'question' => 'Why are park fees a big part of the price?',
            'answer' => 'Tanzania park and conservation fees are set by the relevant authorities and are charged per person, often per day. They help fund wildlife protection and park management. Ask whether the fees are included in your quoted price.',
        ],
        [
            'question' => 'Is a cheaper per-day price always better value?',
            'answer' => 'Not always. A lower price may mean a shared vehicle, longer driving days, simpler accommodation or fees excluded. Compare the same route and inclusions before deciding.',
        ],
        [
            'question' => 'Does group size change the safari price?',
            'answer' => 'Yes. Vehicle and guide costs are spread across the group, so private trips for two people usually cost more per person than larger shared departures.',
        ],
        [
            'question' => 'What is the cheapest month for a Tanzania safari?',
            'answer' => 'The long rains, roughly March to May, are often quieter and better value, but some roads and camps can be affected. Discuss the trade-off with your operator before choosing.',
        ],
        [
            'question' => 'Are flights to Tanzania included in safari prices?',
            'answer' => 'International and domestic flights are usually quoted separately unless a package explicitly says otherwise. Always check what transfers are included at each end of the trip.',
        ],
    ];
@endphp

@section('schema')
@include('public.guides.partials.breadcrumb-schema')
@include('public.guides.partials.schema')
@endsection

@section('content')
@include('public.guides.partials.hero', [
    'title' => $pageTitle,
    'intro' => 'Safari prices in Tanzania are driven by park fees, season, group size, route length and accommodation. Below you can see published Twina Safaris itinerary prices, then learn exactly what to compare before you book.',
])

<section class="py-14 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <div class="p-6 md:p-8 rounded-3xl bg-[#fffdf8] border border-[#e8dfcf]">
            <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-4">The short answer</h2>
            @if($tourCount > 0)
            <p class="text-stone-600 leading-relaxed">
                Across {{ $tourCount }} published itinerar{{ $tourCount === 1 ? 'y' : 'ies' }}, current listed prices run from <strong class="text-stone-900">{{ $minPrice }}</strong> to <strong class="text-stone-900">{{ $maxPrice }}</strong> per person, or about <strong class="text-stone-900">{{ $minPerDay }}–{{ $maxPerDay }}</strong> per person per day. These are published USD prices at the time this page was generated and may change by season, group size and availability.
            </p>
            @else
            <p class="text-stone-600 leading-relaxed">
                No priced itineraries are published at the moment. Browse the full tour collection or ask for a tailored quote, and the operator will break down park fees, accommodation, guiding and transport for your dates.
            </p>
            @endif
        </div>
    </div>
</section>

@if($examples->isNotEmpty())
<section class="py-14 bg-stone-50 border-y border-stone-200/80">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-6">Published itinerary examples</h2>
        <div class="overflow-x-auto bg-white rounded-2xl border border-stone-200">
            <table class="w-full text-sm text-left min-w-[720px]">
                <thead class="bg-stone-100 text-stone-700 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="p-4">Itinerary</th>
                        <th class="p-4">Destination</th>
                        <th class="p-4">Days</th>
                        <th class="p-4">Listed price</th>
                        <th class="p-4">Per day</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-stone-600">
                    @foreach($examples as $example)
                    <tr class="hover:bg-stone-50/70">
                        <td class="p-4">
                            <a href="{{ $example['path'] }}" class="font-semibold text-stone-900 hover:text-[#8A6D0B]">{{ $example['title'] }}</a>
                        </td>
                        <td class="p-4">{{ $example['destination'] }}</td>
                        <td class="p-4">{{ $example['days'] }}</td>
                        <td class="p-4 font-semibold text-stone-900">{{ $example['price_label'] }}</td>
                        <td class="p-4">{{ $example['per_day_label'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p class="text-xs text-stone-500 mt-4">
            Prices are shown in USD for comparison. Check the individual itinerary for what is included, current fees and seasonal supplements.
        </p>
    </div>
</section>
@endif

<section class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-6">What actually changes the price</h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach([
                ['Season', 'Dry-season months are usually busier and cost more; the long rains can offer better value with some road and camp trade-offs.'],
                ['Group size', 'Private vehicles and guides cost more per person for two travellers than for a larger group sharing the same vehicle.'],
                ['Route length', 'Each extra day adds accommodation, meals, guiding and park or conservation fees.'],
                ['Accommodation', 'Lodges, tented camps and mobile camps vary widely in price and should be named in the quote.'],
                ['Park fees', 'Park and conservation fees are significant and may or may not be included in the headline price.'],
                ['Transfers and flights', 'Airport transfers and internal flights are sometimes separate line items. Confirm them explicitly.'],
            ] as $factor)
            <div class="p-5 rounded-2xl border border-stone-200 bg-stone-50">
                <h3 class="font-bold text-stone-900 mb-2">{{ $factor[0] }}</h3>
                <p class="text-sm text-stone-600 leading-relaxed">{{ $factor[1] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-16 bg-stone-50 border-y border-stone-200/80">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-6">Included, excluded and worth confirming</h2>
        <div class="grid md:grid-cols-3 gap-5">
            <div class="p-6 rounded-2xl bg-white border border-stone-200">
                <h3 class="font-bold text-stone-900 mb-3">Usually included</h3>
                <ul class="space-y-2 text-sm text-stone-600">
                    <li>• Guided game drives on the listed route</li>
                    <li>• Accommodation for the stated nights</li>
                    <li>• Meals specified in the itinerary</li>
                    <li>• Vehicle and driver-guide</li>
                </ul>
            </div>
            <div class="p-6 rounded-2xl bg-white border border-stone-200">
                <h3 class="font-bold text-stone-900 mb-3">Often excluded</h3>
                <ul class="space-y-2 text-sm text-stone-600">
                    <li>• International and domestic flights</li>
                    <li>• Travel insurance and visas</li>
                    <li>• Optional activities and balloon safaris</li>
                    <li>• Tips and personal expenses</li>
                </ul>
            </div>
            <div class="p-6 rounded-2xl bg-white border border-stone-200">
                <h3 class="font-bold text-stone-900 mb-3">Always confirm</h3>
                <ul class="space-y-2 text-sm text-stone-600">
                    <li>• Whether park fees are included</li>
                    <li>• Single-room supplements</li>
                    <li>• Payment and cancellation terms</li>
                    <li>• What happens if flights are delayed</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-5">Plan around the season, not only the price</h2>
        <p class="text-stone-600 leading-relaxed mb-7">
            A lower price can be excellent value in the green season, but the same price in the dry season may mean a much shorter route. Read the itinerary, then compare the season, parks and inclusions.
        </p>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('guides.best-time') }}" class="px-5 py-3 rounded-full border border-stone-300 text-sm font-bold text-stone-700 hover:border-[#b8920d] hover:text-[#8A6D0B] transition-colors">Best time for a Tanzania safari</a>
            <a href="{{ route('tours.index') }}" class="px-5 py-3 rounded-full border border-stone-300 text-sm font-bold text-stone-700 hover:border-[#b8920d] hover:text-[#8A6D0B] transition-colors">Browse all safari packages</a>
            <a href="{{ route('custom-safari.create') }}" class="px-5 py-3 rounded-full border border-stone-300 text-sm font-bold text-stone-700 hover:border-[#b8920d] hover:text-[#8A6D0B] transition-colors">Ask for a tailored quote</a>
        </div>
    </div>
</section>

@include('public.guides.partials.faq')
@include('public.guides.partials.cta')
@endsection
