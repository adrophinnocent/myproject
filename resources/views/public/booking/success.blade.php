@extends('public.layouts.app')

@section('title', 'Booking Confirmed - ' . (\App\Models\Setting::get('site_name', 'Twina Safaris')))

@section('content')
<div class="bg-[#fcfaf7] min-h-screen pt-32 pb-20">
    <div class="max-w-5xl mx-auto px-4">

        {{-- Main Success Card --}}
        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-gold-500/5 border border-gold-500/10 overflow-hidden relative">

            {{-- Luxury Accent Pattern --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-gold-500/5 rounded-full -mr-32 -mt-32 blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-gold-500/5 rounded-full -ml-32 -mb-32 blur-3xl pointer-events-none"></div>

            <div class="grid grid-cols-1 lg:grid-cols-12">

                {{-- Left Side: Success Message & Summary --}}
                <div class="lg:col-span-7 p-8 md:p-12 lg:border-r border-gray-100">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-16 h-16 bg-green-500 rounded-2xl flex items-center justify-center shadow-lg shadow-green-500/20 rotate-3">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <h1 class="font-display text-3xl md:text-4xl font-bold text-safari-dark">Booking Confirmed</h1>
                            <p class="text-gold-600 font-medium tracking-wide">Jambo, {{ $booking->first_name }}! Your adventure awaits.</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-safari-dark rounded-3xl p-8 text-white relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                                <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L1 21h22L12 2zm0 3.45l8.15 14.1H3.85L12 5.45z"/></svg>
                            </div>

                            <div class="relative z-10">
                                <span class="text-gold-400 text-xs font-bold uppercase tracking-[0.2em] mb-2 block">Reservation Details</span>
                                <h2 class="text-2xl font-display font-bold mb-6">{{ $booking->bookable_item->title }}</h2>

                                <div class="grid grid-cols-2 gap-8">
                                    <div>
                                        <p class="text-white/40 text-[10px] font-bold uppercase tracking-widest mb-1">Booking Reference</p>
                                        <p class="text-xl font-mono font-bold text-gold-500">{{ $booking->booking_reference }}</p>
                                    </div>
                                    <div>
                                        <p class="text-white/40 text-[10px] font-bold uppercase tracking-widest mb-1">Travel Date</p>
                                        <p class="text-xl font-bold">{{ $booking->travel_date->format('d M, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-white border border-gray-100 rounded-2xl p-6 shadow-sm">
                                <p class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Guests</p>
                                <p class="text-gray-900 font-bold">{{ $booking->number_of_adults }} Adults @if($booking->number_of_children > 0), {{ $booking->number_of_children }} Children @endif</p>
                            </div>
                            <div class="bg-gold-50/50 border border-gold-100 rounded-2xl p-6 shadow-sm">
                                <p class="text-gold-600/60 text-[10px] font-bold uppercase tracking-widest mb-1">Amount Due</p>
                                <p class="text-2xl font-display font-bold text-gold-600">${{ number_format($booking->total_price, 2) }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 p-4 bg-blue-50/50 border border-blue-100 rounded-2xl">
                            <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-xs text-blue-700 leading-relaxed">
                                <strong>Urgent Note:</strong> Your booking is currently reserved for the next <strong>24 hours</strong>. Please complete the payment process below to secure your spot.
                            </p>
                        </div>

                        {{-- Itinerary Preview Accordion --}}
                        <div class="mt-8 border-t border-gray-100 pt-8" x-data="{ openPreview: false }">
                            <button @click="openPreview = !openPreview" class="w-full flex items-center justify-between p-4 bg-gray-50 rounded-2xl hover:bg-gold-50 transition-colors group">
                                <div class="flex items-center gap-3">
                                    <span class="text-xl">🗺️</span>
                                    <span class="font-bold text-gray-900">Preview My Itinerary</span>
                                </div>
                                <svg class="w-5 h-5 text-gray-400 group-hover:text-gold-600 transition-transform" :class="openPreview ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div x-show="openPreview" x-collapse>
                                <div class="mt-4 space-y-4 max-h-96 overflow-y-auto pr-2 custom-scrollbar">
                                    @php
                                        $itinerary = $booking->bookable_item->itinerary ?? [];
                                        if (is_string($itinerary)) $itinerary = json_decode($itinerary, true);
                                    @endphp

                                    @forelse($itinerary as $index => $item)
                                        <div class="flex gap-4 p-4 border-l-2 border-gold-500 bg-white">
                                            <div class="font-black text-gold-600 text-xs shrink-0">DAY {{ $loop->iteration }}</div>
                                            <div>
                                                <h4 class="font-bold text-gray-900 text-sm mb-1">{{ is_array($item) ? ($item['title'] ?? '') : $item }}</h4>
                                                <p class="text-gray-500 text-xs leading-relaxed line-clamp-2">{{ is_array($item) ? ($item['description'] ?? '') : '' }}</p>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-gray-400 text-xs italic p-4">Detailed day-by-day itinerary will be shared upon payment confirmation.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Side: Payment & Next Steps --}}
                <div class="lg:col-span-5 bg-gray-50/50 p-8 md:p-12">
                    <h3 class="text-xl font-bold text-safari-dark mb-8 flex items-center gap-2">
                        Payment Details
                    </h3>

                    <div class="bg-white border border-gold-500/20 rounded-3xl p-8 shadow-xl shadow-gold-500/5 mb-8 relative">
                        <div class="absolute -top-3 -right-3 w-10 h-10 bg-gold-500 rounded-full flex items-center justify-center text-safari-dark shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </div>

                        <div class="space-y-6">
                            @php
                                $paymentMethod = $booking->payment_method === 'paypal' ? 'PayPal' : 'Bank Transfer';
                            @endphp

                            <div class="pb-4 border-b border-gray-100">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Account Name</span>
                                <span class="font-bold text-gray-900 text-lg">Twina Safaris Ltd</span>
                            </div>

                            <div class="grid grid-cols-1 gap-6">
                                <div>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Bank Name</span>
                                    <span class="font-bold text-gray-800">CRDB Bank PLC</span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">Account Number (USD)</span>
                                    <span class="font-bold text-gray-800 tracking-wider">0150 0000 0000</span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1">SWIFT / BIC Code</span>
                                    <span class="font-bold text-gray-800">CORUTZTXXXX</span>
                                </div>
                            </div>

                            <div class="bg-gold-500/10 p-4 rounded-xl border border-gold-500/20">
                                <p class="text-[11px] text-gold-700 font-bold leading-relaxed text-center">
                                    ⚠️ IMPORTANT: Always include your reference <span class="underline decoration-2">{{ $booking->booking_reference }}</span> in the payment description.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Download PDF Section --}}
                    <div class="mb-10">
                        <a href="{{ route('booking.download', $booking->booking_reference) }}" class="w-full btn-gold py-5 rounded-2xl font-bold flex items-center justify-center gap-3 shadow-xl shadow-gold-500/10 hover:scale-[1.02] active:scale-95 transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Download Full Itinerary (PDF)
                        </a>
                        <p class="text-center text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-3">Ready to print or save on your device</p>
                    </div>

                    <div class="space-y-6">
                        <h4 class="text-sm font-bold text-safari-dark uppercase tracking-widest">Your Next Steps</h4>
                        <div class="space-y-4">
                            @foreach([
                                ['num' => '1', 'text' => 'Make payment via Bank Transfer or M-Pesa'],
                                ['num' => '2', 'text' => 'Send proof of payment via WhatsApp'],
                                ['num' => '3', 'text' => 'Receive confirmation within 1–3 hours'],
                                ['num' => '4', 'text' => 'Receive your final safari itinerary']
                            ] as $step)
                            <div class="flex items-center gap-4">
                                <span class="w-6 h-6 rounded-full bg-safari-dark text-white text-[10px] font-bold flex items-center justify-center shrink-0">{{ $step['num'] }}</span>
                                <span class="text-sm text-gray-600 font-medium">{{ $step['text'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Action Buttons --}}
        <div class="mt-12 flex flex-col sm:flex-row items-center justify-center gap-6">
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\Setting::get('whatsapp_number', '+255754000000')) }}?text={{ urlencode('Hello Twina Safaris, I have just completed my booking. Reference: ' . $booking->booking_reference . '. Here is my proof of payment.') }}"
               target="_blank"
               class="bg-[#25D366] hover:bg-[#128C7E] text-white px-10 py-5 rounded-full font-bold shadow-xl shadow-green-500/20 flex items-center gap-3 transition-all hover:scale-105 group w-full sm:w-auto justify-center">
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.483 8.413-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.308 1.654zm6.757-4.051c1.535.913 3.041 1.389 4.617 1.389 5.234 0 9.493-4.258 9.495-9.493.002-5.233-4.258-9.491-9.493-9.491-5.235 0-9.493 4.258-9.495 9.493-.001 1.761.492 3.393 1.458 4.789l-.953 3.483 3.593-.941zm11.234-6.44c-.301-.15-1.782-.88-2.056-.981-.275-.1-.475-.15-.675.15-.199.301-.775.981-.95 1.179-.175.199-.35.225-.651.076-.301-.15-1.269-.468-2.418-1.581-.893-.796-1.496-1.78-1.671-2.08-.175-.301-.019-.464.132-.613.135-.134.301-.351.451-.526.15-.175.199-.301.301-.501.1-.199.05-.375-.025-.526-.05-.15-.675-1.625-.925-2.225-.244-.583-.491-.503-.675-.513-.175-.01-.375-.011-.575-.011s-.525.075-.8.375c-.275.301-1.05 1.026-1.05 2.501s1.075 2.901 1.225 3.101c.15.199 2.115 3.227 5.124 4.532.715.31 1.274.496 1.709.634.719.227 1.373.196 1.89.117.577-.088 1.782-.726 2.032-1.426.25-.7.25-1.301.175-1.426-.075-.125-.275-.199-.575-.349z"/></svg>
                Confirm via WhatsApp
            </a>
            <a href="{{ route('home') }}" class="text-safari-dark font-bold text-sm hover:text-gold-600 transition-colors uppercase tracking-widest border-b-2 border-transparent hover:border-gold-500 pb-1">
                Return to Homepage
            </a>
        </div>

        <div class="mt-16 text-center">
            <div class="inline-block bg-white border border-gold-500/20 px-8 py-6 rounded-[2rem] shadow-xl shadow-gold-500/5 animate-pulse-soft">
                <div class="flex items-center justify-center gap-3 mb-2">
                    <span class="flex h-2 w-2 rounded-full bg-gold-500"></span>
                    <p class="text-safari-dark font-black text-sm uppercase tracking-widest">Confirmation Sent</p>
                </div>
                <p class="text-gray-500 text-sm leading-relaxed">
                    A copy of this confirmation has been sent to <span class="text-gold-600 font-black">{{ $booking->email }}</span>.<br>
                    Please check your <span class="font-bold">inbox</span> (and spam folder) for further details.
                </p>
            </div>
        </div>

    </div>
</div>

<style>
    [x-cloak] { display: none !important; }

    @keyframes pulse-soft {
        0%, 100% { transform: scale(1); box-shadow: 0 20px 25px -5px rgb(212 175 55 / 0.05); }
        50% { transform: scale(1.03); box-shadow: 0 25px 30px -5px rgb(212 175 55 / 0.1); }
    }
    .animate-pulse-soft {
        animation: pulse-soft 4s ease-in-out infinite;
    }
</style>
@endsection
