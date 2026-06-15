@extends('public.layouts.app')
@section('title', $album->name . ' - Photo Gallery')
@section('meta_description', $album->description ?? 'View photos from ' . $album->name)

@section('content')
<div class="relative pt-32 pb-12 bg-safari-dark">
    <div class="absolute inset-0">
        @if($album->cover_url)
            <img src="{{ $album->cover_url }}" alt="{{ $album->name }}" class="w-full h-full object-cover opacity-20">
        @elseif($album->images->first())
            <img src="{{ $album->images->first()->image_url }}" alt="{{ $album->name }}" class="w-full h-full object-cover opacity-20">
        @endif
    </div>
    <div class="relative z-10 max-w-7xl mx-auto px-4">
        <a href="{{ route('gallery.index') }}" class="inline-flex items-center gap-2 text-white/70 hover:text-white transition-colors mb-4">
            <span>←</span> Back to Gallery
        </a>
        <h1 class="font-display text-4xl md:text-5xl text-white font-bold">{{ $album->name }}</h1>
        @if($album->description)
            <p class="text-gray-300 mt-4 max-w-2xl">{{ $album->description }}</p>
        @endif
        <p class="text-gold-400 mt-2 font-semibold">{{ $album->images->count() }} Photos</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-12">
    @if($album->images->count() > 0)
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($album->images as $image)
                <div class="group relative aspect-square overflow-hidden rounded-xl bg-gray-100">
                    <img src="{{ $image->image_url }}" alt="{{ $image->caption ?? $album->name }}" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @if($image->caption)
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4 opacity-0 group-hover:opacity-100 transition-opacity">
                            <p class="text-white text-sm">{{ $image->caption }}</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-24 bg-gray-50 rounded-2xl">
            <div class="text-6xl mb-4">📷</div>
            <h3 class="font-display text-xl text-gray-700 mb-2">No Photos Yet</h3>
            <p class="text-gray-400 text-sm">This album is empty</p>
            <a href="{{ route('gallery.index') }}" class="btn-gold inline-block mt-6 px-6 py-3 rounded-full text-sm font-semibold">Back to Gallery</a>
        </div>
    @endif
</div>
@endsection
