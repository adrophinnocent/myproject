@extends('public.layouts.app')

@section('title', 'Unsubscribed - Twina Safaris')
@section('meta_description', 'You have been unsubscribed from the Twina Safaris newsletter. You will no longer receive Tanzania safari news, travel guides or special offers by email.')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center px-4 py-20 bg-stone-50">
    <div class="max-w-lg w-full bg-white rounded-3xl shadow-lg border border-stone-100 p-10 text-center">
        <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-stone-100 flex items-center justify-center">
            <svg class="w-8 h-8 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </div>
        <h1 class="font-display text-3xl font-black text-stone-900 mb-3">{{ __('You have been unsubscribed') }}</h1>
        <p class="text-stone-600 mb-8">{{ __('Sorry to see you go! You will no longer receive newsletter emails from Twina Safaris. If this was a mistake, you can subscribe again anytime from our website.') }}</p>
        <a href="{{ route('home') }}" class="inline-block bg-[#D4AF37] hover:bg-[#b8920d] text-[#1a1209] font-bold px-8 py-3 rounded-xl transition-colors">{{ __('Back to Homepage') }}</a>
    </div>
</div>
@endsection
