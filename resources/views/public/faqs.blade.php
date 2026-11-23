@extends('public.layouts.app')

@section('title', 'Tanzania Safari FAQs | Twina Safaris')
@section('meta_description', 'Find answers to common questions about Twina Safaris, booking, travel, and safari experiences in Tanzania.')

@php
    $faqSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $faqs->map(fn ($faq) => [
            '@type' => 'Question',
            'name' => $faq->question,
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => strip_tags((string) $faq->answer),
            ],
        ])->values()->all(),
    ];
@endphp

@section('schema')
@if($faqs->isNotEmpty())
<script type="application/ld+json">@json($faqSchema)</script>
@endif
@endsection

@section('content')
<section class="relative bg-safari-dark text-white overflow-hidden">
    @if($banner && $banner->image)
        <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->translate('title') }}" class="absolute inset-0 w-full h-full object-cover opacity-40">
    @endif
    <div class="absolute inset-0 bg-gradient-to-r from-safari-dark/90 to-safari-dark/60"></div>
    <div class="relative max-w-7xl mx-auto px-4 py-24">
        <div class="max-w-2xl">
            <span class="text-gold-400 text-sm font-semibold uppercase tracking-widest">{{ __('Help Center') }}</span>
            <h1 class="font-display text-4xl md:text-6xl font-bold mt-4 mb-6">{{ $banner ? $banner->translate('title') : __('Frequently Asked Questions') }}</h1>
            @if($banner && $banner->description)
                <p class="text-xl text-gray-300">{{ $banner->translate('description') }}</p>
            @endif
        </div>
    </div>
</section>

<section class="py-16 bg-stone-100/60">
    <div class="max-w-4xl mx-auto px-4">
        <h2 class="font-display text-3xl font-black text-stone-900 border-b-2 border-[#b8920d] pb-3 mb-6">
            {{ __('Frequently Asked Questions') }}
        </h2>
        <div class="space-y-3">
            @forelse($faqs as $index => $faq)
                <div x-data="{ open: false }" class="bg-white border border-[#e8dfcf] rounded-2xl overflow-hidden shadow-xs hover:border-[#b8920d]/60 transition-all">
                    <button type="button" @click="open = !open" class="w-full text-left p-5 md:p-6 font-bold text-stone-900 text-sm sm:text-base flex items-center justify-between cursor-pointer hover:bg-stone-50/50 transition-colors">
                        <span>{{ $faq->translate('question') }}</span>
                        <span class="text-[#8A6D0B] text-xl font-bold ml-2 shrink-0 transition-transform duration-200" :class="open ? 'rotate-45' : ''">+</span>
                    </button>
                    <div x-show="open" x-collapse x-cloak class="px-5 pb-5 pt-1 text-stone-600 text-xs sm:text-sm leading-relaxed border-t border-[#e8dfcf]/60 bg-[#fffdf9]">
                        {!! $faq->translate('answer') !!}
                    </div>
                </div>
            @empty
                <div class="text-center py-16 bg-white rounded-2xl border border-stone-200">
                    <p class="text-stone-500 text-sm">{{ __('No FAQs available yet. Check back soon!') }}</p>
                </div>
            @endforelse
        </div>

        <div class="mt-16 text-center p-8 bg-gradient-to-r from-gold-100 to-gold-50 rounded-xl border border-gold-200">
            <h3 class="font-display text-2xl text-safari-dark mb-3">{{ __('Still have questions?') }}</h3>
            <p class="text-gray-600 mb-6">{{ __("Can't find the answer you're looking for? Feel free to contact us directly.") }}</p>
            <a href="{{ route('contact.index') }}" class="inline-flex items-center gap-2 btn-gold px-6 py-3 rounded-full font-semibold">
                <span>{{ __('Contact Us') }}</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </div>
</section>
@endsection
