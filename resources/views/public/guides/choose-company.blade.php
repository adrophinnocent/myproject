@extends('public.layouts.app')

@section('title', 'How to Choose a Safari Company in Tanzania | Twina Safaris')
@section('meta_description', 'Searching for the best tour company in Tanzania? Use this 12-point checklist to compare licences, guides, vehicles, reviews and quote details before you pay.')

@php
    $pageTitle = 'How to Choose a Tanzania Safari Company';
    $pagePath = '/choose-safari-company';
    $faqs = [
        [
            'question' => 'How do I know if a Tanzania tour company is legitimate?',
            'answer' => 'Ask for the company registration or tourism licence number, a physical office address, and a written contract. A legitimate operator will explain the permit and park-fee process instead of asking you to trust a headline price.',
        ],
        [
            'question' => 'Are online reviews enough to choose a safari company?',
            'answer' => 'Reviews are useful, but read them alongside the details of the itinerary. Check whether reviewers name the guide, the vehicle, the accommodation and the parks. Recent, specific reviews are more helpful than a bare rating.',
        ],
        [
            'question' => 'Why do two safari quotes for the same parks have different prices?',
            'answer' => 'Prices change with season, group size, accommodation level, vehicle type, route length and whether park or conservation fees are included. Always compare the inclusions line by line.',
        ],
        [
            'question' => 'Should I book a private or shared Tanzania safari?',
            'answer' => 'A private safari gives you control over pace, stops and accommodation. A shared safari can cost less. Choose based on how much flexibility you want, not only on the lowest price.',
        ],
        [
            'question' => 'What should a good safari quote include in writing?',
            'answer' => 'It should list the route and parks, number of days and nights, accommodation or camping style, meals, vehicle and guide, park fees where included, transfers, exclusions, payment schedule and cancellation terms.',
        ],
        [
            'question' => 'Can I combine Kilimanjaro, Serengeti and Zanzibar in one trip?',
            'answer' => 'Yes, but the order matters. Many travellers trek first, recover on safari, then fly to Zanzibar. Ask the operator to check flight days, rest days and park entry times before confirming.' ,
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
    'intro' => 'If you are searching for the best tour company in Tanzania, do not choose from adjectives alone. Choose the operator that can show you proof, explain the itinerary clearly and put every inclusion in writing.',
])

<section class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <div class="grid lg:grid-cols-3 gap-12">
            <div class="lg:col-span-2">
                <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-4">
                    Short answer: look for proof, not promises
                </h2>
                <p class="text-stone-600 leading-relaxed mb-10">
                    A good Tanzania safari company should be able to tell you who will guide you, what vehicle you will travel in, where you will sleep, what is included in the price and what happens if plans change. If those answers are vague, keep comparing.
                </p>

                <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-6">
                    12 checks before you pay a deposit
                </h2>
                <ol class="grid sm:grid-cols-2 gap-4 list-none">
                    @foreach([
                        'Ask for the company licence or registration details.',
                        'Confirm a physical office address and a reachable phone number.',
                        'Ask who your guide will be and whether the guide is employed by the operator.',
                        'Find out what type of vehicle is used and whether it is private or shared.',
                        'Request the full route, including park names and driving times.',
                        'Check whether park and conservation fees are included.',
                        'Compare accommodation by name or category, not just "luxury" or "standard".',
                        'Ask what meals and drinking water are provided.',
                        'Read the cancellation and refund terms before paying.',
                        'Ask how the operator handles medical or weather emergencies.',
                        'Check recent reviews for specific details, not only a star rating.',
                        'Make sure every agreed item appears in the written itinerary.',
                    ] as $index => $check)
                    <li class="flex gap-4 p-4 rounded-2xl bg-stone-50 border border-stone-200/80">
                        <span class="w-8 h-8 rounded-full bg-[#b8920d] text-white flex items-center justify-center text-xs font-bold shrink-0">{{ $index + 1 }}</span>
                        <span class="text-sm text-stone-700 leading-relaxed">{{ $check }}</span>
                    </li>
                    @endforeach
                </ol>
            </div>

            <aside class="space-y-6">
                <div class="p-6 rounded-2xl bg-[#fffdf8] border border-[#e8dfcf]">
                    <h2 class="font-display text-xl font-bold text-stone-900 mb-4">Red flags</h2>
                    <ul class="space-y-3 text-sm text-stone-600">
                        <li class="flex gap-2"><span aria-hidden="true">•</span><span>A price far below comparable itineraries with no explanation.</span></li>
                        <li class="flex gap-2"><span aria-hidden="true">•</span><span>Refusal to provide licence details or a written contract.</span></li>
                        <li class="flex gap-2"><span aria-hidden="true">•</span><span>Guarantees about wildlife sightings.</span></li>
                        <li class="flex gap-2"><span aria-hidden="true">•</span><span>No clear answer on what happens if a road or flight is disrupted.</span></li>
                    </ul>
                </div>

                <div class="p-6 rounded-2xl bg-safari-dark text-white">
                    <h2 class="font-display text-xl font-bold mb-3">Compare real itineraries</h2>
                    <p class="text-stone-300 text-sm leading-relaxed mb-5">
                        Once you know what to ask, browse published routes and prices and compare the inclusions line by line.
                    </p>
                    <a href="{{ route('tours.index') }}" class="text-gold-400 text-sm font-bold hover:text-gold-300 inline-flex items-center gap-1">
                        View Tanzania safari packages <span aria-hidden="true">→</span>
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>

<section class="py-16 bg-stone-50 border-y border-stone-200/80">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-6">What a clear quote should show</h2>
        <div class="overflow-x-auto bg-white rounded-2xl border border-stone-200">
            <table class="w-full text-sm text-left">
                <thead class="bg-stone-100 text-stone-700 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="p-4">Should be written down</th>
                        <th class="p-4">Why it matters</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 text-stone-600">
                    <tr><td class="p-4 font-semibold text-stone-800">Route and park names</td><td class="p-4">Stops, driving time and seasonal road conditions affect the experience.</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-800">Accommodation level</td><td class="p-4">"Lodge", "tented camp" and "basic camp" are very different products.</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-800">Fees and permits</td><td class="p-4">Park, crater or trekking permits are a large part of the real cost.</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-800">Guide and vehicle</td><td class="p-4">You should know whether you have a private guide and vehicle.</td></tr>
                    <tr><td class="p-4 font-semibold text-stone-800">Payment and cancellation terms</td><td class="p-4">These protect both you and the operator if plans change.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-5">When we are not the right choice</h2>
        <p class="text-stone-600 leading-relaxed mb-6">
            Twina Safaris is a locally based operator arranging tailor-made itineraries. We may not be the right fit if you want the cheapest option regardless of inclusions, need a confirmed itinerary without giving travel dates, or expect anyone to guarantee wildlife sightings. Being clear about that is better than promising the impossible.
        </p>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('guides.safari-cost') }}" class="px-5 py-3 rounded-full border border-stone-300 text-sm font-bold text-stone-700 hover:border-[#b8920d] hover:text-[#8A6D0B] transition-colors">Understand safari costs</a>
            <a href="{{ route('guides.best-time') }}" class="px-5 py-3 rounded-full border border-stone-300 text-sm font-bold text-stone-700 hover:border-[#b8920d] hover:text-[#8A6D0B] transition-colors">Choose the right season</a>
            <a href="{{ route('custom-safari.create') }}" class="px-5 py-3 rounded-full border border-stone-300 text-sm font-bold text-stone-700 hover:border-[#b8920d] hover:text-[#8A6D0B] transition-colors">Build a custom safari</a>
        </div>
    </div>
</section>

@include('public.guides.partials.faq')
@include('public.guides.partials.cta')
@endsection
