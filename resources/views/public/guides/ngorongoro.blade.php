@extends('public.layouts.app')

@section('title', 'Ngorongoro Crater Tours | Twina Safaris')
@section('meta_description', 'Plan an Ngorongoro Crater tour: how long to spend, when to go, what is included, crater rules and how to combine it with Serengeti or Tarangire safaris.')

@php
    $pageTitle = 'Ngorongoro Crater Tours & Safari Guide';
    $pagePath = '/ngorongoro-tour';
    $faqs = [
        [
            'question' => 'How long should I spend on an Ngorongoro tour?',
            'answer' => 'A full day on the crater floor is the standard visit. Many travellers sleep on the crater rim the night before to avoid an early drive from Arusha or Moshi, then continue to Serengeti or Tarangire.',
        ],
        [
            'question' => 'Is Ngorongoro a national park?',
            'answer' => 'No. The Ngorongoro Crater is inside the Ngorongoro Conservation Area, which is managed differently from a national park. Rules, fees and accommodation options differ, so check current authority guidance.',
        ],
        [
            'question' => 'What wildlife can I expect in the crater?',
            'answer' => 'The crater supports resident wildlife including elephants, buffalo, zebras, wildebeest, hippos and predators. Sightings are never guaranteed, but the enclosed landscape makes for concentrated game viewing.',
        ],
        [
            'question' => 'Can vehicles drive anywhere in the crater?',
            'answer' => 'No. Driving is restricted to designated tracks, and time on the crater floor is limited by conservation rules. Your guide should follow those rules rather than chase sightings off-road.',
        ],
        [
            'question' => 'Are Ngorongoro fees included in the tour price?',
            'answer' => 'It varies by itinerary. Conservation and crater fees are a significant part of the cost, so check the inclusion list and ask for current rates before comparing quotes.',
        ],
        [
            'question' => 'Can I visit Ngorongoro and Serengeti in one trip?',
            'answer' => 'Yes. Three to five days is a common combination, often starting from Arusha or Moshi. The best order depends on road conditions, your start point and the season.',
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
    'intro' => 'An Ngorongoro tour is one of the most concentrated wildlife experiences in Tanzania. The key decisions are how long to stay, where to sleep the night before and how to combine the crater with nearby parks.',
])

<section class="py-14 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-4">Choose the right length</h2>
        <div class="overflow-x-auto rounded-2xl border border-stone-200 bg-white">
            <table class="w-full text-sm text-left min-w-[680px]">
                <thead class="bg-stone-100 text-stone-700 uppercase text-xs tracking-wider">
                    <tr><th class="p-4">Option</th><th class="p-4">Who it suits</th><th class="p-4">What to know</th></tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-stone-600">
                    <tr><td class="p-4 font-semibold text-stone-900">Day trip from Arusha or Moshi</td><td class="p-4">Travellers short on time</td><td class="p-4">Early start and a long return drive; less time at the crater rim.</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-900">Overnight on the rim</td><td class="p-4">Most first-time visitors</td><td class="p-4">Allows an earlier crater descent and a more relaxed pace.</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-900">Ngorongoro + Serengeti</td><td class="p-4">Classic first safari</td><td class="p-4">Usually three to five days, depending on how much of Serengeti you cover.</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-900">Ngorongoro + Tarangire</td><td class="p-4">Shorter northern circuit trip</td><td class="p-4">Good when you want variety without the longest drives.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="py-14 bg-stone-50 border-y border-stone-200/80">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-6">Rules and practicalities</h2>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach([
                ['Designated tracks', 'Vehicles must stay on approved routes inside the crater. Ask your guide how the route is planned for the day.'],
                ['Limited floor time', 'Time on the crater floor is controlled by conservation rules. A rushed day trip gives you less time than an overnight stay.'],
                ['Fees vary', 'Conservation and crater fees are set by the authority and change. Confirm the current figure and whether it is included.'],
                ['Weather shifts quickly', 'The highlands can be cool and misty even when the plains are hot. Bring layers.'],
                ['Accommodation location', 'Rim, crater-side and nearby village stays offer different access and price levels.'],
                ['No off-road chasing', 'Responsible guides do not drive off-track to force a sighting.'],
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
        <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-5">When to visit</h2>
        <p class="text-stone-600 leading-relaxed mb-6">
            Wildlife is resident in the crater throughout the year, so there is no bad month for Ngorongoro. The dry season from June to October usually gives clearer viewing, while the green season is quieter and can be better value. Road and crater access rules can change, so confirm them close to your travel date.
        </p>
        <a href="{{ route('guides.best-time') }}" class="text-sm font-bold text-[#8A6D0B] hover:text-gold-600 inline-flex items-center gap-1">Read the month-by-month safari guide <span aria-hidden="true">→</span></a>
    </div>
</section>

<section class="py-16 bg-stone-50 border-t border-stone-200/80">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-5">Plan the full northern circuit</h2>
        <p class="text-stone-600 leading-relaxed mb-7">
            Ngorongoro works well as a standalone day, but it is even better when paired with Serengeti, Tarangire or a Kilimanjaro trek. Compare routes with published prices and clear inclusions.
        </p>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('guides.serengeti') }}" class="px-5 py-3 rounded-full border border-stone-300 text-sm font-bold text-stone-700 hover:border-[#b8920d] hover:text-[#8A6D0B] transition-colors">Serengeti safari guide</a>
            <a href="{{ route('guides.kilimanjaro') }}" class="px-5 py-3 rounded-full border border-stone-300 text-sm font-bold text-stone-700 hover:border-[#b8920d] hover:text-[#8A6D0B] transition-colors">Kilimanjaro trekking guide</a>
            <a href="{{ route('guides.safari-cost') }}" class="px-5 py-3 rounded-full border border-stone-300 text-sm font-bold text-stone-700 hover:border-[#b8920d] hover:text-[#8A6D0B] transition-colors">Safari cost guide</a>
            <a href="{{ route('tours.index', ['destination' => 'ngorongoro']) }}" class="px-5 py-3 rounded-full border border-stone-300 text-sm font-bold text-stone-700 hover:border-[#b8920d] hover:text-[#8A6D0B] transition-colors">See Ngorongoro tours</a>
        </div>
    </div>
</section>

@include('public.guides.partials.faq')
@include('public.guides.partials.cta')
@endsection
