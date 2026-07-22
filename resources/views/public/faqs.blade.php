@extends('public.layouts.app')

@section('title', 'Frequently Asked Questions')
@section('meta-description', 'Find answers to common questions about Twina Safaris, booking, travel, and safari experiences in Tanzania.')

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

<section class="py-20 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4">
        <div x-data="{ openFaq: null }">
            @forelse($faqs as $faq)
                <div class="mb-6 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <button
                        @click="openFaq = openFaq === {{ $loop->index }} ? null : {{ $loop->index }}"
                        class="w-full text-left px-6 py-5 flex items-center justify-between hover:bg-gray-50 transition-colors"
                    >
                        <span class="text-lg font-semibold text-safari-dark">{{ $faq->translate('question') }}</span>
                        <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gold-100 text-gold-600 transition-transform duration-200"
                             :class="openFaq === {{ $loop->index }} ? 'rotate-180' : ''">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </button>
                    <div
                        x-show="openFaq === {{ $loop->index }}"
                        x-transition:enter="transition-all duration-300 ease-out"
                        x-transition:enter-start="max-h-0 opacity-0"
                        x-transition:enter-end="max-h-96 opacity-100"
                        x-transition:leave="transition-all duration-200 ease-in"
                        x-transition:leave-start="max-h-96 opacity-100"
                        x-transition:leave-end="max-h-0 opacity-0"
                        class="overflow-hidden"
                    >
                        <div class="px-6 pb-5 text-gray-600 leading-relaxed">
                            {{ $faq->translate('answer') }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 bg-white rounded-xl border border-gray-200">
                    <p class="text-gray-500 text-lg">{{ __('No FAQs available yet. Check back soon!') }}</p>
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
