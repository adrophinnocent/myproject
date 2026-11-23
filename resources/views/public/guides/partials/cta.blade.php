<section class="py-14 bg-safari-dark text-white">
    <div class="max-w-5xl mx-auto px-4 text-center">
        <h2 class="font-display text-2xl md:text-4xl font-bold mb-4">Turn this guide into your own itinerary</h2>
        <p class="text-stone-300 max-w-2xl mx-auto mb-8 text-sm md:text-base leading-relaxed">
            Tell us your travel dates, preferred parks, pace and budget. We will put together a clear, tailor-made Tanzania itinerary with the inclusions written down.
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('custom-safari.create') }}" class="btn-gold px-8 py-4 rounded-full text-sm font-bold text-center">{{ __('Request a Custom Safari') }}</a>
            <a href="{{ route('contact.index') }}" class="border border-white/25 px-8 py-4 rounded-full text-sm font-bold hover:bg-white hover:text-safari-dark transition-colors text-center">{{ __('Ask a Question') }}</a>
        </div>
    </div>
</section>
