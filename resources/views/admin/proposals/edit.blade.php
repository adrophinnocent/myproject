@extends('admin.layouts.app')

@section('title', 'Edit Proposal - ' . $proposal->title)
@section('page-title', 'Edit Client Proposal')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Edit Proposal: {{ $proposal->client_name }}</h2>
        <p class="text-gray-400 font-bold uppercase text-[10px] tracking-widest mt-1">Update personalized itinerary, pricing, or internal costing</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.proposals.show', $proposal) }}" class="px-4 py-2 neo-btn text-xs font-bold text-gray-600 uppercase">View Workspace</a>
        <a href="{{ route('admin.proposals.index') }}" class="px-4 py-2 neo-btn text-xs font-bold text-gray-600 uppercase">Back to List</a>
    </div>
</div>

<form action="{{ route('admin.proposals.update', $proposal) }}" method="POST" x-data="proposalEditForm()">
    @csrf
    @method('PUT')

    {{-- Status Selector --}}
    <div class="neo-card p-6 mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4 border-l-4 border-amber-500">
        <div>
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Proposal Status</label>
            <p class="text-xs text-gray-500">Current status: <span class="font-bold text-amber-600 uppercase">{{ str_replace('_', ' ', $proposal->status) }}</span></p>
        </div>
        <div class="w-full md:w-64">
            <select name="status" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-xs font-bold text-gray-800 focus:outline-none">
                <option value="draft" {{ old('status', $proposal->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="sent" {{ old('status', $proposal->status) == 'sent' ? 'selected' : '' }}>Sent to Client</option>
                <option value="viewed" {{ old('status', $proposal->status) == 'viewed' ? 'selected' : '' }}>Viewed by Client</option>
                <option value="accepted" {{ old('status', $proposal->status) == 'accepted' ? 'selected' : '' }}>Accepted</option>
                <option value="changes_requested" {{ old('status', $proposal->status) == 'changes_requested' ? 'selected' : '' }}>Changes Requested</option>
                <option value="declined" {{ old('status', $proposal->status) == 'declined' ? 'selected' : '' }}>Declined</option>
            </select>
        </div>
    </div>

    {{-- 1. CLIENT DETAILS --}}
    <div class="neo-card p-6 mb-8">
        <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-4 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs">1</span>
            Client Information
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Client Full Name *</label>
                <input type="text" name="client_name" value="{{ old('client_name', $proposal->client_name) }}" required
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Client Email *</label>
                <input type="email" name="client_email" value="{{ old('client_email', $proposal->client_email) }}" required
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Phone / WhatsApp</label>
                <input type="text" name="client_phone" value="{{ old('client_phone', $proposal->client_phone) }}"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
            </div>
        </div>
    </div>

    {{-- 2. SAFARI & TRIP OVERVIEW --}}
    <div class="neo-card p-6 mb-8">
        <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-4 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs">2</span>
            Safari & Trip Overview
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Proposal / Safari Title *</label>
                <input type="text" name="title" value="{{ old('title', $proposal->title) }}" required
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Personal Welcome Message</label>
                <textarea name="welcome_message" rows="3"
                          class="w-full bg-white border border-gray-300 rounded-xl p-4 text-xs font-medium focus:ring-2 focus:ring-amber-500/20 outline-none">{{ old('welcome_message', $proposal->welcome_message) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Start Date</label>
                <input type="date" name="start_date" value="{{ old('start_date', $proposal->start_date ? $proposal->start_date->format('Y-m-d') : '') }}"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">End Date</label>
                <input type="date" name="end_date" value="{{ old('end_date', $proposal->end_date ? $proposal->end_date->format('Y-m-d') : '') }}"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Duration (Days) *</label>
                <input type="number" name="duration_days" x-model="durationDays" required min="1"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Adults *</label>
                    <input type="number" name="adults" value="{{ old('adults', $proposal->adults) }}" required min="1"
                           class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Children</label>
                    <input type="number" name="children" value="{{ old('children', $proposal->children) }}" min="0"
                           class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
                </div>
            </div>
        </div>
    </div>

    {{-- 3. DAY-BY-DAY ITINERARY BUILDER --}}
    <div class="neo-card p-6 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs">3</span>
                Day-by-Day Itinerary Builder
            </h3>
            <button type="button" @click="addDay()" class="px-4 py-2 neo-btn text-xs font-bold text-amber-600 uppercase flex items-center gap-1">
                + Add Day
            </button>
        </div>

        <div class="space-y-6">
            <template x-for="(day, index) in days" :key="index">
                <div class="p-5 bg-white/60 border border-gray-200/80 rounded-2xl relative shadow-sm">
                    <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-200">
                        <span class="text-xs font-black text-amber-600 uppercase tracking-wider" x-text="'Day ' + (index + 1)"></span>
                        <button type="button" @click="removeDay(index)" x-show="days.length > 1" class="text-rose-500 hover:text-rose-700 text-xs font-bold">
                            Remove Day
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Day Title *</label>
                            <input type="text" :name="'itinerary[' + index + '][title]'" x-model="day.title" required
                                   class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-xs font-bold focus:outline-none">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Day Description & Activities</label>
                            <textarea :name="'itinerary[' + index + '][description]'" x-model="day.description" rows="3"
                                      class="w-full bg-white border border-gray-300 rounded-xl p-3 text-xs focus:outline-none"></textarea>
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Accommodation / Lodge</label>
                            <input type="text" :name="'itinerary[' + index + '][accommodation]'" x-model="day.accommodation"
                                   class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-xs focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Included Meals</label>
                            <input type="text" :name="'itinerary[' + index + '][meals]'" x-model="day.meals"
                                   class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-xs focus:outline-none">
                        </div>

                        <div class="md:col-span-2">
                            <div class="p-4 bg-emerald-50/40 rounded-xl border border-emerald-900/20">
                                <div class="flex items-center justify-between mb-3 pb-2 border-b border-emerald-900/10">
                                    <div>
                                        <h4 class="text-xs font-black text-[#052010] uppercase tracking-wider flex items-center gap-1.5">
                                            <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            Day <span x-text="index + 1"></span> Photos & Captions
                                        </h4>
                                        <p class="text-[11px] text-gray-500">Upload multiple photos directly from your device for Day <span x-text="index + 1"></span>. Displayed 2–3 per row below description.</p>
                                    </div>

                                    <div>
                                        <input type="file"
                                               accept="image/*"
                                               multiple
                                               :id="'edit_day_images_input_' + index"
                                               class="hidden"
                                               @change="uploadDayImages($event, index)">

                                        <button type="button"
                                                @click="document.getElementById('edit_day_images_input_' + index).click()"
                                                class="px-3.5 py-2 bg-[#052010] hover:bg-[#08331a] text-[#D4AF37] border border-[#D4AF37]/40 rounded-xl font-bold text-xs uppercase tracking-wider shadow-sm transition-all flex items-center gap-1.5 cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            Upload Images from Device
                                        </button>
                                    </div>
                                </div>

                                {{-- Grid of Uploaded Images for this Day --}}
                                <div x-show="day.images && day.images.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-3">
                                    <template x-for="(img, imgIndex) in day.images" :key="imgIndex">
                                        <div class="bg-white border border-gray-200 rounded-xl p-3 shadow-xs relative flex flex-col justify-between">
                                            {{-- Hidden Form Fields --}}
                                            <input type="hidden" :name="'itinerary[' + index + '][images][' + imgIndex + '][url]'" :value="img.url">
                                            <input type="hidden" :name="'itinerary[' + index + '][images][' + imgIndex + '][caption]'" :value="img.caption">

                                            <div>
                                                <div class="h-28 w-full rounded-lg overflow-hidden bg-gray-100 relative mb-2.5 border border-gray-200">
                                                    <img :src="img.url.startsWith('storage/') ? '/' + img.url : img.url" class="w-full h-full object-cover">

                                                    {{-- Action Buttons --}}
                                                    <div class="absolute top-1.5 right-1.5 flex items-center gap-1">
                                                        <button type="button"
                                                                x-show="imgIndex > 0"
                                                                @click="moveDayImage(index, imgIndex, -1)"
                                                                title="Move Left"
                                                                class="p-1 bg-gray-900/70 hover:bg-gray-900 text-white rounded-md transition-all">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                                        </button>

                                                        <button type="button"
                                                                x-show="imgIndex < day.images.length - 1"
                                                                @click="moveDayImage(index, imgIndex, 1)"
                                                                title="Move Right"
                                                                class="p-1 bg-gray-900/70 hover:bg-gray-900 text-white rounded-md transition-all">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                        </button>

                                                        <input type="file"
                                                               accept="image/*"
                                                               :id="'edit_replace_image_' + index + '_' + imgIndex"
                                                               class="hidden"
                                                               @change="replaceDayImage($event, index, imgIndex)">
                                                        <button type="button"
                                                                @click="document.getElementById('edit_replace_image_' + index + '_' + imgIndex).click()"
                                                                title="Replace Image"
                                                                class="p-1 bg-amber-600 hover:bg-amber-700 text-white rounded-md transition-all">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                        </button>

                                                        <button type="button"
                                                                @click="deleteDayImage(index, imgIndex)"
                                                                title="Delete Image"
                                                                class="p-1 bg-rose-600 hover:bg-rose-700 text-white rounded-md transition-all">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div>
                                                    <div class="flex items-center gap-1.5 mb-1">
                                                        <span class="text-[10px] font-extrabold text-[#b8920d] uppercase tracking-wider" x-text="'Figure ' + (imgIndex + 1) + '.'"></span>
                                                        <span class="text-[9px] font-bold text-gray-400 uppercase">Caption</span>
                                                    </div>
                                                    <input type="text"
                                                           x-model="img.caption"
                                                           placeholder="e.g. Lions resting under acacia tree"
                                                           class="w-full bg-slate-50 border border-gray-300 rounded-lg px-2.5 py-1.5 text-xs font-medium focus:bg-white focus:ring-1 focus:ring-amber-500 outline-none">
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                {{-- Empty State --}}
                                <div x-show="!day.images || day.images.length === 0"
                                     @click="document.getElementById('edit_day_images_input_' + index).click()"
                                     class="mt-2 p-4 border-2 border-dashed border-gray-300 hover:border-amber-500 rounded-xl text-center cursor-pointer bg-white/50 transition-all">
                                    <svg class="w-8 h-8 mx-auto text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <div class="text-xs font-bold text-gray-700">No images uploaded for this day yet</div>
                                    <div class="text-[10px] text-gray-400 mt-0.5">Click here to upload multiple photos directly from your device</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- 4. INCLUSIONS & EXCLUSIONS --}}
    <div class="neo-card p-6 mb-8">
        <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-4 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs">4</span>
            Inclusions & Exclusions
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-emerald-700 uppercase mb-2">What is Included (One per line)</label>
                <textarea name="inclusions" rows="8"
                          class="w-full bg-white border border-emerald-300 rounded-xl p-4 text-xs font-medium focus:ring-2 focus:ring-emerald-500/20 outline-none">@if(is_array($proposal->inclusions)){{ implode("\n", $proposal->inclusions) }}@else{{ old('inclusions') }}@endif</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-rose-700 uppercase mb-2">What is Excluded (One per line)</label>
                <textarea name="exclusions" rows="8"
                          class="w-full bg-white border border-rose-300 rounded-xl p-4 text-xs font-medium focus:ring-2 focus:ring-rose-500/20 outline-none">@if(is_array($proposal->exclusions)){{ implode("\n", $proposal->exclusions) }}@else{{ old('exclusions') }}@endif</textarea>
            </div>
        </div>
    </div>

    {{-- 5. PUBLIC CLIENT PRICING & PAYMENT TERMS --}}
    <div class="neo-card p-6 mb-8">
        <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-4 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs">5</span>
            Client Price & Payment Terms
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Currency *</label>
                <select name="currency" x-model="currency" required class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
                    <option value="USD" {{ old('currency', $proposal->currency) == 'USD' ? 'selected' : '' }}>USD ($)</option>
                    <option value="EUR" {{ old('currency', $proposal->currency) == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                    <option value="GBP" {{ old('currency', $proposal->currency) == 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                    <option value="TZS" {{ old('currency', $proposal->currency) == 'TZS' ? 'selected' : '' }}>TZS (TSh)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Total Selling Price *</label>
                <input type="number" step="0.01" name="total_price" x-model.number="totalPrice" required
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-black text-amber-600 focus:ring-2 focus:ring-amber-500/20 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Price Per Person</label>
                <input type="number" step="0.01" name="price_per_person" value="{{ old('price_per_person', $proposal->price_per_person) }}"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Deposit Required</label>
                <input type="number" step="0.01" name="deposit_required" value="{{ old('deposit_required', $proposal->deposit_required) }}"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold focus:ring-2 focus:ring-amber-500/20 outline-none">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Payment Terms</label>
                <textarea name="payment_terms" rows="3"
                          class="w-full bg-white border border-gray-300 rounded-xl p-3 text-xs focus:ring-2 focus:ring-amber-500/20 outline-none">{{ old('payment_terms', $proposal->payment_terms) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Terms & Conditions Summary</label>
                <textarea name="terms_conditions" rows="3"
                          class="w-full bg-white border border-gray-300 rounded-xl p-3 text-xs focus:ring-2 focus:ring-amber-500/20 outline-none">{{ old('terms_conditions', $proposal->terms_conditions) }}</textarea>
            </div>
        </div>
    </div>

    {{-- 6. INTERNAL COSTING (ADMIN ONLY) --}}
    @php
        $costing = $proposal->internal_costing ?? [];
    @endphp
    <div class="neo-card p-6 mb-8 border-2 border-emerald-900/20 bg-[#f4fdf7]">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-black text-[#052010] uppercase tracking-widest flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-[#052010] text-[#D4AF37] flex items-center justify-center text-xs">6</span>
                    Internal Costing & Profit Margin
                    <span class="bg-rose-100 text-rose-800 text-[9px] font-black uppercase px-2 py-0.5 rounded-full">ADMIN ONLY</span>
                </h3>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div>
                <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Supplier / Operator Costs</label>
                <input type="number" step="0.01" name="internal_costing[supplier_costs]" x-model.number="costs.supplier"
                       class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-xs font-bold outline-none">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Vehicle / Fuel Costs</label>
                <input type="number" step="0.01" name="internal_costing[vehicle_costs]" x-model.number="costs.vehicle"
                       class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-xs font-bold outline-none">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Accommodation Costs</label>
                <input type="number" step="0.01" name="internal_costing[accommodation_costs]" x-model.number="costs.accommodation"
                       class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-xs font-bold outline-none">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Guide / Driver Fees</label>
                <input type="number" step="0.01" name="internal_costing[guide_costs]" x-model.number="costs.guide"
                       class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-xs font-bold outline-none">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Park & Entry Fees</label>
                <input type="number" step="0.01" name="internal_costing[park_fees]" x-model.number="costs.park"
                       class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-xs font-bold outline-none">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Activity Costs</label>
                <input type="number" step="0.01" name="internal_costing[activities_costs]" x-model.number="costs.activities"
                       class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-xs font-bold outline-none">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Other Expenses</label>
                <input type="number" step="0.01" name="internal_costing[other_expenses]" x-model.number="costs.other"
                       class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2 text-xs font-bold outline-none">
            </div>
        </div>

        {{-- Profit Summary Box --}}
        <div class="p-4 bg-[#052010] text-white rounded-2xl grid grid-cols-1 sm:grid-cols-3 gap-4 text-center border border-[#D4AF37]/30">
            <div>
                <div class="text-[10px] font-bold uppercase text-amber-200/80">Total Internal Cost</div>
                <div class="text-lg font-black" x-text="currency + ' ' + totalCost.toFixed(2)"></div>
            </div>
            <div>
                <div class="text-[10px] font-bold uppercase text-amber-200/80">Expected Profit</div>
                <div class="text-lg font-black text-emerald-400" x-text="currency + ' ' + profit.toFixed(2)"></div>
            </div>
            <div>
                <div class="text-[10px] font-bold uppercase text-amber-200/80">Profit Margin</div>
                <div class="text-lg font-black text-[#D4AF37]" x-text="profitMargin.toFixed(2) + '%'"></div>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-4">
        <a href="{{ route('admin.proposals.show', $proposal) }}" class="px-6 py-3 neo-btn text-xs font-bold text-gray-600 uppercase">Cancel</a>
        <button type="submit" class="px-8 py-3 bg-[#052010] hover:bg-[#08331a] text-[#D4AF37] border border-[#D4AF37]/40 rounded-xl font-black text-xs uppercase shadow-lg transition-all">
            Update Proposal
        </button>
    </div>
</form>

@php
    $defaultEditItinerary = [
        ['title' => 'Day 1', 'description' => '', 'accommodation' => '', 'meals' => '', 'activities' => '', 'images' => []]
    ];
    $initialEditItinerary = is_array($proposal->itinerary) && count($proposal->itinerary) > 0 ? $proposal->itinerary : $defaultEditItinerary;

    foreach ($initialEditItinerary as &$dayItem) {
        $existingImages = $dayItem['images'] ?? $dayItem['day_images'] ?? [];
        if (is_string($existingImages)) {
            $decoded = json_decode($existingImages, true);
            $existingImages = is_array($decoded) ? $decoded : [];
        }

        $formattedImages = [];
        if (is_array($existingImages)) {
            foreach ($existingImages as $imgObj) {
                $u = is_array($imgObj) ? ($imgObj['url'] ?? $imgObj['path'] ?? '') : $imgObj;
                $c = is_array($imgObj) ? ($imgObj['caption'] ?? '') : '';
                if ($u) {
                    $formattedImages[] = ['url' => $u, 'caption' => $c];
                }
            }
        }

        // Migration from legacy cover_image/image
        if (empty($formattedImages)) {
            if (!empty($dayItem['cover_image'])) {
                $formattedImages[] = ['url' => $dayItem['cover_image'], 'caption' => ''];
            } elseif (!empty($dayItem['image'])) {
                $formattedImages[] = ['url' => $dayItem['image'], 'caption' => ''];
            }
        }

        $dayItem['images'] = $formattedImages;
    }
    unset($dayItem);

    $editItineraryData = old('itinerary', $initialEditItinerary);
@endphp

<script>
function proposalEditForm() {
    return {
        currency: '{{ old('currency', $proposal->currency) }}',
        durationDays: {{ old('duration_days', $proposal->duration_days) }},
        totalPrice: {{ old('total_price', $proposal->total_price) }},
        costs: {
            supplier: {{ old('internal_costing.supplier_costs', $costing['supplier_costs'] ?? 0) }},
            vehicle: {{ old('internal_costing.vehicle_costs', $costing['vehicle_costs'] ?? 0) }},
            accommodation: {{ old('internal_costing.accommodation_costs', $costing['accommodation_costs'] ?? 0) }},
            guide: {{ old('internal_costing.guide_costs', $costing['guide_costs'] ?? 0) }},
            park: {{ old('internal_costing.park_fees', $costing['park_fees'] ?? 0) }},
            activities: {{ old('internal_costing.activities_costs', $costing['activities_costs'] ?? 0) }},
            other: {{ old('internal_costing.other_expenses', $costing['other_expenses'] ?? 0) }}
        },
        days: @json($editItineraryData),
        addDay() {
            this.days.push({
                title: 'Day ' + (this.days.length + 1) + ': ',
                description: '',
                accommodation: '',
                meals: '',
                activities: '',
                images: []
            });
            this.durationDays = this.days.length;
        },
        removeDay(index) {
            if (this.days.length > 1) {
                this.days.splice(index, 1);
                this.durationDays = this.days.length;
            }
        },
        uploadDayImages(event, dayIndex) {
            const files = Array.from(event.target.files);
            if (!files || files.length === 0) return;

            if (!this.days[dayIndex].images) {
                this.days[dayIndex].images = [];
            }

            files.forEach(file => {
                const formData = new FormData();
                formData.append('image', file);
                formData.append('_token', '{{ csrf_token() }}');

                fetch('{{ route('admin.proposals.upload-day-image') }}', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.url) {
                        this.days[dayIndex].images.push({
                            url: data.url,
                            caption: ''
                        });
                    } else {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.days[dayIndex].images.push({
                                url: e.target.result,
                                caption: ''
                            });
                        };
                        reader.readAsDataURL(file);
                    }
                })
                .catch(() => {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.days[dayIndex].images.push({
                            url: e.target.result,
                            caption: ''
                        });
                    };
                    reader.readAsDataURL(file);
                });
            });

            event.target.value = '';
        },
        replaceDayImage(event, dayIndex, imgIndex) {
            const file = event.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('image', file);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route('admin.proposals.upload-day-image') }}', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.url) {
                    this.days[dayIndex].images[imgIndex].url = data.url;
                } else {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.days[dayIndex].images[imgIndex].url = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            })
            .catch(() => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.days[dayIndex].images[imgIndex].url = e.target.result;
                };
                reader.readAsDataURL(file);
            });

            event.target.value = '';
        },
        deleteDayImage(dayIndex, imgIndex) {
            this.days[dayIndex].images.splice(imgIndex, 1);
        },
        moveDayImage(dayIndex, imgIndex, direction) {
            const images = this.days[dayIndex].images;
            const targetIndex = imgIndex + direction;
            if (targetIndex >= 0 && targetIndex < images.length) {
                const temp = images[imgIndex];
                images[imgIndex] = images[targetIndex];
                images[targetIndex] = temp;
            }
        },
        get totalCost() {
            return (this.costs.supplier || 0) +
                   (this.costs.vehicle || 0) +
                   (this.costs.accommodation || 0) +
                   (this.costs.guide || 0) +
                   (this.costs.park || 0) +
                   (this.costs.activities || 0) +
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
