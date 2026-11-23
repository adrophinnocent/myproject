@extends('public.layouts.app')

@section('title', 'Deposit Payment - ' . $booking->booking_reference)

@section('content')
<div class="bg-[#fcfaf7] min-h-screen py-16" x-data="{
    method: 'bank_transfer',
    payType: 'deposit'
}">
    <div class="max-w-4xl mx-auto px-4">

        {{-- Top Header --}}
        <div class="mb-8">
            <span class="text-xs font-black text-amber-600 uppercase tracking-widest block mb-2">{{ __('SECURE MANUAL PAYMENT') }}</span>
            <h1 class="font-display text-3xl md:text-4xl font-bold text-stone-900">
                {{ __('Deposit Payment for') }} {{ $booking->booking_reference }}
            </h1>
            <p class="text-stone-600 text-sm mt-1">
                {{ $booking->bookable_item->title ?? 'Tanzania Safari' }} &middot; {{ __('Guest') }}: {{ $booking->full_name }}
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

        @if(session('error'))
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-sm">
                <p class="font-bold">{{ session('error') }}</p>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
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

                            {{-- Bank Wire Transfer --}}
                            <button type="button" @click="method = 'bank_transfer'"
                                    class="p-4 rounded-2xl border text-left flex items-center justify-between transition-all cursor-pointer"
                                    :class="method === 'bank_transfer' ? 'border-amber-500 bg-amber-50/30 ring-2 ring-amber-500/20' : 'border-stone-200 bg-white hover:bg-stone-50'">
                                <div class="flex items-center gap-3">
                                    <span class="text-xl">🏦</span>
                                    <div>
                                        <h4 class="font-bold text-stone-900 text-xs">{{ __('Direct Bank Transfer') }}</h4>
                                        <p class="text-[10px] text-stone-500">{{ __('SWIFT / International Wire') }}</p>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-amber-700" x-show="method === 'bank_transfer'">✓</span>
                            </button>

                            {{-- M-Pesa --}}
                            <button type="button" @click="method = 'mpesa'"
                                    class="p-4 rounded-2xl border text-left flex items-center justify-between transition-all cursor-pointer"
                                    :class="method === 'mpesa' ? 'border-amber-500 bg-amber-50/30 ring-2 ring-amber-500/20' : 'border-stone-200 bg-white hover:bg-stone-50'">
                                <div class="flex items-center gap-3">
                                    <span class="text-xl">📱</span>
                                    <div>
                                        <h4 class="font-bold text-stone-900 text-xs">{{ __('M-Pesa') }}</h4>
                                        <p class="text-[10px] text-stone-500">{{ __('Vodacom Tanzania') }}</p>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-amber-700" x-show="method === 'mpesa'">✓</span>
                            </button>

                            {{-- Tigo Pesa --}}
                            <button type="button" @click="method = 'tigopesa'"
                                    class="p-4 rounded-2xl border text-left flex items-center justify-between transition-all cursor-pointer"
                                    :class="method === 'tigopesa' ? 'border-amber-500 bg-amber-50/30 ring-2 ring-amber-500/20' : 'border-stone-200 bg-white hover:bg-stone-50'">
                                <div class="flex items-center gap-3">
                                    <span class="text-xl">📲</span>
                                    <div>
                                        <h4 class="font-bold text-stone-900 text-xs">{{ __('Tigo Pesa') }}</h4>
                                        <p class="text-[10px] text-stone-500">{{ __('Tigo Tanzania') }}</p>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-amber-700" x-show="method === 'tigopesa'">✓</span>
                            </button>

                            {{-- Airtel Money --}}
                            <button type="button" @click="method = 'airtelmoney'"
                                    class="p-4 rounded-2xl border text-left flex items-center justify-between transition-all cursor-pointer"
                                    :class="method === 'airtelmoney' ? 'border-amber-500 bg-amber-50/30 ring-2 ring-amber-500/20' : 'border-stone-200 bg-white hover:bg-stone-50'">
                                <div class="flex items-center gap-3">
                                    <span class="text-xl">💳</span>
                                    <div>
                                        <h4 class="font-bold text-stone-900 text-xs">{{ __('Airtel Money') }}</h4>
                                        <p class="text-[10px] text-stone-500">{{ __('Airtel Tanzania') }}</p>
                                    </div>
                                </div>
                                <span class="text-xs font-bold text-amber-700" x-show="method === 'airtelmoney'">✓</span>
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
                    <div x-show="method !== 'bank_transfer'" x-cloak class="p-6 bg-stone-50 border border-stone-200 rounded-2xl space-y-3 text-xs">
                        <h4 class="font-bold text-stone-900 text-sm uppercase tracking-wider text-amber-800">{{ __('Tanzania Mobile Money Instructions') }}</h4>
                        <p class="text-stone-700 font-medium">
                            {{ __('Send payment to') }}
                            <strong class="text-stone-900 font-bold">
                                <span x-show="method === 'mpesa'">M-Pesa: {{ $mobileMoney['mpesa'] }}</span>
                                <span x-show="method === 'tigopesa'">Tigo Pesa: {{ $mobileMoney['tigopesa'] }}</span>
                                <span x-show="method === 'airtelmoney'">Airtel Money: {{ $mobileMoney['airtelmoney'] }}</span>
                            </strong>
                        </p>
                        <p class="text-[11px] text-stone-500 italic">
                            * {{ __('After sending, enter your transaction ID / confirmation code in the notes box below.') }}
                        </p>
                    </div>

                    {{-- Form Submission --}}
                    <form action="{{ route('booking.payment.process', $booking->token) }}" method="POST" class="space-y-4 pt-4 border-t border-stone-100">
                        @csrf
                        <input type="hidden" name="gateway" :value="method">
                        <input type="hidden" name="payment_type" :value="payType">

                        <div>
                            <label class="block text-xs font-bold text-stone-700 uppercase tracking-wider mb-1">{{ __('Payment / Transaction Reference (Optional)') }}</label>
                            <input type="text" name="transaction_notes" maxlength="1000" placeholder="{{ __('e.g. Bank transfer reference or M-Pesa transaction ID...') }}"
                                   class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-xs focus:ring-2 focus:ring-amber-500/30 focus:border-amber-500 outline-none">
                        </div>

                        <p class="text-[11px] text-stone-500 leading-relaxed">
                            {{ __('This form records your payment details for manual verification. No card details are collected. Our team will confirm the transaction and update your booking status.') }}
                        </p>

                        <button type="submit" class="btn-gold w-full text-center py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg">
                            {{ __('Submit Payment for Verification') }}
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
                        <span>{{ __('Deposit') }} ({{ number_format($booking->deposit_percentage, 0) }}%)</span>
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
                        {{ __('All payments are protected under our official registration in Tanzania. You will receive a digital receipt and an updated booking voucher after verification.') }}
                    </p>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
