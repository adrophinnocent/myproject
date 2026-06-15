@extends('public.layouts.app')

@section('title', 'Twina Safaris Blog - Travel Tips & Stories')
@section('meta_description', 'Discover the latest news, travel tips, and safari stories from Tanzania with Twina Safaris.')

@section('content')
{{-- Page Header --}}
<div class="relative h-[40vh] min-h-80 flex items-center">
    <div class="absolute inset-0">
        @if($banner = \App\Models\Setting::get('blog_banner'))
            <img src="{{ asset('storage/' . $banner) }}" class="w-full h-full object-cover" alt="Blog">
        @else
            <img src="{{ asset('images/blog-banner.jpg') }}" class="w-full h-full object-cover" alt="Blog">
        @endif
        <div class="absolute inset-0 bg-black/50"></div>
    </div>
    <div class="absolute inset-0 flex items-center">
        <div class="max-w-7xl mx-auto px-4 w-full text-center">
            <h1 class="font-display text-4xl md:text-6xl text-white font-bold mb-4">Twina Safaris Blog</h1>
            <p class="text-white/80 max-w-2xl mx-auto">Insights, stories, and tips for your next African adventure.</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-16">
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
                        <span class="text-gray-300">•</span>
                        <span class="text-gray-500">{{ $post->published_at->format('M d, Y') }}</span>
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
                        <span class="bg-white px-2 py-0.5 rounded-lg text-xs border border-gray-200 group-hover:border-gold-200 transition-colors">{{ $cat->posts_count }}</span>
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
