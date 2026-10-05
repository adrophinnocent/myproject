@extends('layouts.proposal')

@section('title', 'Twina Safaris — ' . $proposal->title . ' | Safari Proposal')

@section('content')
    {{-- TOP BRAND HEADER --}}
    <header class="bg-[#0d2818] border-b border-stone-800 text-white sticky top-0 z-40 backdrop-blur-md bg-opacity-95 shadow-md">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3.5 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <span class="text-xl md:text-2xl font-bold tracking-wider font-serif-title text-[#d4af37]">
                    TWINA SAFARIS
                </span>
            </a>

            <div class="flex items-center gap-3">
                <a href="{{ $proposal->whatsapp_message_url }}" target="_blank"
                   class="px-3.5 py-2 bg-emerald-800/40 hover:bg-emerald-800/60 text-emerald-300 border border-emerald-600/30 rounded-lg text-xs font-semibold transition-all flex items-center gap-2">
                    <svg class="w-4 h-4 fill-current text-emerald-400" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.105 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                    <span class="hidden sm:inline">Contact Twina</span>
                </a>
                <a href="{{ route('proposal.pdf', $proposal->token) }}"
                   class="px-3.5 py-2 border border-[#d4af37]/60 hover:bg-[#d4af37] hover:text-[#0d2818] text-[#d4af37] rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>PDF</span>
                </a>
            </div>
        </div>
    </header>

    {{-- FLASH NOTIFICATIONS --}}
    @if(session('success'))
    <div class="max-w-6xl mx-auto px-4 mt-6">
        <div class="bg-emerald-50 border border-emerald-300 text-emerald-900 p-4 rounded-xl flex items-center gap-3 shadow-xs">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div class="text-xs font-semibold">{!! session('success') !!}</div>
        </div>
    </div>
    @endif

    {{-- PROPOSAL HERO HEADER --}}
    <section class="bg-[#0d2818] text-white py-12 md:py-16 border-b border-stone-800">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-8">
                <div class="space-y-3 max-w-3xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-[#d4af37]/10 border border-[#d4af37]/30 rounded-full text-[#d4af37] text-xs font-semibold tracking-wider">
                        <span>Private Safari Proposal &bull; Ref: {{ $proposal->full_reference }}</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold font-serif-title text-white leading-tight">
                        {{ $proposal->title }}
                    </h1>

                    @if($proposal->subtitle)
                        <p class="text-stone-300 text-sm md:text-base font-normal">{{ $proposal->subtitle }}</p>
                    @endif

                    <div class="pt-2 text-xs text-stone-300">
                        Prepared for <strong class="text-white font-semibold">{{ $proposal->client_name }}</strong>
                    </div>
                </div>

                {{-- Price Summary Box --}}
                <div class="bg-[#143d22] border border-stone-700/80 rounded-2xl p-5 shrink-0 text-left md:text-right">
                    <div class="text-[10px] uppercase font-bold text-[#d4af37] tracking-wider mb-0.5">Total Safari Investment</div>
                    <div class="text-3xl font-bold text-white font-serif-title">{{ $proposal->formatted_total_price }}</div>
                    <div class="text-xs text-stone-300 mt-1">
                        {{ $proposal->adults }} Adult(s) @if($proposal->children > 0) & {{ $proposal->children }} Child(ren) @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <main class="max-w-6xl mx-auto px-4 sm:px-6 pt-8">

        {{-- ROUTE BAR --}}
        <div class="bg-white rounded-2xl p-5 mb-8 border border-stone-200/80 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="text-[10px] font-bold uppercase text-stone-500 tracking-wider">Safari Route</div>
                <div class="text-sm font-bold text-stone-900 mt-0.5">{{ $proposal->route_chain }}</div>
            </div>
            <div class="text-xs text-stone-600 bg-stone-100 border border-stone-200 px-3.5 py-1.5 rounded-lg self-start sm:self-auto font-medium">
                Valid Until: <strong class="text-stone-900">{{ $proposal->valid_until ? $proposal->valid_until->format('d M Y') : now()->addDays(30)->format('d M Y') }}</strong>
            </div>
        </div>

        {{-- OVERVIEW GRID CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-10">
            <div class="bg-white p-4 rounded-xl border border-stone-200/80 shadow-xs">
                <div class="text-[10px] font-bold uppercase text-stone-400 tracking-wider">Travel Dates</div>
                <div class="text-xs font-bold text-stone-900 mt-1">
                    @if($proposal->start_date)
                        {{ $proposal->start_date->format('M d, Y') }}
                        @if($proposal->end_date) - {{ $proposal->end_date->format('M d, Y') }} @endif
                    @else
                        Flexible Dates
                    @endif
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl border border-stone-200/80 shadow-xs">
                <div class="text-[10px] font-bold uppercase text-stone-400 tracking-wider">Duration</div>
                <div class="text-xs font-bold text-stone-900 mt-1">{{ $proposal->duration_days }} Days / {{ $proposal->duration_nights }} Nights</div>
            </div>

            <div class="bg-white p-4 rounded-xl border border-stone-200/80 shadow-xs">
                <div class="text-[10px] font-bold uppercase text-stone-400 tracking-wider">Travelers</div>
                <div class="text-xs font-bold text-stone-900 mt-1">{{ $proposal->adults }} Adult(s) @if($proposal->children > 0), {{ $proposal->children }} Child(ren) @endif</div>
            </div>

            <div class="bg-white p-4 rounded-xl border border-stone-200/80 shadow-xs">
                <div class="text-[10px] font-bold uppercase text-stone-400 tracking-wider">Safari Comfort</div>
                <div class="text-xs font-bold text-stone-900 mt-1 uppercase">{{ $proposal->accommodation_level ?: 'Comfort' }}</div>
            </div>
        </div>

        {{-- WELCOME MESSAGE --}}
        @if($proposal->welcome_message)
        <div class="bg-white rounded-2xl p-6 mb-10 border border-stone-200 border-l-4 border-l-[#d4af37] shadow-xs">
            <div class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-2">Message from Safari Consultant</div>
            <p class="text-stone-800 text-sm md:text-base leading-relaxed italic font-serif-title">
                "{{ $proposal->welcome_message }}"
            </p>
        </div>
        @endif

        {{-- SEQUENTIAL ACCORDION SECTIONS (1 -> 2 -> 3 -> 4) --}}
        <div class="space-y-6">

            {{-- SECTION 1: ITINERARY --}}
            <div class="bg-white rounded-2xl border border-stone-200/90 shadow-xs overflow-hidden">
                <button @click="toggleSection('itinerary')"
                        class="w-full p-5 bg-[#0d2818] text-white text-left flex items-center justify-between cursor-pointer">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-lg bg-[#d4af37] text-[#0d2818] font-bold text-xs flex items-center justify-center shrink-0">
                            1
                        </span>
                        <h2 class="text-base md:text-lg font-bold font-serif-title tracking-wide text-white">1. Day-by-Day Safari Itinerary</h2>
                    </div>
                    <svg class="w-5 h-5 text-[#d4af37] transition-transform duration-300" :class="sections.itinerary ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div x-show="sections.itinerary" x-collapse x-cloak class="p-5 md:p-8 space-y-8 bg-[#faf9f6]">
                    @if(is_array($proposal->itinerary) && count($proposal->itinerary) > 0)
                        <div class="space-y-8">
                            @foreach($proposal->itinerary as $day)
                            <div class="bg-white rounded-xl p-5 md:p-7 border border-stone-200/80 shadow-xs">
                                {{-- Day Header --}}
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 mb-5 pb-3 border-b border-stone-100">
                                    <div>
                                        <span class="text-xs font-bold text-[#b8920d] uppercase tracking-wider block mb-0.5">
                                            DAY {{ sprintf('%02d', $day['day'] ?? $loop->iteration) }}
                                        </span>
                                        <h3 class="text-xl md:text-2xl font-bold font-serif-title text-stone-900">
                                            {{ $day['title'] ?? '' }}
                                        </h3>
                                    </div>
                                    @if(!empty($day['destination']))
                                        <span class="px-3 py-1 bg-stone-100 text-stone-800 border border-stone-200 rounded-md text-xs font-semibold self-start md:self-auto">
                                            {{ $day['destination'] }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Framed Image Container (As requested in User Image Format) --}}
                                @if(!empty($day['cover_image']))
                                <div class="p-3 bg-[#f5f4f0] border border-stone-200/80 rounded-2xl mb-6 shadow-xs">
                                    <img src="{{ $day['cover_image'] }}" alt="{{ $day['title'] ?? '' }}" class="w-full h-auto max-h-[480px] object-cover rounded-xl">
                                </div>
                                @endif

                                {{-- Narrative Description --}}
                                @if(!empty($day['description']))
                                <div class="text-stone-700 text-sm md:text-base leading-relaxed mb-6 font-normal whitespace-pre-line">
                                    {{ $day['description'] }}
                                </div>
                                @endif

                                {{-- Day Details --}}
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 p-4 bg-stone-50 rounded-xl border border-stone-200/70 text-xs">
                                    @if(!empty($day['activities']))
                                    <div>
                                        <div class="font-bold text-stone-400 uppercase text-[10px]">Activities</div>
                                        <div class="font-semibold text-stone-800 mt-0.5">{{ $day['activities'] }}</div>
                                    </div>
                                    @endif

                                    @if(!empty($day['accommodation_property']))
                                    <div>
                                        <div class="font-bold text-stone-400 uppercase text-[10px]">Lodge / Camp</div>
                                        <div class="font-semibold text-stone-800 mt-0.5">{{ $day['accommodation_property'] }} @if(!empty($day['room_type'])) ({{ $day['room_type'] }}) @endif</div>
                                    </div>
                                    @endif

                                    @if(!empty($day['meals']))
                                    <div>
                                        <div class="font-bold text-stone-400 uppercase text-[10px]">Meals Included</div>
                                        <div class="font-semibold text-stone-800 mt-0.5">{{ $day['meals'] }}</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- SECTION 2: ACCOMMODATIONS --}}
            <div class="bg-white rounded-2xl border border-stone-200/90 shadow-xs overflow-hidden">
                <button @click="toggleSection('accommodations')"
                        class="w-full p-5 bg-[#0d2818] text-white text-left flex items-center justify-between cursor-pointer">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-lg bg-[#d4af37] text-[#0d2818] font-bold text-xs flex items-center justify-center shrink-0">
                            2
                        </span>
                        <h2 class="text-base md:text-lg font-bold font-serif-title tracking-wide text-white">2. Accommodation Summary</h2>
                    </div>
                    <svg class="w-5 h-5 text-[#d4af37] transition-transform duration-300" :class="sections.accommodations ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div x-show="sections.accommodations" x-collapse x-cloak class="p-5 md:p-8 bg-[#faf9f6]">
                    @if(is_array($proposal->accommodations) && count($proposal->accommodations) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            @foreach($proposal->accommodations as $acc)
                            <div class="bg-white rounded-xl p-5 border border-stone-200/80 shadow-xs">
                                <div class="flex items-center justify-between gap-2 mb-2">
                                    <span class="px-2.5 py-0.5 bg-stone-100 text-stone-800 border border-stone-200 rounded text-[10px] font-semibold uppercase">
                                        {{ $acc['category'] ?? 'Comfort' }}
                                    </span>
                                    <span class="text-xs font-medium text-stone-500">{{ $acc['location'] ?? '' }}</span>
                                </div>
                                <h4 class="text-lg font-bold font-serif-title text-stone-900">{{ $acc['property_name'] ?? 'Safari Lodge' }}</h4>
                                <p class="text-xs text-stone-600 mt-1 font-medium">{{ $acc['room_type'] ?? '' }} &bull; {{ $acc['meal_plan'] ?? 'Full Board' }}</p>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-white rounded-xl p-6 text-center text-stone-500 font-medium text-xs">
                            Accommodations feature handpicked safari lodges and tented camps as detailed in each day's itinerary.
                        </div>
                    @endif
                </div>
            </div>

            {{-- SECTION 3: INCLUSIONS & PRICING --}}
            <div class="bg-white rounded-2xl border border-stone-200/90 shadow-xs overflow-hidden">
                <button @click="toggleSection('pricing')"
                        class="w-full p-5 bg-[#0d2818] text-white text-left flex items-center justify-between cursor-pointer">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-lg bg-[#d4af37] text-[#0d2818] font-bold text-xs flex items-center justify-center shrink-0">
                            3
                        </span>
                        <h2 class="text-base md:text-lg font-bold font-serif-title tracking-wide text-white">3. Inclusions & Safari Investment</h2>
                    </div>
                    <svg class="w-5 h-5 text-[#d4af37] transition-transform duration-300" :class="sections.pricing ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div x-show="sections.pricing" x-collapse x-cloak class="p-5 md:p-8 space-y-6 bg-[#faf9f6]">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Included --}}
                        <div class="bg-white p-6 rounded-xl border border-stone-200/80 shadow-xs">
                            <h3 class="text-base font-bold font-serif-title text-[#0d2818] uppercase tracking-wider mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                What Is Included
                            </h3>

                            <ul class="space-y-2.5 text-xs text-stone-800">
                                @if(is_array($proposal->inclusions) && count($proposal->inclusions) > 0)
                                    @foreach($proposal->inclusions as $inc)
                                    <li class="flex items-start gap-2.5">
                                        <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        <span>{{ $inc }}</span>
                                    </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>

                        {{-- Excluded --}}
                        <div class="bg-white p-6 rounded-xl border border-stone-200/80 shadow-xs">
                            <h3 class="text-base font-bold font-serif-title text-rose-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                What Is Excluded
                            </h3>

                            <ul class="space-y-2.5 text-xs text-stone-700">
                                @if(is_array($proposal->exclusions) && count($proposal->exclusions) > 0)
                                    @foreach($proposal->exclusions as $exc)
                                    <li class="flex items-start gap-2.5">
                                        <svg class="w-4 h-4 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                        <span>{{ $exc }}</span>
                                    </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>

                    {{-- Price Summary Box --}}
                    <div class="bg-[#0d2818] text-white p-6 md:p-8 rounded-2xl shadow-sm border border-stone-800">
                        <div class="text-[10px] uppercase font-bold text-[#d4af37] tracking-wider mb-0.5">Investment Breakdown</div>
                        <h3 class="text-xl font-bold font-serif-title text-white mb-6">Price Summary</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-stone-700/80 text-xs">
                            <div class="space-y-2.5">
                                <div class="flex justify-between"><span class="text-stone-300">Adult Guests:</span><span class="font-semibold text-white">{{ $proposal->adults }} Adults @if($proposal->adult_price) ({{ $proposal->currency_symbol }}{{ number_format($proposal->adult_price, 2) }} / adult) @endif</span></div>
                                @if($proposal->children > 0)
                                    <div class="flex justify-between"><span class="text-stone-300">Child Guests:</span><span class="font-semibold text-white">{{ $proposal->children }} Children @if($proposal->child_price) ({{ $proposal->currency_symbol }}{{ number_format($proposal->child_price, 2) }} / child) @endif</span></div>
                                @endif
                                @if($proposal->subtotal_price && $proposal->discount_amount > 0)
                                    <div class="flex justify-between"><span class="text-stone-300">Standard Price:</span><span class="line-through text-stone-400">{{ $proposal->currency_symbol }}{{ number_format($proposal->subtotal_price, 2) }}</span></div>
                                    <div class="flex justify-between"><span class="text-emerald-400">Savings Discount:</span><span class="font-bold text-emerald-400">-{{ $proposal->currency_symbol }}{{ number_format($proposal->discount_amount, 2) }}</span></div>
                                @endif
                            </div>

                            <div class="text-left md:text-right space-y-1">
                                <div class="text-[10px] uppercase font-bold text-[#d4af37] tracking-wider">Total Investment</div>
                                <div class="text-3xl font-bold text-white font-serif-title">{{ $proposal->formatted_total_price }}</div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-6 text-xs">
                            <div class="bg-[#143d22] p-4 rounded-xl border border-stone-700/60">
                                <div class="text-[10px] uppercase font-bold text-[#d4af37]">Deposit Required ({{ $proposal->deposit_percentage ?? 30 }}%)</div>
                                <div class="text-xl font-bold text-white mt-1">{{ $proposal->formatted_deposit_required }}</div>
                            </div>

                            <div class="bg-[#143d22] p-4 rounded-xl border border-stone-700/60">
                                <div class="text-[10px] uppercase font-bold text-[#d4af37]">Remaining Balance</div>
                                <div class="text-xl font-bold text-white mt-1">{{ $proposal->formatted_balance_amount }}</div>
                            </div>

                            <div class="bg-[#143d22] p-4 rounded-xl border border-stone-700/60">
                                <div class="text-[10px] uppercase font-bold text-[#d4af37]">Quotation Validity</div>
                                <div class="text-sm font-bold text-stone-200 mt-1">
                                    Valid Until {{ $proposal->valid_until ? $proposal->valid_until->format('d M Y') : now()->addDays(30)->format('d M Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION 4: PAYMENT TERMS --}}
            <div class="bg-white rounded-2xl border border-stone-200/90 shadow-xs overflow-hidden">
                <button @click="toggleSection('terms')"
                        class="w-full p-5 bg-[#0d2818] text-white text-left flex items-center justify-between cursor-pointer">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-lg bg-[#d4af37] text-[#0d2818] font-bold text-xs flex items-center justify-center shrink-0">
                            4
                        </span>
                        <h2 class="text-base md:text-lg font-bold font-serif-title tracking-wide text-white">4. Payment Terms & Policy</h2>
                    </div>
                    <svg class="w-5 h-5 text-[#d4af37] transition-transform duration-300" :class="sections.terms ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                <div x-show="sections.terms" x-collapse x-cloak class="p-5 md:p-8 bg-[#faf9f6]">
                    <div class="bg-white p-6 rounded-xl border border-stone-200/80 shadow-xs space-y-6">
                        <div>
                            <h3 class="text-sm font-bold font-serif-title text-stone-900 uppercase tracking-wider mb-2">Payment Terms</h3>
                            <p class="text-xs md:text-sm text-stone-700 leading-relaxed font-normal">
                                {{ $proposal->payment_terms ?: "A deposit of 30% is required upon booking confirmation. The remaining balance is payable 30 days prior to your arrival date. We accept Bank Wire Transfers and major Credit Cards." }}
                            </p>
                        </div>

                        <div class="border-t border-stone-100 pt-5">
                            <h3 class="text-sm font-bold font-serif-title text-stone-900 uppercase tracking-wider mb-2">Cancellation Policy</h3>
                            <p class="text-xs md:text-sm text-stone-700 leading-relaxed font-normal">
                                {{ $proposal->cancellation_policy ?: "Cancellations made 60+ days before travel are subject to standard lodge and handling cancellation fees." }}
                            </p>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button @click="acceptModal = true"
                                class="w-full sm:w-auto px-8 py-3.5 bg-[#0d2818] hover:bg-[#143d22] text-[#d4af37] border border-[#d4af37]/60 rounded-xl text-xs uppercase font-bold tracking-wider shadow-md transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Accept Proposal</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </main>

    {{-- BOTTOM STICKY ACTION BAR --}}
    <div class="fixed bottom-0 inset-x-0 bg-[#0d2818] border-t border-stone-800 py-3.5 px-4 sm:px-6 z-40 text-white shadow-lg">
        <div class="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="text-center sm:text-left">
                <div class="text-[10px] uppercase font-bold text-[#d4af37] tracking-wider">Total Investment</div>
                <div class="text-xl font-bold font-serif-title text-white">{{ $proposal->formatted_total_price }}</div>
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto">
                <a href="{{ $proposal->whatsapp_message_url }}" target="_blank"
                   class="px-4 py-2.5 bg-emerald-800/40 hover:bg-emerald-800/60 text-emerald-300 border border-emerald-600/30 rounded-lg text-xs font-bold uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 fill-current text-emerald-400" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.105 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                    <span>Contact Twina</span>
                </a>

                <button @click="changesModal = true"
                        class="flex-1 sm:flex-none px-4 py-2.5 bg-stone-800 hover:bg-stone-700 text-stone-200 border border-stone-700 rounded-lg text-xs font-bold uppercase tracking-wider">
                    Request Changes
                </button>

                @if($proposal->status === \App\Models\Proposal::STATUS_ACCEPTED)
                    <div class="flex-1 sm:flex-none px-5 py-2.5 bg-emerald-700 text-white rounded-lg text-xs font-bold uppercase tracking-wider flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span>Accepted</span>
                    </div>
                @else
                    <button @click="acceptModal = true"
                            class="flex-1 sm:flex-none px-6 py-2.5 bg-[#d4af37] hover:bg-[#b8920d] text-[#0d2818] rounded-lg text-xs font-bold uppercase tracking-wider shadow-sm transition-all">
                        Accept Proposal
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- ACCEPT PROPOSAL MODAL --}}
    <div x-show="acceptModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-xs">
        <div @click.away="acceptModal = false" class="bg-white rounded-2xl max-w-lg w-full p-6 text-stone-900 shadow-xl border border-stone-200 relative">
            <button @click="acceptModal = false" class="absolute top-4 right-4 text-stone-400 hover:text-stone-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <h3 class="text-xl font-bold font-serif-title text-stone-900 mb-1">Accept Safari Proposal</h3>
            <p class="text-xs text-stone-500 mb-5">Confirm your acceptance for <strong>{{ $proposal->title }}</strong> ({{ $proposal->formatted_total_price }}).</p>

            <form action="{{ route('proposal.accept', $proposal->token) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase text-stone-700 mb-1">Your Full Name (Electronic Signature) *</label>
                    <input type="text" name="signature_name" required value="{{ $proposal->client_name }}"
                           class="w-full bg-stone-50 border border-stone-300 rounded-lg px-3.5 py-2.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-[#d4af37]">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-stone-700 mb-1">Additional Notes (Optional)</label>
                    <textarea name="client_notes" rows="3"
                              class="w-full bg-stone-50 border border-stone-300 rounded-lg p-3 text-xs focus:outline-none focus:ring-2 focus:ring-[#d4af37]"></textarea>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3">
                    <button type="button" @click="acceptModal = false" class="px-4 py-2 text-xs font-bold uppercase text-stone-500">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#0d2818] text-[#d4af37] border border-[#d4af37]/60 text-xs uppercase font-bold rounded-lg shadow-xs">
                        Confirm & Accept
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- REQUEST CHANGES MODAL --}}
    <div x-show="changesModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-xs">
        <div @click.away="changesModal = false" class="bg-white rounded-2xl max-w-lg w-full p-6 text-stone-900 shadow-xl border border-stone-200 relative">
            <button @click="changesModal = false" class="absolute top-4 right-4 text-stone-400 hover:text-stone-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <h3 class="text-xl font-bold font-serif-title text-stone-900 mb-1">Request Changes</h3>
            <p class="text-xs text-stone-500 mb-5">Let us know what adjustments you would like made to this itinerary.</p>

            <form action="{{ route('proposal.changes', $proposal->token) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase text-stone-700 mb-1">What would you like us to change? *</label>
                    <textarea name="feedback" rows="4" required
                              class="w-full bg-stone-50 border border-stone-300 rounded-lg p-3 text-xs font-medium focus:outline-none focus:ring-2 focus:ring-[#d4af37]"></textarea>
                </div>

                <div class="pt-3 flex items-center justify-end gap-3">
                    <button type="button" @click="changesModal = false" class="px-4 py-2 text-xs font-bold uppercase text-stone-500">Cancel</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#0d2818] text-white text-xs font-bold uppercase rounded-lg shadow-xs">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
