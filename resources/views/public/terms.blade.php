@extends('public.layouts.app')

@section('title', 'Terms of Service - Twina Safaris')
@section('meta_description', 'Review the Terms of Service for safari bookings, deposits, cancellations, and travel agreements with Twina Safaris.')

@section('content')
<div class="relative pt-32 pb-16 bg-[#0a0703] text-white">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <span class="text-gold-400 text-xs font-bold uppercase tracking-widest block mb-2">Terms & Conditions</span>
        <h1 class="font-display text-4xl md:text-5xl font-bold">Terms of Service</h1>
        <p class="text-gray-400 text-sm mt-3">Last updated: {{ date('F d, Y') }}</p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 py-16 text-gray-700 leading-relaxed space-y-8 font-normal">
    <section class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm space-y-4">
        <h2 class="text-2xl font-bold font-display text-gray-900">1. Booking Confirmation & Deposits</h2>
        <p>A 30% deposit is required to confirm your safari reservation. The remaining balance must be settled 65 days prior to the travel arrival date unless otherwise agreed upon in writing.</p>
    </section>

    <section class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm space-y-4">
        <h2 class="text-2xl font-bold font-display text-gray-900">2. Cancellation & Refund Policy</h2>
        <p>Cancellations made 60+ days prior to arrival are subject to standard lodge cancellation fees and processing costs. Cancellations made within 30 days of arrival are non-refundable due to non-refundable lodge bookings and park permit reservations.</p>
    </section>

    <section class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm space-y-4">
        <h2 class="text-2xl font-bold font-display text-gray-900">3. Travel Insurance</h2>
        <p>All guests are strongly advised to purchase comprehensive travel, medical, and cancellation insurance prior to departure to cover medical emergencies, trip interruptions, or flight cancellations.</p>
    </section>

    <section class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm space-y-4">
        <h2 class="text-2xl font-bold font-display text-gray-900">4. Governing Law</h2>
        <p>These terms and conditions are governed by and construed in accordance with the laws of the United Republic of Tanzania.</p>
    </section>
</div>
@endsection
