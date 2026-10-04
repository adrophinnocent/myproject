@extends('admin.layouts.app')

@section('title', 'Edit Proposal Template - ' . $template->title)
@section('page-title', 'Edit Proposal Template')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Edit Proposal Template</h2>
        <p class="text-gray-400 font-bold uppercase text-[10px] tracking-widest mt-1">Editing template: {{ $template->title }}</p>
    </div>
    <a href="{{ route('admin.proposal-templates.index') }}" class="px-4 py-2 neo-btn text-xs font-bold text-gray-600 uppercase">Back to Templates</a>
</div>

<form action="{{ route('admin.proposal-templates.update', $template) }}" method="POST" x-data="templateBuilderForm()">
    @csrf
    @method('PUT')

    {{-- SECTION 1: TEMPLATE OVERVIEW --}}
    <div class="neo-card p-6 mb-8">
        <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-4 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs">1</span>
            Template Overview
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Template Title *</label>
                <input type="text" name="title" value="{{ old('title', $template->title) }}" required
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-bold outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Subtitle / Tagline</label>
                <input type="text" name="subtitle" value="{{ old('subtitle', $template->subtitle) }}"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-bold outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Duration (Days) *</label>
                <input type="number" name="duration_days" x-model.number="durationDays" required min="1"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Nights</label>
                <input type="number" name="duration_nights" value="{{ old('duration_nights', $template->duration_nights) }}" min="0"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Start Location</label>
                <input type="text" name="start_location" value="{{ old('start_location', $template->start_location) }}"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">End Location</label>
                <input type="text" name="end_location" value="{{ old('end_location', $template->end_location) }}"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Safari Style</label>
                <input type="text" name="safari_style" value="{{ old('safari_style', $template->safari_style) }}"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Accommodation Level</label>
                <select name="accommodation_level" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold outline-none">
                    <option value="Budget" {{ $template->accommodation_level === 'Budget' ? 'selected' : '' }}>Budget</option>
                    <option value="Standard" {{ $template->accommodation_level === 'Standard' ? 'selected' : '' }}>Standard</option>
                    <option value="Comfort" {{ $template->accommodation_level === 'Comfort' ? 'selected' : '' }}>Comfort / Mid-Range</option>
                    <option value="Luxury" {{ $template->accommodation_level === 'Luxury' ? 'selected' : '' }}>Luxury</option>
                    <option value="Premium" {{ $template->accommodation_level === 'Premium' ? 'selected' : '' }}>Premium</option>
                </select>
            </div>
        </div>
    </div>

    {{-- SECTION 2: DAY-BY-DAY BUILDER --}}
    <div class="neo-card p-6 mb-8">
        <div class="flex items-center justify-between gap-4 mb-6 pb-4 border-b">
            <div>
                <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs">2</span>
                    Template Itinerary Days
                </h3>
            </div>
            <button type="button" @click="addDay()" class="px-4 py-2 neo-btn text-xs font-bold text-amber-600 uppercase">+ Add Day</button>
        </div>

        <div class="space-y-6">
            <template x-for="(day, index) in days" :key="index">
                <div class="p-6 bg-white border border-gray-200 rounded-2xl relative shadow-sm">
                    <div class="flex items-center justify-between mb-4 pb-2 border-b">
                        <span class="text-xs font-black text-amber-600 uppercase" x-text="'Day ' + (index + 1)"></span>
                        <button type="button" @click="removeDay(index)" x-show="days.length > 1" class="text-rose-500 hover:text-rose-700 text-xs font-bold">Remove Day</button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Day Title *</label>
                            <input type="text" :name="'itinerary[' + index + '][title]'" x-model="day.title" required class="w-full bg-white border rounded-xl px-3 py-2 text-xs font-bold">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Destination</label>
                            <input type="text" :name="'itinerary[' + index + '][destination]'" x-model="day.destination" class="w-full bg-white border rounded-xl px-3 py-2 text-xs font-bold">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Description *</label>
                        <textarea :name="'itinerary[' + index + '][description]'" x-model="day.description" rows="3" class="w-full bg-white border rounded-xl p-3 text-xs"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Activities</label>
                            <input type="text" :name="'itinerary[' + index + '][activities]'" x-model="day.activities" class="w-full bg-white border rounded-lg px-2.5 py-1.5 text-xs">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Meals</label>
                            <input type="text" :name="'itinerary[' + index + '][meals]'" x-model="day.meals" class="w-full bg-white border rounded-lg px-2.5 py-1.5 text-xs">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-600 uppercase mb-1">Accommodation Property</label>
                            <input type="text" :name="'itinerary[' + index + '][accommodation_property]'" x-model="day.accommodation_property" class="w-full bg-white border rounded-lg px-2.5 py-1.5 text-xs">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-700 uppercase mb-1">Day Cover Image URL</label>
                        <input type="text" :name="'itinerary[' + index + '][cover_image]'" x-model="day.cover_image" class="w-full bg-white border rounded-lg px-3 py-2 text-xs">
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- SECTION 3: INCLUSIONS & EXCLUSIONS --}}
    <div class="neo-card p-6 mb-8">
        <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-4 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs">3</span>
            Inclusions & Exclusions
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-xs font-bold text-emerald-700 uppercase mb-2">Standard Inclusions (One per line)</label>
                <textarea name="inclusions" rows="6" class="w-full bg-white border border-emerald-300 rounded-xl p-4 text-xs font-medium outline-none">{{ implode("\n", $template->structured_inclusions) }}</textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-rose-700 uppercase mb-2">Standard Exclusions (One per line)</label>
                <textarea name="exclusions" rows="6" class="w-full bg-white border border-rose-300 rounded-xl p-4 text-xs font-medium outline-none">{{ implode("\n", $template->structured_exclusions) }}</textarea>
            </div>
        </div>
    </div>

    {{-- SECTION 4: STANDARD PRICING --}}
    <div class="neo-card p-6 mb-8">
        <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-4 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs">4</span>
            Standard Selling Price Structure
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Default Total Selling Price *</label>
                <input type="number" step="0.01" name="default_total_price" value="{{ old('default_total_price', $template->default_total_price) }}" required class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-black text-amber-600 outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Default Adult Price</label>
                <input type="number" step="0.01" name="default_adult_price" value="{{ old('default_adult_price', $template->default_adult_price) }}" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Default Child Price</label>
                <input type="number" step="0.01" name="default_child_price" value="{{ old('default_child_price', $template->default_child_price) }}" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold outline-none">
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-4">
        <a href="{{ route('admin.proposal-templates.index') }}" class="px-6 py-3 neo-btn text-xs font-bold text-gray-600 uppercase">Cancel</a>
        <button type="submit" class="px-8 py-3 bg-amber-500 hover:bg-amber-600 text-white font-black text-xs uppercase rounded-xl shadow">
            Update Proposal Template
        </button>
    </div>
</form>

<script>
function templateBuilderForm() {
    return {
        durationDays: {{ $template->duration_days }},
        days: @json($template->structured_itinerary),
        addDay() {
            this.days.push({ title: 'Day ' + (this.days.length + 1) + ': ', destination: '', description: '', activities: '', meals: 'Breakfast, Lunch, Dinner', accommodation_property: '', cover_image: '' });
            this.durationDays = this.days.length;
        },
        removeDay(i) {
            if (this.days.length > 1) {
                this.days.splice(i, 1);
                this.durationDays = this.days.length;
            }
        }
    }
}
</script>
@endsection
