@extends('public.layouts.app')

@section('title', 'Booking Confirmation - ' . $booking->booking_reference)

@section('content')
<div class="bg-[#fcfaf7] min-h-screen py-16">
    <div class="max-w-4xl mx-auto px-4">

        {{-- Success Banner --}}
        <div class="bg-[#0a0703] text-white rounded-3xl p-8 md:p-12 mb-8 shadow-2xl relative overflow-hidden">
            <div class="relative z-10">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-amber-500/20 border border-amber-500/40 rounded-full text-amber-400 font-black text-xs uppercase tracking-widest mb-6">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                    {{ __('BOOKING REQUEST RECEIVED') }}
                </div>

                <h1 class="font-display text-3xl md:text-5xl font-bold text-white mb-4">
                    {{ __('Asante!') }} {{ $booking->first_name }}, {{ __('Your Adventure Begins Here.') }}
                </h1>

                <p class="text-stone-300 text-sm md:text-base leading-relaxed max-w-2xl mb-8">
                    {{ __('We have received your booking request for') }} <strong class="text-white font-bold">{{ $booking->bookable_item->title ?? 'your safari' }}</strong>.
                    {{ __('Our team is reviewing lodge availability and vehicle arrangements for your travel dates.') }}
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-6 border-t border-stone-800">
                    <div>
                        <span class="block text-[10px] text-stone-400 uppercase font-black tracking-widest mb-1">{{ __('Booking Ref') }}</span>
                        <span class="font-mono text-xl font-bold text-amber-400">{{ $booking->booking_reference }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] text-stone-400 uppercase font-black tracking-widest mb-1">{{ __('Status') }}</span>
                        {!! $booking->status_badge !!}
                    </div>
                    <div>
                        <span class="block text-[10px] text-stone-400 uppercase font-black tracking-widest mb-1">{{ __('Travel Date') }}</span>
                        <span class="font-bold text-white text-base">{{ $booking->travel_date ? $booking->travel_date->format('d M Y') : 'To be confirmed' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Booking Summary Card --}}
        <div class="bg-white rounded-3xl p-8 border border-stone-200 shadow-sm space-y-8 mb-8">

            {{-- Tour Overview --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 pb-8 border-b border-stone-100">
                <div class="w-24 h-24 rounded-2xl overflow-hidden bg-stone-900 shrink-0">
                    <img src="{{ $booking->bookable_item->featured_image_url ?? asset('images/banners/hero_fallback.webp') }}"
                         alt="{{ $booking->bookable_item->title ?? 'Safari' }}"
                         class="w-full h-full object-cover">
                </div>
                <div class="flex-1">
                    <span class="text-xs font-black text-amber-600 uppercase tracking-widest block mb-1">
                        {{ $booking->bookable_item->category->name ?? 'Safari Package' }}
                    </span>
                    <h2 class="font-display text-2xl font-bold text-stone-900 mb-2">
                        {{ $booking->bookable_item->title ?? 'Custom Safari' }}
                    </h2>
                    <p class="text-xs text-stone-500 font-medium">
                        {{ $booking->number_of_adults }} {{ __('Adults') }}, {{ $booking->number_of_children }} {{ __('Children') }} • {{ __('Pickup') }}: {{ $booking->accommodation_preference ?: 'Arusha / Moshi' }}
                    </p>
                </div>
            </div>

            {{-- Financial Breakdown --}}
            <div>
                <h3 class="font-display text-xl font-bold text-stone-900 mb-4">{{ __('Financial Summary') }}</h3>
                <div class="bg-stone-50 rounded-2xl p-6 border border-stone-200/80 space-y-3 text-sm">
                    <div class="flex justify-between items-center text-stone-700">
                        <span>{{ __('Estimated Total Price') }}</span>
                        <span class="font-bold text-stone-900 text-base">{{ $booking->formatted_total_price }}</span>
                    </div>
                    <div class="flex justify-between items-center text-stone-700">
                        <span>{{ __('Deposit Required') }} ({{ number_format($booking->deposit_percentage, 0) }}%)</span>
                        <span class="font-bold text-amber-700 text-base">{{ $booking->formatted_deposit_required }}</span>
                    </div>
                    <div class="flex justify-between items-center text-stone-700 pt-3 border-t border-stone-200">
                        <span>{{ __('Remaining Balance') }} (Due 60 days before trip)</span>
                        <span class="font-bold text-stone-800 text-base">{{ $booking->formatted_balance_amount }}</span>
                    </div>
                </div>
            </div>

            {{-- Next Steps --}}
            <div class="space-y-4">
                <h3 class="font-display text-xl font-bold text-stone-900">{{ __('What Happens Next?') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                    <div class="p-5 bg-stone-50 rounded-2xl border border-stone-200/60">
                        <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-800 font-black flex items-center justify-center mb-3 text-sm">1</div>
                        <h4 class="font-bold text-stone-900 mb-1">{{ __('Availability Check') }}</h4>
                        <p class="text-stone-500 leading-relaxed">{{ __('Our specialists check hotel, park permits & guide schedules.') }}</p>
                    </div>
                    <div class="p-5 bg-stone-50 rounded-2xl border border-stone-200/60">
                        <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-800 font-black flex items-center justify-center mb-3 text-sm">2</div>
                        <h4 class="font-bold text-stone-900 mb-1">{{ __('Deposit Payment Link') }}</h4>
                        <p class="text-stone-500 leading-relaxed">{{ __('Once approved, you receive an email & WhatsApp with secure deposit link.') }}</p>
                    </div>
                    <div class="p-5 bg-stone-50 rounded-2xl border border-stone-200/60">
                        <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-800 font-black flex items-center justify-center mb-3 text-sm">3</div>
                        <h4 class="font-bold text-stone-900 mb-1">{{ __('Final Confirmation') }}</h4>
                        <p class="text-stone-500 leading-relaxed">{{ __('Upon deposit payment, your official PDF voucher and receipt are issued.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-4 pt-4 border-t border-stone-100">
                <a href="{{ route('booking.status', $booking->booking_reference) }}"
                   class="btn-gold flex-1 text-center py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-md">
                    {{ __('Track Booking Status') }}
                </a>

                <a href="{{ route('booking.download', $booking->booking_reference) }}"
                   class="px-8 py-4 bg-stone-100 hover:bg-stone-200 text-stone-800 rounded-2xl font-bold text-xs uppercase tracking-wider text-center transition-colors">
                    {{ __('Download PDF Request') }}
                </a>
            </div>

        </div>

        {{-- Assistance Note --}}
        <div class="text-center text-xs text-stone-500 font-medium">
            <p>{{ __('Questions about your booking?') }} {{ __('Email us at') }} <a href="mailto:twinasafaris@gmail.com" class="text-amber-700 font-bold underline">twinasafaris@gmail.com</a> {{ __('or WhatsApp') }} <a href="https://wa.me/255795482197" class="text-amber-700 font-bold underline">+255 795 482 197</a></p>
        </div>

    </div>
</div>
@endsection
