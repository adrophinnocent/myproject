@extends('public.layouts.app')
@section('title', 'Photo Gallery - Tanzania & East Africa')
@section('meta_description', 'Browse our stunning photo gallery of Tanzania safaris, Kilimanjaro climbs, Zanzibar beaches and East African adventures.')

@section('content')
<div class="relative pt-32 pb-16 bg-safari-dark min-h-[40vh] flex items-center">
    @if($banner = \App\Models\Setting::get('gallery_banner'))
        <img src="{{ asset('storage/' . $banner) }}" alt="Gallery" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/50"></div>
    @else
        <div class="absolute inset-0 bg-gradient-to-br from-purple-900 via-slate-900 to-slate-900"></div>
    @endif
    <div class="relative z-10 max-w-7xl mx-auto px-4 text-center w-full">
        <span class="text-gold-400 text-sm uppercase tracking-widest font-semibold">Visual Journey</span>
        <h1 class="font-display text-4xl md:text-6xl text-white font-bold mt-3">Photo Gallery</h1>
        <p class="text-gray-300 max-w-2xl mx-auto mt-4">Explore our collection of unforgettable moments captured across East Africa</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-16">
    @if($albums->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($albums as $album)
                <a href="{{ route('gallery.show', $album->slug) }}" class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl transition-all overflow-hidden">
                    <div class="relative h-56 overflow-hidden">
                        @if($album->cover_url)
                            <img src="{{ $album->cover_url }}" alt="{{ $album->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @elseif($album->images->first())
                            <img src="{{ $album->images->first()->image_url }}" alt="{{ $album->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                <span class="text-4xl">🖼️</span>
                            </div>
                        @endif
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4">
                            <span class="text-white/90 text-sm">{{ $album->images->count() }} Photos</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-display text-xl font-semibold text-gray-900 mb-2 group-hover:text-gold-600 transition-colors">{{ $album->name }}</h3>
                        @if($album->description)
                            <p class="text-gray-500 text-sm line-clamp-2">{{ $album->description }}</p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>

        @if($albums->hasPages())
            <div class="mt-12">
                {{ $albums->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-24 bg-gray-50 rounded-2xl">
            <div class="text-6xl mb-4">📸</div>
            <h3 class="font-display text-xl text-gray-700 mb-2">No Albums Yet</h3>
            <p class="text-gray-400 text-sm">Check back soon for our photo gallery!</p>
        </div>
    @endif
</div>
@endsection
