@extends('public.layouts.app')

@section('title', '500 - Server Error | Twina Safaris')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
    <div class="max-w-xl w-full text-center">
        <div class="text-9xl font-black text-red-100 mb-8 select-none">500</div>
        <h1 class="font-display text-4xl md:text-5xl font-bold text-gray-900 mb-4">A Rough Patch on the Trail</h1>
        <p class="text-gray-500 text-lg mb-10 leading-relaxed">
            Our server encountered an unexpected obstacle. We've notified our technical scouts and are working to clear the path.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ url('/') }}" class="btn-gold px-8 py-4 rounded-full font-bold text-sm uppercase tracking-widest shadow-xl">
                Return to Basecamp
            </a>
            <a href="{{ route('contact.index') }}" class="btn-outline-gold px-8 py-4 rounded-full font-bold text-sm uppercase tracking-widest">
                Report this Issue
            </a>
        </div>
    </div>
</div>
@endsection
