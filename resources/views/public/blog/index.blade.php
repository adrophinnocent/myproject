@extends('public.layouts.app')

@section('title', 'Twina Safaris Blog - Travel Tips & Stories')
@section('meta_description', 'Discover the latest news, travel tips, and safari stories from Tanzania with Twina Safaris.')

@section('content')
{{-- Page Header --}}
<div class="relative h-[60vh] min-h-[450px] flex items-center justify-center overflow-hidden bg-safari-dark">
    <div class="absolute inset-0">
        <img src="{{ \App\Helpers\AssetHelper::getBannerUrl('blog_banner') }}" class="w-full h-full object-cover opacity-60" alt="Blog Journal">
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-transparent to-transparent"></div>
    </div>
    <div class="relative z-10 max-w-5xl mx-auto px-4 text-center">
        <span class="inline-block text-gold-400 text-xs md:text-sm font-black uppercase tracking-[0.5em] mb-6">Safari Journal & Stories</span>
        <h1 class="font-display text-5xl md:text-7xl lg:text-8xl text-white font-bold mb-8 leading-tight drop-shadow-2xl">
            The Spirit <span class="italic text-gold-500 font-serif">of Africa</span>
        </h1>
        <p class="text-white/90 text-lg md:text-xl max-w-3xl mx-auto font-light leading-relaxed drop-shadow-md">
            Dive into a world of adventure where the wild meets luxury. Our journal brings you closer to the heart of Tanzania, sharing the secrets of the Serengeti, the spirit of the Maasai, and the magic of Zanzibar’s turquoise shores.
        </p>
    </div>
    <div class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-white to-transparent"></div>
</div>

<div class="max-w-7xl mx-auto px-4 pt-16">
    <div class="text-center mb-16">
        <div class="max-w-4xl mx-auto space-y-6">
            <h2 class="font-display text-3xl md:text-4xl font-black text-safari-dark uppercase tracking-tight">Voices of the Wild</h2>
            <p class="text-gray-600 text-lg leading-relaxed font-light">
                Discover inspiring safari stories, unforgettable travel experiences, breathtaking wildlife encounters, and hidden gems from across Tanzania and East Africa. Stay updated with expert travel tips, real guest adventures, and destination highlights that bring the spirit of Africa to life.
            </p>
            <p class="text-gray-700 text-lg font-medium italic border-l-4 border-gold-500 pl-8 inline-block text-left">
                From the vast plains of the <strong>Serengeti to the peaks of Mount Kilimanjaro and the beaches of Zanzibar</strong>, our blog shares real moments, practical guides, and stories that spark your next journey.
            </p>
        </div>
        <div class="w-24 h-1 bg-gold-500 mx-auto mt-12 rounded-full"></div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 pb-24">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        {{-- Posts Grid --}}
        <div class="lg:col-span-2 space-y-12">
            @forelse($posts as $post)
            <article class="flex flex-col md:flex-row gap-8 group">
                <a href="{{ route('blog.show', $post->slug) }}" class="w-full md:w-80 h-60 shrink-0 overflow-hidden rounded-2xl">
                    <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </a>
                <div class="flex flex-col justify-center">
                    <div class="flex items-center gap-3 text-gold-600 text-xs font-bold uppercase tracking-widest mb-3">
                        <span>{{ $post->category->name ?? 'Travel' }}</span>
                    </div>
                    <h2 class="font-display text-2xl font-bold text-gray-900 mb-4 hover:text-gold-600 transition-colors">
                        <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                    </h2>
                    <p class="text-gray-600 mb-6 line-clamp-3 leading-relaxed">
                        {{ $post->excerpt }}
                    </p>
                    <a href="{{ route('blog.show', $post->slug) }}" class="text-gold-600 font-bold text-sm flex items-center gap-2 hover:gap-3 transition-all">
                        Read Story <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </article>
            @empty
            <div class="text-center py-20 bg-gray-50 rounded-2xl border border-dashed border-gray-300">
                <p class="text-gray-500">We are currently writing new stories. Check back soon!</p>
            </div>
            @endforelse

            {{-- Pagination --}}
            <div class="pt-8">
                {{ $posts->links() }}
            </div>
        </div>

        {{-- Sidebar --}}
        <aside class="space-y-10">
            {{-- Categories --}}
            <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100">
                <h3 class="font-display text-xl font-bold text-gray-900 mb-6">Categories</h3>
                <div class="space-y-3">
                    @foreach($categories as $cat)
                    <a href="{{ route('blog.index', ['category' => $cat->slug]) }}" class="flex items-center justify-between text-gray-700 hover:text-gold-600 font-medium group">
                        <span>{{ $cat->name }}</span>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Newsletter --}}
            <div class="bg-safari-dark rounded-2xl p-8 text-white relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="font-display text-xl font-bold mb-4">Get the latest tips</h3>
                    <p class="text-white/60 text-sm mb-6">Subscribe to our newsletter and receive travel inspiration directly in your inbox.</p>
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="space-y-3">
                        @csrf
                        <input type="email" name="email" placeholder="Your email address" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-gold-500">
                        <button class="btn-gold w-full py-3 rounded-xl font-bold text-sm">Subscribe</button>
                    </form>
                </div>
                <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-gold-500/10 rounded-full blur-2xl"></div>
            </div>
        </aside>
    </div>
</div>
@endsection
