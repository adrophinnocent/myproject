@extends('public.layouts.app')

@section('title', 'Booking Status - ' . $booking->booking_reference)

@section('content')
<div class="bg-[#fcfaf7] min-h-screen py-16">
    <div class="max-w-4xl mx-auto px-4">

        {{-- Top Navigation --}}
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('booking.my-bookings') }}" class="text-xs font-bold text-stone-500 hover:text-amber-700 transition-colors uppercase tracking-widest flex items-center gap-1">
                ← {{ __('Back to My Bookings') }}
            </a>
            <span class="font-mono text-xs font-bold text-stone-400">Ref: {{ $booking->booking_reference }}</span>
        </div>

        {{-- Notifications --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-sm shadow-xs">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <p class="font-bold">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Header Status Card --}}
        <div class="bg-white rounded-3xl p-8 border border-stone-200 shadow-sm space-y-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 border-b border-stone-100">
                <div>
                    <span class="text-xs font-black text-amber-600 uppercase tracking-widest block mb-1">{{ __('BOOKING TRACKING') }}</span>
                    <h1 class="font-display text-2xl md:text-3xl font-bold text-stone-900">
                        {{ $booking->bookable_item->title ?? 'Tanzania Safari' }}
                    </h1>
                    <p class="text-xs text-stone-500 mt-1">
                        {{ __('Client') }}: <strong class="text-stone-800">{{ $booking->full_name }}</strong> • {{ __('Travel Date') }}: <strong class="text-stone-800">{{ $booking->travel_date ? $booking->travel_date->format('d M Y') : 'TBD' }}</strong>
                    </p>
                </div>
                <div class="shrink-0 flex items-center gap-3">
                    {!! $booking->status_badge !!}
                </div>
            </div>

            {{-- Progress Steps --}}
            <div>
                <h3 class="font-bold text-stone-900 text-xs uppercase tracking-wider mb-4">{{ __('Booking Journey Status') }}</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                    <div class="p-3.5 rounded-2xl border text-center {{ in_array($booking->status, ['pending', 'approved', 'deposit_paid', 'confirmed', 'completed']) ? 'bg-amber-50/50 border-amber-300 text-amber-900 font-bold' : 'bg-stone-50 border-stone-200 text-stone-400' }}">
                        <span>1. Request Received</span>
                    </div>
                    <div class="p-3.5 rounded-2xl border text-center {{ in_array($booking->status, ['approved', 'deposit_paid', 'confirmed', 'completed']) ? 'bg-amber-50/50 border-amber-300 text-amber-900 font-bold' : 'bg-stone-50 border-stone-200 text-stone-400' }}">
                        <span>2. Approved</span>
                    </div>
                    <div class="p-3.5 rounded-2xl border text-center {{ in_array($booking->status, ['deposit_paid', 'confirmed', 'completed']) ? 'bg-amber-50/50 border-amber-300 text-amber-900 font-bold' : 'bg-stone-50 border-stone-200 text-stone-400' }}">
                        <span>3. Deposit Paid</span>
                    </div>
                    <div class="p-3.5 rounded-2xl border text-center {{ in_array($booking->status, ['confirmed', 'completed']) ? 'bg-emerald-50 border-emerald-300 text-emerald-900 font-bold' : 'bg-stone-50 border-stone-200 text-stone-400' }}">
                        <span>4. Confirmed</span>
                    </div>
                </div>
            </div>

            {{-- Financial & Payment Details --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-stone-100 text-xs">
                <div class="p-4 bg-stone-50 rounded-2xl border border-stone-200/80">
                    <span class="block text-[10px] text-stone-400 uppercase font-black tracking-widest mb-1">{{ __('Total Trip Price') }}</span>
                    <span class="font-display text-xl font-bold text-stone-900">{{ $booking->formatted_total_price }}</span>
                </div>
                <div class="p-4 bg-stone-50 rounded-2xl border border-stone-200/80">
                    <span class="block text-[10px] text-stone-400 uppercase font-black tracking-widest mb-1">{{ __('Deposit Paid') }}</span>
                    <span class="font-display text-xl font-bold text-amber-700">{{ $booking->currency_symbol }}{{ number_format($booking->deposit_paid, 2) }}</span>
                </div>
                <div class="p-4 bg-stone-50 rounded-2xl border border-stone-200/80">
                    <span class="block text-[10px] text-stone-400 uppercase font-black tracking-widest mb-1">{{ __('Outstanding Balance') }}</span>
                    <span class="font-display text-xl font-bold text-stone-900">{{ $booking->formatted_balance_amount }}</span>
                </div>
            </div>

            {{-- Action CTA --}}
            @if($booking->status === 'approved' || $booking->status === 'pending')
                <div class="p-6 bg-amber-50 border border-amber-200 rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div>
                        <h4 class="font-bold text-amber-900 text-sm mb-1">{{ __('Deposit Required to Confirm') }}</h4>
                        <p class="text-xs text-amber-800">{{ __('Pay deposit of') }} <strong>{{ $booking->formatted_deposit_required }}</strong> {{ __('to secure safari vehicle & lodges.') }}</p>
                    </div>
                    <a href="{{ route('booking.payment', $booking->booking_reference) }}"
                       class="btn-gold px-6 py-3 rounded-xl font-black text-xs uppercase tracking-widest shrink-0 shadow-md">
                        {{ __('Pay Deposit Now') }}
                    </a>
                </div>
            @endif

            {{-- Document Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-stone-100">
                <a href="{{ route('booking.download', $booking->booking_reference) }}"
                   class="flex-1 px-6 py-3.5 bg-stone-900 hover:bg-black text-white rounded-xl font-bold text-xs uppercase tracking-wider text-center transition-colors flex items-center justify-center gap-2 shadow-xs">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>{{ __('Download Trip PDF Voucher') }}</span>
                </a>
            </div>

        </div>

        {{-- Payment History Table --}}
        <div class="bg-white rounded-3xl p-8 border border-stone-200 shadow-sm space-y-4">
            <h3 class="font-bold text-stone-900 text-sm uppercase tracking-wider">{{ __('Payment History & Records') }}</h3>
            @if($booking->payments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-stone-700">
                        <thead>
                            <tr class="border-b border-stone-200 text-stone-400 uppercase font-black text-[10px] tracking-wider">
                                <th class="py-3 px-2">Ref</th>
                                <th class="py-3 px-2">Method</th>
                                <th class="py-3 px-2">Type</th>
                                <th class="py-3 px-2">Amount</th>
                                <th class="py-3 px-2">Status</th>
                                <th class="py-3 px-2">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @foreach($booking->payments as $p)
                            <tr>
                                <td class="py-3 px-2 font-mono font-bold">{{ $p->transaction_reference }}</td>
                                <td class="py-3 px-2 uppercase font-semibold">{{ $p->gateway }}</td>
                                <td class="py-3 px-2 uppercase">{{ $p->payment_type }}</td>
                                <td class="py-3 px-2 font-bold text-stone-900">{{ $p->currency }} {{ number_format($p->amount, 2) }}</td>
                                <td class="py-3 px-2">
                                    <span class="px-2.5 py-0.5 rounded-full font-bold text-[10px] uppercase {{ $p->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $p->status }}
                                    </span>
                                </td>
                                <td class="py-3 px-2 text-stone-500">{{ $p->created_at->format('d M Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-xs text-stone-500 italic py-4 border border-dashed border-stone-200 rounded-2xl text-center">
                    {{ __('No online or bank transactions recorded yet.') }}
                </p>
            @endif
        </div>

    </div>
</div>
@endsection
