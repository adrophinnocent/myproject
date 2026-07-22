@extends('public.layouts.app')

@section('title', $post->meta_title ?? $post->title . ' - Twina Safaris Blog')
@section('meta_description', $post->meta_description ?? $post->excerpt)

@section('content')
<article class="bg-white">
    {{-- Post Hero --}}
    <header class="relative h-[60vh] min-h-[400px]">
        <img src="{{ $post->featured_image_url }}" alt="{{ $post->translate('title') }}" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
        <div class="absolute inset-0 flex items-end">
            <div class="max-w-4xl mx-auto px-4 pb-12 w-full">
                <div class="flex items-center gap-3 text-gold-400 text-xs font-bold uppercase tracking-widest mb-6">
                    <a href="{{ route('blog.index', ['category' => $post->category->slug ?? '']) }}" class="hover:text-white transition-colors">
                        {{ __($post->category->name ?? 'Safari Journal') }}
                    </a>
                    <span class="text-white/30">•</span>
                    <span class="text-white/80">{{ $post->reading_time }}</span>
                </div>
                <h1 class="font-display text-4xl md:text-6xl text-white font-bold leading-tight drop-shadow-lg">{{ $post->translate('title') }}</h1>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 py-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
            {{-- Content --}}
            <div class="lg:col-span-8">
                <div class="prose prose-lg prose-gold max-w-none text-gray-700 leading-relaxed
                            prose-headings:font-display prose-headings:font-bold prose-headings:text-gray-900
                            prose-p:mb-6 prose-img:rounded-3xl prose-img:shadow-xl">
                    {!! $post->translate('content') !!}
                </div>

                {{-- Author & Share --}}
                <div class="mt-16 pt-10 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-8">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-gold-100 flex items-center justify-center text-gold-600 font-bold font-display text-lg">
                            {{ substr($post->author->name ?? 'T', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">{{ __('Published By') }}</p>
                            <p class="font-bold text-gray-900">{{ $post->author->name ?? __('Twina Safaris') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <span class="text-xs text-gray-400 font-bold uppercase tracking-widest">{{ __('Share story') }}:</span>
                        <div class="flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" class="w-10 h-10 rounded-full border border-gray-100 flex items-center justify-center text-gray-400 hover:text-white hover:bg-[#1877F2] hover:border-[#1877F2] transition-all">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12.073-12-12.073s-12 5.446-12 12.073c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($post->translate('title')) }}" target="_blank" class="w-10 h-10 rounded-full border border-gray-100 flex items-center justify-center text-gray-400 hover:text-white hover:bg-black hover:border-black transition-all">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.25h-6.657l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <aside class="lg:col-span-4 space-y-12">
                {{-- Book a Safari --}}
                <div class="bg-safari-dark rounded-3xl p-8 text-white text-center relative overflow-hidden shadow-2xl">
                    <div class="relative z-10">
                        <h3 class="font-display text-2xl font-bold mb-4">{{ __('Inspired by this story?') }}</h3>
                        <p class="text-white/60 mb-8 leading-relaxed">{{ __('Let us take you to the places mentioned in this article with a custom-tailored safari experience.') }}</p>
                        <a href="{{ route('trip-plan.index') }}" class="btn-gold w-full block py-4 rounded-xl font-bold">{{ __('Start Planning') }}</a>
                    </div>
                    <div class="absolute -top-10 -left-10 w-40 h-40 bg-gold-500/10 rounded-full blur-3xl"></div>
                </div>

                {{-- Related Posts --}}
                @if($relatedPosts->count() > 0)
                <div>
                    <h3 class="font-display text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                        {{ __('More Stories') }}
                        <div class="h-px flex-1 bg-gray-100"></div>
                    </h3>
                    <div class="space-y-6">
                        @foreach($relatedPosts as $rel)
                        <a href="{{ route('blog.show', $rel->slug) }}" class="flex gap-4 group">
                            <div class="w-24 h-20 shrink-0 rounded-xl overflow-hidden">
                                <img src="{{ $rel->featured_image_url }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $rel->translate('title') }}">
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-sm leading-snug group-hover:text-gold-600 transition-colors line-clamp-2">{{ $rel->translate('title') }}</h4>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </aside>
        </div>
    </div>
</article>

{{-- Newsletter Section --}}
<section class="bg-gray-50 py-20">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="font-display text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ __('Stay updated with wild stories') }}</h2>
        <p class="text-gray-600 mb-10 max-w-xl mx-auto">{{ __('Join our community of travelers and receive expert tips and inspiration for your next Tanzanian journey.') }}</p>
        <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex flex-col sm:flex-row gap-3 max-w-lg mx-auto">
            @csrf
            <input type="email" name="email" placeholder="{{ __('Enter your email') }}" class="flex-1 bg-white border border-gray-200 rounded-xl px-6 py-4 focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
            <button class="btn-gold px-8 py-4 rounded-xl font-bold whitespace-nowrap">{{ __('Join Now') }}</button>
        </form>
    </div>
</section>
@endsection
