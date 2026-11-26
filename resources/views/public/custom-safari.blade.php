@extends('public.layouts.app')

@section('title', 'Custom Safari Tanzania | Build Your Own Itinerary')
@section('meta_description', 'Design a tailor-made Tanzania safari: choose your parks, pace, accommodation and dates. Tell Twina Safaris what you want and get an itinerary built around it.')

@php
    $customFaqs = [
        [
            'question' => 'What can I customise on a Tanzania safari?',
            'answer' => 'You can choose parks, number of days, travel dates, accommodation style, vehicle type, group size, meal plan and optional activities such as balloon safaris or cultural visits.',
        ],
        [
            'question' => 'How far in advance should I send a custom safari request?',
            'answer' => 'Earlier is better for popular dry-season dates and specific lodges, but you can send a request at any time and we will check current availability.',
        ],
        [
            'question' => 'Can I combine a custom safari with Kilimanjaro or Zanzibar?',
            'answer' => 'Yes. Include those in your request and the itinerary can account for transfer days, flight times and rest days.',
        ],
        [
            'question' => 'Will I receive a fixed price before paying?',
            'answer' => 'A clear quote with inclusions, exclusions and payment terms should be provided before any booking is confirmed.',
        ],
    ];

    $customFaqSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => collect($customFaqs)->map(fn (array $faq) => [
            '@type' => 'Question',
            'name' => $faq['question'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq['answer']],
        ])->values()->all(),
    ];
@endphp

@section('schema')
<script type="application/ld+json">@json($customFaqSchema)</script>
@endsection

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
            Custom Safari in Tanzania
        </h1>
        <p class="text-slate-300 text-sm md:text-base max-w-2xl mx-auto leading-relaxed">
            Tell us your dates, preferred parks, pace and budget. We will build a tailor-made Tanzania itinerary around your plans, with the route, accommodation and inclusions written down clearly.
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
                <h2 class="text-base font-black text-emerald-900 mb-1">Request Received!</h2>
                <p class="text-xs md:text-sm font-medium leading-relaxed">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <form action="{{ route('custom-safari.store') }}" method="POST" class="bg-white rounded-3xl p-6 md:p-10 border border-slate-200/80 shadow-xl space-y-10">
            @csrf

            {{-- SECTION 1: PERSONAL INFORMATION --}}
            <div>
                <h2 class="text-lg font-black font-serif text-slate-900 uppercase tracking-wider mb-6 flex items-center gap-3 border-b border-slate-100 pb-3">
                    <span class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs font-sans">1</span>
                    Your Information
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Full Name *</label>
                        <input type="text" name="full_name" aria-label="Full Name" required value="{{ old('full_name') }}"
                               placeholder="e.g. Sarah Jenkins"
                               class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Email Address *</label>
                        <input type="email" name="email" aria-label="Email Address" required value="{{ old('email') }}"
                               placeholder="e.g. sarah@example.com"
                               class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">WhatsApp / Phone Number</label>
                        <input type="text" name="phone" aria-label="WhatsApp or Phone Number" value="{{ old('phone') }}"
                               placeholder="e.g. +1 555 234 5678"
                               class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Country of Residence</label>
                        <input type="text" name="country" aria-label="Country of Residence" value="{{ old('country') }}"
                               placeholder="e.g. United States, Germany, United Kingdom"
                               class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>
            </div>

            {{-- SECTION 2: TRIP & TRAVEL DETAILS --}}
            <div>
                <h2 class="text-lg font-black font-serif text-slate-900 uppercase tracking-wider mb-6 flex items-center gap-3 border-b border-slate-100 pb-3">
                    <span class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs font-sans">2</span>
                    Travelers & Schedule
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Number of Adults *</label>
                        <input type="number" name="adults" aria-label="Number of Adults" required min="1" value="{{ old('adults', 2) }}"
                               class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Number of Children</label>
                        <input type="number" name="children" aria-label="Number of Children" min="0" value="{{ old('children', 0) }}"
                               class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Children's Ages (If any)</label>
                        <input type="text" name="children_ages" aria-label="Children's Ages" value="{{ old('children_ages') }}"
                               placeholder="e.g. 6, 11 years"
                               class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Preferred Travel Date</label>
                        <input type="date" name="travel_date" aria-label="Preferred Travel Date" value="{{ old('travel_date') }}"
                               class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Number of Days *</label>
                        <input type="number" name="duration_days" aria-label="Number of Days" required min="1" value="{{ old('duration_days', 7) }}"
                               class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>

                    <div class="flex items-center pt-6">
                        <label class="inline-flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="flexible_dates" aria-label="My travel dates are flexible" value="1" {{ old('flexible_dates') ? 'checked' : '' }} class="w-5 h-5 text-amber-500 rounded border-slate-300 focus:ring-amber-500">
                            <span class="text-xs font-bold text-slate-700 uppercase">My travel dates are flexible (+/- 3 days)</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- SECTION 3: SAFARI PREFERENCES --}}
            <div>
                <h2 class="text-lg font-black font-serif text-slate-900 uppercase tracking-wider mb-6 flex items-center gap-3 border-b border-slate-100 pb-3">
                    <span class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs font-sans">3</span>
                    Safari Preferences
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Trip Type</label>
                        <select name="trip_type" aria-label="Trip Type" class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                            <option value="Wildlife Safari">Wildlife Safari</option>
                            <option value="Kilimanjaro Trekking">Kilimanjaro Trekking</option>
                            <option value="Zanzibar Beach Resort">Zanzibar Beach Resort</option>
                            <option value="Safari + Zanzibar Combination">Safari + Zanzibar Combination</option>
                            <option value="Kilimanjaro + Safari + Zanzibar">Kilimanjaro + Safari + Zanzibar</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Traveler Group Style</label>
                        <select name="group_type" aria-label="Traveler Group Style" class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
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
                        <select name="accommodation_preference" aria-label="Accommodation Level" class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
                            <option value="Comfort / Mid-Range Lodges">Comfort / Mid-Range Lodges</option>
                            <option value="Luxury Safari Camps & Lodges">Luxury Safari Camps & Lodges</option>
                            <option value="Super Luxury / Exclusive Tented Camps">Super Luxury / Exclusive Tented Camps</option>
                            <option value="Budget / Camping Safari">Budget / Camping Safari</option>
                            <option value="Mix of Comfort and Luxury">Mix of Comfort and Luxury</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Target Budget Per Person (Excl. Flights)</label>
                        <select name="budget_per_person" aria-label="Target Budget Per Person" class="w-full bg-slate-50 border border-slate-300 rounded-2xl px-4 py-3.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-amber-500">
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
                                <input type="checkbox" name="activities[]" aria-label="{{ $act }}" value="{{ $act }}" class="w-4 h-4 text-amber-500 rounded border-slate-300 focus:ring-amber-500">
                                <span>{{ $act }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Special Requests or Additional Details</label>
                    <textarea name="special_requests" aria-label="Special Requests or Additional Details" rows="4"
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

<section class="py-16 bg-white border-t border-stone-200">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-6">How the custom safari process works</h2>
        <div class="grid md:grid-cols-3 gap-5">
            @foreach([
                ['1. Send your brief', 'Share your dates, group size, must-see parks, accommodation style and rough budget through the form above.'],
                ['2. Review a written itinerary', 'We check routes and availability, then send back a day-by-day plan with inclusions and exclusions.'],
                ['3. Confirm and prepare', 'Once you are happy with the plan, payment terms and travel documents are confirmed before departure.'],
            ] as $step)
            <div class="p-6 rounded-2xl bg-stone-50 border border-stone-200">
                <h3 class="font-bold text-stone-900 mb-2">{{ $step[0] }}</h3>
                <p class="text-sm text-stone-600 leading-relaxed">{{ $step[1] }}</p>
            </div>
            @endforeach
        </div>
        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('guides.safari-cost') }}" class="px-5 py-3 rounded-full border border-stone-300 text-sm font-bold text-stone-700 hover:border-[#b8920d] hover:text-[#8A6D0B] transition-colors">Understand safari costs</a>
            <a href="{{ route('guides.best-time') }}" class="px-5 py-3 rounded-full border border-stone-300 text-sm font-bold text-stone-700 hover:border-[#b8920d] hover:text-[#8A6D0B] transition-colors">Best time to visit</a>
            <a href="{{ route('tours.index') }}" class="px-5 py-3 rounded-full border border-stone-300 text-sm font-bold text-stone-700 hover:border-[#b8920d] hover:text-[#8A6D0B] transition-colors">Browse published itineraries</a>
        </div>
    </div>
</section>

<section class="py-16 bg-stone-100/70 border-t border-stone-200/80">
    <div class="max-w-4xl mx-auto px-4">
        <h2 class="font-display text-2xl md:text-3xl font-bold text-stone-900 mb-6">Custom safari questions</h2>
        <div class="space-y-3">
            @foreach($customFaqs as $faq)
            <details class="bg-white border border-stone-200 rounded-2xl">
                <summary class="cursor-pointer list-none p-5 font-bold text-stone-900 text-sm flex items-start justify-between gap-3">
                    <span>{{ $faq['question'] }}</span>
                    <span class="text-[#8A6D0B] text-lg leading-none" aria-hidden="true">+</span>
                </summary>
                <div class="px-5 pb-5 pt-1 text-stone-600 text-sm leading-relaxed border-t border-stone-100">{{ $faq['answer'] }}</div>
            </details>
            @endforeach
        </div>
    </div>
</section>
@endsection
