@extends('public.layouts.app')

@section('title', '404 - Page Not Found | Twina Safaris')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
    <div class="max-w-xl w-full text-center">
        <div class="text-9xl font-black text-gold-200 mb-8 select-none">404</div>
        <h1 class="font-display text-4xl md:text-5xl font-bold text-gray-900 mb-4">Lost in the Wilderness?</h1>
        <p class="text-gray-500 text-lg mb-10 leading-relaxed">
            The page you are looking for has wandered off the trail. Don't worry, our guides can help you find your way back.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ url('/') }}" class="btn-gold px-8 py-4 rounded-full font-bold text-sm uppercase tracking-widest shadow-xl">
                Return to Basecamp
            </a>
            <a href="{{ route('tours.index') }}" class="btn-outline-gold px-8 py-4 rounded-full font-bold text-sm uppercase tracking-widest">
                Explore Our Tours
            </a>
        </div>
    </div>
</div>
@endsection
