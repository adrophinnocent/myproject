@extends('public.layouts.app')

@section('title', $campaign->title)
@section('meta_description', $campaign->description)

@section('og_image', $campaign->image_url)

@section('content')
<div class="bg-white min-h-screen">
    {{-- Hero Section --}}
    <div class="relative h-[70vh] flex items-center justify-center overflow-hidden bg-[#0a0703]">
        <img src="{{ $campaign->image_url }}" class="absolute inset-0 w-full h-full object-cover opacity-50">
        <div class="absolute inset-0 bg-gradient-to-t from-[#0a0703] via-transparent to-transparent"></div>

        <div class="relative z-10 text-center px-4 max-w-5xl">
            <span class="px-5 py-1.5 rounded-full bg-gold-500 text-safari-dark text-[10px] font-black uppercase tracking-[0.2em] mb-6 inline-block shadow-2xl">
                {{ $campaign->type }}
            </span>
            <h1 class="font-display text-4xl md:text-8xl text-white font-black leading-[0.9] mb-8 drop-shadow-2xl">
                {{ $campaign->title }}
            </h1>
            @if($campaign->price)
            <div class="text-2xl md:text-4xl text-gold-400 font-black drop-shadow-lg">
                Exclusive Offer from ${{ number_format($campaign->price, 0) }}
            </div>
            @endif
        </div>
    </div>

    {{-- Trust Badges Section (Text only as requested) --}}
    <div class="bg-white border-b border-gray-50 py-10">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-y-8">
                <div class="flex flex-col items-center text-center px-4">
                    <h4 class="text-[11px] font-black uppercase tracking-[0.25em] text-gray-500 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-gold-500"></span>
                        Award Winning
                    </h4>
                </div>
                <div class="flex flex-col items-center text-center px-4 border-l border-gray-100">
                    <h4 class="text-[11px] font-black uppercase tracking-[0.25em] text-gray-500 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-gold-500"></span>
                        Fully Insured
                    </h4>
                </div>
                <div class="flex flex-col items-center text-center px-4 border-l border-gray-100">
                    <h4 class="text-[11px] font-black uppercase tracking-[0.25em] text-gray-500 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-gold-500"></span>
                        Local Experts
                    </h4>
                </div>
                <div class="flex flex-col items-center text-center px-4 border-l border-gray-100">
                    <h4 class="text-[11px] font-black uppercase tracking-[0.25em] text-gray-500 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-gold-500"></span>
                        5-Star Rated
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-20">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">

            {{-- Content Area --}}
            <div class="lg:col-span-2 space-y-16">
                <div>
                    <h2 class="font-display text-4xl font-black text-gray-900 mb-8 tracking-tight border-l-4 border-gold-500 pl-6">Adventure Overview</h2>
                    <p class="text-gray-700 leading-relaxed text-xl font-light">{{ $campaign->description }}</p>
                </div>

                @if($campaign->itinerary)
                <div>
                    <h2 class="font-display text-4xl font-black text-gray-900 mb-8 tracking-tight border-l-4 border-gold-500 pl-6">Experience Highlights</h2>
                    <div class="prose max-w-none text-gray-700 whitespace-pre-line text-lg leading-relaxed font-medium bg-gray-50 p-8 rounded-3xl border border-gray-100">
                        {{ $campaign->itinerary }}
                    </div>
                </div>
                @endif
            </div>

            {{-- Sticky Form Card --}}
            <div class="relative">
                <div class="sticky top-28 bg-white rounded-[2.5rem] p-10 shadow-[0_35px_60px_-15px_rgba(0,0,0,0.1)] border border-gray-100">
                    <h3 class="font-display text-3xl font-black text-gray-900 mb-2">Claim Offer</h3>
                    <p class="text-sm text-gray-500 mb-8 font-medium italic">Available for a limited time only.</p>

                    <form action="{{ route('campaign.lead', $campaign) }}" method="POST" class="space-y-5">
                        @csrf
                        <div class="space-y-1">
                            <input type="text" name="name" required placeholder="Full Name" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-6 py-4 text-gray-900 focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 outline-none transition-all font-bold">
                        </div>
                        <div class="space-y-1">
                            <input type="email" name="email" required placeholder="Email Address" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-6 py-4 text-gray-900 focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 outline-none transition-all font-bold">
                        </div>
                        <div class="space-y-1">
                            <input type="text" name="phone" required placeholder="WhatsApp / Phone" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-6 py-4 text-gray-900 focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 outline-none transition-all font-bold">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-[10px] font-black uppercase text-gray-400 ml-2 tracking-widest">Date</label>
                                <input type="date" name="travel_date" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-4 text-xs font-bold">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black uppercase text-gray-400 ml-2 tracking-widest">Travelers</label>
                                <input type="number" name="travelers_count" min="1" value="1" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-4 py-4 text-xs font-bold">
                            </div>
                        </div>

                        <button type="submit" class="btn-gold w-full py-5 rounded-2xl font-black uppercase tracking-widest shadow-xl transition-all hover:scale-105">
                            Get Quote Now
                        </button>
                    </form>

                    <div class="mt-10 pt-10 border-t border-gray-100 text-center">
                        <p class="text-xs text-gray-400 mb-4 font-bold uppercase tracking-widest">Talk to us instantly</p>
                        <a href="https://wa.me/{{ preg_replace('/\D/','',\App\Models\Setting::get('site_phone')) }}?text=Hi! I'm interested in the '{{ $campaign->title }}' offer."
                           onclick="trackWhatsApp()" target="_blank"
                           class="inline-flex items-center justify-center gap-3 bg-[#25D366] hover:bg-[#20ba56] text-white px-8 py-4 rounded-2xl font-black transition-all shadow-lg hover:shadow-green-500/30">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            WhatsApp Chat
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ========== RELATED TRIPS (HORIZONTAL SLIDER) ========== --}}
    <section class="py-24 bg-gray-50 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-end justify-between mb-12">
                <div>
                    <span class="text-gold-600 text-sm font-black uppercase tracking-[0.2em] mb-3 block">More Options</span>
                    <h2 class="font-display text-4xl md:text-5xl font-black text-gray-900 leading-tight">Recommended Trips</h2>
                </div>
                <div class="hidden md:flex gap-3">
                    <button onclick="scrollRelated(-400)" class="w-12 h-12 rounded-2xl bg-white border border-gray-200 flex items-center justify-center text-gray-900 hover:bg-gold-500 hover:text-white transition-all shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button onclick="scrollRelated(400)" class="w-12 h-12 rounded-2xl bg-white border border-gray-200 flex items-center justify-center text-gray-900 hover:bg-gold-500 hover:text-white transition-all shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>

            <div id="related-slider" class="flex gap-6 overflow-x-auto pb-8 snap-x snap-mandatory no-scrollbar scroll-smooth">
                @foreach($relatedTours as $tour)
                <div class="snap-start shrink-0 w-full sm:w-[380px]">
                    <div class="bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-2xl transition-all duration-500 group h-full flex flex-col">
                        <div class="relative h-64 overflow-hidden">
                            <img src="{{ $tour->featured_image_url }}" alt="{{ $tour->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 rounded-full bg-white/90 backdrop-blur-sm text-safari-dark text-[10px] font-black uppercase tracking-widest">{{ $tour->category->name ?? 'Safari' }}</span>
                            </div>
                            <div class="absolute bottom-4 right-4">
                                <span class="px-4 py-2 rounded-xl bg-gold-500 text-safari-dark font-black text-sm shadow-xl">From {{ $tour->formatted_price }}</span>
                            </div>
                        </div>
                        <div class="p-8 flex-grow flex flex-col">
                            <h3 class="font-display text-2xl font-black text-gray-900 mb-4 group-hover:text-gold-600 transition-colors leading-tight">
                                <a href="{{ route('tours.show', ['type' => $tour->item_type ?? 'tour', 'slug' => $tour->slug ?? 'default']) }}">{{ $tour->title }}</a>
                            </h3>
                            <div class="flex items-center gap-4 text-gray-400 text-[10px] font-black uppercase tracking-widest mb-6">
                                <span>{{ $tour->duration_text }}</span>
                                <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                <span>{{ $tour->destination->name ?? 'Tanzania' }}</span>
                            </div>
                            <div class="mt-auto">
                                <a href="{{ route('tours.show', ['type' => $tour->item_type ?? 'tour', 'slug' => $tour->slug ?? 'default']) }}" class="inline-flex items-center gap-2 text-safari-dark font-black uppercase tracking-widest text-[10px] hover:text-gold-600 transition-all border-b-2 border-gold-500 pb-1">
                                    View Full Itinerary
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
</div>

<script>
function trackWhatsApp() {
    fetch('{{ route('campaign.track', $campaign) }}?type=whatsapp');
}
function scrollRelated(distance) {
    document.getElementById('related-slider').scrollBy({ left: distance, behavior: 'smooth' });
}
</script>

<style>
.no-scrollbar::-webkit-scrollbar { display: none; }
.no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
