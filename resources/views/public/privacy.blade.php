@extends('public.layouts.app')

@section('title', 'Privacy Policy - Twina Safaris')
@section('meta_description', 'Read how Twina Safaris collects, uses, stores and protects your personal data, including booking details, newsletter preferences and your privacy choices.')

@section('schema')
    @include('public.partials.schema-breadcrumb', [
        'crumbs' => [
            ['name' => 'Home', 'url' => \App\Helpers\SeoHelper::siteUrl().'/'],
            ['name' => 'Privacy Policy', 'url' => \App\Helpers\SeoHelper::canonicalUrl(request())],
        ],
    ])
@endsection

@section('content')
<div class="relative pt-32 pb-16 bg-[#0a0703] text-white">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <span class="text-gold-400 text-xs font-bold uppercase tracking-widest block mb-2">Legal & Governance</span>
        <h1 class="font-display text-4xl md:text-5xl font-bold">Privacy Policy</h1>
        <p class="text-gray-400 text-sm mt-3">Last updated: {{ date('F d, Y') }}</p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 py-16 text-gray-700 leading-relaxed space-y-8 font-normal">
    <section class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm space-y-4">
        <h2 class="text-2xl font-bold font-display text-gray-900">1. Information We Collect</h2>
        <p>At Twina Safaris, we collect personal information required to process your safari bookings, inquiries, and trip planning requests. This includes your full name, email address, phone number, nationality, and travel preferences.</p>
    </section>

    <section class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm space-y-4">
        <h2 class="text-2xl font-bold font-display text-gray-900">2. How We Use Your Data</h2>
        <p>Your information is strictly used to communicate with you regarding your safari itineraries, lodge reservations, park permits, and customer support. We do not sell or rent your personal data to third parties.</p>
    </section>

    <section class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm space-y-4">
        <h2 class="text-2xl font-bold font-display text-gray-900">3. Data Protection & Security</h2>
        <p>We implement industry-standard SSL encryption and security protocols to safeguard your personal and payment details. All online transactions are processed through encrypted payment gateways.</p>
    </section>

    <section class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm space-y-4">
        <h2 class="text-2xl font-bold font-display text-gray-900">4. Contact Us</h2>
        <p>If you have any questions regarding our Privacy Policy or your data protection rights, please contact our team at <a href="mailto:twinasafaris@gmail.com" class="text-gold-600 font-bold underline">twinasafaris@gmail.com</a>.</p>
    </section>
</div>
@endsection
