@extends('public.layouts.app')

@section('title', 'Serengeti Safari Packages | Routes, Seasons & Costs | Twina Safaris')
@section('meta_description', 'Plan a Serengeti safari with private 4x4 routes, migration-season advice, lodge or camp options and clear itinerary prices from Twina Safaris.')

@php
    $pageTitle = 'Serengeti Safari: Routes, Seasons and What to Expect';
    $pagePath = '/serengeti-safari';
    $faqs = [
        [
            'question' => 'How many days do I need for a Serengeti safari?',
            'answer' => 'Two days gives you a taste, three to four days is a common first safari length, and five or more days lets you cover more of the ecosystem at a slower pace. Add travel time from Arusha or Moshi.',
        ],
        [
            'question' => 'Where should I stay for the Great Migration?',
            'answer' => 'It depends on the month. The south and Ndutu are important around January to March, the west and central areas around May to July, and the northern Serengeti around July to September. Your operator should route the itinerary around your travel dates.',
        ],
        [
            'question' => 'Can I see the Mara River crossing every year?',
            'answer' => 'No. River crossings depend on where the herds are and how conditions unfold. July to September is the common window, but no operator can guarantee a crossing on a specific date.',
        ],
        [
            'question' => 'Is a private Serengeti safari worth it?',
            'answer' => 'A private safari gives you control over departure times, stops and accommodation. It usually costs more per person than a shared vehicle, especially for two travellers.',
        ],
        [
            'question' => 'Can I combine Serengeti with Ngorongoro?',
            'answer' => 'Yes. A common route combines Serengeti and Ngorongoro Crater over three to five days, sometimes with Tarangire or Lake Manyara added. The best order depends on road access and your start point.',
        ],
        [
            'question' => 'Are park fees included in Serengeti safari prices?',
            'answer' => 'It varies by itinerary. Always check the inclusion list and ask for the current fee structure before comparing prices.',
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
    'intro' => 'A great Serengeti safari is planned around the season, the area you want to explore and the time you have. Use this guide to choose your route, then compare published itineraries with the inclusions written down.',
])

<section class="py-14 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-4">Start with the season, not the lodge</h2>
        <p class="text-stone-600 leading-relaxed mb-7">
            The Serengeti is large, and wildlife moves. The same itinerary can feel very different in February and September. Match your dates to the part of the ecosystem you want to visit before choosing accommodation.
        </p>
        <div class="overflow-x-auto rounded-2xl border border-stone-200 bg-white">
            <table class="w-full text-sm text-left min-w-[680px]">
                <thead class="bg-stone-100 text-stone-700 uppercase text-xs tracking-wider">
                    <tr><th class="p-4">Months</th><th class="p-4">Area to focus on</th><th class="p-4">Typical reason</th></tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-stone-600">
                    <tr><td class="p-4 font-semibold text-stone-900">Jan–Mar</td><td class="p-4">Southern Serengeti / Ndutu</td><td class="p-4">Calving season and predator activity</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-900">Apr–May</td><td class="p-4">Central and western corridors</td><td class="p-4">Green season, quieter parks, check road access</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-900">Jun–Jul</td><td class="p-4">Western Serengeti / Grumeti</td><td class="p-4">Herd movement and early dry-season viewing</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-900">Jul–Sep</td><td class="p-4">Northern Serengeti / Mara</td><td class="p-4">Mara River crossing season</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-900">Oct–Nov</td><td class="p-4">North and central areas</td><td class="p-4">Dry-season end, then short rains and greenery</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-900">Dec</td><td class="p-4">Southern movement begins</td><td class="p-4">Festive-period demand; book early</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="py-14 bg-stone-50 border-y border-stone-200/80">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-6">How many days to allow</h2>
        <div class="grid md:grid-cols-3 gap-5">
            @foreach([
                ['2 days', 'A short introduction. Best combined with Ngorongoro or Tarangire. Expect a lot of driving.'],
                ['3–4 days', 'The most common choice for a first Serengeti safari. Gives time to explore more than one area.'],
                ['5+ days', 'A slower pace, more area covered and better chances of matching the season to the herds.'],
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
        <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-5">What to check in a Serengeti itinerary</h2>
        <ul class="grid sm:grid-cols-2 gap-3 text-sm text-stone-600">
            @foreach([
                'Which Serengeti gate and areas the route uses',
                'Whether the vehicle is private or shared',
                'Named lodges or camps, not only accommodation category',
                'Whether park fees are included in the price',
                'How many game drives are scheduled each day',
                'Whether internal flights or long road transfers are used',
                'What happens if seasonal roads are closed',
                'How much free time you have between activities',
            ] as $item)
            <li class="p-4 rounded-xl bg-stone-50 border border-stone-200/80">{{ $item }}</li>
            @endforeach
        </ul>
    </div>
</section>

<section class="py-16 bg-stone-50 border-t border-stone-200/80">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-5">Build the rest of the trip</h2>
        <p class="text-stone-600 leading-relaxed mb-7">
            Serengeti is often combined with Ngorongoro Crater, Tarangire, Kilimanjaro trekking or Zanzibar beach time. Start with the season guide, then compare real routes and prices.
        </p>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('guides.best-time') }}" class="px-5 py-3 rounded-full border border-stone-300 text-sm font-bold text-stone-700 hover:border-[#b8920d] hover:text-[#8A6D0B] transition-colors">Best time guide</a>
            <a href="{{ route('guides.ngorongoro') }}" class="px-5 py-3 rounded-full border border-stone-300 text-sm font-bold text-stone-700 hover:border-[#b8920d] hover:text-[#8A6D0B] transition-colors">Ngorongoro tour guide</a>
            <a href="{{ route('guides.safari-cost') }}" class="px-5 py-3 rounded-full border border-stone-300 text-sm font-bold text-stone-700 hover:border-[#b8920d] hover:text-[#8A6D0B] transition-colors">Safari cost guide</a>
            <a href="{{ route('tours.index', ['destination' => 'serengeti']) }}" class="px-5 py-3 rounded-full border border-stone-300 text-sm font-bold text-stone-700 hover:border-[#b8920d] hover:text-[#8A6D0B] transition-colors">See Serengeti packages</a>
        </div>
    </div>
</section>

@include('public.guides.partials.faq')
@include('public.guides.partials.cta')
@endsection
