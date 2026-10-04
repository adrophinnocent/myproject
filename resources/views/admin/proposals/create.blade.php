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

{{-- Quick Template Loaders --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    {{-- Complete Proposal Template --}}
    <div class="neo-card p-6 border-l-4 border-amber-500">
        <h3 class="text-xs font-black uppercase text-gray-700 tracking-wider mb-2">Load Complete Itinerary Template</h3>
        <p class="text-xs text-gray-500 mb-4">Select a pre-built proposal template (e.g. 7 Days Tanzania Comfort) to populate all days, accommodations, and pricing.</p>
        <form action="{{ route('admin.proposals.create') }}" method="GET" class="flex items-center gap-3">
            @if($selectedInquiry)<input type="hidden" name="inquiry_id" value="{{ $selectedInquiry->id }}">@endif
            <select name="template_id" class="flex-1 bg-white border border-gray-300 rounded-xl px-3 py-2 text-xs font-bold text-gray-800 focus:outline-none">
                <option value="">-- Select Itinerary Template --</option>
                @foreach($proposalTemplates as $pt)
                    <option value="{{ $pt->id }}" {{ (request('template_id') == $pt->id || ($selectedProposalTemplate && $selectedProposalTemplate->id == $pt->id)) ? 'selected' : '' }}>
                        {{ $pt->title }} ({{ $pt->duration_days }} Days)
                    </option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 neo-btn bg-amber-500 text-white font-black text-xs uppercase rounded-xl hover:bg-amber-600">
                Load Template
            </button>
        </form>
    </div>

    {{-- Tour Clone --}}
    <div class="neo-card p-6 border-l-4 border-blue-500">
        <h3 class="text-xs font-black uppercase text-gray-700 tracking-wider mb-2">Clone Public Tour</h3>
        <p class="text-xs text-gray-500 mb-4">Select an existing public tour to pre-fill standard marketing itinerary and inclusions.</p>
        <form action="{{ route('admin.proposals.create') }}" method="GET" class="flex items-center gap-3">
            @if($selectedInquiry)<input type="hidden" name="inquiry_id" value="{{ $selectedInquiry->id }}">@endif
            <select name="tour_id" class="flex-1 bg-white border border-gray-300 rounded-xl px-3 py-2 text-xs font-bold text-gray-800 focus:outline-none">
                <option value="">-- Select Public Tour --</option>
                @foreach($tours as $tour)
                    <option value="{{ $tour->id }}" {{ (request('tour_id') == $tour->id || ($selectedTour && $selectedTour->id == $tour->id)) ? 'selected' : '' }}>
                        {{ $tour->title }} ({{ $tour->duration_days }} Days)
                    </option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 neo-btn bg-blue-600 text-white font-black text-xs uppercase rounded-xl hover:bg-blue-700">
                Clone Tour
            </button>
        </form>
    </div>
</div>

<form action="{{ route('admin.proposals.store') }}" method="POST" x-data="proposalBuilderForm()">
    @csrf

    @if($selectedInquiry)
        <input type="hidden" name="custom_safari_inquiry_id" value="{{ $selectedInquiry->id }}">
    @endif

    {{-- SECTION 1: CLIENT DETAILS --}}
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
                <input type="text" name="title" value="{{ old('title', $selectedProposalTemplate ? $selectedProposalTemplate->title : ($selectedInquiry ? $selectedInquiry->duration_days . ' Days Custom ' . ($selectedInquiry->trip_type ?: 'Tanzania Safari') : ($selectedTour ? $selectedTour->title : ''))) }}" required
                       placeholder="e.g. 7 Days Tanzania Safari"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Subtitle</label>
                <input type="text" name="subtitle" value="{{ old('subtitle', $selectedInquiry ? $selectedInquiry->adults . ' Adults · ' . ($selectedInquiry->travel_style ?: 'Private Safari') . ' · ' . ($selectedInquiry->accommodation_preference ?: 'Comfort') : '') }}"
                       placeholder="e.g. 2 Adults · Private Safari · Comfort"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Personal Welcome Message</label>
                <textarea name="welcome_message" rows="3"
                          class="w-full bg-white border border-gray-300 rounded-xl p-4 text-xs font-medium focus:ring-2 focus:ring-amber-500/20 outline-none">@if($selectedInquiry)Dear {{ $selectedInquiry->full_name }}, Thank you for contacting Twina Safaris! We are excited to present your personalized {{ $selectedInquiry->duration_days }}-day {{ $selectedInquiry->trip_type ?: 'Tanzania Safari' }} itinerary. @else{{ old('welcome_message') }}@endif</textarea>
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
                <input type="number" name="duration_days" x-model="durationDays" required min="1"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Nights</label>
                <input type="number" name="duration_nights" value="{{ old('duration_nights', $selectedProposalTemplate ? $selectedProposalTemplate->duration_nights : ($selectedTour ? $selectedTour->duration_nights : 6)) }}" min="0"
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
                <input type="text" name="start_location" value="{{ old('start_location', 'Moshi / Arusha') }}"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">End Location</label>
                <input type="text" name="end_location" value="{{ old('end_location', 'Arusha / Zanzibar') }}"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Safari Style</label>
                <input type="text" name="safari_style" value="{{ old('safari_style', $selectedInquiry ? $selectedInquiry->travel_style : 'Private 4x4 Safari') }}"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Accommodation Level</label>
                <select name="accommodation_level" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
                    <option value="Budget">Budget</option>
                    <option value="Standard">Standard</option>
                    <option value="Comfort" selected>Comfort / Mid-Range</option>
                    <option value="Luxury">Luxury</option>
                    <option value="Premium">Premium / Super Luxury</option>
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
                {{-- Insert Day Template Dropdown --}}
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

                    {{-- Day Basic Info --}}
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

                    {{-- Description & Experience --}}
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

                    {{-- Travel Info & Accommodation --}}
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

                    {{-- DAY MEDIA SECTION (MANDATORY DAY-SPECIFIC IMAGES) --}}
                    <div class="p-4 bg-amber-50/40 rounded-xl border border-amber-200">
                        <h4 class="text-[11px] font-black text-amber-900 uppercase tracking-wider mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Day <span x-text="index + 1"></span> Media (Belongs strictly to this day)
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-700 uppercase mb-1">Day Cover Image URL *</label>
                                <input type="text" :name="'itinerary[' + index + '][cover_image]'" x-model="day.cover_image"
                                       placeholder="Cover Image URL for Day"
                                       class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none">
                                <template x-if="day.cover_image">
                                    <img :src="day.cover_image" class="mt-2 h-20 rounded-lg object-cover border">
                                </template>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-gray-700 uppercase mb-1">Additional Gallery Images (Comma or JSON)</label>
                                <input type="text" :name="'itinerary[' + index + '][gallery_images]'"
                                       :value="typeof day.gallery_images === 'string' ? day.gallery_images : JSON.stringify(day.gallery_images)"
                                       @input="day.gallery_images = $event.target.value"
                                       placeholder="e.g. image1.jpg, image2.jpg"
                                       class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none">
                                <span class="text-[10px] text-gray-500">Day-specific gallery images shown as small thumbnail gallery</span>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- SECTION 4: ACCOMMODATION SUMMARY TABLE --}}
    <div class="neo-card p-6 mb-8" x-data="{
        accommodations: [
            { property_name: 'Tarangire Safari Lodge', location: 'Tarangire', category: 'Comfort', room_type: 'Luxury Tent', nights: 1, meal_plan: 'Full Board', description: '', image: '', website_url: '' }
        ],
        addAcc() { this.accommodations.push({ property_name: '', location: '', category: 'Comfort', room_type: '', nights: 1, meal_plan: 'Full Board', description: '', image: '', website_url: '' }); },
        removeAcc(i) { if(this.accommodations.length > 1) this.accommodations.splice(i, 1); }
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
                        <button type="button" @click="removeAcc(aIndex)" x-show="accommodations.length > 1" class="text-rose-600 text-xs font-bold">Remove</button>
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
                          class="w-full bg-white border border-emerald-300 rounded-xl p-4 text-xs font-medium focus:ring-2 focus:ring-emerald-500/20 outline-none">@if($selectedTour && is_array($selectedTour->inclusions)){{ implode("\n", $selectedTour->inclusions) }}@else{{ old('inclusions', "All national park entry fees\nPrivate 4x4 Safari Land Cruiser with pop-up roof\nProfessional English-speaking driver/guide\nFull board accommodation on safari\nAll meals as specified in the itinerary\nUnlimited bottled drinking water in vehicle") }}@endif</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-rose-700 uppercase mb-2">What is Excluded (One per line)</label>
                <textarea name="exclusions" rows="8"
                          class="w-full bg-white border border-rose-300 rounded-xl p-4 text-xs font-medium focus:ring-2 focus:ring-rose-500/20 outline-none">@if($selectedTour && is_array($selectedTour->exclusions)){{ implode("\n", $selectedTour->exclusions) }}@else{{ old('exclusions', "International flights & visas\nTravel & medical insurance\nTips for driver/guide & lodge staff\nPersonal items & laundry\nOptional experiences (e.g. Balloon Safari)") }}@endif</textarea>
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
                <input type="number" step="0.01" name="adult_price" value="{{ old('adult_price', 1600) }}"
                       placeholder="e.g. 1600.00"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Child Price (Per Person)</label>
                <input type="number" step="0.01" name="child_price" value="{{ old('child_price') }}"
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
                <input type="number" step="0.01" name="deposit_required" value="{{ old('deposit_required', 1000) }}"
                       placeholder="e.g. 1000.00"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold outline-none">
            </div>
        </div>
    </div>

    {{-- SECTION 7: GRANULAR INTERNAL COSTING ENGINE (ADMIN ONLY) --}}
    <div class="neo-card p-6 mb-8 border-2 border-indigo-200 bg-indigo-50/20">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-black text-indigo-950 uppercase tracking-widest flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xs">7</span>
                    Internal Costing Engine
                    <span class="bg-rose-100 text-rose-800 text-[9px] font-black uppercase px-2 py-0.5 rounded-full">ADMIN ONLY</span>
                </h3>
                <p class="text-[11px] text-indigo-700 font-bold mt-1">Private cost breakdown for profit margin analysis. Strictly hidden from client.</p>
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

        {{-- Profit Summary Box --}}
        <div class="p-4 bg-indigo-900 text-white rounded-2xl grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
            <div>
                <div class="text-[10px] font-bold uppercase text-indigo-300">Total Internal Cost</div>
                <div class="text-lg font-black" x-text="currency + ' ' + totalCost.toFixed(2)"></div>
            </div>
            <div>
                <div class="text-[10px] font-bold uppercase text-indigo-300">Net Profit</div>
                <div class="text-lg font-black text-emerald-400" x-text="currency + ' ' + profit.toFixed(2)"></div>
            </div>
            <div>
                <div class="text-[10px] font-bold uppercase text-indigo-300">Profit Margin</div>
                <div class="text-lg font-black text-amber-400" x-text="profitMargin.toFixed(2) + '%'"></div>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-4">
        <a href="{{ route('admin.proposals.index') }}" class="px-6 py-3 neo-btn text-xs font-bold text-gray-600 uppercase">Cancel</a>
        <button type="submit" class="px-8 py-3 bg-amber-500 text-white rounded-xl font-black text-xs uppercase shadow-lg hover:bg-amber-600 transition-all">
            Save & Publish Proposal
        </button>
    </div>
</form>

@php
    $defaultItinerary = [
        [
            'title' => 'Day 1: Arrival at Kilimanjaro & Safari Briefing',
            'destination' => 'Arusha',
            'starting_point' => 'JRO Airport',
            'ending_point' => 'Arusha Planet Lodge',
            'route' => 'JRO Airport → Arusha',
            'description' => 'Upon arrival at Kilimanjaro International Airport (JRO), you will be met by your private Twina Safaris driver guide and transferred to your lodge in Arusha.',
            'activities' => 'Airport transfer, safari briefing',
            'optional_activities' => '',
            'driving_time' => '50 km / 1 hr',
            'meals' => 'Dinner',
            'accommodation_property' => 'Arusha Planet Lodge',
            'room_type' => 'Standard Room',
            'cover_image' => 'https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1200&q=80',
            'gallery_images' => []
        ],
        [
            'title' => 'Day 2: Tarangire National Park Game Drive',
            'destination' => 'Tarangire',
            'starting_point' => 'Arusha',
            'ending_point' => 'Tarangire Safari Lodge',
            'route' => 'Arusha → Tarangire',
            'description' => 'After breakfast, drive to Tarangire National Park, famous for its massive elephant herds and iconic baobab trees. Enjoy a full day game drive with a picnic lunch.',
            'activities' => 'Game drive, wildlife viewing, picnic lunch',
            'optional_activities' => '',
            'driving_time' => '120 km / 2.5 hrs',
            'meals' => 'Breakfast, Lunch, Dinner',
            'accommodation_property' => 'Tarangire Safari Lodge',
            'room_type' => 'Luxury Tent',
            'cover_image' => 'https://images.unsplash.com/photo-1534567153574-2b12153a87f0?auto=format&fit=crop&w=1200&q=80',
            'gallery_images' => []
        ]
    ];

    if ($selectedProposalTemplate && is_array($selectedProposalTemplate->itinerary) && count($selectedProposalTemplate->itinerary) > 0) {
        $initialItinerary = $selectedProposalTemplate->itinerary;
    } elseif ($selectedTour && is_array($selectedTour->itinerary) && count($selectedTour->itinerary) > 0) {
        $initialItinerary = $selectedTour->itinerary;
    } else {
        $initialItinerary = $defaultItinerary;
    }

    $itineraryData = old('itinerary', $initialItinerary);
@endphp

<script>
function proposalBuilderForm() {
    return {
        currency: 'USD',
        durationDays: {{ old('duration_days', $selectedInquiry ? $selectedInquiry->duration_days : ($selectedProposalTemplate ? $selectedProposalTemplate->duration_days : ($selectedTour ? $selectedTour->duration_days : 7))) }},
        adults: {{ old('adults', $selectedInquiry ? $selectedInquiry->adults : 2) }},
        children: {{ old('children', $selectedInquiry ? $selectedInquiry->children : 0) }},
        subtotalPrice: {{ old('subtotal_price', 3500) }},
        discountAmount: {{ old('discount_amount', 300) }},
        totalPrice: {{ old('total_price', 3200) }},
        costs: {
            accommodation: 1200,
            park: 650,
            vehicle: 400,
            guide: 200,
            meals: 0,
            transfers: 0,
            flights: 0,
            activities: 0,
            government: 0,
            other: 0
        },
        days: @json($itineraryData),
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
