@extends('admin.layouts.app')

@section('title', 'Create Client Proposal')
@section('page-title', 'Create Client Proposal')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Proposal Builder</h2>
        <p class="text-gray-400 font-bold uppercase text-[10px] tracking-widest mt-1">Build a personalized itinerary, accommodation summary & internal cost breakdown</p>
    </div>
    <a href="{{ route('admin.proposals.index') }}" class="px-4 py-2 neo-btn text-xs font-bold text-gray-600 uppercase">Back to Proposals</a>
</div>

@if($selectedInquiry)
<div class="mb-8 neo-card p-6 border-l-4 border-emerald-500 bg-emerald-50/20">
    <div class="flex items-center gap-3">
        <svg class="w-6 h-6 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>
            <h3 class="text-xs font-black uppercase text-emerald-900 tracking-wider">Converted from Custom Safari Request</h3>
            <p class="text-xs text-slate-600 mt-0.5">Pre-filled with inquiry details for <strong>{{ $selectedInquiry->full_name }}</strong> ({{ $selectedInquiry->email }}).</p>
        </div>
    </div>
</div>
@endif

{{-- START METHOD SELECTION HEADER --}}
<div class="neo-card p-6 mb-8 border-2 border-amber-300/80 bg-amber-50/30">
    <div class="mb-4">
        <h3 class="text-sm font-black uppercase text-gray-900 tracking-wider">How would you like to start?</h3>
        <p class="text-xs text-gray-600 mt-0.5">Choose your starting workflow below. Each option populates the proposal builder accordingly.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Option 1: Start from Scratch --}}
        <div class="p-5 bg-white rounded-2xl border-2 transition-all {{ $startType === 'scratch' ? 'border-amber-500 ring-2 ring-amber-500/20 shadow-md' : 'border-gray-200 hover:border-gray-300' }}">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-black uppercase tracking-wider text-amber-700">Option 1</span>
                @if($startType === 'scratch')
                    <span class="px-2 py-0.5 bg-amber-100 text-amber-900 text-[10px] font-black uppercase rounded-full">Selected</span>
                @endif
            </div>
            <h4 class="text-sm font-black text-gray-900 mb-1">Start from Scratch</h4>
            <p class="text-[11px] text-gray-500 leading-relaxed mb-4">Create a completely empty proposal. Nothing will be automatically copied. Build every detail from scratch.</p>

            <a href="{{ route('admin.proposals.create', array_merge(request()->only(['inquiry_id']), ['start_type' => 'scratch'])) }}"
               class="inline-block w-full text-center px-4 py-2.5 rounded-xl text-xs font-black uppercase transition-all {{ $startType === 'scratch' ? 'bg-amber-500 text-white shadow' : 'bg-gray-100 hover:bg-gray-200 text-gray-800' }}">
                Start from Scratch
            </a>
        </div>

        {{-- Option 2: Use Proposal Template --}}
        <div class="p-5 bg-white rounded-2xl border-2 transition-all {{ $startType === 'template' ? 'border-amber-500 ring-2 ring-amber-500/20 shadow-md' : 'border-gray-200 hover:border-gray-300' }}">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-black uppercase tracking-wider text-amber-700">Option 2</span>
                @if($startType === 'template')
                    <span class="px-2 py-0.5 bg-amber-100 text-amber-900 text-[10px] font-black uppercase rounded-full">Active</span>
                @endif
            </div>
            <h4 class="text-sm font-black text-gray-900 mb-1">Use Proposal Template</h4>
            <p class="text-[11px] text-gray-500 leading-relaxed mb-3">Start with a reusable itinerary template (e.g. 7 Days Tanzania Comfort) and customize it for this client.</p>

            <form action="{{ route('admin.proposals.create') }}" method="GET" class="space-y-2">
                @if($selectedInquiry)<input type="hidden" name="inquiry_id" value="{{ $selectedInquiry->id }}">@endif
                <input type="hidden" name="start_type" value="template">
                <select name="template_id" required class="w-full bg-slate-50 border border-gray-300 rounded-xl px-3 py-2 text-xs font-bold text-gray-800 outline-none">
                    <option value="">-- Select Proposal Template --</option>
                    @foreach($proposalTemplates as $pt)
                        <option value="{{ $pt->id }}" {{ ($selectedProposalTemplate && $selectedProposalTemplate->id == $pt->id) ? 'selected' : '' }}>
                            {{ $pt->title }} ({{ $pt->duration_days }} Days)
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="w-full px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-black text-xs uppercase rounded-xl transition-all shadow">
                    Load Template
                </button>
            </form>
        </div>

        {{-- Option 3: Start from Public Tour --}}
        <div class="p-5 bg-white rounded-2xl border-2 transition-all {{ $startType === 'tour' ? 'border-amber-500 ring-2 ring-amber-500/20 shadow-md' : 'border-gray-200 hover:border-gray-300' }}">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-black uppercase tracking-wider text-amber-700">Option 3</span>
                @if($startType === 'tour')
                    <span class="px-2 py-0.5 bg-amber-100 text-amber-900 text-[10px] font-black uppercase rounded-full">Active</span>
                @endif
            </div>
            <h4 class="text-sm font-black text-gray-900 mb-1">Start from Public Tour</h4>
            <p class="text-[11px] text-gray-500 leading-relaxed mb-3">Use an existing website tour as the starting point for a personalized client proposal.</p>

            <form action="{{ route('admin.proposals.create') }}" method="GET" class="space-y-2">
                @if($selectedInquiry)<input type="hidden" name="inquiry_id" value="{{ $selectedInquiry->id }}">@endif
                <input type="hidden" name="start_type" value="tour">
                <select name="tour_id" required class="w-full bg-slate-50 border border-gray-300 rounded-xl px-3 py-2 text-xs font-bold text-gray-800 outline-none">
                    <option value="">-- Select Public Tour --</option>
                    @foreach($tours as $tour)
                        <option value="{{ $tour->id }}" {{ ($selectedTour && $selectedTour->id == $tour->id) ? 'selected' : '' }}>
                            {{ $tour->title }} ({{ $tour->duration_days }} Days)
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="w-full px-4 py-2 bg-[#052010] hover:bg-[#08331a] text-[#D4AF37] border border-[#D4AF37]/30 font-black text-xs uppercase rounded-xl transition-all shadow">
                    Create Proposal from Tour
                </button>
            </form>
        </div>
    </div>
</div>

{{-- PHP PRE-CALCULATION FOR INITIAL BUILDER STATE --}}
@php
    if ($startType === 'template' && $selectedProposalTemplate) {
        $initTitle = $selectedProposalTemplate->title;
        $initSubtitle = $selectedProposalTemplate->subtitle ?: '';
        $initWelcome = "Dear " . ($selectedInquiry ? $selectedInquiry->full_name : "Valued Guest") . ",\n\nThank you for contacting Twina Safaris! We are delighted to present your custom itinerary proposal based on our " . $selectedProposalTemplate->title . ".";
        $initDurationDays = $selectedProposalTemplate->duration_days;
        $initDurationNights = $selectedProposalTemplate->duration_nights;
        $initStartLocation = $selectedProposalTemplate->start_location ?: 'Arusha';
        $initEndLocation = $selectedProposalTemplate->end_location ?: 'Arusha';
        $initSafariStyle = $selectedProposalTemplate->safari_style ?: 'Private 4x4 Safari';
        $initAccommodationLevel = $selectedProposalTemplate->accommodation_level ?: 'Comfort';
        $initItinerary = $selectedProposalTemplate->structured_itinerary;
        $initAccommodations = $selectedProposalTemplate->structured_accommodations;
        $initInclusions = implode("\n", $selectedProposalTemplate->structured_inclusions);
        $initExclusions = implode("\n", $selectedProposalTemplate->structured_exclusions);
        $initTotalPrice = (float) $selectedProposalTemplate->default_total_price;
        $initSubtotalPrice = (float) $selectedProposalTemplate->default_total_price;
        $initAdultPrice = (float) ($selectedProposalTemplate->default_adult_price ?: ($selectedProposalTemplate->default_total_price / 2));
        $initChildPrice = (float) ($selectedProposalTemplate->default_child_price ?: 0);
        $initDepositPercentage = (float) ($selectedProposalTemplate->default_deposit_percentage ?: 30);
        $initDepositRequired = round(($initTotalPrice * $initDepositPercentage) / 100, 2);
        $initCosting = $selectedProposalTemplate->internal_costing ?? [];
    } elseif ($startType === 'tour' && $selectedTour) {
        $initTitle = $selectedTour->title;
        $initSubtitle = $selectedTour->duration_text . ' · ' . ($selectedTour->tour_type ?: 'Public Tour');
        $initWelcome = "Dear " . ($selectedInquiry ? $selectedInquiry->full_name : "Valued Guest") . ",\n\nThank you for choosing Twina Safaris! Below is our personalized itinerary proposal for " . $selectedTour->title . ".";
        $initDurationDays = $selectedTour->duration_days;
        $initDurationNights = $selectedTour->duration_nights;
        $initStartLocation = $selectedTour->departure_location ?: 'Arusha';
        $initEndLocation = 'Arusha';
        $initSafariStyle = $selectedTour->tour_type ?: 'Private Safari';
        $initAccommodationLevel = $selectedTour->accommodation_type ?: 'Comfort';
        $initItinerary = is_array($selectedTour->itinerary) ? $selectedTour->itinerary : [];
        $initAccommodations = [];
        $initInclusions = is_array($selectedTour->inclusions) ? implode("\n", $selectedTour->inclusions) : '';
        $initExclusions = is_array($selectedTour->exclusions) ? implode("\n", $selectedTour->exclusions) : '';
        $initTotalPrice = (float) $selectedTour->price;
        $initSubtotalPrice = (float) $selectedTour->price;
        $initAdultPrice = (float) $selectedTour->price;
        $initChildPrice = (float) ($selectedTour->child_price ?: 0);
        $initDepositPercentage = 30.0;
        $initDepositRequired = round(($initTotalPrice * 0.3), 2);
        $initCosting = [];
    } else { // Scratch
        $initTitle = $selectedInquiry ? ($selectedInquiry->duration_days . ' Days Custom Safari') : '';
        $initSubtitle = $selectedInquiry ? ($selectedInquiry->adults . ' Adults · ' . ($selectedInquiry->travel_style ?: 'Private Safari')) : '';
        $initWelcome = $selectedInquiry ? ("Dear " . $selectedInquiry->full_name . ",\n\nThank you for contacting Twina Safaris! Below is your custom safari itinerary proposal.") : '';
        $initDurationDays = $selectedInquiry ? $selectedInquiry->duration_days : 1;
        $initDurationNights = 0;
        $initStartLocation = 'Arusha';
        $initEndLocation = 'Arusha';
        $initSafariStyle = 'Private 4x4 Safari';
        $initAccommodationLevel = 'Comfort';
        $initItinerary = [
            [
                'day' => 1,
                'title' => 'Day 1: ',
                'destination' => '',
                'starting_point' => '',
                'ending_point' => '',
                'route' => '',
                'description' => '',
                'activities' => '',
                'optional_activities' => '',
                'driving_time' => '',
                'meals' => '',
                'accommodation_property' => '',
                'room_type' => '',
                'cover_image' => '',
                'gallery_images' => []
            ]
        ];
        $initAccommodations = [];
        $initInclusions = '';
        $initExclusions = '';
        $initTotalPrice = 0.00;
        $initSubtotalPrice = 0.00;
        $initAdultPrice = 0.00;
        $initChildPrice = 0.00;
        $initDepositPercentage = 30.0;
        $initDepositRequired = 0.00;
        $initCosting = [];
    }
@endphp

<form action="{{ route('admin.proposals.store') }}" method="POST" x-data="proposalBuilderForm()">
    @csrf

    @if($selectedInquiry)
        <input type="hidden" name="custom_safari_inquiry_id" value="{{ $selectedInquiry->id }}">
    @endif

    @if($selectedTour)
        <input type="hidden" name="tour_id" value="{{ $selectedTour->id }}">
    @endif

    {{-- SECTION 1: CLIENT DETAILS (NEVER COPIED FROM TEMPLATE) --}}
    <div class="neo-card p-6 mb-8">
        <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-4 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs">1</span>
            Client Information
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Client Full Name *</label>
                <input type="text" name="client_name" value="{{ old('client_name', $selectedInquiry ? $selectedInquiry->full_name : '') }}" required
                       placeholder="e.g. John & Sarah Smith"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Client Email *</label>
                <input type="email" name="client_email" value="{{ old('client_email', $selectedInquiry ? $selectedInquiry->email : '') }}" required
                       placeholder="e.g. client@example.com"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">WhatsApp / Phone</label>
                <input type="text" name="client_phone" value="{{ old('client_phone', $selectedInquiry ? $selectedInquiry->phone : '') }}"
                       placeholder="e.g. +1 555 123 4567"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Country of Residence</label>
                <input type="text" name="country" value="{{ old('country', $selectedInquiry ? $selectedInquiry->country : '') }}"
                       placeholder="e.g. United States"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
            </div>
        </div>
    </div>

    {{-- SECTION 2: TRIP OVERVIEW --}}
    <div class="neo-card p-6 mb-8">
        <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-4 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs">2</span>
            Trip Overview
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Proposal Title *</label>
                <input type="text" name="title" value="{{ old('title', $initTitle) }}" required
                       placeholder="e.g. 7 Days Tanzania Comfort Safari"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Subtitle / Tagline</label>
                <input type="text" name="subtitle" value="{{ old('subtitle', $initSubtitle) }}"
                       placeholder="e.g. 2 Adults · Private Safari · Comfort"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Personal Welcome Message</label>
                <textarea name="welcome_message" rows="3"
                          class="w-full bg-white border border-gray-300 rounded-xl p-4 text-xs font-medium focus:ring-2 focus:ring-amber-500/20 outline-none">{{ old('welcome_message', $initWelcome) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Start Date</label>
                <input type="date" name="start_date" value="{{ old('start_date', $selectedInquiry && $selectedInquiry->travel_date ? $selectedInquiry->travel_date->format('Y-m-d') : '') }}"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">End Date</label>
                <input type="date" name="end_date" value="{{ old('end_date') }}"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Duration (Days) *</label>
                <input type="number" name="duration_days" x-model.number="durationDays" required min="1"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Nights</label>
                <input type="number" name="duration_nights" value="{{ old('duration_nights', $initDurationNights) }}" min="0"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Adults *</label>
                <input type="number" name="adults" x-model.number="adults" required min="1"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Children</label>
                <input type="number" name="children" x-model.number="children" min="0"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Start Location</label>
                <input type="text" name="start_location" value="{{ old('start_location', $initStartLocation) }}"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">End Location</label>
                <input type="text" name="end_location" value="{{ old('end_location', $initEndLocation) }}"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Safari Style</label>
                <input type="text" name="safari_style" value="{{ old('safari_style', $initSafariStyle) }}"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Accommodation Level</label>
                <select name="accommodation_level" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
                    <option value="Budget" {{ $initAccommodationLevel === 'Budget' ? 'selected' : '' }}>Budget</option>
                    <option value="Standard" {{ $initAccommodationLevel === 'Standard' ? 'selected' : '' }}>Standard</option>
                    <option value="Comfort" {{ $initAccommodationLevel === 'Comfort' ? 'selected' : '' }}>Comfort / Mid-Range</option>
                    <option value="Luxury" {{ $initAccommodationLevel === 'Luxury' ? 'selected' : '' }}>Luxury</option>
                    <option value="Premium" {{ $initAccommodationLevel === 'Premium' ? 'selected' : '' }}>Premium</option>
                </select>
            </div>
        </div>
    </div>

    {{-- SECTION 3: DAY-BY-DAY BUILDER WITH DAY-SPECIFIC IMAGES --}}
    <div class="neo-card p-6 mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-gray-200">
            <div>
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs">3</span>
                    Day-by-Day Builder (Day-Specific Media)
                </h3>
                <p class="text-xs text-gray-500 mt-1">Each day has its own cover image and gallery media specifically bound to that day.</p>
            </div>

            <div class="flex items-center gap-3">
                <select @change="insertDayTemplate($event.target.value); $event.target.value=''" class="bg-white border border-gray-300 rounded-xl px-3 py-2 text-xs font-bold text-gray-700 outline-none">
                    <option value="">+ Insert Day Template...</option>
                    @foreach($dayTemplates as $dt)
                        <option value="{{ $dt->id }}">{{ $dt->title }} ({{ $dt->destination }})</option>
                    @endforeach
                </select>

                <button type="button" @click="addDay()" class="px-4 py-2 neo-btn text-xs font-bold text-amber-600 uppercase flex items-center gap-1">
                    + Add Day
                </button>
            </div>
        </div>

        <div class="space-y-8">
            <template x-for="(day, index) in days" :key="index">
                <div class="p-6 bg-white/90 border-2 border-gray-200 rounded-2xl relative shadow-sm">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-200">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-black text-amber-600 uppercase tracking-wider" x-text="'Day ' + (index + 1)"></span>
                            <span x-show="day.destination" class="text-[10px] bg-amber-100 text-amber-900 font-bold px-2 py-0.5 rounded-full" x-text="day.destination"></span>
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="button" @click="saveDayAsTemplate(index)" class="text-[10px] font-bold text-indigo-600 hover:underline">
                                Save Day as Template
                            </button>
                            <button type="button" @click="removeDay(index)" x-show="days.length > 1" class="text-rose-500 hover:text-rose-700 text-xs font-bold">
                                Remove Day
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Day Title *</label>
                            <input type="text" :name="'itinerary[' + index + '][title]'" x-model="day.title" required
                                   class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-xs font-bold focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Destination</label>
                            <input type="text" :name="'itinerary[' + index + '][destination]'" x-model="day.destination"
                                   class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-xs font-bold focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Starting Point</label>
                            <input type="text" :name="'itinerary[' + index + '][starting_point]'" x-model="day.starting_point"
                                   class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-xs focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Ending Point</label>
                            <input type="text" :name="'itinerary[' + index + '][ending_point]'" x-model="day.ending_point"
                                   class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-xs focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Route</label>
                            <input type="text" :name="'itinerary[' + index + '][route]'" x-model="day.route"
                                   class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-xs focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Main Description *</label>
                            <textarea :name="'itinerary[' + index + '][description]'" x-model="day.description" rows="3"
                                      class="w-full bg-white border border-gray-300 rounded-xl p-3 text-xs focus:outline-none"></textarea>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Activities</label>
                            <input type="text" :name="'itinerary[' + index + '][activities]'" x-model="day.activities"
                                   class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-xs focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Optional Activities</label>
                            <input type="text" :name="'itinerary[' + index + '][optional_activities]'" x-model="day.optional_activities"
                                   class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-xs focus:outline-none">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200/80 mb-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Driving Time / Distance</label>
                            <input type="text" :name="'itinerary[' + index + '][driving_time]'" x-model="day.driving_time"
                                   class="w-full bg-white border border-gray-300 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Meals Included</label>
                            <input type="text" :name="'itinerary[' + index + '][meals]'" x-model="day.meals"
                                   class="w-full bg-white border border-gray-300 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Accommodation Property</label>
                            <input type="text" :name="'itinerary[' + index + '][accommodation_property]'" x-model="day.accommodation_property"
                                   class="w-full bg-white border border-gray-300 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Room Type & Meal Plan</label>
                            <input type="text" :name="'itinerary[' + index + '][room_type]'" x-model="day.room_type"
                                   class="w-full bg-white border border-gray-300 rounded-lg px-2.5 py-1.5 text-xs focus:outline-none">
                        </div>
                    </div>

                    <div class="p-4 bg-amber-50/40 rounded-xl border border-amber-200">
                        <h4 class="text-[11px] font-black text-amber-900 uppercase tracking-wider mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Day <span x-text="index + 1"></span> Media (Belongs strictly to this day)
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-700 uppercase mb-1">Day Cover Image URL *</label>
                                <div class="flex gap-2">
                                    <input type="text"
                                           :id="'day_cover_image_' + index"
                                           :name="'itinerary[' + index + '][cover_image]'"
                                           x-model="day.cover_image"
                                           placeholder="Image URL or click Select / Upload"
                                           class="flex-1 bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none">
                                    <button type="button"
                                            @click="window.dispatchEvent(new CustomEvent('open-media-picker', {detail: {targetId: 'day_cover_image_' + index, previewId: 'day_cover_preview_' + index}}))"
                                            class="bg-amber-600 hover:bg-amber-700 text-white px-3 py-2 rounded-lg font-bold text-[10px] uppercase tracking-wider transition-all shrink-0 flex items-center gap-1 shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        Select / Upload
                                    </button>
                                </div>
                                <template x-if="day.cover_image">
                                    <div class="mt-2 relative inline-block group">
                                        <img :id="'day_cover_preview_' + index" :src="day.cover_image" class="h-20 w-36 rounded-lg object-cover border shadow-sm">
                                        <button type="button" @click="day.cover_image = ''" class="absolute -top-2 -right-2 bg-rose-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity shadow">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-gray-700 uppercase mb-1">Additional Gallery Images (Comma or JSON)</label>
                                <div class="flex gap-2">
                                    <input type="text"
                                           :id="'day_gallery_images_' + index"
                                           :name="'itinerary[' + index + '][gallery_images]'"
                                           :value="typeof day.gallery_images === 'string' ? day.gallery_images : JSON.stringify(day.gallery_images)"
                                           @input="day.gallery_images = $event.target.value"
                                           placeholder="e.g. image1.jpg, image2.jpg"
                                           class="flex-1 bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none">
                                    <button type="button"
                                            @click="window.dispatchEvent(new CustomEvent('open-media-picker', {detail: {targetId: 'day_gallery_images_' + index}}))"
                                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-lg font-bold text-[10px] uppercase tracking-wider transition-all shrink-0 flex items-center gap-1 shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Add Media
                                    </button>
                                </div>
                                <span class="text-[10px] text-gray-500 block mt-1">Day-specific gallery images shown as small thumbnail gallery</span>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- SECTION 4: ACCOMMODATION SUMMARY TABLE --}}
    <div class="neo-card p-6 mb-8" x-data="{
        accommodations: @json(old('accommodations', $initAccommodations)),
        addAcc() { this.accommodations.push({ property_name: '', location: '', category: 'Comfort', room_type: '', nights: 1, meal_plan: 'Full Board', description: '', image: '', website_url: '' }); },
        removeAcc(i) { if(this.accommodations.length > 0) this.accommodations.splice(i, 1); }
    }">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs">4</span>
                Accommodation Summary
            </h3>
            <button type="button" @click="addAcc()" class="px-4 py-2 neo-btn text-xs font-bold text-amber-600 uppercase">+ Add Accommodation</button>
        </div>

        <div class="space-y-4">
            <template x-for="(acc, aIndex) in accommodations" :key="aIndex">
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/80">
                    <div class="flex items-center justify-between mb-3 pb-2 border-b">
                        <span class="text-xs font-black text-slate-800" x-text="'Property #' + (aIndex + 1)"></span>
                        <button type="button" @click="removeAcc(aIndex)" class="text-rose-600 text-xs font-bold">Remove</button>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Property Name</label>
                            <input type="text" :name="'accommodations[' + aIndex + '][property_name]'" x-model="acc.property_name" class="w-full bg-white border border-gray-300 rounded-lg px-2.5 py-1.5 text-xs">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Location</label>
                            <input type="text" :name="'accommodations[' + aIndex + '][location]'" x-model="acc.location" class="w-full bg-white border border-gray-300 rounded-lg px-2.5 py-1.5 text-xs">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Category / Level</label>
                            <select :name="'accommodations[' + aIndex + '][category]'" x-model="acc.category" class="w-full bg-white border border-gray-300 rounded-lg px-2.5 py-1.5 text-xs font-bold">
                                <option value="Budget">Budget</option>
                                <option value="Standard">Standard</option>
                                <option value="Comfort">Comfort</option>
                                <option value="Luxury">Luxury</option>
                                <option value="Premium">Premium</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Room Type & Nights</label>
                            <input type="text" :name="'accommodations[' + aIndex + '][room_type]'" x-model="acc.room_type" placeholder="e.g. Luxury Tent (1 Night)" class="w-full bg-white border border-gray-300 rounded-lg px-2.5 py-1.5 text-xs">
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- SECTION 5: INCLUSIONS & EXCLUSIONS --}}
    <div class="neo-card p-6 mb-8">
        <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-4 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs">5</span>
            Inclusions & Exclusions
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-emerald-700 uppercase mb-2">What is Included (One per line)</label>
                <textarea name="inclusions" rows="8"
                          class="w-full bg-white border border-emerald-300 rounded-xl p-4 text-xs font-medium focus:ring-2 focus:ring-emerald-500/20 outline-none">{{ old('inclusions', $initInclusions) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-rose-700 uppercase mb-2">What is Excluded (One per line)</label>
                <textarea name="exclusions" rows="8"
                          class="w-full bg-white border border-rose-300 rounded-xl p-4 text-xs font-medium focus:ring-2 focus:ring-rose-500/20 outline-none">{{ old('exclusions', $initExclusions) }}</textarea>
            </div>
        </div>
    </div>

    {{-- SECTION 6: CLIENT-FACING PRICING & GROUP PRICING --}}
    <div class="neo-card p-6 mb-8">
        <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-4 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs">6</span>
            Client Investment & Group Pricing
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Currency *</label>
                <select name="currency" x-model="currency" required class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold outline-none">
                    <option value="USD">USD ($)</option>
                    <option value="EUR">EUR (€)</option>
                    <option value="GBP">GBP (£)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Subtotal Price</label>
                <input type="number" step="0.01" name="subtotal_price" x-model.number="subtotalPrice"
                       placeholder="e.g. 3500.00"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Discount Amount</label>
                <input type="number" step="0.01" name="discount_amount" x-model.number="discountAmount"
                       placeholder="e.g. 300.00"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Final Client Selling Price *</label>
                <input type="number" step="0.01" name="total_price" x-model.number="totalPrice" required
                       placeholder="e.g. 3200.00"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-black text-amber-600 outline-none">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Adult Price (Per Person)</label>
                <input type="number" step="0.01" name="adult_price" x-model.number="adultPrice"
                       placeholder="e.g. 1600.00"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Child Price (Per Person)</label>
                <input type="number" step="0.01" name="child_price" x-model.number="childPrice"
                       placeholder="e.g. 800.00"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Child Age Range</label>
                <input type="text" name="child_age_range" value="{{ old('child_age_range', '3 - 12 Years') }}"
                       placeholder="e.g. 3 - 12 Years"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Deposit Required</label>
                <input type="number" step="0.01" name="deposit_required" x-model.number="depositRequired"
                       placeholder="e.g. 1000.00"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold outline-none">
            </div>
        </div>
    </div>

    {{-- SECTION 7: GRANULAR INTERNAL COSTING ENGINE (ADMIN ONLY) --}}
    <div class="neo-card p-6 mb-8 border-2 border-emerald-900/20 bg-[#f4fdf7]">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-black text-[#052010] uppercase tracking-widest flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-[#052010] text-[#D4AF37] flex items-center justify-center text-xs">7</span>
                    Internal Costing Engine
                    <span class="bg-rose-100 text-rose-800 text-[9px] font-black uppercase px-2 py-0.5 rounded-full">ADMIN ONLY</span>
                </h3>
                <p class="text-[11px] text-emerald-800 font-bold mt-1">Private cost breakdown for profit margin analysis. Strictly hidden from client.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <div>
                <label class="block text-[10px] font-bold text-gray-700 uppercase mb-1">Accommodation Cost</label>
                <input type="number" step="0.01" name="internal_costing[accommodation_cost]" x-model.number="costs.accommodation" class="w-full bg-white border rounded-xl px-3 py-2 text-xs font-bold">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-700 uppercase mb-1">Park & Entry Fees</label>
                <input type="number" step="0.01" name="internal_costing[park_fees]" x-model.number="costs.park" class="w-full bg-white border rounded-xl px-3 py-2 text-xs font-bold">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-700 uppercase mb-1">Vehicle Cost</label>
                <input type="number" step="0.01" name="internal_costing[vehicle_cost]" x-model.number="costs.vehicle" class="w-full bg-white border rounded-xl px-3 py-2 text-xs font-bold">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-700 uppercase mb-1">Guide / Driver Fees</label>
                <input type="number" step="0.01" name="internal_costing[guide_cost]" x-model.number="costs.guide" class="w-full bg-white border rounded-xl px-3 py-2 text-xs font-bold">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-700 uppercase mb-1">Meals Cost</label>
                <input type="number" step="0.01" name="internal_costing[meals_cost]" x-model.number="costs.meals" class="w-full bg-white border rounded-xl px-3 py-2 text-xs font-bold">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-700 uppercase mb-1">Transfers Cost</label>
                <input type="number" step="0.01" name="internal_costing[transfers_cost]" x-model.number="costs.transfers" class="w-full bg-white border rounded-xl px-3 py-2 text-xs font-bold">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-700 uppercase mb-1">Domestic Flights</label>
                <input type="number" step="0.01" name="internal_costing[flights_cost]" x-model.number="costs.flights" class="w-full bg-white border rounded-xl px-3 py-2 text-xs font-bold">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-700 uppercase mb-1">Activities Cost</label>
                <input type="number" step="0.01" name="internal_costing[activities_cost]" x-model.number="costs.activities" class="w-full bg-white border rounded-xl px-3 py-2 text-xs font-bold">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-700 uppercase mb-1">Government Fees & Tax</label>
                <input type="number" step="0.01" name="internal_costing[government_fees]" x-model.number="costs.government" class="w-full bg-white border rounded-xl px-3 py-2 text-xs font-bold">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-700 uppercase mb-1">Other Costs</label>
                <input type="number" step="0.01" name="internal_costing[other_costs]" x-model.number="costs.other" class="w-full bg-white border rounded-xl px-3 py-2 text-xs font-bold">
            </div>
        </div>

        <div class="p-4 bg-[#052010] text-white rounded-2xl grid grid-cols-1 sm:grid-cols-3 gap-4 text-center border border-[#D4AF37]/30">
            <div>
                <div class="text-[10px] font-bold uppercase text-amber-200/80">Total Internal Cost</div>
                <div class="text-lg font-black" x-text="currency + ' ' + totalCost.toFixed(2)"></div>
            </div>
            <div>
                <div class="text-[10px] font-bold uppercase text-amber-200/80">Net Profit</div>
                <div class="text-lg font-black text-emerald-400" x-text="currency + ' ' + profit.toFixed(2)"></div>
            </div>
            <div>
                <div class="text-[10px] font-bold uppercase text-amber-200/80">Profit Margin</div>
                <div class="text-lg font-black text-[#D4AF37]" x-text="profitMargin.toFixed(2) + '%'"></div>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-4">
        <a href="{{ route('admin.proposals.index') }}" class="px-6 py-3 neo-btn text-xs font-bold text-gray-600 uppercase">Cancel</a>
        <button type="submit" class="px-8 py-3 bg-[#052010] hover:bg-[#08331a] text-[#D4AF37] border border-[#D4AF37]/40 rounded-xl font-black text-xs uppercase shadow-lg transition-all">
            Save & Publish Proposal
        </button>
    </div>
</form>

<script>
function proposalBuilderForm() {
    return {
        currency: 'USD',
        durationDays: {{ old('duration_days', $initDurationDays) }},
        adults: {{ old('adults', $selectedInquiry ? $selectedInquiry->adults : 2) }},
        children: {{ old('children', $selectedInquiry ? $selectedInquiry->children : 0) }},
        subtotalPrice: {{ old('subtotal_price', $initSubtotalPrice) }},
        discountAmount: {{ old('discount_amount', 0) }},
        totalPrice: {{ old('total_price', $initTotalPrice) }},
        adultPrice: {{ old('adult_price', $initAdultPrice) }},
        childPrice: {{ old('child_price', $initChildPrice) }},
        depositRequired: {{ old('deposit_required', $initDepositRequired) }},
        costs: {
            accommodation: {{ $initCosting['accommodation_cost'] ?? 0 }},
            park: {{ $initCosting['park_fees'] ?? 0 }},
            vehicle: {{ $initCosting['vehicle_cost'] ?? 0 }},
            guide: {{ $initCosting['guide_cost'] ?? 0 }},
            meals: {{ $initCosting['meals_cost'] ?? 0 }},
            transfers: {{ $initCosting['transfers_cost'] ?? 0 }},
            flights: {{ $initCosting['flights_cost'] ?? 0 }},
            activities: {{ $initCosting['activities_cost'] ?? 0 }},
            government: {{ $initCosting['government_fees'] ?? 0 }},
            other: {{ $initCosting['other_costs'] ?? 0 }}
        },
        days: @json(old('itinerary', $initItinerary)),
        addDay() {
            this.days.push({
                title: 'Day ' + (this.days.length + 1) + ': ',
                destination: '',
                starting_point: '',
                ending_point: '',
                route: '',
                description: '',
                activities: '',
                optional_activities: '',
                driving_time: '',
                meals: 'Breakfast, Lunch, Dinner',
                accommodation_property: '',
                room_type: '',
                cover_image: '',
                gallery_images: []
            });
            this.durationDays = this.days.length;
        },
        removeDay(index) {
            if (this.days.length > 1) {
                this.days.splice(index, 1);
                this.durationDays = this.days.length;
            }
        },
        insertDayTemplate(templateId) {
            if (!templateId) return;
            fetch('{{ route('admin.day-templates.index') }}')
                .then(r => r.json())
                .then(data => {
                    const dt = data.find(t => t.id == templateId);
                    if (dt) {
                        this.days.push({
                            title: 'Day ' + (this.days.length + 1) + ': ' + dt.title,
                            destination: dt.destination || '',
                            starting_point: dt.starting_point || '',
                            ending_point: dt.ending_point || '',
                            route: dt.route || '',
                            description: dt.description || '',
                            activities: dt.activities || '',
                            optional_activities: dt.optional_activities || '',
                            driving_time: dt.driving_time || '',
                            meals: dt.meals || 'Breakfast, Lunch, Dinner',
                            accommodation_property: dt.accommodation_property || '',
                            room_type: dt.room_type || '',
                            cover_image: dt.cover_image || '',
                            gallery_images: dt.gallery_images || []
                        });
                        this.durationDays = this.days.length;
                    }
                });
        },
        saveDayAsTemplate(index) {
            const day = this.days[index];
            const name = prompt('Enter a title for this Day Template:', day.title || 'Safari Day');
            if (!name) return;

            fetch('{{ route('admin.day-templates.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    title: name,
                    destination: day.destination,
                    starting_point: day.starting_point,
                    ending_point: day.ending_point,
                    route: day.route,
                    description: day.description,
                    activities: day.activities,
                    driving_time: day.driving_time,
                    meals: day.meals,
                    accommodation_property: day.accommodation_property,
                    cover_image: day.cover_image
                })
            }).then(r => r.json()).then(res => {
                alert('Day Template saved successfully!');
            });
        },
        get totalCost() {
            return (this.costs.accommodation || 0) +
                   (this.costs.park || 0) +
                   (this.costs.vehicle || 0) +
                   (this.costs.guide || 0) +
                   (this.costs.meals || 0) +
                   (this.costs.transfers || 0) +
                   (this.costs.flights || 0) +
                   (this.costs.activities || 0) +
                   (this.costs.government || 0) +
                   (this.costs.other || 0);
        },
        get profit() {
            return (this.totalPrice || 0) - this.totalCost;
        },
        get profitMargin() {
            if (!this.totalPrice || this.totalPrice <= 0) return 0;
            return (this.profit / this.totalPrice) * 100;
        }
    }
}
</script>
@endsection
