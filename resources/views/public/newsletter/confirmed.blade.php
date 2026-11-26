@extends('public.layouts.app')

@section('title', 'Subscription Confirmed - Twina Safaris')
@section('meta_description', 'Your Twina Safaris newsletter subscription is confirmed. Get Tanzania safari ideas, seasonal wildlife tips, Kilimanjaro guides and Zanzibar travel advice.')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center px-4 py-20 bg-stone-50">
    <div class="max-w-lg w-full bg-white rounded-3xl shadow-lg border border-stone-100 p-10 text-center">
        <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-emerald-100 flex items-center justify-center">
            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <h1 class="font-display text-3xl font-black text-stone-900 mb-3">{{ __('You are subscribed!') }}</h1>
        <p class="text-stone-600 mb-8">{{ __('Asante :name! Your email has been confirmed. You will now receive safari news, travel tips and exclusive offers from Twina Safaris.', ['name' => $subscriber->name]) }}</p>
        <a href="{{ route('tours.index') }}" class="inline-block bg-[#D4AF37] hover:bg-[#b8920d] text-[#1a1209] font-bold px-8 py-3 rounded-xl transition-colors">{{ __('Explore Our Tours') }}</a>
        <p class="text-xs text-stone-400 mt-8">{{ __('You can unsubscribe at any time using the link in any of our emails.') }}</p>
    </div>
</div>
@endsection
