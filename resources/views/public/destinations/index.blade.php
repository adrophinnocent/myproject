@extends('public.layouts.app')
@section('title', 'Our Destinations - Twina Safaris')
@section('meta_description', 'Explore the most beautiful destinations across Tanzania and East Africa with Twina Safaris.')

@section('content')
<div class="relative pt-32 pb-16 bg-safari-dark">
    <div class="absolute inset-0 bg-gradient-to-br from-slate-800 via-amber-900/20 to-slate-800"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 text-center">
        <span class="text-gold-400 text-sm uppercase tracking-widest font-semibold">Where We Go</span>
        <h1 class="font-display text-4xl md:text-6xl text-white font-bold mt-3">Our Destinations</h1>
        <p class="text-gray-300 max-w-2xl mx-auto mt-4">From the endless plains of the Serengeti to the turquoise waters of Zanzibar, discover the heart of Africa.</p>
    </div>
</div>

<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($destinations as $destination)
            <div class="group bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-2xl transition-all duration-500">
                <a href="{{ route('tours.index', ['destination' => $destination->id]) }}" class="block relative h-80 overflow-hidden">
                    <img src="{{ $destination->featured_image_url }}" alt="{{ $destination->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-80 group-hover:opacity-100 transition-opacity"></div>
                    <div class="absolute inset-0 flex flex-col justify-end p-8">
                        <span class="text-gold-400 text-xs font-bold uppercase tracking-widest mb-2">{{ $destination->country }}</span>
                        <h3 class="font-display text-3xl text-white font-bold mb-2">{{ $destination->name }}</h3>
                        <p class="text-gray-300 text-sm line-clamp-2 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500 opacity-0 group-hover:opacity-100">
                            {{ $destination->description }}
                        </p>
                    </div>
                </a>
                <div class="p-6 flex items-center justify-between border-t border-gray-50">
                    <span class="text-gray-500 text-sm font-medium">{{ $destination->tours_count }} Safari Packages</span>
                    <a href="{{ route('tours.index', ['destination' => $destination->id]) }}" class="text-gold-600 font-bold text-sm hover:text-gold-700 flex items-center gap-2 group/link">
                        Explore Tours
                        <svg class="w-4 h-4 group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="py-20 bg-safari-dark relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <img src="{{ asset('images/pattern.png') }}" class="w-full h-full object-cover">
    </div>
    <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
        <h2 class="font-display text-3xl md:text-5xl text-white font-bold mb-6">Can't decide where to go?</h2>
        <p class="text-gray-300 text-lg mb-10">Our safari experts are ready to help you craft the perfect multi-destination itinerary tailored to your dreams.</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('trip-plan.index') }}" class="btn-gold px-10 py-4 rounded-full font-bold">Plan Custom Safari</a>
            <a href="{{ route('contact.index') }}" class="px-10 py-4 rounded-full border-2 border-white/20 text-white font-bold hover:bg-white hover:text-safari-dark transition-all">Talk to an Expert</a>
        </div>
    </div>
</section>
@endsection
