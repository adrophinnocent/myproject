@extends('public.layouts.app')

@section('title', 'My Safari Bookings - Twina Safaris')

@section('content')
<div class="bg-[#fcfaf7] min-h-screen py-16">
    <div class="max-w-4xl mx-auto px-4">

        <div class="text-center mb-10">
            <span class="text-xs font-black text-amber-600 uppercase tracking-widest block mb-2">{{ __('CLIENT PORTAL') }}</span>
            <h1 class="font-display text-3xl md:text-4xl font-bold text-stone-900 mb-3">{{ __('Track Your Safari Bookings') }}</h1>
            <p class="text-stone-600 text-sm max-w-md mx-auto">
                {{ __('Enter your Booking Reference Code and the email address used for the booking to view its status and payment history.') }}
            </p>
        </div>

        {{-- Search Card --}}
        <div class="bg-white rounded-3xl p-8 border border-stone-200 shadow-sm mb-8">
            <form action="{{ route('booking.my-bookings.search') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="booking_reference" class="block text-[10px] font-black text-stone-500 uppercase tracking-widest mb-2">{{ __('Booking Reference') }}</label>
                        <input id="booking_reference" type="text" name="reference" value="{{ $reference ?? '' }}" required
                               placeholder="{{ __('e.g. TWN-2026-A1B2C3') }}"
                               class="w-full bg-stone-50 border border-stone-300 rounded-2xl px-5 py-4 text-sm font-semibold text-stone-800 outline-none focus:border-amber-500 transition-all">
                    </div>
                    <div>
                        <label for="booking_email" class="block text-[10px] font-black text-stone-500 uppercase tracking-widest mb-2">{{ __('Email Address') }}</label>
                        <input id="booking_email" type="email" name="email" value="{{ $email ?? '' }}" required
                               placeholder="{{ __('The email used for booking') }}"
                               class="w-full bg-stone-50 border border-stone-300 rounded-2xl px-5 py-4 text-sm font-semibold text-stone-800 outline-none focus:border-amber-500 transition-all">
                    </div>
                </div>
                <button type="submit" class="btn-gold w-full py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-md">
                    {{ __('Find Booking') }}
                </button>
            </form>

            @if($errors->any())
                <div class="mt-4 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs">
                    {{ $errors->first() }}
                </div>
            @endif
        </div>

        {{-- Results List --}}
        @if(($reference ?? '') !== '' && ($email ?? '') !== '')
            @if(isset($bookings) && $bookings->count() > 0)
                <div class="space-y-4">
                    <h2 class="font-bold text-stone-900 text-xs uppercase tracking-wider">{{ __('Found') }} {{ $bookings->count() }} {{ __('Booking(s)') }}</h2>
                    @foreach($bookings as $b)
                    <div class="bg-white rounded-3xl p-6 border border-stone-200 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-6 hover:border-amber-500/60 transition-all">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <span class="font-mono text-sm font-bold text-amber-700 bg-amber-50 px-3 py-1 rounded-lg border border-amber-200/60">{{ $b->booking_reference }}</span>
                                {!! $b->status_badge !!}
                            </div>
                            <h3 class="font-display font-bold text-stone-900 text-base mb-1">{{ $b->bookable_item->title ?? 'Tanzania Safari' }}</h3>
                            <p class="text-xs text-stone-500 font-medium">
                                {{ __('Travel Date') }}: <strong class="text-stone-800">{{ $b->travel_date ? $b->travel_date->format('d M Y') : 'TBD' }}</strong> &middot; {{ __('Total') }}: <strong class="text-stone-800">{{ $b->formatted_total_price }}</strong>
                            </p>
                        </div>
                        <div class="shrink-0 flex items-center gap-3">
                            <a href="{{ route('booking.status', $b->token) }}" class="btn-gold px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider">
                                {{ __('View Safari Booking Details') }}
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-white rounded-3xl border border-stone-200/80 text-stone-500 text-xs space-y-3">
                    <p class="font-bold text-stone-800 text-sm">{{ __('No booking found for those details') }}</p>
                    <p>{{ __('Please double-check your booking reference number and email address.') }}</p>
                </div>
            @endif
        @endif

    </div>
</div>
@endsection
