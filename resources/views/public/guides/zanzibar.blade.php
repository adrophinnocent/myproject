@extends('public.layouts.app')

@section('title', 'Zanzibar Holiday Packages 2026 | Twina Safaris')
@section('meta_description', 'Compare Zanzibar holiday packages by beach region, season and safari combination. See what to include and request a tailor-made Tanzania itinerary.')

@php
    $pageTitle = 'Zanzibar Holiday Packages: Beaches, Culture and Safaris';
    $pagePath = '/zanzibar-holiday-packages';
    $faqs = [
        [
            'question' => 'How many days should I spend in Zanzibar?',
            'answer' => 'Three to four nights suits a short beach add-on, five to seven nights gives proper relaxation time, and longer stays work well if you want to explore more than one coast area.',
        ],
        [
            'question' => 'Which Zanzibar beach area should I choose?',
            'answer' => 'Nungwi and Kendwa are known for beaches and snorkelling, Paje and Jambiani for a relaxed east coast feel, and Michamvi for a quieter setting. Stone Town is best for history and food.',
        ],
        [
            'question' => 'When is the best time for Zanzibar?',
            'answer' => 'June to October is generally dry and popular, December to February is warm and festive, and March to May is wetter and quieter. Sea conditions vary by coast, so ask about the specific area you plan to stay in.',
        ],
        [
            'question' => 'Do I need a visa for Zanzibar?',
            'answer' => 'Zanzibar is part of Tanzania, so Tanzania entry rules apply. Check current visa and passport requirements for your nationality before booking.',
        ],
        [
            'question' => 'Can I combine Zanzibar with a safari?',
            'answer' => 'Yes. Many itineraries start with a mainland safari and finish on the coast. Flight timing, rest days and luggage allowances should be checked before you confirm.',
        ],
        [
            'question' => 'Are flights to Zanzibar included in holiday packages?',
            'answer' => 'It depends on the package. Domestic flights from mainland Tanzania are often separate line items. Always check what transfers and flights are included.',
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
    'intro' => 'Zanzibar works as a beach break, a culture stop or the relaxed ending to a Tanzania safari. Choose the coast area and season first, then compare what each package actually includes.',
])

<section class="py-14 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-4">Choose the right base</h2>
        <div class="overflow-x-auto rounded-2xl border border-stone-200 bg-white">
            <table class="w-full text-sm text-left min-w-[720px]">
                <thead class="bg-stone-100 text-stone-700 uppercase text-xs tracking-wider">
                    <tr><th class="p-4">Area</th><th class="p-4">Feel</th><th class="p-4">Good for</th></tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-stone-600">
                    <tr><td class="p-4 font-semibold text-stone-900">Stone Town</td><td class="p-4">Historic, busy, cultural</td><td class="p-4">Old Town walks, food, spice tours, ferry and airport access</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-900">Nungwi / Kendwa</td><td class="p-4">Lively north coast</td><td class="p-4">Beaches, snorkelling and sunset trips</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-900">Paje / Jambiani</td><td class="p-4">Relaxed east coast</td><td class="p-4">Kite surfing, long beaches and guesthouses</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-900">Michamvi</td><td class="p-4">Quieter southeast coast</td><td class="p-4">A slower beach stay and rock restaurant area</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="py-14 bg-stone-50 border-y border-stone-200/80">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-6">How long to stay</h2>
        <div class="grid md:grid-cols-3 gap-5">
            @foreach([
                ['3–4 nights', 'A short beach add-on after a safari. Best if you want a few days of rest without losing too much time.'],
                ['5–7 nights', 'A proper beach holiday with time for Stone Town, a spice tour and trips along the coast.'],
                ['7+ nights', 'For travellers who want to slow down, change base or combine beach time with more activities.'],
            ] as $option)
            <div class="p-6 rounded-2xl bg-white border border-stone-200">
                <h3 class="font-display text-xl font-bold text-stone-900 mb-2">{{ $option[0] }}</h3>
                <p class="text-sm text-stone-600 leading-relaxed">{{ $option[1] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-5">What to check before booking</h2>
        <ul class="grid sm:grid-cols-2 gap-3 text-sm text-stone-600">
            @foreach([
                'Whether domestic flights and airport transfers are included',
                'The named hotel or a clear accommodation category',
                'Meal plan: breakfast only, half board or all inclusive',
                'Seasonal supplements and holiday-period rates',
                'Activity costs such as snorkelling, spice tours or Stone Town tours',
                'Cancellation terms and payment schedule',
                'Rest days between a safari, flight and beach stay',
                'Current visa and entry requirements for your nationality',
            ] as $item)
            <li class="p-4 rounded-xl bg-stone-50 border border-stone-200/80">{{ $item }}</li>
            @endforeach
        </ul>
    </div>
</section>

<section class="py-16 bg-stone-50 border-t border-stone-200/80">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-5">Safari and Zanzibar in one itinerary</h2>
        <p class="text-stone-600 leading-relaxed mb-7">
            A common Tanzania itinerary combines Serengeti or Ngorongoro with a few nights on the coast. Ask the operator to check flight days and transfer times so you do not lose a full day between the bush and the beach.
        </p>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('guides.best-time') }}" class="px-5 py-3 rounded-full border border-stone-300 text-sm font-bold text-stone-700 hover:border-[#b8920d] hover:text-[#8A6D0B] transition-colors">Best time to visit</a>
            <a href="{{ route('guides.serengeti') }}" class="px-5 py-3 rounded-full border border-stone-300 text-sm font-bold text-stone-700 hover:border-[#b8920d] hover:text-[#8A6D0B] transition-colors">Serengeti safari guide</a>
            <a href="{{ route('guides.kilimanjaro') }}" class="px-5 py-3 rounded-full border border-stone-300 text-sm font-bold text-stone-700 hover:border-[#b8920d] hover:text-[#8A6D0B] transition-colors">Kilimanjaro trekking guide</a>
            <a href="{{ route('tours.index') }}" class="px-5 py-3 rounded-full border border-stone-300 text-sm font-bold text-stone-700 hover:border-[#b8920d] hover:text-[#8A6D0B] transition-colors">Browse all packages</a>
        </div>
    </div>
</section>

@include('public.guides.partials.faq')
@include('public.guides.partials.cta')
@endsection
