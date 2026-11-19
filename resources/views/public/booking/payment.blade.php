@extends('public.layouts.app')

@section('title', 'Secure Deposit Payment - ' . $booking->booking_reference)

@section('content')
<div class="bg-[#fcfaf7] min-h-screen py-16" x-data="{
    method: 'visa_mastercard',
    payType: 'deposit',
    depositAmount: {{ $booking->calculated_deposit_required }},
    fullAmount: {{ $booking->total_price ?? 0 }},
    get activeAmount() {
        return this.payType === 'full' ? this.fullAmount : this.depositAmount;
    }
}">
    <div class="max-w-4xl mx-auto px-4">

        {{-- Top Header --}}
        <div class="mb-8">
            <span class="text-xs font-black text-amber-600 uppercase tracking-widest block mb-2">{{ __('SECURE PAYMENT GATEWAY') }}</span>
            <h1 class="font-display text-3xl md:text-4xl font-bold text-stone-900">
                {{ __('Deposit Payment for') }} {{ $booking->booking_reference }}
            </h1>
            <p class="text-stone-600 text-sm mt-1">
                {{ $booking->bookable_item->title ?? 'Tanzania Safari' }} • {{ __('Guest') }}: {{ $booking->full_name }}
            </p>
        </div>

        {{-- Notifications --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-sm">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <p class="font-bold">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('info'))
            <div class="mb-6 p-4 bg-sky-50 border border-sky-200 text-sky-800 rounded-2xl text-sm">
                <p class="font-bold">{{ session('info') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            {{-- Main Payment Options --}}
            <div class="lg:col-span-8">
                <div class="bg-white rounded-3xl p-8 border border-stone-200 shadow-sm space-y-8">

                    {{-- 1. Select Payment Type (Deposit vs Full) --}}
                    <div>
                        <h3 class="font-bold text-stone-900 text-sm uppercase tracking-wider mb-4">{{ __('1. Choose Payment Type') }}</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="p-5 border-2 rounded-2xl cursor-pointer transition-all flex flex-col justify-between"
                                   :class="payType === 'deposit' ? 'border-amber-500 bg-amber-50/20' : 'border-stone-200 bg-stone-50'">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-black text-stone-900 text-sm uppercase">{{ __('Pay Deposit') }} ({{ number_format($booking->deposit_percentage, 0) }}%)</span>
                                    <input type="radio" name="pay_type_radio" value="deposit" x-model="payType" class="text-amber-600 focus:ring-amber-500">
                                </div>
                                <span class="font-display text-2xl font-bold text-amber-700">{{ $booking->formatted_deposit_required }}</span>
                                <span class="text-[11px] text-stone-500 mt-1">{{ __('Secures your booking immediately.') }}</span>
                            </label>

                            <label class="p-5 border-2 rounded-2xl cursor-pointer transition-all flex flex-col justify-between"
                                   :class="payType === 'full' ? 'border-amber-500 bg-amber-50/20' : 'border-stone-200 bg-stone-50'">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-black text-stone-900 text-sm uppercase">{{ __('Pay Full Amount') }}</span>
                                    <input type="radio" name="pay_type_radio" value="full" x-model="payType" class="text-amber-600 focus:ring-amber-500">
                                </div>
                                <span class="font-display text-2xl font-bold text-stone-900">{{ $booking->formatted_total_price }}</span>
                                <span class="text-[11px] text-stone-500 mt-1">{{ __('Full trip payment in one go.') }}</span>
                            </label>
                        </div>
                    </div>

                    {{-- 2. Select Payment Method --}}
                    <div>
                        <h3 class="font-bold text-stone-900 text-sm uppercase tracking-wider mb-4">{{ __('2. Choose Payment Method') }}</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                            {{-- Visa / Mastercard --}}
                            <button type="button" @click="method = 'visa_mastercard'"
                                    class="p-4 rounded-2xl border text-left flex items-center justify-between transition-all cursor-pointer"
                                    :class="method === 'visa_mastercard' ? 'border-amber-500 bg-amber-50/30 ring-2 ring-amber-500/20' : 'border-stone-200 bg-white hover:bg-stone-50'">
                                <div class="flex items-center gap-3">
                                    <span class="text-xl">💳</span>
                                    <div>
                                        <h4 class="font-bold text-stone-900 text-xs">{{ __('Visa / Mastercard') }}</h4>
                                        <p class="text-[10px] text-stone-500">{{ __('International Credit/Debit') }}</p>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-amber-700" x-show="method === 'visa_mastercard'">✓</span>
                            </button>

                            {{-- PayPal --}}
                            <button type="button" @click="method = 'paypal'"
                                    class="p-4 rounded-2xl border text-left flex items-center justify-between transition-all cursor-pointer"
                                    :class="method === 'paypal' ? 'border-amber-500 bg-amber-50/30 ring-2 ring-amber-500/20' : 'border-stone-200 bg-white hover:bg-stone-50'">
                                <div class="flex items-center gap-3">
                                    <span class="text-xl">🅿️</span>
                                    <div>
                                        <h4 class="font-bold text-stone-900 text-xs">{{ __('PayPal') }}</h4>
                                        <p class="text-[10px] text-stone-500">{{ __('PayPal Account / Cards') }}</p>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-amber-700" x-show="method === 'paypal'">✓</span>
                            </button>

                            {{-- Direct Bank Wire Transfer --}}
                            <button type="button" @click="method = 'bank_transfer'"
                                    class="p-4 rounded-2xl border text-left flex items-center justify-between transition-all cursor-pointer"
                                    :class="method === 'bank_transfer' ? 'border-amber-500 bg-amber-50/30 ring-2 ring-amber-500/20' : 'border-stone-200 bg-white hover:bg-stone-50'">
                                <div class="flex items-center gap-3">
                                    <span class="text-xl">🏦</span>
                                    <div>
                                        <h4 class="font-bold text-stone-900 text-xs">{{ __('Direct Bank Wire') }}</h4>
                                        <p class="text-[10px] text-stone-500">{{ __('SWIFT / Bank Transfer') }}</p>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-amber-700" x-show="method === 'bank_transfer'">✓</span>
                            </button>

                            {{-- Mobile Money (M-Pesa / Tigo Pesa) --}}
                            <button type="button" @click="method = 'mpesa'"
                                    class="p-4 rounded-2xl border text-left flex items-center justify-between transition-all cursor-pointer"
                                    :class="method === 'mpesa' ? 'border-amber-500 bg-amber-50/30 ring-2 ring-amber-500/20' : 'border-stone-200 bg-white hover:bg-stone-50'">
                                <div class="flex items-center gap-3">
                                    <span class="text-xl">📱</span>
                                    <div>
                                        <h4 class="font-bold text-stone-900 text-xs">{{ __('M-Pesa / Tigo / Airtel') }}</h4>
                                        <p class="text-[10px] text-stone-500">{{ __('Tanzania Mobile Money') }}</p>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-amber-700" x-show="method === 'mpesa'">✓</span>
                            </button>

                        </div>
                    </div>

                    {{-- Bank Wire Details Display --}}
                    <div x-show="method === 'bank_transfer'" x-cloak class="p-6 bg-stone-50 border border-stone-200 rounded-2xl space-y-3 text-xs">
                        <h4 class="font-bold text-stone-900 text-sm uppercase tracking-wider text-amber-800">{{ __('Twina Safaris Bank Account Details') }}</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-stone-700">
                            <div><span class="font-bold text-stone-900">{{ __('Bank Name') }}:</span> {{ $bankDetails['bank_name'] }}</div>
                            <div><span class="font-bold text-stone-900">{{ __('Account Name') }}:</span> {{ $bankDetails['account_name'] }}</div>
                            <div><span class="font-bold text-stone-900">{{ __('Account Number') }}:</span> {{ $bankDetails['account_number'] }}</div>
                            <div><span class="font-bold text-stone-900">{{ __('SWIFT Code') }}:</span> {{ $bankDetails['swift_code'] }}</div>
                            <div class="sm:col-span-2"><span class="font-bold text-stone-900">{{ __('Branch') }}:</span> {{ $bankDetails['branch'] }}</div>
                        </div>
                        <p class="text-[11px] text-stone-500 italic pt-2 border-t border-stone-200">
                            * {{ __('Please include your Booking Reference') }} <strong class="text-stone-900 font-bold">{{ $booking->booking_reference }}</strong> {{ __('in the bank transfer notes.') }}
                        </p>
                    </div>

                    {{-- Mobile Money Details Display --}}
                    <div x-show="method === 'mpesa'" x-cloak class="p-6 bg-stone-50 border border-stone-200 rounded-2xl space-y-3 text-xs">
                        <h4 class="font-bold text-stone-900 text-sm uppercase tracking-wider text-amber-800">{{ __('Tanzania Mobile Money Instructions') }}</h4>
                        <p class="text-stone-700 font-medium">
                            {{ __('Send payment to') }} <strong class="text-stone-900 font-bold">Twina Safaris Vodacom M-Pesa / Tigo Pesa: +255 795 482 197</strong>.
                        </p>
                        <p class="text-[11px] text-stone-500 italic">
                            * {{ __('After sending, paste your transaction ID / MPESA confirmation code in the notes box below.') }}
                        </p>
                    </div>

                    {{-- Form Submission --}}
                    <form action="{{ route('booking.payment.process', $booking->booking_reference) }}" method="POST" class="space-y-4 pt-4 border-t border-stone-100">
                        @csrf
                        <input type="hidden" name="gateway" :value="method">
                        <input type="hidden" name="payment_type" :value="payType">
                        <input type="hidden" name="amount" :value="activeAmount">

                        <div>
                            <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">{{ __('Payment / Bank Reference Notes (Optional)') }}</label>
                            <input type="text" name="transaction_notes" placeholder="{{ __('e.g. Bank transfer reference or M-Pesa transaction ID...') }}"
                                   class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-xs focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 outline-none">
                        </div>

                        <button type="submit" class="btn-gold w-full text-center py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg">
                            <span x-text="'Proceed with ' + activeAmount + ' ' + '{{ $booking->currency }}' + ' Payment'"></span>
                        </button>
                    </form>

                </div>
            </div>

            {{-- Sidebar Summary --}}
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-3xl p-6 border border-stone-200 shadow-sm space-y-4 text-xs">
                    <h3 class="font-bold text-stone-900 text-sm uppercase tracking-wider mb-3">{{ __('Order Summary') }}</h3>

                    <div class="flex justify-between items-center text-stone-600 pb-3 border-b border-stone-100">
                        <span>{{ __('Booking Ref') }}</span>
                        <span class="font-mono font-bold text-stone-900">{{ $booking->booking_reference }}</span>
                    </div>

                    <div class="flex justify-between items-center text-stone-600 pb-3 border-b border-stone-100">
                        <span>{{ __('Trip Total') }}</span>
                        <span class="font-bold text-stone-900">{{ $booking->formatted_total_price }}</span>
                    </div>

                    <div class="flex justify-between items-center text-stone-600 pb-3 border-b border-stone-100">
                        <span>{{ __('Deposit (20%)') }}</span>
                        <span class="font-bold text-amber-700">{{ $booking->formatted_deposit_required }}</span>
                    </div>

                    <div class="flex justify-between items-center text-stone-600">
                        <span>{{ __('Balance Due') }}</span>
                        <span class="font-bold text-stone-900">{{ $booking->formatted_balance_amount }}</span>
                    </div>
                </div>

                <div class="p-6 bg-[#0a0703] text-white rounded-3xl space-y-3 text-xs">
                    <h4 class="font-bold text-amber-400 uppercase tracking-widest">{{ __('Twina Safaris Guarantee') }}</h4>
                    <p class="text-stone-300 leading-relaxed">
                        {{ __('All payments are protected under our official registration in Tanzania. You will receive an instant digital receipt and updated booking voucher.') }}
                    </p>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
