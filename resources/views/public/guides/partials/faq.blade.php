@php
    $heading = $heading ?? 'Questions travellers ask';
@endphp

<section class="py-16 bg-stone-100/70 border-t border-stone-200/80">
    <div class="max-w-4xl mx-auto px-4">
        <h2 class="font-display text-3xl font-bold text-stone-900 mb-8">{{ $heading }}</h2>

        <div class="space-y-3">
            @foreach($faqs as $faq)
            <details class="group bg-white border border-stone-200 rounded-2xl shadow-xs open:border-[#b8920d]/60 transition-colors">
                <summary class="cursor-pointer list-none p-5 md:p-6 flex items-start justify-between gap-4 font-bold text-stone-900 text-sm md:text-base">
                    <span>{{ $faq['question'] }}</span>
                    <span class="text-[#8A6D0B] text-xl font-bold leading-none shrink-0" aria-hidden="true">+</span>
                </summary>
                <div class="px-5 md:px-6 pb-6 pt-1 text-stone-600 text-sm leading-relaxed border-t border-stone-100">
                    {{ $faq['answer'] }}
                </div>
            </details>
            @endforeach
        </div>
    </div>
</section>
