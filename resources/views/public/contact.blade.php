@extends('public.layouts.app')
@section('title', 'Contact Us - Twina Safaris')
@section('meta_description', 'Get in touch with Twina Safaris for your dream African safari adventure. We are available 24/7 for bookings and inquiries.')

@section('content')
<div class="relative h-[40vh] min-h-80 flex items-center bg-safari-dark">
    <img src="{{ \App\Helpers\AssetHelper::getBannerUrl('contact_banner') }}" alt="Contact Us" class="absolute inset-0 w-full h-full object-cover opacity-60">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="absolute inset-0 flex items-end">
        <div class="max-w-7xl mx-auto px-4 pb-12 w-full text-center md:text-left">
            <h1 class="font-display text-4xl md:text-6xl text-white font-bold leading-tight">{{ $banner->title ?? 'Contact Us' }}</h1>
            <p class="text-gold-400 mt-4 max-w-2xl text-lg font-medium">Your journey to Africa starts with a simple message to us.</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">

        <!-- Left Side: Contact Form -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-10">
                <div class="mb-8">
                    <h2 class="font-display text-3xl font-bold text-gray-900 mb-2">Send Us a Message</h2>
                    <p class="text-gray-500">We usually respond within 1–2 hours on WhatsApp.</p>
                </div>

                @if(session('success'))
                <div class="mb-8 p-5 bg-green-50 border border-green-200 rounded-2xl text-green-700 flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                @endif

                <form method="POST" action="{{ route('contact.send') }}" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Full Name</label>
                            <input type="text" name="name" required placeholder="Jambo! Enter your name" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Email Address</label>
                            <input type="email" name="email" required placeholder="email@example.com" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Phone Number (WhatsApp recommended)</label>
                            <input type="tel" name="phone" placeholder="+1 234 567 890" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Subject</label>
                            <select name="subject" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all appearance-none cursor-pointer">
                                <option value="general">General Inquiry</option>
                                <option value="safari">Safari Booking</option>
                                <option value="zanzibar">Zanzibar Tour</option>
                                <option value="kilimanjaro">Kilimanjaro Trekking</option>
                                <option value="custom">Custom Package</option>
                                <option value="feedback">Feedback</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Message</label>
                        <textarea name="message" rows="6" required placeholder="Tell us about your dream trip..." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all"></textarea>
                    </div>

                    <button type="submit" class="w-full btn-gold py-5 rounded-2xl text-lg font-bold shadow-xl shadow-gold-500/20 flex items-center justify-center gap-3">
                        Send Message
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Side: Contact Info -->
        <div class="lg:col-span-5 space-y-8">
            {{-- Quick Actions --}}
            <div class="grid grid-cols-1 gap-4">
                <a href="{{ route('tours.index') }}" class="btn-gold p-6 rounded-[2rem] flex items-center justify-between group shadow-lg">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-2xl">⚡</div>
                        <div>
                            <span class="block font-bold text-lg">Book Your Safari Now</span>
                            <span class="text-xs opacity-80 uppercase tracking-widest font-semibold">Instant response on WhatsApp</span>
                        </div>
                    </div>
                    <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>

            {{-- Info Cards --}}
            <div class="bg-gray-50 rounded-[2.5rem] p-10 border border-gray-100 space-y-10">
                <h3 class="font-display text-2xl font-bold text-gray-900">Get In Touch</h3>

                <div class="flex gap-6">
                    <div class="w-12 h-12 shrink-0 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm uppercase tracking-widest mb-1">Our Office</h4>
                        <p class="text-gray-600 font-medium">Moshi, Kilimanjaro</p>
                        <p class="text-gray-400 text-xs mt-1">Tanzania</p>
                    </div>
                </div>

                <div class="flex gap-6">
                    <div class="w-12 h-12 shrink-0 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm uppercase tracking-widest mb-1">Phone</h4>
                        <a href="tel:{{ \App\Models\Setting::get('site_phone', '255795482197') }}" class="text-gray-700 font-bold hover:text-gold-600 transition-colors">{{ \App\Models\Setting::get('site_phone', '+255 795 482 197') }}</a>
                        <p class="text-gray-400 text-xs mt-1">Available 24/7 for bookings & inquiries</p>
                        <div class="mt-3 py-1.5 px-3 bg-red-50 rounded-lg inline-flex items-center gap-2 border border-red-100">
                            <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                            <span class="text-[10px] text-red-600 font-bold uppercase tracking-tighter">Emergency Line</span>
                        </div>
                    </div>
                </div>

                <div class="flex gap-6">
                    <div class="w-12 h-12 shrink-0 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm uppercase tracking-widest mb-1">Email</h4>
                        <a href="mailto:info@twinasafaris.com" class="text-gray-700 font-bold hover:text-gold-600 transition-colors">info@twinasafaris.com</a>
                        <p class="text-gray-400 text-xs mt-1">We reply within 24 hours</p>
                    </div>
                </div>

                <div class="flex gap-6">
                    <div class="w-12 h-12 shrink-0 bg-green-500 rounded-2xl shadow-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm uppercase tracking-widest mb-1">WhatsApp</h4>
                        <a href="https://wa.me/{{ \App\Models\Setting::get('site_whatsapp', '255795482197') }}" target="_blank" class="text-green-600 font-bold hover:text-green-700 transition-colors flex items-center gap-2 underline">
                            +{{ \App\Models\Setting::get('site_whatsapp', '255795482197') }}
                        </a>
                        <p class="text-gray-400 text-xs mt-1 italic">Click to chat instantly with our experts</p>
                    </div>
                </div>

                {{-- Social --}}
                <div class="pt-10 border-t border-gray-200">
                    <h4 class="font-bold text-gray-900 text-sm uppercase tracking-widest mb-6">Follow Us</h4>
                    <div class="flex flex-wrap gap-4">
                        <a href="#" class="w-12 h-12 bg-white rounded-xl shadow-sm border border-gray-100 flex items-center justify-center text-gray-700 hover:bg-gold-500 hover:text-white transition-all transform hover:-translate-y-1">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="w-12 h-12 bg-white rounded-xl shadow-sm border border-gray-100 flex items-center justify-center text-gray-700 hover:bg-gold-500 hover:text-white transition-all transform hover:-translate-y-1">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="#" class="w-12 h-12 bg-white rounded-xl shadow-sm border border-gray-100 flex items-center justify-center text-gray-700 hover:bg-gold-500 hover:text-white transition-all transform hover:-translate-y-1">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.01 3.64.01 5.34.49 7.27 2.12.35.3.46.42.81.79 1.63 1.93 2.11 3.63 2.12 7.27.01 6.28.01 6.28-.01 12.54-.01 3.64-.49 5.34-2.12 7.27-.3.35-.42.46-.79.81-1.93 1.63-3.63 2.11-7.27 2.12-1.3.01-2.6.01-3.91.01-3.64-.01-5.34-.49-7.27-2.12-.35-.3-.46-.42-.81-.79-1.63-1.93-2.11-3.63-2.12-7.27C-.01 16.14-.01 16.14.01 9.88.02 6.24.5 4.54 2.13 2.61c.3-.35.42-.46.79-.81C4.85.17 6.55-.31 10.19-.32c.78.01 1.55.01 2.33.34zm-.52 2.33c-3.15.01-4.22.45-5.63 1.86-1.41 1.41-1.85 2.48-1.86 5.63.01 6.28.01 6.28-.01 12.54.01 3.15.45 4.22 1.86 5.63 1.41 1.41 2.48 1.85 5.63 1.86 6.28-.01 6.28-.01 12.54.01 3.15-.45 4.22-1.86 5.63-1.86 1.41-1.41 1.85-2.48 1.86-5.63-.01-6.28-.01-6.28.01-12.54-.01-3.15-.45-4.22-1.86-5.63-1.41-1.41-2.48-1.85-5.63-1.86-6.28.01-6.28.01-12.54-.01zM12 6.13c3.31 0 6 2.69 6 6s-2.69 6-6 6-6-2.69-6-6 2.69-6 6-6zm0 2c-2.21 0-4 2.69-4 4s1.79 4 4 4 4-2.69 4-4-1.79-4-4-4zm6.41-3.41c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1z"/></svg>
                        </a>
                        <a href="#" class="w-12 h-12 bg-white rounded-xl shadow-sm border border-gray-100 flex items-center justify-center text-gray-700 hover:bg-gold-500 hover:text-white transition-all transform hover:-translate-y-1">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                        <a href="#" class="w-12 h-12 bg-white rounded-xl shadow-sm border border-gray-100 flex items-center justify-center text-gray-700 hover:bg-gold-500 hover:text-white transition-all transform hover:-translate-y-1">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.84 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </a>
                    </div>
                    <p class="text-xs text-gray-400 mt-4 font-bold uppercase tracking-widest">@TwinaSafaris</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="mt-20 bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden h-[500px]">
        <iframe
            width="100%"
            height="100%"
            frameborder="0" style="border:0"
            src="https://maps.google.com/maps?q=Moshi+Town,Tanzania&t=&z=15&ie=UTF8&iwloc=&output=embed"
            allowfullscreen loading="lazy">
        </iframe>
    </div>
</div>
@endsection
