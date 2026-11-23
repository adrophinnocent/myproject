@php
    $eyebrow = $eyebrow ?? 'Tanzania Safari Planning Guides';
@endphp

<section class="relative bg-safari-dark text-white overflow-hidden">
    <div class="absolute inset-0 opacity-10" aria-hidden="true">
        <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-gold-500 blur-3xl"></div>
        <div class="absolute -bottom-32 -left-20 w-80 h-80 rounded-full bg-emerald-400 blur-3xl"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 py-16 md:py-20">
        <nav aria-label="Breadcrumb" class="mb-7 flex flex-wrap items-center gap-2 text-xs text-stone-400">
            <a href="{{ route('home') }}" class="hover:text-gold-400">{{ __('Home') }}</a>
            <span aria-hidden="true">/</span>
            <span>{{ __('Planning guides') }}</span>
            <span aria-hidden="true">/</span>
            <span class="text-stone-200">{{ $title }}</span>
        </nav>

        <span class="inline-block px-4 py-1.5 bg-amber-500/15 border border-amber-500/35 rounded-full text-amber-300 text-[11px] font-bold uppercase tracking-widest mb-5">
            {{ $eyebrow }}
        </span>
        <h1 class="font-display text-3xl md:text-5xl font-bold max-w-4xl leading-tight">{{ $title }}</h1>
        <p class="text-stone-300 text-base md:text-lg max-w-3xl mt-5 leading-relaxed">{{ $intro }}</p>
    </div>
</section>
