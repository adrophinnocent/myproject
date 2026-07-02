@extends('public.layouts.app')
@section('title', $album->name . ' - Photo Gallery')

@section('content')
{{-- Extra CSS for Lightbox --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />

{{-- Hero Section --}}
<div class="relative pt-32 pb-20 bg-safari-dark">
    <div class="absolute inset-0 z-0">
        @php
            $cover = $album->cover_url ?? ($album->images->first() ? $album->images->first()->image_url : null);
        @endphp
        @if($cover)
            <img src="{{ $cover }}" alt="{{ $album->name }}" class="w-full h-full object-cover opacity-30">
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-safari-dark to-transparent"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 w-full text-center md:text-left">
        <a href="{{ route('gallery.index') }}" class="inline-flex items-center gap-2 text-gold-400 hover:text-white transition-colors mb-6 font-black uppercase tracking-widest text-[10px]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Collections
        </a>
        <h1 class="font-display text-4xl md:text-7xl text-white font-black leading-tight mb-4 tracking-tighter">{{ $album->name }}</h1>
        @if($album->description)
            <p class="text-gray-300 max-w-3xl text-lg leading-relaxed font-light">{{ $album->description }}</p>
        @endif
    </div>
</div>

{{-- Gallery Grid --}}
<div class="max-w-7xl mx-auto px-4 py-24 bg-white min-h-[500px]">
    @php $allImages = $album->images; @endphp

    @if($allImages->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-10">
            @foreach($allImages as $image)
                <div class="space-y-4">
                    <a href="{{ $image->image_url }}"
                       class="glightbox group block relative rounded-[2.5rem] overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-700 bg-gray-50 border border-gray-100"
                       data-gallery="gallery-{{ $album->id }}"
                       data-title="{{ $image->caption ?? '' }}">

                        <div class="relative pb-[125%] w-full h-0">
                            <img src="{{ $image->image_url }}"
                                 alt="{{ $image->caption ?? $album->name }}"
                                 class="absolute inset-0 w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110"
                                 loading="lazy">
                        </div>

                        {{-- Hover Indicator --}}
                        <div class="absolute inset-0 bg-safari-dark/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <div class="w-14 h-14 rounded-full bg-gold-500 flex items-center justify-center text-safari-dark shadow-2xl transform scale-50 group-hover:scale-100 transition-transform duration-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                        </div>
                    </a>
                    @if($image->caption)
                        <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest text-center px-4 leading-relaxed">{{ $image->caption }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-40 bg-gray-50 rounded-[4rem] border-2 border-dashed border-gray-200">
            <div class="text-9xl mb-10 grayscale opacity-20">📸</div>
            <h3 class="font-display text-3xl font-black text-gray-800">This collection is coming soon</h3>
            <p class="text-gray-500 mt-4 max-w-md mx-auto">Our photographers are currently uploading high-resolution frames to this album. Please check back in a few moments.</p>
            <a href="{{ route('gallery.index') }}" class="btn-gold inline-block mt-12 px-10 py-5 rounded-full text-xs font-black uppercase tracking-widest shadow-xl">Explore Other Collections</a>
        </div>
    @endif
</div>

{{-- Footer CTA --}}
<div class="bg-safari-dark py-20">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h3 class="font-display text-3xl text-white font-bold mb-4">Capture your own story</h3>
        <p class="text-gray-400 mb-10">Experience the magic of Tanzania and become part of our visual journey.</p>
        <a href="{{ route('trip-plan.index') }}" class="btn-gold px-12 py-5 rounded-full text-sm font-black uppercase tracking-widest">Plan My Safari</a>
    </div>
</div>

{{-- Lightbox JS --}}
<script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const lightbox = GLightbox({
            selector: '.glightbox',
            touchNavigation: true,
            loop: true,
            zoomable: true,
            openEffect: 'zoom',
            closeEffect: 'fade'
        });
    });
</script>
@endsection
