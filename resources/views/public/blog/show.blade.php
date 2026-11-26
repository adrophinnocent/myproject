@extends('public.layouts.app')

@php
    $seoTitle = trim(strip_tags((string) ($post->meta_title ?: $post->translate('title') ?: 'Twina Safaris Travel Journal')));
    $seoDescription = trim(strip_tags((string) ($post->meta_description ?: $post->excerpt ?: '')));
    if (mb_strlen($seoDescription) < 145) {
        $seoDescription .= str_repeat(' Read more Tanzania travel advice from Twina Safaris.', 5);
    }
    $seoDescription = \Illuminate\Support\Str::limit($seoDescription, 158, '');

    $canonicalUrl = \App\Helpers\SeoHelper::canonicalUrl(request());
    $siteUrl = \App\Helpers\SeoHelper::siteUrl();
    $publishedAt = optional($post->published_at)->toIso8601String() ?: optional($post->created_at)->toIso8601String();
    $modifiedAt = optional($post->updated_at)->toIso8601String() ?: $publishedAt;
    $authorName = optional($post->author)->name ?: 'Twina Safaris';

    $blogSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        '@id' => $canonicalUrl.'#blogposting',
        'headline' => trim(strip_tags((string) ($post->translate('title') ?: $post->title ?: ''))),
        'description' => $seoDescription,
        'image' => $post->featured_image_url,
        'author' => [
            '@type' => optional($post->author)->name ? 'Person' : 'Organization',
            '@id' => optional($post->author)->name ? $canonicalUrl.'#author' : $siteUrl.'/#organization',
            'name' => $authorName,
        ],
        'publisher' => ['@id' => $siteUrl.'/#organization'],
        'datePublished' => $publishedAt,
        'dateModified' => $modifiedAt,
        'inLanguage' => str_replace('_', '-', app()->getLocale()),
        'mainEntityOfPage' => $canonicalUrl,
        'url' => $canonicalUrl,
    ];
    if (! empty($post->tags)) {
        $blogSchema['keywords'] = implode(', ', (array) $post->tags);
    }

    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        '@id' => $canonicalUrl.'#breadcrumb',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => __('Home'), 'item' => route('home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => __('Travel Journal'), 'item' => route('blog.index')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => trim(strip_tags((string) ($post->translate('title') ?: $post->title ?: '')))],
        ],
    ];
@endphp
@section('title', \Illuminate\Support\Str::limit($seoTitle, 60, ''))
@section('meta_description', $seoDescription)
@section('meta_keywords', implode(', ', (array) ($post->tags ?? [])))
@section('og_image', $post->featured_image_url)
@section('og_type', 'article')

@section('article_meta')
<meta property="article:published_time" content="{{ $publishedAt }}">
<meta property="article:modified_time" content="{{ $modifiedAt }}">
<meta property="article:author" content="{{ $authorName }}">
@endsection

@section('schema')
<script type="application/ld+json">@json($blogSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>
<script type="application/ld+json">@json($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>
@endsection

@section('content')
<article class="bg-white article-page" itemscope itemtype="https://schema.org/BlogPosting">
    <meta itemprop="headline" content="{{ $post->translate('title') }}">
    <meta itemprop="datePublished" content="{{ $publishedAt }}">
    <meta itemprop="author" content="{{ $authorName }}">

    {{-- 1. Full-width hero, 3:2, cropped and capped at 620px on desktop --}}
    <figure class="article-hero w-full m-0 p-0 border-none">
        <div class="ph relative aspect-[3/2] w-full max-h-[620px] bg-stone-200 overflow-hidden rounded-none border-none p-0">
            <nav aria-label="{{ __('Breadcrumb') }}" class="absolute top-28 left-4 z-10 flex flex-wrap items-center gap-2 bg-white/85 px-3 py-2 rounded-lg text-xs text-stone-700 font-semibold shadow-sm">
                <a href="{{ route('home') }}" class="hover:text-[#8A6D0B]">{{ __('Home') }}</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('blog.index') }}" class="hover:text-[#8A6D0B]">{{ __('Travel Journal') }}</a>
                <span aria-hidden="true">/</span>
                <span class="text-stone-900" aria-current="page">{{ $post->translate('title') }}</span>
            </nav>
            <img src="{{ $post->featured_image_url }}"
                 width="1200" height="800"
                 alt="{{ $post->translate('title') }}"
                 class="w-full h-full object-cover rounded-none border-none p-0 shadow-none"
                 loading="eager"
                 fetchpriority="high"
                 decoding="async">
        </div>
        @if($post->hero_caption)
            <figcaption class="max-w-4xl mx-auto px-4 py-3 text-xs italic font-medium text-stone-500 text-center">
                {{ $post->hero_caption }}
            </figcaption>
        @endif
    </figure>

    <style>
        /* 1. Article Hero Image: Edge-to-Edge, Sharp 3:2, Capped at 620px, No Frame */
        .article-hero img {
            width: 100% !important;
            max-height: 620px !important;
            aspect-ratio: 3 / 2 !important;
            object-fit: cover !important;
            border-radius: 0 !important;
            border: none !important;
            padding: 0 !important;
            box-shadow: none !important;
            margin: 0 !important;
        }

        /* 2. Article Body Images: 3:2 Aspect Ratio, Sharp Corners (rounded-none), No Frame/Border/Padding/Shadow */
        .article-body figure.image,
        .article-body figure,
        .article-body p img,
        .article-body img {
            width: 100% !important;
            aspect-ratio: 3 / 2 !important;
            object-fit: cover !important;
            border-radius: 0 !important;
            border: none !important;
            padding: 0 !important;
            box-shadow: none !important;
            outline: none !important;
            margin: 2rem 0 0.5rem 0 !important;
            background: transparent !important;
        }

        /* 3. More to Read Images: Sharp 3:2 Aspect Ratio, No Rounded Corners, No Borders */
        .more-read-image {
            width: 100% !important;
            aspect-ratio: 3 / 2 !important;
            object-fit: cover !important;
            border-radius: 0 !important;
            border: none !important;
            padding: 0 !important;
            box-shadow: none !important;
        }

        /* 4. Article Captions */
        .article-body figcaption,
        figcaption {
            margin-top: 0.5rem;
            margin-bottom: 1.75rem;
            font-size: 0.8125rem;
            font-style: italic;
            color: #78716c;
            text-align: center;
        }

        /* 5. 2-Column Lists for ✓ and • */
        .article-body ul.two-col,
        .article-body ol.two-col,
        .article-body .grid-list-2col {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 0.75rem;
            margin-bottom: 1.75rem;
        }
        @media (min-width: 768px) {
            .article-body ul.two-col,
            .article-body ol.two-col,
            .article-body .grid-list-2col {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 1rem;
            }
        }
    </style>

    {{-- 2. Title block --}}
    <header class="max-w-3xl mx-auto px-4 pt-12 pb-10 text-center">
        <div class="flex items-center justify-center gap-3 text-[11px] font-black uppercase tracking-[.16em] mb-5">
            <span class="px-3 py-1 rounded-full bg-[#f6eedb] text-[#8A6D0B] border border-[#e8dfcf]">
                {{ __($post->category->name ?? 'Safari Journal') }}
            </span>
            <span class="text-stone-300">•</span>
            <span class="text-stone-500">{{ $post->reading_time }}</span>
        </div>

        <h1 class="font-display text-4xl md:text-6xl font-bold text-[#0d2818] leading-[1.08] tracking-tight">
            {{ $post->translate('title') }}
        </h1>

        @if($post->excerpt)
            <p class="mt-6 text-lg md:text-xl text-stone-600 font-serif-title italic leading-relaxed">
                {{ $post->translate('excerpt') ?: $post->excerpt }}
            </p>
        @endif

        <div class="mt-8 flex items-center justify-center gap-3">
            <span class="w-10 h-10 rounded-full bg-[#f6eedb] text-[#8A6D0B] font-black flex items-center justify-center font-display text-lg">
                {{ substr($post->author->name ?? 'T', 0, 1) }}
            </span>
            <div class="text-left">
                <p class="text-xs font-black text-stone-800">{{ $post->author->name ?? __('Twina Safaris') }}</p>
                <p class="text-[10px] font-bold uppercase tracking-wider text-stone-400">
                    {{ $post->published_at ? $post->published_at->format('F d, Y') : $post->created_at->format('F d, Y') }}
                </p>
            </div>
        </div>

        @if(!empty($post->tags))
            <div class="flex flex-wrap justify-center gap-2 mt-6">
                @foreach((array) $post->tags as $tag)
                    <span class="px-2.5 py-1 rounded-full border border-[#e8dfcf] bg-[#fcfaf7] text-[#8A6D0B] text-[10px] font-bold uppercase tracking-wider">#{{ $tag }}</span>
                @endforeach
            </div>
        @endif
    </header>

    {{-- 3. Centred 760px body --}}
    @php
        $articleContent = (string) $post->translate('content');
        // Editor uploads are normalised to 3:2. Older content may omit intrinsic
        // dimensions, which causes layout shifts before the image loads.
        $articleContent = preg_replace_callback('/<img\b[^>]*>/i', static function (array $matches): string {
            $tag = $matches[0];
            if (! preg_match('/\bwidth\s*=/i', $tag)) {
                return preg_replace('/\s*\/?>$/', ' width="1200" height="800"$0', $tag) ?? $tag;
            }
            if (! preg_match('/\bheight\s*=/i', $tag)) {
                return preg_replace('/\s*\/?>$/', ' height="800"$0', $tag) ?? $tag;
            }

            return $tag;
        }, $articleContent) ?? $articleContent;
    @endphp
    <div class="max-w-[760px] mx-auto px-4 pb-16">
        <div class="article-body" itemprop="articleBody">
            {!! $articleContent !!}
        </div>

        {{-- Share --}}
        <div class="mt-14 pt-8 border-t border-stone-200 flex flex-col sm:flex-row items-center justify-between gap-5">
            <span class="text-[11px] font-black uppercase tracking-widest text-stone-400">{{ __('Share this story') }}</span>
            <div class="flex gap-2">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" rel="noopener" aria-label="Share on Facebook" class="w-10 h-10 rounded-full border border-stone-200 flex items-center justify-center text-stone-500 hover:text-white hover:bg-[#1877F2] hover:border-[#1877F2] transition-all">
                    <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12.073-12-12.073s-12 5.446-12 12.073c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($post->translate('title')) }}" target="_blank" rel="noopener" aria-label="Share on X" class="w-10 h-10 rounded-full border border-stone-200 flex items-center justify-center text-stone-500 hover:text-white hover:bg-black hover:border-black transition-all">
                    <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.25h-6.657l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
            </div>
        </div>

        {{-- End-of-article CTA box --}}
        <div class="article-cta mt-12">
            <h2>{{ __('Ready for Adventure?') }}</h2>
            <p>{{ __('Let our local experts plan the safari you have been dreaming about.') }}</p>
            <a class="btn-gold" href="{{ route('tours.index') }}">{{ __('Browse Tanzania Safari Packages') }}</a>
        </div>

        {{-- Author box --}}
        <div class="mt-10 rounded-[1.5rem] border border-[#e8dfcf] bg-[#fcfaf7] p-6 md:p-8 flex flex-col sm:flex-row items-center gap-5">
            <div class="w-16 h-16 shrink-0 rounded-full bg-[#f6eedb] text-[#8A6D0B] font-black flex items-center justify-center font-display text-2xl">
                {{ substr($post->author->name ?? 'T', 0, 1) }}
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-[#8A6D0B] mb-1">{{ __('Written by') }}</p>
                <p class="font-display text-xl font-bold text-[#0d2818]">{{ $post->author->name ?? __('Twina Safaris') }}</p>
                <p class="text-xs text-stone-500 mt-1">{{ __('Safari experts sharing real stories from the heart of Tanzania.') }}</p>
            </div>
        </div>
    </div>

    {{-- 4. More to Read --}}
    @if(isset($relatedPosts) && $relatedPosts->count() > 0)
        <section class="bg-[#fcfaf7] py-16 border-t border-stone-200">
            <div class="max-w-6xl mx-auto px-4">
                <div class="flex items-center gap-4 mb-9">
                    <h2 class="font-display text-3xl font-bold text-[#0d2818]">{{ __('More to Read') }}</h2>
                    <div class="h-px flex-1 bg-stone-200"></div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-7">
                    @foreach($relatedPosts as $rel)
                        <a href="{{ route('blog.show', $rel->slug) }}" class="group bg-white rounded-[1.5rem] overflow-hidden border border-[#e8dfcf] hover:border-[#D4AF37]/60 hover:shadow-xl transition-all duration-300">
                            <div class="overflow-hidden bg-stone-900">
                                <img src="{{ $rel->featured_image_url }}"
                                     width="600" height="400"
                                     alt="{{ $rel->translate('title') }}"
                                     class="more-read-image w-full group-hover:scale-105 transition-transform duration-700"
                                     loading="lazy" decoding="async">
                            </div>
                            <div class="p-5">
                                <h3 class="font-display text-lg font-bold text-stone-900 leading-snug group-hover:text-[#8A6D0B] transition-colors line-clamp-2">
                                    {{ $rel->translate('title') }}
                                </h3>
                                <p class="mt-3 text-[10px] font-black uppercase tracking-widest text-stone-400">
                                    {{ $rel->published_at ? $rel->published_at->format('M d, Y') : '' }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</article>

<script>
    // Older articles may contain images without a caption, lazy loading or alt text.
    // The 3:2 crop itself is handled by the one global CSS rule in the site layout.
    document.querySelectorAll('.article-body img').forEach(function (img) {
        if (!img.hasAttribute('loading')) img.setAttribute('loading', 'lazy');
        if (!img.hasAttribute('alt')) img.setAttribute('alt', @js($post->translate('title')));

        var figure = img.closest('figure');
        if (!figure) {
            figure = document.createElement('figure');
            img.parentNode.insertBefore(figure, img);
            figure.appendChild(img);
        }
        if (!figure.querySelector('figcaption')) {
            var caption = document.createElement('figcaption');
            caption.textContent = img.getAttribute('alt') || @js($post->translate('title'));
            figure.appendChild(caption);
        }
    });
</script>

{{-- Newsletter Section --}}
<section class="bg-[#fcfaf7] py-24 border-t border-gray-100">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <span class="text-gold-600 text-xs font-black uppercase tracking-[0.4em] mb-4 block">Newsletter</span>
        <h2 class="font-display text-4xl md:text-5xl font-bold text-gray-900 mb-6">{{ __('Never miss a story') }}</h2>
        <p class="text-gray-500 mb-12 max-w-xl mx-auto text-lg">{{ __('Get the latest safari tips, wildlife news, and exclusive offers delivered to your inbox.') }}</p>
        <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex flex-col sm:flex-row gap-4 max-w-lg mx-auto">
            @csrf
            <input type="email" name="email" aria-label="{{ __('Email address for the newsletter') }}" required placeholder="{{ __('Email address') }}" class="flex-1 bg-white border border-gray-200 rounded-2xl px-6 py-5 focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all shadow-sm">
            <button class="btn-gold px-10 py-5 rounded-2xl font-black uppercase tracking-widest text-xs shadow-xl">{{ __('Subscribe') }}</button>
        </form>
    </div>
</section>
@endsection
