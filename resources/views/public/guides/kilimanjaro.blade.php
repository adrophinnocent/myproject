@extends('public.layouts.app')

@section('title', 'Kilimanjaro Treks & Routes | Twina Safaris')
@section('meta_description', 'Compare Machame, Lemosho, Marangu and Rongai Kilimanjaro routes with lengths, acclimatisation, included services and clear pricing from a Moshi team.')

@php
    $pageTitle = 'Kilimanjaro Treks & Routes from Moshi';
    $pagePath = '/kilimanjaro-trekking-company';
    $faqs = [
        [
            'question' => 'Which Kilimanjaro route is best for a first climb?',
            'answer' => 'Longer routes with gentle acclimatisation, such as Lemosho or a nine to ten day Northern Circuit, are often recommended. The best route depends on your fitness, budget and available days, not only on difficulty labels.',
        ],
        [
            'question' => 'Can a trekking company guarantee I will reach the summit?',
            'answer' => 'No responsible operator can guarantee a summit. Weather, altitude response and health all matter. Choose a company that explains acclimatisation, pacing and its emergency procedure instead of advertising a fixed success percentage.',
        ],
        [
            'question' => 'What should be included in a Kilimanjaro trek quote?',
            'answer' => 'Check park permits and rescue fees, guides and porters, tents or huts, meals and water, transport to and from the gate, and emergency oxygen. The inclusion list should be written before you pay.',
        ],
        [
            'question' => 'How many days do I need for Kilimanjaro?',
            'answer' => 'Routes range from about five to ten or more days. Shorter routes cost less but allow less acclimatisation. Give yourself extra days if possible, and consider a safari afterwards to recover.',
        ],
        [
            'question' => 'Is Marangu the easiest Kilimanjaro route?',
            'answer' => 'Marangu uses huts rather than tents and is often marketed as easier, but it is short and altitude remains the main challenge. Acclimatisation matters more than hut comfort.',
        ],
        [
            'question' => 'Can I combine Kilimanjaro with a safari or Zanzibar?',
            'answer' => 'Yes. Many travellers trek first, then go on safari or to Zanzibar. Build in rest and transfer days, and check flight times before confirming the full itinerary.',
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
    'intro' => 'Choosing a Kilimanjaro trekking company is about more than the cheapest headline price. Compare route length, acclimatisation, guide and porter support, permit handling and the emergency plan before you commit.',
])

<section class="py-14 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-4">Compare the main routes</h2>
        <p class="text-stone-600 text-sm mb-6">Typical durations vary by operator and itinerary. Always read the day-by-day plan.</p>
        <div class="overflow-x-auto rounded-2xl border border-stone-200 bg-white">
            <table class="w-full text-sm text-left min-w-[720px]">
                <thead class="bg-stone-100 text-stone-700 uppercase text-xs tracking-wider">
                    <tr><th class="p-4">Route</th><th class="p-4">Typical days</th><th class="p-4">Style</th><th class="p-4">Best for</th></tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-stone-600">
                    <tr><td class="p-4 font-semibold text-stone-900">Machame</td><td class="p-4">6–7</td><td class="p-4">Camping</td><td class="p-4">A popular route with varied scenery</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-900">Lemosho</td><td class="p-4">7–8</td><td class="p-4">Camping</td><td class="p-4">Longer acclimatisation and remote start</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-900">Marangu</td><td class="p-4">5–6</td><td class="p-4">Huts</td><td class="p-4">Travellers who prefer hut accommodation</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-900">Rongai</td><td class="p-4">6–7</td><td class="p-4">Camping</td><td class="p-4">A quieter northern approach</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-900">Northern Circuit</td><td class="p-4">9–10</td><td class="p-4">Camping</td><td class="p-4">More time to acclimatise</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="py-14 bg-stone-50 border-y border-stone-200/80">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-6">What a responsible quote should include</h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach([
                ['Permits and fees', 'Park permits, rescue fees and any conservation or camping charges should be itemised.'],
                ['Guide and porter team', 'Ask how many guides and porters support your group and what they are responsible for.'],
                ['Accommodation', 'Specify tents or huts, and confirm who carries equipment on camping routes.'],
                ['Meals and water', 'Know what is provided at each altitude and whether water treatment is included.'],
                ['Transfers', 'Confirm transport from Moshi or the airport to the gate and back after the climb.'],
                ['Emergency plan', 'Ask about oxygen, evacuation procedures and how altitude illness is monitored.'],
            ] as $item)
            <div class="p-5 rounded-2xl bg-white border border-stone-200">
                <h3 class="font-bold text-stone-900 mb-2">{{ $item[0] }}</h3>
                <p class="text-sm text-stone-600 leading-relaxed">{{ $item[1] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-5">Altitude comes before scenery</h2>
        <p class="text-stone-600 leading-relaxed mb-6">
            A beautiful route is not enough if the schedule climbs too quickly. Ask to see the daily sleeping altitudes, where acclimatisation days are built in, and how guides decide to turn back. A company that talks honestly about those details is safer than one that only advertises a summit percentage.
        </p>
        <a href="{{ route('guides.best-time') }}" class="text-sm font-bold text-[#8A6D0B] hover:text-gold-600 inline-flex items-center gap-1">See the best season guide <span aria-hidden="true">→</span></a>
    </div>
</section>

<section class="py-16 bg-stone-50 border-t border-stone-200/80">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-5">Trek, safari and coast</h2>
        <p class="text-stone-600 leading-relaxed mb-7">
            Kilimanjaro is often the first part of a longer Tanzania trip. Compare published trekking itineraries, then add recovery time, a safari or Zanzibar beach days.
        </p>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('tours.index', ['tour_type' => 'kilimanjaro-trekking']) }}" class="px-5 py-3 rounded-full border border-stone-300 text-sm font-bold text-stone-700 hover:border-[#b8920d] hover:text-[#8A6D0B] transition-colors">See Kilimanjaro treks</a>
            <a href="{{ route('guides.serengeti') }}" class="px-5 py-3 rounded-full border border-stone-300 text-sm font-bold text-stone-700 hover:border-[#b8920d] hover:text-[#8A6D0B] transition-colors">Add a Serengeti safari</a>
            <a href="{{ route('guides.zanzibar') }}" class="px-5 py-3 rounded-full border border-stone-300 text-sm font-bold text-stone-700 hover:border-[#b8920d] hover:text-[#8A6D0B] transition-colors">Add Zanzibar beach time</a>
            <a href="{{ route('custom-safari.create') }}" class="px-5 py-3 rounded-full border border-stone-300 text-sm font-bold text-stone-700 hover:border-[#b8920d] hover:text-[#8A6D0B] transition-colors">Request a combined itinerary</a>
        </div>
    </div>
</section>

@include('public.guides.partials.faq')
@include('public.guides.partials.cta')
@endsection
