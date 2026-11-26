@extends('public.layouts.app')

@section('title', 'Best Time for Tanzania Safari 2026 | Month-by-Month Guide')
@section('meta_description', 'Best time for a Tanzania safari: Jun–Oct for game viewing, Jan–Feb for calving, Jul–Sep for Mara crossings. See weather, crowds, prices and park advice.')

@php
    $pageTitle = 'Best Time for a Tanzania Safari 2026';
    $pagePath = '/best-time-for-tanzania-safari';
    $faqs = [
        [
            'question' => 'What is the best month for a Tanzania safari?',
            'answer' => 'For most first-time visitors, June to October offers dry weather, good visibility and concentrated wildlife. January and February are excellent for calving in the southern Serengeti, while July to September is the classic window for Mara River crossings.',
        ],
        [
            'question' => 'When is the Great Migration in the Serengeti?',
            'answer' => 'The migration moves through different parts of the ecosystem across the year. Calving is concentrated in the south around January to March, the herds move west and north around May to July, and river crossings are commonly associated with July to September in the north.',
        ],
        [
            'question' => 'Is the green season worth considering?',
            'answer' => 'Yes. March to May can be quieter and better value, with green landscapes and fewer visitors. The trade-off is heavier rain, muddy roads in some areas and some camps closing for maintenance.',
        ],
        [
            'question' => 'What is the best time for Ngorongoro Crater?',
            'answer' => 'The crater is visited year-round because wildlife is resident. The dry season offers clearer viewing, while the green season is quieter. Crater road conditions and daily access rules should be checked close to travel.',
        ],
        [
            'question' => 'Can I combine a safari with Zanzibar at any time of year?',
            'answer' => 'Yes, but weather differs by coast and season. June to October is generally dry and popular; December to February is warm and festive; March to May is wetter and quieter. Check flight connections when building the itinerary.',
        ],
        [
            'question' => 'Does Kilimanjaro have a good season?',
            'answer' => 'The drier months from June to October and December to February are commonly preferred, but routes can be climbed outside those windows. Acclimatisation and route choice matter more than picking a perfect month.',
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
    'intro' => 'June to October is the main dry-season safari window, January and February are prime calving months in the southern Serengeti, and July to September is the classic period for Mara River crossings. Here is how to choose for your own trip.',
])

<section class="py-14 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-6">Seasons at a glance</h2>
        <div class="overflow-x-auto rounded-2xl border border-stone-200">
            <table class="w-full text-sm text-left min-w-[680px] bg-white">
                <thead class="bg-stone-100 text-stone-700 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="p-4">Season</th>
                        <th class="p-4">Months</th>
                        <th class="p-4">Best for</th>
                        <th class="p-4">Trade-off</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-stone-600">
                    <tr><td class="p-4 font-semibold text-stone-900">Long dry season</td><td class="p-4">Jun–Oct</td><td class="p-4">Game viewing, Mara crossings, Ngorongoro</td><td class="p-4">Higher demand and prices</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-900">Calving season</td><td class="p-4">Jan–Feb</td><td class="p-4">Southern Serengeti, young animals, predators</td><td class="p-4">Short rains in some areas</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-900">Short rains</td><td class="p-4">Nov–Dec</td><td class="p-4">Green scenery, fewer crowds</td><td class="p-4">Showers, festive-season demand</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-900">Long rains</td><td class="p-4">Mar–May</td><td class="p-4">Value, quiet parks, birding</td><td class="p-4">Muddy roads, some camp closures</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="py-14 bg-stone-50 border-y border-stone-200/80">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-3">Month-by-month guide</h2>
        <p class="text-stone-600 text-sm mb-6">Wildlife and weather follow patterns, not fixed dates. Confirm current conditions with your operator before booking.</p>
        <div class="overflow-x-auto rounded-2xl border border-stone-200 bg-white">
            <table class="w-full text-sm text-left min-w-[760px]">
                <thead class="bg-stone-100 text-stone-700 uppercase text-xs tracking-wider">
                    <tr><th class="p-4">Month</th><th class="p-4">What to expect</th><th class="p-4">Planning note</th></tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-stone-600">
                    <tr><td class="p-4 font-semibold text-stone-900">January</td><td class="p-4">Short rains taper; calving begins in the southern Serengeti/Ndutu area.</td><td class="p-4">Good for predators and young animals.</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-900">February</td><td class="p-4">Peak calving period in the south.</td><td class="p-4">One of the best months for Serengeti photography.</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-900">March</td><td class="p-4">Herds begin moving; long rains start.</td><td class="p-4">Quieter parks and lower demand.</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-900">April</td><td class="p-4">Wettest month in many areas.</td><td class="p-4">Best value, but some roads and camps are affected.</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-900">May</td><td class="p-4">Rains ease; landscapes remain green.</td><td class="p-4">Check camp openings and road access.</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-900">June</td><td class="p-4">Dry season begins; Wildlife is easier to spot.</td><td class="p-4">Excellent all-round choice before peak prices.</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-900">July</td><td class="p-4">Dry, busy, and migration herds move north.</td><td class="p-4">Book accommodation early.</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-900">August</td><td class="p-4">Peak dry season; Mara River crossing period.</td><td class="p-4">High demand and strong availability pressure.</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-900">September</td><td class="p-4">Dry and clear; crossings can continue.</td><td class="p-4">Very good game viewing with slightly lighter crowds.</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-900">October</td><td class="p-4">Dry season ends; animals gather near water.</td><td class="p-4">Good shoulder-month value.</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-900">November</td><td class="p-4">Short rains begin; greenery returns.</td><td class="p-4">Fewer visitors and birding improves.</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-900">December</td><td class="p-4">Short rains, festive travel period.</td><td class="p-4">Book early for holiday dates.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-6">Best time by place</h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach([
                ['Serengeti', 'Jan–Feb for calving in the south, Jul–Sep for northern Mara crossings, Jun–Oct for general game viewing.'],
                ['Ngorongoro Crater', 'Year-round resident wildlife; dry months give clearer viewing, green months are quieter.'],
                ['Tarangire', 'Jun–Oct is especially strong as animals gather around the river when water is scarce.'],
                ['Kilimanjaro', 'Jun–Oct and Dec–Feb are commonly preferred, but route choice and acclimatisation matter most.'],
                ['Zanzibar', 'Jun–Oct is dry and popular; Dec–Feb is warm; Mar–May is wetter and quieter.'],
                ['Budget travellers', 'Mar–May and Nov can offer better value, with weather and access trade-offs.'],
            ] as $place)
            <div class="p-6 rounded-2xl border border-stone-200 bg-stone-50">
                <h3 class="font-bold text-stone-900 mb-2">{{ $place[0] }}</h3>
                <p class="text-sm text-stone-600 leading-relaxed">{{ $place[1] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-16 bg-stone-50 border-t border-stone-200/80">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-5">Match the season to your itinerary</h2>
        <p class="text-stone-600 leading-relaxed mb-7">
            The right month depends on what you want to see and how much flexibility you have. Use these related guides to build the rest of the trip.
        </p>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('guides.serengeti') }}" class="px-5 py-3 rounded-full border border-stone-300 text-sm font-bold text-stone-700 hover:border-[#b8920d] hover:text-[#8A6D0B] transition-colors">Serengeti safari guide</a>
            <a href="{{ route('guides.ngorongoro') }}" class="px-5 py-3 rounded-full border border-stone-300 text-sm font-bold text-stone-700 hover:border-[#b8920d] hover:text-[#8A6D0B] transition-colors">Ngorongoro tour guide</a>
            <a href="{{ route('guides.safari-cost') }}" class="px-5 py-3 rounded-full border border-stone-300 text-sm font-bold text-stone-700 hover:border-[#b8920d] hover:text-[#8A6D0B] transition-colors">Tanzania safari costs</a>
            <a href="{{ route('tours.index') }}" class="px-5 py-3 rounded-full border border-stone-300 text-sm font-bold text-stone-700 hover:border-[#b8920d] hover:text-[#8A6D0B] transition-colors">Browse safari packages</a>
        </div>
    </div>
</section>

@include('public.guides.partials.faq')
@include('public.guides.partials.cta')
@endsection
