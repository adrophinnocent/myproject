@extends('public.layouts.app')
@section('title', 'Visual Journey - Twina Safaris Photo Gallery')
@section('meta_description', 'Experience the magic of Africa through our lens. Stunning visuals of safaris, peaks, and paradise.')

@section('content')
{{-- Enhanced Immersive Header --}}
<div class="relative min-h-[60vh] flex items-center justify-center bg-safari-dark overflow-hidden pt-20">
    <div class="absolute inset-0 z-0">
        <img src="{{ \App\Helpers\AssetHelper::getBannerUrl('gallery_banner') }}"
             width="1920" height="600"
             class="w-full h-full object-cover opacity-40" alt="Safari Background" loading="eager">
        <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-transparent to-safari-light"></div>
    </div>

    <div class="relative z-10 max-w-5xl mx-auto px-4 text-center">
        <span class="inline-block text-gold-400 text-xs md:text-sm font-black uppercase tracking-[0.5em] mb-6 animate-fade-in">Visual Chronicles</span>
        <h1 class="font-display text-5xl md:text-8xl text-white font-black leading-tight drop-shadow-2xl mb-8">
            The Spirit of <br> <span class="italic text-gold-500 font-serif">The Wild</span>
        </h1>
        <p class="text-white/80 text-lg md:text-xl max-w-2xl mx-auto font-light leading-relaxed drop-shadow-md">
            Every photo tells a story of adventure, breath-taking landscapes, and unforgettable encounters in the heart of Africa.
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-24">
    @if($albums->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
            @foreach($albums as $album)
                <a href="{{ route('gallery.show', $album->slug) }}" class="group block relative rounded-[2.5rem] overflow-hidden bg-white shadow-sm hover:shadow-2xl transition-all duration-700 hover:-translate-y-2">
                    {{-- Album Image Container --}}
                    <div class="relative h-[400px] overflow-hidden">
                        @php
                            $coverImg = $album->cover_url ?? ($album->images->first() ? $album->images->first()->image_url : null);
                        @endphp

                        @if($coverImg)
                            <img src="{{ $coverImg }}" width="500" height="400" alt="{{ $album->name }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" loading="lazy">
                        @else
                            <div class="w-full h-full bg-gray-100 flex items-center justify-center">🦁</div>
                        @endif

                        {{-- Overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-60 group-hover:opacity-80 transition-opacity"></div>

                        {{-- Album Meta --}}
                        <div class="absolute inset-0 p-8 flex flex-col justify-end transform transition-transform duration-500">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="px-4 py-1.5 bg-gold-500 text-safari-dark text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg">
                                    {{ $album->images->count() }} Frames
                                </span>
                            </div>
                            <h3 class="font-display text-3xl font-black text-white leading-tight group-hover:text-gold-400 transition-colors mb-2">
                                {{ $album->name }}
                            </h3>
                            <p class="text-white/70 text-sm font-medium line-clamp-2 opacity-0 group-hover:opacity-100 transition-all duration-500 transform translate-y-4 group-hover:translate-y-0">
                                {{ $album->description ?? 'Explore this collection of moments.' }}
                            </p>

                            <div class="mt-6 flex items-center gap-2 text-gold-400 font-bold text-xs uppercase tracking-widest group-hover:gap-4 transition-all">
                                Open Collection
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        @if($albums->hasPages())
            <div class="mt-20 flex justify-center">
                {{ $albums->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-32 bg-gray-50 rounded-[3rem] border-2 border-dashed border-gray-200">
            <div class="text-8xl mb-8 grayscale">🐘</div>
            <h3 class="font-display text-3xl font-black text-gray-800 mb-4">The Gallery is Preparing</h3>
            <p class="text-gray-500 max-w-md mx-auto">We are currently curating the most beautiful moments from recent safaris. Check back very soon.</p>
            <a href="{{ route('home') }}" class="btn-gold inline-block mt-10 px-10 py-4 rounded-full text-sm font-black uppercase tracking-widest">Back to Explore</a>
        </div>
    @endif
</div>

{{-- Call to Action --}}
<section class="bg-safari-dark py-24 mt-12 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <img src="https://images.unsplash.com/photo-1547471080-7cc2caa01a7e?q=80&w=2000&auto=format&fit=crop"
             width="1920" height="400"
             class="w-full h-full object-cover" loading="lazy">
    </div>
    <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
        <h2 class="font-display text-4xl text-white font-bold mb-6">Want to be in the next frame?</h2>
        <p class="text-gray-400 text-lg mb-10">Start planning your customized safari journey and capture your own unforgettable moments.</p>
        <a href="{{ route('trip-plan.index') }}" class="btn-gold px-12 py-5 rounded-full text-lg font-black shadow-2xl transition-all hover:scale-105">
            Plan My Trip Now
        </a>
    </div>
</section>

@endsection
