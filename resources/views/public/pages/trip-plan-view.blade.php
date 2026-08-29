@extends('public.layouts.app')

@section('title', $tripPlan->trip_title ?: 'Your Safari Itinerary - Twina Safaris')

@section('content')
<div class="bg-gray-50 min-h-screen pt-32 pb-20">
    <div class="max-w-5xl mx-auto px-4">

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-2xl p-6 mb-8 text-center shadow-sm">
                <div class="text-green-600 font-bold text-lg">{{ session('success') }}</div>
            </div>
        @endif

        {{-- Hero/Header --}}
        <div class="bg-safari-dark rounded-[3rem] p-12 md:p-20 text-center relative overflow-hidden shadow-2xl mb-12">
            <div class="absolute inset-0 opacity-20">
                <img src="{{ \App\Helpers\AssetHelper::getBannerUrl('itinerary_bg') }}" class="w-full h-full object-cover">
            </div>
            <div class="relative z-10">
                <span class="inline-block px-4 py-1 bg-gold-500 text-safari-dark text-[10px] font-black uppercase tracking-[0.3em] rounded-full mb-6">
                    Personalized Expedition Plan
                </span>
                <h1 class="text-4xl md:text-7xl font-display font-black text-white leading-tight mb-6">
                    {{ $tripPlan->trip_title ?: 'Your African Journey' }}
                </h1>
                <div class="flex flex-wrap justify-center gap-6 text-white/80 font-bold uppercase tracking-widest text-xs">
                    <div class="flex items-center gap-2">
                        <span class="text-gold-400">📅</span>
                        {{ $tripPlan->travel_date ? $tripPlan->travel_date->format('d M Y') : 'TBD' }}
                        @if($tripPlan->end_date) — {{ $tripPlan->end_date->format('d M Y') }} @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-gold-400">👥</span>
                        {{ $tripPlan->adults + $tripPlan->children }} Travelers
                    </div>
                </div>

                {{-- Message History (Customer View) --}}
                @if($tripPlan->messages->count() > 0)
                <div class="mt-12">
                    <h2 class="text-3xl font-display font-black text-gray-900 mb-8 border-l-4 border-gold-500 pl-6 uppercase tracking-tight">Recent Updates</h2>
                    <div class="space-y-4">
                        @foreach($tripPlan->messages as $msg)
                            <div class="p-6 rounded-3xl border border-gray-100 {{ $msg->sender_type === 'admin' ? 'bg-amber-50/30' : 'bg-white' }}">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-[10px] font-black uppercase tracking-widest {{ $msg->sender_type === 'admin' ? 'text-gold-600' : 'text-gray-400' }}">
                                        {{ $msg->sender_type === 'admin' ? 'Twina Safaris Support' : 'Your Message' }}
                                    </span>
                                    <span class="text-[9px] text-gray-400 font-bold">{{ $msg->created_at->format('d M, H:i') }}</span>
                                </div>
                                <p class="text-sm text-gray-700 leading-relaxed font-medium">{{ $msg->message }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

            {{-- Itinerary Content --}}
            <div class="lg:col-span-2 space-y-12">

                {{-- Overview --}}
                <div class="prose prose-lg max-w-none text-gray-600 font-light leading-relaxed">
                    <h2 class="text-3xl font-display font-black text-gray-900 mb-6 border-l-4 border-gold-500 pl-6 uppercase tracking-tight">Overview</h2>
                    <p>{{ $tripPlan->message }}</p>
                </div>

                {{-- Day by Day --}}
                <div class="space-y-8">
                    <h2 class="text-3xl font-display font-black text-gray-900 mb-8 border-l-4 border-gold-500 pl-6 uppercase tracking-tight">Your Daily Adventure</h2>

                    @if($tripPlan->itinerary_data)
                        @foreach($tripPlan->itinerary_data as $index => $day)
                            <div class="relative pl-12 pb-12 last:pb-0">
                                @if(!$loop->last)
                                    <div class="absolute left-4 top-10 bottom-0 w-0.5 bg-gray-200"></div>
                                @endif
                                <div class="absolute left-0 top-0 w-9 h-9 rounded-full bg-gold-500 flex items-center justify-center text-safari-dark font-black text-sm shadow-lg shadow-gold-500/20">
                                    {{ $day['day'] }}
                                </div>
                                <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm hover:shadow-md transition-all">
                                    <h3 class="text-xl font-display font-black text-gray-900 mb-4 uppercase tracking-tight">{{ $day['title'] }}</h3>
                                    <p class="text-gray-500 text-sm leading-relaxed font-medium">{{ $day['desc'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="bg-amber-50 rounded-2xl p-8 border border-amber-100 italic text-amber-700">
                            Our team is still finalizing the day-by-day details. Please check back soon!
                        </div>
                    @endif
                </div>

                {{-- Inclusions/Exclusions --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-green-50/50 rounded-3xl p-8 border border-green-100">
                        <h3 class="text-lg font-black text-green-800 mb-6 uppercase tracking-widest flex items-center gap-2">
                            <span class="text-xl">✓</span> What's Included
                        </h3>
                        <ul class="space-y-3">
                            @foreach($tripPlan->inclusions_data ?: [] as $item)
                                <li class="flex items-start gap-3 text-sm text-green-700 font-bold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-400 mt-2 shrink-0"></span>
                                    {{ $item }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="bg-red-50/30 rounded-3xl p-8 border border-red-100">
                        <h3 class="text-lg font-black text-red-800 mb-6 uppercase tracking-widest flex items-center gap-2">
                            <span class="text-xl">✕</span> Not Included
                        </h3>
                        <ul class="space-y-3">
                            @foreach($tripPlan->exclusions_data ?: [] as $item)
                                <li class="flex items-start gap-3 text-sm text-red-400 font-bold">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-200 mt-2 shrink-0"></span>
                                    {{ $item }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- Message History (Customer View) --}}
                @if($tripPlan->messages->count() > 0)
                <div class="mt-12">
                    <h2 class="text-3xl font-display font-black text-gray-900 mb-8 border-l-4 border-gold-500 pl-6 uppercase tracking-tight">Recent Updates</h2>
                    <div class="space-y-4">
                        @foreach($tripPlan->messages as $msg)
                            <div class="p-6 rounded-3xl border border-gray-100 {{ $msg->sender_type === 'admin' ? 'bg-amber-50/30' : 'bg-white' }}">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-[10px] font-black uppercase tracking-widest {{ $msg->sender_type === 'admin' ? 'text-gold-600' : 'text-gray-400' }}">
                                        {{ $msg->sender_type === 'admin' ? 'Twina Safaris Support' : 'Your Message' }}
                                    </span>
                                    <span class="text-[9px] text-gray-400 font-bold">{{ $msg->created_at->format('d M, H:i') }}</span>
                                </div>
                                <p class="text-sm text-gray-700 leading-relaxed font-medium">{{ $msg->message }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar: Pricing & Actions --}}
            <aside class="space-y-8">
                <div class="bg-white rounded-[2.5rem] p-10 shadow-xl border border-gray-100 sticky top-28">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Investment Summary</h3>
                        <button onclick="window.print()" class="text-gold-600 hover:text-gold-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        </button>
                    </div>

                    <div class="text-center mb-10">
                        <div class="text-4xl font-display font-black text-safari-dark mb-1">
                            {{ $tripPlan->currency ?: 'USD' }} ${{ number_format($tripPlan->total_price, 0) }}
                        </div>
                        <p class="text-[10px] text-gold-600 font-black uppercase tracking-widest">Total for {{ $tripPlan->adults + $tripPlan->children }} Travelers</p>
                    </div>

                    <div class="space-y-4 mb-10 pt-6 border-t border-gray-50">
                        <div class="flex justify-between items-center text-xs font-bold uppercase tracking-widest text-gray-500">
                            <span>Per Person</span>
                            <span class="text-gray-900">${{ number_format($tripPlan->price_per_person, 0) }}</span>
                        </div>
                        @if($tripPlan->discount_amount > 0)
                        <div class="flex justify-between items-center text-xs font-bold uppercase tracking-widest text-red-500">
                            <span>Special Discount</span>
                            <span>-${{ number_format($tripPlan->discount_amount, 0) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between items-center text-xs font-bold uppercase tracking-widest text-gold-600 pt-2 border-t border-dashed border-gray-200">
                            <span>Deposit to Secure</span>
                            <span class="text-lg font-black">${{ number_format($tripPlan->deposit_amount, 0) }}</span>
                        </div>
                    </div>

                    @if($tripPlan->status !== 'accepted' && $tripPlan->status !== 'booking')
                    <div class="space-y-4">
                        <form action="{{ route('trip-plan.accept', $tripPlan->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-safari-dark text-white py-5 rounded-2xl font-black uppercase tracking-widest text-xs shadow-xl hover:scale-[1.02] active:scale-95 transition-all">
                                Accept Trip Plan
                            </button>
                        </form>

                        <div x-data="{ open: false }">
                            <button @click="open = !open" class="w-full bg-white border-2 border-gray-100 text-gray-500 py-5 rounded-2xl font-black uppercase tracking-widest text-[10px] hover:border-gold-500 hover:text-gold-600 transition-all">
                                Request Changes
                            </button>

                            <div x-show="open" x-cloak class="mt-4 p-6 bg-gray-50 rounded-2xl border border-gray-100 shadow-inner">
                                <form action="{{ route('trip-plan.changes', $tripPlan->id) }}" method="POST" class="space-y-4">
                                    @csrf
                                    <textarea name="message" rows="4" required class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-xs font-bold outline-none focus:border-gold-500" placeholder="What would you like to adjust?"></textarea>
                                    <button type="submit" class="w-full bg-gold-500 text-safari-dark py-3 rounded-xl font-black uppercase text-[10px] tracking-widest">Send Request</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @else
                        <div class="bg-green-50 p-6 rounded-2xl border border-green-200 text-center">
                            <span class="text-3xl block mb-2">🎉</span>
                            <p class="text-sm text-green-700 font-bold uppercase tracking-widest">Plan Accepted</p>
                            <p class="text-[10px] text-green-600 mt-2">Our team is preparing your final booking confirmation.</p>
                        </div>
                    @endif

                    <div class="mt-8 pt-8 border-t border-gray-50 text-center">
                        <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-4">Have Questions?</p>
                        <a href="https://wa.me/{{ preg_replace('/\D/','',\App\Models\Setting::get('site_phone')) }}" class="flex items-center justify-center gap-2 text-green-600 font-black uppercase text-[10px] tracking-widest">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            WhatsApp Our Guide
                        </a>
                    </div>
                </div>
            </aside>

        </div>
    </div>
</div>
@endsection
