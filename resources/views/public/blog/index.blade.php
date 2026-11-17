@extends('public.layouts.app')

@section('title', 'Twina Safaris Blog - Travel Tips & Stories')
@section('meta_description', 'Discover the latest news, travel tips, and safari stories from Tanzania with Twina Safaris.')

@section('content')
{{-- Page Header --}}
<div class="relative h-[60vh] min-h-[450px] flex items-center justify-center overflow-hidden bg-safari-dark">
    <div class="absolute inset-0">
        <img src="{{ \App\Helpers\AssetHelper::getBannerUrl('blog_banner') }}"
             width="1920" height="600"
             class="w-full h-full object-cover opacity-60" alt="{{ __('Blog Journal') }}" loading="eager">
        <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-transparent to-transparent"></div>
    </div>
    <div class="relative z-10 max-w-5xl mx-auto px-4 text-center">
        <span class="inline-block text-gold-400 text-xs md:text-sm font-black uppercase tracking-[0.5em] mb-6">{{ __('Safari Journal & Stories') }}</span>
        <h1 class="font-display text-5xl md:text-7xl lg:text-8xl text-white font-bold mb-8 leading-tight drop-shadow-2xl">
            {{ __('The Spirit') }} <span class="italic text-gold-500 font-serif">{{ __('of Africa') }}</span>
        </h1>
        <p class="text-white/90 text-lg md:text-xl max-w-3xl mx-auto font-light leading-relaxed drop-shadow-md">
            {{ __('Dive into a world of adventure where the wild meets luxury. Our journal brings you closer to the heart of Tanzania, sharing the secrets of the Serengeti, the spirit of the Maasai, and the magic of Zanzibar’s turquoise shores.') }}
        </p>
    </div>
    <div class="absolute bottom-0 left-0 w-full h-32 bg-gradient-to-t from-white to-transparent"></div>
</div>

<div class="max-w-7xl mx-auto px-4 pt-16">
    <div class="text-center mb-16">
        <div class="max-w-4xl mx-auto space-y-6">
            <h2 class="font-display text-3xl md:text-4xl font-black text-safari-dark uppercase tracking-tight">{{ __('Voices of the Wild') }}</h2>
            <p class="text-gray-600 text-lg leading-relaxed font-light">
                {{ __('Discover inspiring safari stories, unforgettable travel experiences, breathtaking wildlife encounters, and hidden gems from across Tanzania and East Africa. Stay updated with expert travel tips, real guest adventures, and destination highlights that bring the spirit of Africa to life.') }}
            </p>
            <p class="text-gray-700 text-lg font-medium italic border-l-4 border-gold-500 pl-8 inline-block text-left">
                {{ __('From the vast plains of the') }} <strong>{{ __('Serengeti to the peaks of Mount Kilimanjaro and the beaches of Zanzibar') }}</strong>, {{ __('our blog shares real moments, practical guides, and stories that spark your next journey.') }}
            </p>
        </div>
        <div class="w-24 h-1 bg-gold-500 mx-auto mt-12 rounded-full"></div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 pb-24">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        {{-- Posts Grid --}}
        <div class="lg:col-span-2 space-y-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                @forelse($posts as $post)
                    @php
                        $rawTitle = $post->translate('title') ?: $post->title;
                        $titleStr = \App\Helpers\AssetHelper::asString($rawTitle);

                        $rawExcerpt = $post->translate('excerpt') ?: $post->excerpt;
                        $excerptStr = \App\Helpers\AssetHelper::asString($rawExcerpt);
                    @endphp
                    <a href="{{ route('blog.show', $post->slug) }}"
                       class="group relative block w-full rounded-md md:rounded-lg overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 h-64 bg-stone-900 border border-stone-800/50">

                        {{-- Background Cover Image with Zoom Effect --}}
                        <img src="{{ $post->featured_image_url }}"
                             alt="{{ $titleStr }}"
                             class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out"
                             loading="lazy" decoding="async">

                        {{-- Dark Overlay Gradient for Readable Text --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/50 to-black/20 group-hover:from-black/90 transition-all pointer-events-none"></div>

                        {{-- Category Badge at TOP RIGHT --}}
                        <div class="absolute top-2.5 right-2.5 z-10">
                            <span class="inline-block bg-[#f8b218] text-stone-950 font-black text-xs px-2.5 py-1 rounded shadow-md uppercase tracking-wider">
                                {{ $post->category->name ?? 'BLOG' }}
                            </span>
                        </div>

                        {{-- Centered Text Content (Title & Excerpt) --}}
                        <div class="absolute inset-x-0 bottom-0 top-0 p-4 pb-5 flex flex-col justify-end items-center text-center z-10 pointer-events-none">
                            <h3 class="font-black text-white text-sm md:text-base leading-tight uppercase tracking-tight group-hover:text-amber-300 transition-colors drop-shadow-md max-w-[90%]">
                                {{ $titleStr }}
                            </h3>
                            @if($excerptStr)
                            <p class="text-stone-200 text-xs font-normal leading-snug line-clamp-2 max-w-[92%] mt-1 drop-shadow-xs">
                                {{ $excerptStr }}
                            </p>
                            @endif
                        </div>

                        {{-- White Circle Arrow Button at Bottom Right --}}
                        <div class="absolute bottom-2.5 right-2.5 w-8 h-8 rounded-full bg-white text-stone-950 flex items-center justify-center shrink-0 shadow-lg group-hover:scale-110 group-hover:bg-[#f8b218] transition-all duration-300 z-10">
                            <svg class="w-4 h-4 text-stone-950" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center py-20 bg-gray-50 rounded-2xl border border-dashed border-gray-300">
                        <p class="text-gray-500">{{ __('We are currently writing new stories. Check back soon!') }}</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="pt-8">
                {{ $posts->links() }}
            </div>
        </div>

        {{-- Sidebar --}}
        <aside class="space-y-10">
            {{-- Categories --}}
            <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100">
                <h3 class="font-display text-xl font-bold text-gray-900 mb-6">{{ __('Categories') }}</h3>
                <div class="space-y-3">
                    @foreach($categories as $cat)
                    <a href="{{ route('blog.index', ['category' => $cat->slug]) }}" class="flex items-center justify-between text-gray-700 hover:text-gold-600 font-medium group">
                        <span>{{ __($cat->name) }}</span>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Newsletter --}}
            <div class="bg-safari-dark rounded-2xl p-8 text-white relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="font-display text-xl font-bold mb-4">{{ __('Get the latest tips') }}</h3>
                    <p class="text-white/60 text-sm mb-6">{{ __('Subscribe to our newsletter and receive travel inspiration directly in your inbox.') }}</p>
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="space-y-3">
                        @csrf
                        <input type="email" name="email" placeholder="{{ __('Your email address') }}" class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-gold-500">
                        <button class="btn-gold w-full py-3 rounded-xl font-bold text-sm">{{ __('Subscribe') }}</button>
                    </form>
                </div>
                <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-gold-500/10 rounded-full blur-2xl"></div>
            </div>
        </aside>
    </div>
</div>
@endsection
