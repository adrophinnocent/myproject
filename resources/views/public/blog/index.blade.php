@extends('public.layouts.app')

@section('title', 'Tanzania Travel Journal & Safari Stories | Twina Safaris')
@section('meta_description', 'Explore Tanzania safari guides, Serengeti wildlife insights, Kilimanjaro climbing tips and Zanzibar itineraries with Twina Safaris and local experts.')

@php
    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => __('Home'), 'item' => route('home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => __('Travel Journal'), 'item' => route('blog.index')],
        ],
    ];

    $itemListSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => __('Tanzania Travel Journal & Safari Stories'),
        'url' => route('blog.index'),
        'itemListElement' => collect($posts->items())->map(fn ($post, $index) => [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => trim(strip_tags((string) ($post->translate('title') ?: $post->title ?: ''))),
            'url' => route('blog.show', ['slug' => $post->slug]),
        ])->values()->all(),
    ];
@endphp

@section('schema')
<script type="application/ld+json">@json($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>
<script type="application/ld+json">@json($itemListSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>
@endsection

@section('content')
{{-- Page Hero Header --}}
<div class="relative pt-32 pb-16 bg-safari-dark">
    <div class="absolute inset-0 z-0">
        <img src="{{ \App\Helpers\AssetHelper::getBannerUrl('blog_banner') }}" width="1920" height="600" class="w-full h-full object-cover opacity-20" alt="{{ __('Tanzania safari journal background') }}" loading="eager" decoding="async">
    </div>
    <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-amber-900/20 to-slate-900 opacity-80"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 text-center">
        <nav aria-label="{{ __('Breadcrumb') }}" class="flex flex-wrap justify-center items-center gap-2 text-xs text-stone-300 mb-4 font-medium">
            <a href="{{ route('home') }}" class="hover:text-gold-400 transition-colors">{{ __('Home') }}</a>
            <span aria-hidden="true">/</span>
            <span class="text-white font-bold" aria-current="page">{{ __('Travel Journal') }}</span>
        </nav>
        <span class="text-gold-400 text-xs sm:text-sm uppercase tracking-widest font-black">{{ __('Safari Journal & Stories') }}</span>
        <h1 class="font-display text-4xl md:text-6xl text-white font-bold mt-3">{{ __('Tanzania Travel Journal & Safari Stories') }}</h1>
        <p class="text-gray-300 max-w-2xl mx-auto mt-4 text-sm sm:text-base leading-relaxed">
            {{ __('Discover inspiring safari stories, expert travel guides, Kilimanjaro trek advice, and hidden gems from across Tanzania and East Africa.') }}
        </p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-12">
    {{-- 2-Column Main Layout: Left Sidebar + Right Blog Grid (Matching Tours Catalog Layout) --}}
    <div class="flex flex-col lg:flex-row gap-8 items-start">

        {{-- Left Column: Filter & Category Sidebar --}}
        <aside class="w-full lg:w-72 shrink-0">
            <div class="bg-white rounded-3xl border border-stone-200/90 shadow-sm p-6 sticky top-24 space-y-6">
                <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                    <h2 class="font-bold text-stone-900 text-base">{{ __('Search Journal') }}</h2>
                    @if(request('category') || request('search'))
                        <a href="{{ route('blog.index') }}" class="text-xs font-bold text-amber-800 hover:underline">{{ __('Clear All') }}</a>
                    @endif
                </div>

                <form method="GET" action="{{ route('blog.index') }}" class="space-y-4">
                    <div>
                        <input type="text" name="search" aria-label="{{ __('Search journal articles') }}" value="{{ request('search') }}" placeholder="{{ __('Search articles...') }}"
                               class="w-full bg-stone-50 border border-stone-200 rounded-xl px-4 py-2.5 text-xs font-semibold text-stone-800 focus:outline-none focus:border-amber-500">
                    </div>

                    <div>
                        <label class="block font-bold text-stone-500 uppercase tracking-wider text-[11px] mb-2.5">{{ __('Categories') }}</label>
                        <div class="space-y-2 max-h-60 overflow-y-auto pr-1 text-xs">
                            <a href="{{ route('blog.index') }}" class="flex items-center justify-between p-2 rounded-xl transition-all {{ !request('category') ? 'bg-amber-50 text-amber-900 font-bold border border-amber-200' : 'text-stone-700 hover:bg-stone-50' }}">
                                <span>{{ __('All Articles') }}</span>
                                <span class="text-[10px] bg-stone-200 px-2 py-0.5 rounded-full font-bold">{{ $posts->total() }}</span>
                            </a>
                            @foreach($categories as $cat)
                                <a href="{{ route('blog.index', ['category' => $cat->slug]) }}" class="flex items-center justify-between p-2 rounded-xl transition-all {{ request('category') === $cat->slug ? 'bg-amber-50 text-amber-900 font-bold border border-amber-200' : 'text-stone-700 hover:bg-stone-50' }}">
                                    <span>{{ __($cat->name) }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </form>

                {{-- Newsletter Signup --}}
                <div class="bg-safari-dark rounded-2xl p-6 text-white relative overflow-hidden space-y-3">
                    <h3 class="font-bold text-amber-400 text-sm">{{ __('Get Travel Tips') }}</h3>
                    <p class="text-xs text-stone-300 leading-relaxed">{{ __('Subscribe to receive safari advice and itinerary updates directly.') }}</p>
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="space-y-2">
                        @csrf
                        <input type="email" name="email" aria-label="{{ __('Email address for the newsletter') }}" required placeholder="{{ __('your@email.com') }}" class="w-full bg-white/10 border border-white/20 rounded-xl px-3.5 py-2.5 text-xs text-white placeholder-stone-400 focus:outline-none focus:border-amber-400">
                        <button class="w-full bg-amber-500 hover:bg-amber-600 text-stone-950 font-black py-2.5 rounded-xl text-xs uppercase tracking-wider transition-all">
                            {{ __('Subscribe') }}
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Right Column: Blog Cards Grid (TourRadar Style) --}}
        <div class="flex-1 w-full min-w-0">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                @forelse($posts as $post)
                    @php
                        $rawTitle = $post->translate('title') ?: $post->title;
                        $titleStr = \App\Helpers\AssetHelper::asString($rawTitle);

                        $rawExcerpt = $post->translate('excerpt') ?: $post->excerpt;
                        $excerptStr = \App\Helpers\AssetHelper::asString($rawExcerpt);
                    @endphp

                    {{-- Blog Card (3:2 Aspect Ratio, Sharp Corners, No Image Frame) --}}
                    <div class="bg-white border border-[#e8dfcf] rounded-2xl overflow-hidden shadow-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between group hover:border-[#b8920d]/60">
                        <div>
                            {{-- Top Image Widescreen 3:2 Frame with Sharp Corners --}}
                            <div class="ph relative aspect-[3/2] w-full bg-stone-200 overflow-hidden border-none p-0 rounded-none">
                                <img src="{{ $post->featured_image_url }}"
                                     width="800" height="533"
                                      alt="{{ $titleStr }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out border-none p-0 rounded-none"
                                     loading="lazy" decoding="async">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent pointer-events-none"></div>

                                {{-- Category Badge --}}
                                <div class="absolute top-3 right-3 z-10">
                                    <span class="px-3 py-1 bg-[#d4af37] text-stone-950 font-black text-xs uppercase rounded-lg shadow-md tracking-wider">
                                        {{ $post->category->name ?? 'JOURNAL' }}
                                    </span>
                                </div>

                                {{-- Reading Date Badge --}}
                                <div class="absolute top-3 left-3 z-10">
                                    <span class="px-2.5 py-1 bg-stone-900/80 backdrop-blur-md text-amber-400 font-bold text-[10px] uppercase rounded-lg border border-white/20">
                                        {{ $post->published_at ? $post->published_at->format('M d, Y') : $post->created_at->format('M d, Y') }}
                                    </span>
                                </div>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-5 space-y-3">
                                <h3 class="font-display font-black text-stone-900 text-base md:text-lg leading-snug group-hover:text-amber-800 transition-colors line-clamp-2">
                                    <a href="{{ route('blog.show', $post->slug) }}">
                                        {{ $titleStr }}
                                    </a>
                                </h3>

                                @if($excerptStr)
                                <p class="text-stone-600 text-xs leading-relaxed line-clamp-3">
                                    {{ $excerptStr }}
                                </p>
                                @endif
                            </div>
                        </div>

                        {{-- Card Footer & CTA Button --}}
                        <div class="p-5 pt-0 border-t border-stone-100 flex items-center justify-between gap-2 mt-auto">
                            <span class="text-[11px] text-stone-400 font-bold uppercase">{{ __('Twina Travel Guide') }}</span>

                            <a href="{{ route('blog.show', $post->slug) }}"
                               class="btn-gold px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider shadow-sm flex items-center gap-1.5 group-hover:scale-105 transition-transform">
                                <span>READ ARTICLE</span>
                                <svg class="w-3.5 h-3.5" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-20 bg-stone-50 rounded-3xl border border-stone-200">
                        <div class="text-6xl mb-3">📚</div>
                        <h3 class="font-display text-xl text-stone-800 font-bold mb-1">{{ __('No Articles Found') }}</h3>
                        <p class="text-stone-500 text-xs mb-5">{{ __('We are currently publishing new travel stories. Check back soon!') }}</p>
                        <a href="{{ route('blog.index') }}" class="btn-gold px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider">{{ __('View All Articles') }}</a>
                    </div>
                @endforelse
            </div>

            @if($posts->hasPages())
            <div class="mt-10">
                {{ $posts->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
