@extends('public.layouts.app')
@section('title', 'Contact Us - Twina Safaris')
@section('meta_description', 'Contact Twina Safaris in Moshi, Tanzania to plan a tailor-made safari, Kilimanjaro trek or Zanzibar holiday. Send your dates, parks and budget.')

@php
    $socialLinks = array_values(array_filter([
        ['label' => 'Facebook', 'url' => \App\Models\Setting::get('facebook_url')],
        ['label' => 'Instagram', 'url' => \App\Models\Setting::get('instagram_url')],
        ['label' => 'YouTube', 'url' => \App\Models\Setting::get('youtube_url')],
    ], fn (array $link) => $link['url'] && $link['url'] !== '#'));
@endphp

@section('content')
<div class="relative h-[40vh] min-h-80 flex items-center bg-safari-dark">
    <img src="{{ \App\Helpers\AssetHelper::getBannerUrl('contact_banner') }}" alt="{{ __('Contact Us') }}" class="absolute inset-0 w-full h-full object-cover opacity-60">
    <div class="absolute inset-0 bg-black/40"></div>
    <div class="absolute inset-0 flex items-end">
        <div class="max-w-7xl mx-auto px-4 pb-12 w-full text-center md:text-left">
            <h1 class="font-display text-4xl md:text-6xl text-white font-bold leading-tight">{{ __($banner->title ?? 'Contact Us') }}</h1>
            <p class="text-gold-400 mt-4 max-w-2xl text-lg font-medium">{{ __('Your journey to Africa starts with a simple message to us.') }}</p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-20">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">

        <!-- Left Side: Contact Form -->
        <div class="lg:col-span-7">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-10">
                <div class="mb-8">
                    <h2 class="font-display text-3xl font-bold text-gray-900 mb-2">{{ __('Send Us a Message') }}</h2>
                    <p class="text-gray-500">{{ __('Tell us your travel dates, preferred parks and budget so we can prepare a clear itinerary.') }}</p>
                </div>

                @if(session('success'))
                <div class="mb-8 p-5 bg-green-50 border border-green-200 rounded-2xl text-green-700 flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-medium">{{ __(session('success')) }}</span>
                </div>
                @endif

                <form method="POST" action="{{ route('contact.send') }}" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">{{ __('Full Name') }}</label>
                            <input type="text" name="name" required placeholder="{{ __('Jambo! Enter your name') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">{{ __('Email Address') }}</label>
                            <input type="email" name="email" required placeholder="email@example.com" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">{{ __('Phone Number (WhatsApp recommended)') }}</label>
                            <input type="tel" name="phone" placeholder="+1 234 567 890" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">{{ __('Subject') }}</label>
                            <select name="subject" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all appearance-none cursor-pointer">
                                <option value="general">{{ __('General Inquiry') }}</option>
                                <option value="safari">{{ __('Safari Booking') }}</option>
                                <option value="zanzibar">{{ __('Zanzibar Tour') }}</option>
                                <option value="kilimanjaro">{{ __('Kilimanjaro Trekking') }}</option>
                                <option value="custom">{{ __('Custom Package') }}</option>
                                <option value="feedback">{{ __('Feedback') }}</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">{{ __('Message') }}</label>
                        <textarea name="message" rows="6" required placeholder="{{ __('Tell us about your dream trip...') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-4 focus:outline-none focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 transition-all"></textarea>
                    </div>

                    <button type="submit" class="w-full btn-gold py-5 rounded-2xl text-lg font-bold shadow-xl shadow-gold-500/20 flex items-center justify-center gap-3">
                        {{ __('Send Message') }}
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
                <h3 class="font-display text-2xl font-bold text-gray-900">{{ __('Get In Touch') }}</h3>

                <div class="flex gap-6">
                    <div class="w-12 h-12 shrink-0 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm uppercase tracking-widest mb-1">{{ __('Our Office') }}</h4>
                        <p class="text-gray-600 font-medium">{{ __('Moshi, Kilimanjaro') }}</p>
                        <p class="text-gray-400 text-xs mt-1">{{ __('Tanzania') }}</p>
                    </div>
                </div>

                <div class="flex gap-6">
                    <div class="w-12 h-12 shrink-0 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm uppercase tracking-widest mb-1">{{ __('Phone') }}</h4>
                        <a href="tel:{{ \App\Models\Setting::get('site_phone', '255795482197') }}" class="text-gray-700 font-bold hover:text-gold-600 transition-colors">{{ \App\Models\Setting::get('site_phone', '+255 795 482 197') }}</a>
                        <p class="text-gray-400 text-xs mt-1">{{ __('Call or WhatsApp for bookings and itinerary questions') }}</p>
                        <div class="mt-3 py-1.5 px-3 bg-red-50 rounded-lg inline-flex items-center gap-2 border border-red-100">
                            <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                            <span class="text-[10px] text-red-600 font-bold uppercase tracking-tighter">{{ __('Emergency Line') }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex gap-6">
                    <div class="w-12 h-12 shrink-0 bg-white rounded-2xl shadow-sm border border-gray-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm uppercase tracking-widest mb-1">{{ __('Email') }}</h4>
                        @php $contactEmail = \App\Models\Setting::get('site_email', 'info@twinasafaris.com'); @endphp
                        <a href="mailto:{{ $contactEmail }}" class="text-gray-700 font-bold hover:text-gold-600 transition-colors">{{ $contactEmail }}</a>
                        <p class="text-gray-400 text-xs mt-1">{{ __('Use email for detailed itinerary requests and documents') }}</p>
                    </div>
                </div>

                <div class="flex gap-6">
                    <div class="w-12 h-12 shrink-0 bg-green-500 rounded-2xl shadow-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-bold text-gray-900 text-sm uppercase tracking-widest mb-1">{{ __('WhatsApp') }}</h4>
                        <a href="https://wa.me/{{ \App\Models\Setting::get('site_whatsapp', '255795482197') }}" target="_blank" class="text-green-600 font-bold hover:text-green-700 transition-colors flex items-center gap-2 underline">
                            +{{ \App\Models\Setting::get('site_whatsapp', '255795482197') }}
                        </a>
                        <p class="text-gray-400 text-xs mt-1 italic">{{ __('Click to chat instantly with our experts') }}</p>
                    </div>
                </div>

                {{-- Social --}}
                <div class="pt-10 border-t border-gray-200">
                    <h4 class="font-bold text-gray-900 text-sm uppercase tracking-widest mb-6">{{ __('Follow Us') }}</h4>
                    @if($socialLinks !== [])
                    <div class="flex flex-wrap gap-3">
                        @foreach($socialLinks as $link)
                        <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer" class="px-4 py-3 bg-white rounded-xl shadow-sm border border-gray-100 text-gray-700 text-sm font-bold hover:bg-gold-500 hover:text-white transition-all">
                            {{ $link['label'] }}
                        </a>
                        @endforeach
                    </div>
                    @endif
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
