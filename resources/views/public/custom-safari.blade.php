@extends('public.layouts.app')

@section('title', 'Request a Custom Safari | Twina Safaris')
@section('meta_description', 'Craft your tailor-made African safari with Twina Safaris. Personalize your itinerary, accommodation, travel dates, and experiences.')

@section('content')
{{-- Hero Section --}}
<section class="relative bg-slate-900 text-white py-16 md:py-24 overflow-hidden">
    <div class="absolute inset-0 bg-cover bg-center opacity-35" style="background-image: url('https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1600&q=80');"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/70 to-transparent"></div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 text-center">
        <span class="inline-block px-4 py-1.5 bg-amber-500/20 border border-amber-500/40 rounded-full text-amber-300 text-xs font-bold uppercase tracking-widest mb-4">
            Bespoke African Adventures
        </span>
        <h1 class="text-3xl md:text-5xl font-black font-serif tracking-tight text-white mb-4">
            Request a Custom Safari
        </h1>
        <p class="text-slate-300 text-sm md:text-base max-w-2xl mx-auto leading-relaxed">
            Tell us about your dream African journey. Our local safari experts will design a personalized itinerary tailored to your exact dates, travel style, and budget.
        </p>
    </div>
</section>

{{-- Form Section --}}
<section class="py-12 md:py-20 bg-slate-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">

        @if(session('success'))
        <div class="mb-10 p-6 bg-emerald-50 border-2 border-emerald-500 rounded-3xl text-emerald-900 shadow-sm flex items-start gap-4">
            <div class="w-10 h-10 rounded-2xl bg-emerald-500 text-white flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <h3 class="text-base font-black text-emerald-900 mb-1">Request Received!</h3>
                <p class="text-xs md:text-sm font-medium leading-relaxed">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <form action="{{ route('custom-safari.store') }}" method="POST" class="bg-white rounded-3xl p-6 md:p-10 border border-slate-200/80 shadow-xl space-y-10">
            @csrf

            {{-- SECTION 1: PERSONAL INFORMATION --}}
            <div>
                <h3 class="text-lg font-black font-serif text-slate-900 uppercase tracking-wider mb-6 flex items-center gap-3 border-b border-slate-100 pb-3">
                    <span class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs font-sans">1</span>
                    Your Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Full Name *</label>
                        <input type="text" name="full_name" required value="{{ old('full_name') }}"
                               placeholder="e.g. Sarah Jenkins"
                               class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Email Address *</label>
                        <input type="email" name="email" required value="{{ old('email') }}"
                               placeholder="e.g. sarah@example.com"
                               class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">WhatsApp / Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               placeholder="e.g. +1 555 234 5678"
                               class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Country of Residence</label>
                        <input type="text" name="country" value="{{ old('country') }}"
                               placeholder="e.g. United States, Germany, United Kingdom"
                               class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>
            </div>

            {{-- SECTION 2: TRIP & TRAVEL DETAILS --}}
            <div>
                <h3 class="text-lg font-black font-serif text-slate-900 uppercase tracking-wider mb-6 flex items-center gap-3 border-b border-slate-100 pb-3">
                    <span class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs font-sans">2</span>
                    Travelers & Schedule
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Number of Adults *</label>
                        <input type="number" name="adults" required min="1" value="{{ old('adults', 2) }}"
                               class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Number of Children</label>
                        <input type="number" name="children" min="0" value="{{ old('children', 0) }}"
                               class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Children's Ages (If any)</label>
                        <input type="text" name="children_ages" value="{{ old('children_ages') }}"
                               placeholder="e.g. 6, 11 years"
                               class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Preferred Travel Date</label>
                        <input type="date" name="travel_date" value="{{ old('travel_date') }}"
                               class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Number of Days *</label>
                        <input type="number" name="duration_days" required min="1" value="{{ old('duration_days', 7) }}"
                               class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div class="flex items-center pt-6">
                        <label class="inline-flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="flexible_dates" value="1" {{ old('flexible_dates') ? 'checked' : '' }} class="w-5 h-5 text-amber-500 rounded border-slate-300 focus:ring-amber-500">
                            <span class="text-xs font-bold text-slate-700 uppercase">My travel dates are flexible (+/- 3 days)</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- SECTION 3: SAFARI PREFERENCES --}}
            <div>
                <h3 class="text-lg font-black font-serif text-slate-900 uppercase tracking-wider mb-6 flex items-center gap-3 border-b border-slate-100 pb-3">
                    <span class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs font-sans">3</span>
                    Safari Preferences
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Trip Type</label>
                        <select name="trip_type" class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                            <option value="Wildlife Safari">Wildlife Safari</option>
                            <option value="Kilimanjaro Trekking">Kilimanjaro Trekking</option>
                            <option value="Zanzibar Beach Resort">Zanzibar Beach Resort</option>
                            <option value="Safari + Zanzibar Combination">Safari + Zanzibar Combination</option>
                            <option value="Kilimanjaro + Safari + Zanzibar">Kilimanjaro + Safari + Zanzibar</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Traveler Group Style</label>
                        <select name="group_type" class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                            <option value="Couple / Romantic">Couple / Romantic</option>
                            <option value="Honeymoon Special">Honeymoon Special</option>
                            <option value="Family with Children">Family with Children</option>
                            <option value="Solo Traveler">Solo Traveler</option>
                            <option value="Group of Friends">Group of Friends</option>
                            <option value="Corporate / Organization">Corporate / Organization</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Accommodation Level</label>
                        <select name="accommodation_preference" class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                            <option value="Comfort / Mid-Range Lodges">Comfort / Mid-Range Lodges</option>
                            <option value="Luxury Safari Camps & Lodges">Luxury Safari Camps & Lodges</option>
                            <option value="Super Luxury / Exclusive Tented Camps">Super Luxury / Exclusive Tented Camps</option>
                            <option value="Budget / Camping Safari">Budget / Camping Safari</option>
                            <option value="Mix of Comfort and Luxury">Mix of Comfort and Luxury</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Target Budget Per Person (Excl. Flights)</label>
                        <select name="budget_per_person" class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                            <option value="$1,500 - $2,500 per person">$1,500 - $2,500 per person</option>
                            <option value="$2,500 - $4,000 per person">$2,500 - $4,000 per person</option>
                            <option value="$4,000 - $6,000 per person">$4,000 - $6,000 per person</option>
                            <option value="$6,000+ Luxury per person">$6,000+ Luxury per person</option>
                            <option value="Flexible / Advice Needed">Flexible / Advice Needed</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Activities & Interests</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @php
                                $acts = ['Big Five Game Drives', 'Hot Air Balloon Safari', 'Serengeti Migration', 'Cultural Maasai Visit', 'Zanzibar Beach Relaxation', 'Mount Kilimanjaro Trek', 'Wildlife Photography', 'Walking Safari'];
                            @endphp
                            @foreach($acts as $act)
                            <label class="inline-flex items-center gap-2.5 p-3 bg-slate-50 rounded-xl border border-slate-200 cursor-pointer text-xs font-semibold text-slate-700">
                                <input type="checkbox" name="activities[]" value="{{ $act }}" class="w-4 h-4 text-amber-500 rounded border-slate-300 focus:ring-amber-500">
                                <span>{{ $act }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Special Requests or Additional Details</label>
                    <textarea name="special_requests" rows="4"
                              placeholder="Tell us about specific national parks you wish to visit (Serengeti, Ngorongoro, Tarangire, Manyara), dietary needs, mobility preferences, or flights..."
                              class="w-full bg-slate-50 border border-slate-300 rounded-2xl p-4 text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">{{ old('special_requests') }}</textarea>
                </div>
            </div>

            <div class="pt-4 text-center">
                <button type="submit" class="w-full sm:w-auto px-10 py-4 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-black text-sm uppercase tracking-widest rounded-2xl shadow-xl transition-all">
                    Submit Safari Request
                </button>
            </div>
        </form>
    </div>
</section>
@endsection
