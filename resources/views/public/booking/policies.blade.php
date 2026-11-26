@extends('public.layouts.app')

@section('title', 'Booking Terms & Policies | Twina Safaris')
@section('meta_description', 'Review Twina Safaris booking terms, deposit rules, balance deadlines and cancellation policies so you can reserve a Tanzania safari with confidence.')

@section('content')
<div class="bg-[#fcfaf7] min-h-screen py-16">
    <div class="max-w-4xl mx-auto px-4 space-y-8">

        <div class="text-center">
            <span class="text-xs font-black text-amber-600 uppercase tracking-widest block mb-2">{{ __('TWINA SAFARIS POLICIES') }}</span>
            <h1 class="font-display text-3xl md:text-5xl font-bold text-stone-900 mb-4">{{ __('Booking Terms & Deposit Policy') }}</h1>
            <p class="text-stone-600 text-sm max-w-xl mx-auto leading-relaxed">
                {{ __('Transparency and peace of mind for your safari planning. Please review our deposit, balance, and cancellation terms below.') }}
            </p>
        </div>

        <div class="bg-white rounded-3xl p-8 md:p-12 border border-stone-200 shadow-sm space-y-8 text-stone-700 text-sm leading-relaxed">

            {{-- Section 1: Deposit & Confirmation --}}
            <div class="space-y-3">
                <h2 class="font-display text-2xl font-bold text-stone-900 border-b-2 border-amber-500/30 pb-2 flex items-center gap-2">
                    <span>1. Deposit & Booking Confirmation</span>
                </h2>
                <ul class="space-y-2 list-disc pl-5">
                    <li>A <strong>30% deposit</strong> is required upon approval of your booking request to lock in hotel lodges, national park entry permits, and private safari vehicle allocations.</li>
                    <li>Official booking vouchers and payment receipts are issued immediately upon receipt of deposit.</li>
                    <li>Deposits can be paid via SWIFT Direct Bank Transfer or Tanzania Mobile Money (M-Pesa, Tigo Pesa, Airtel Money). All payments are verified manually by our team.</li>
                </ul>
            </div>

            {{-- Section 2: Balance Payment --}}
            <div class="space-y-3">
                <h2 class="font-display text-2xl font-bold text-stone-900 border-b-2 border-amber-500/30 pb-2 flex items-center gap-2">
                    <span>2. Remaining Balance Payment</span>
                </h2>
                <ul class="space-y-2 list-disc pl-5">
                    <li>The remaining 70% balance is due <strong>65 days prior to your safari start date</strong>.</li>
                    <li>For bookings made within 65 days of travel, the full trip amount is due upon booking approval.</li>
                    <li>Reminders are automatically sent with secure online balance payment links.</li>
                </ul>
            </div>

            {{-- Section 3: Cancellation & Refund Policy --}}
            <div class="space-y-3">
                <h2 class="font-display text-2xl font-bold text-stone-900 border-b-2 border-amber-500/30 pb-2 flex items-center gap-2">
                    <span>3. Cancellation & Refund Policy</span>
                </h2>
                <div class="overflow-x-auto my-4">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-stone-100 text-stone-900 font-bold border-b border-stone-200">
                                <th class="p-3">Notice Period Before Departure</th>
                                <th class="p-3">Cancellation Fee / Refund Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            <tr>
                                <td class="p-3">61+ Days prior to travel</td>
                                <td class="p-3 font-bold text-emerald-800">100% Refund of Deposit (minus bank processing fee)</td>
                            </tr>
                            <tr>
                                <td class="p-3">31 - 60 Days prior to travel</td>
                                <td class="p-3 font-bold text-amber-800">Deposit retained; remaining balance refunded</td>
                            </tr>
                            <tr>
                                <td class="p-3">15 - 30 Days prior to travel</td>
                                <td class="p-3 font-bold text-amber-900">50% Total trip price refunded</td>
                            </tr>
                            <tr>
                                <td class="p-3">0 - 14 Days prior to travel</td>
                                <td class="p-3 font-bold text-rose-800">Non-refundable due to pre-paid park & lodge commitments</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Section 4: Travel Insurance --}}
            <div class="space-y-3">
                <h2 class="font-display text-2xl font-bold text-stone-900 border-b-2 border-amber-500/30 pb-2">
                    <span>4. Travel Insurance Requirement</span>
                </h2>
                <p>
                    We strongly recommend that all travelers purchase comprehensive international travel insurance covering medical emergency, trip cancellation, baggage loss, and air evacuation.
                </p>
            </div>

        </div>

    </div>
</div>
@endsection
