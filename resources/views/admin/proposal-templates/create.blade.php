@extends('admin.layouts.app')

@section('title', 'Create Proposal Template')
@section('page-title', 'Create Proposal Template')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Create Reusable Proposal Template</h2>
        <p class="text-gray-400 font-bold uppercase text-[10px] tracking-widest mt-1">Build an internal itinerary blueprint for future client proposals</p>
    </div>
    <a href="{{ route('admin.proposal-templates.index') }}" class="px-4 py-2 neo-btn text-xs font-bold text-gray-600 uppercase">Back to Templates</a>
</div>

<form action="{{ route('admin.proposal-templates.store') }}" method="POST" x-data="templateBuilderForm()">
    @csrf

    {{-- SECTION 1: TEMPLATE OVERVIEW --}}
    <div class="neo-card p-6 mb-8">
        <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-4 flex items-center gap-2">
            <span class="w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs">1</span>
            Template Overview
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Template Title *</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       placeholder="e.g. 7 Days Tanzania Comfort Safari"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-bold outline-none focus:ring-2 focus:ring-amber-500/20">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Subtitle / Tagline</label>
                <input type="text" name="subtitle" value="{{ old('subtitle') }}"
                       placeholder="e.g. 2 Adults · Private Safari · Comfort Lodges"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-bold outline-none focus:ring-2 focus:ring-amber-500/20">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Duration (Days) *</label>
                <input type="number" name="duration_days" x-model.number="durationDays" required min="1"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Nights</label>
                <input type="number" name="duration_nights" value="{{ old('duration_nights', 6) }}" min="0"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Start Location</label>
                <input type="text" name="start_location" value="{{ old('start_location', 'Arusha') }}"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">End Location</label>
                <input type="text" name="end_location" value="{{ old('end_location', 'Arusha / Zanzibar') }}"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Safari Style</label>
                <input type="text" name="safari_style" value="{{ old('safari_style', 'Private 4x4 Safari') }}"
                       class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Accommodation Level</label>
                <select name="accommodation_level" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold outline-none">
                    <option value="Budget">Budget</option>
                    <option value="Standard">Standard</option>
                    <option value="Comfort" selected>Comfort / Mid-Range</option>
                    <option value="Luxury">Luxury</option>
                    <option value="Premium">Premium</option>
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

                    <div class="mt-4 p-4 bg-emerald-50/40 rounded-xl border border-emerald-900/20">
                        <div class="flex items-center justify-between mb-3 pb-2 border-b border-emerald-900/10">
                            <div>
                                <h4 class="text-xs font-black text-[#052010] uppercase tracking-wider flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Day <span x-text="index + 1"></span> Photos & Captions
                                </h4>
                                <p class="text-[11px] text-gray-500">Upload multiple photos directly from your device for Day <span x-text="index + 1"></span>.</p>
                            </div>

                            <div>
                                <input type="file"
                                       accept="image/*"
                                       multiple
                                       :id="'tmpl_create_day_images_input_' + index"
                                       class="hidden"
                                       @change="uploadDayImages($event, index)">

                                <button type="button"
                                        @click="document.getElementById('tmpl_create_day_images_input_' + index).click()"
                                        class="px-3.5 py-2 bg-[#052010] hover:bg-[#08331a] text-[#D4AF37] border border-[#D4AF37]/40 rounded-xl font-bold text-xs uppercase tracking-wider shadow-sm transition-all flex items-center gap-1.5 cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Upload Images from Device
                                </button>
                            </div>
                        </div>

                        <div x-show="day.images && day.images.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mt-3">
                            <template x-for="(img, imgIndex) in day.images" :key="imgIndex">
                                <div class="bg-white border border-gray-200 rounded-xl p-3 shadow-xs relative flex flex-col justify-between">
                                    <input type="hidden" :name="'itinerary[' + index + '][images][' + imgIndex + '][url]'" :value="img.url">
                                    <input type="hidden" :name="'itinerary[' + index + '][images][' + imgIndex + '][caption]'" :value="img.caption">

                                    <div>
                                        <div class="h-28 w-full rounded-lg overflow-hidden bg-gray-100 relative mb-2.5 border border-gray-200">
                                            <img :src="img.url.startsWith('storage/') ? '/' + img.url : img.url" class="w-full h-full object-cover">
                                            <div class="absolute top-1.5 right-1.5 flex items-center gap-1">
                                                <button type="button" x-show="imgIndex > 0" @click="moveDayImage(index, imgIndex, -1)" class="p-1 bg-gray-900/70 hover:bg-gray-900 text-white rounded-md">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                                </button>
                                                <button type="button" x-show="imgIndex < day.images.length - 1" @click="moveDayImage(index, imgIndex, 1)" class="p-1 bg-gray-900/70 hover:bg-gray-900 text-white rounded-md">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                </button>
                                                <input type="file" accept="image/*" :id="'tmpl_c_repl_' + index + '_' + imgIndex" class="hidden" @change="replaceDayImage($event, index, imgIndex)">
                                                <button type="button" @click="document.getElementById('tmpl_c_repl_' + index + '_' + imgIndex).click()" class="p-1 bg-amber-600 hover:bg-amber-700 text-white rounded-md">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                </button>
                                                <button type="button" @click="deleteDayImage(index, imgIndex)" class="p-1 bg-rose-600 hover:bg-rose-700 text-white rounded-md">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-1.5 mb-1">
                                                <span class="text-[10px] font-extrabold text-[#b8920d] uppercase tracking-wider" x-text="'Figure ' + (imgIndex + 1) + '.'"></span>
                                                <span class="text-[9px] font-bold text-gray-400 uppercase">Caption</span>
                                            </div>
                                            <input type="text" x-model="img.caption" placeholder="Caption..." class="w-full bg-slate-50 border border-gray-300 rounded-lg px-2.5 py-1.5 text-xs">
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div x-show="!day.images || day.images.length === 0" @click="document.getElementById('tmpl_create_day_images_input_' + index).click()" class="mt-2 p-4 border-2 border-dashed border-gray-300 hover:border-amber-500 rounded-xl text-center cursor-pointer bg-white/50">
                            <div class="text-xs font-bold text-gray-700">No images uploaded for this day yet</div>
                            <div class="text-[10px] text-gray-400 mt-0.5">Click here to upload multiple photos directly from your device</div>
                        </div>
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
                <textarea name="inclusions" rows="6" class="w-full bg-white border border-emerald-300 rounded-xl p-4 text-xs font-medium outline-none">All national park entry fees&#10;Private 4x4 Safari Land Cruiser with pop-up roof&#10;Professional English-speaking driver/guide&#10;Full board accommodation on safari&#10;Unlimited bottled drinking water in vehicle</textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-rose-700 uppercase mb-2">Standard Exclusions (One per line)</label>
                <textarea name="exclusions" rows="6" class="w-full bg-white border border-rose-300 rounded-xl p-4 text-xs font-medium outline-none">International flights & visas&#10;Travel & medical insurance&#10;Tips for driver/guide & lodge staff&#10;Personal items & laundry</textarea>
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
                <input type="number" step="0.01" name="default_total_price" value="{{ old('default_total_price', 3200) }}" required class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-sm font-black text-amber-600 outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Default Adult Price</label>
                <input type="number" step="0.01" name="default_adult_price" value="{{ old('default_adult_price', 1600) }}" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-2">Default Child Price</label>
                <input type="number" step="0.01" name="default_child_price" value="{{ old('default_child_price', 800) }}" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-3 text-xs font-bold outline-none">
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-4">
        <a href="{{ route('admin.proposal-templates.index') }}" class="px-6 py-3 neo-btn text-xs font-bold text-gray-600 uppercase">Cancel</a>
        <button type="submit" class="px-8 py-3 bg-amber-500 hover:bg-amber-600 text-white font-black text-xs uppercase rounded-xl shadow">
            Save Proposal Template
        </button>
    </div>
</form>

<script>
function templateBuilderForm() {
    return {
        durationDays: 7,
        days: [
            { title: 'Day 1: Arrival & Safari Briefing', destination: 'Arusha', description: 'Met at airport and transferred to lodge.', activities: 'Airport transfer', meals: 'Dinner', accommodation_property: 'Arusha Lodge', images: [] },
            { title: 'Day 2: Tarangire Game Drive', destination: 'Tarangire', description: 'Full day game drive in Tarangire.', activities: 'Game drive', meals: 'Breakfast, Lunch, Dinner', accommodation_property: 'Tarangire Lodge', images: [] }
        ],
        addDay() {
            this.days.push({ title: 'Day ' + (this.days.length + 1) + ': ', destination: '', description: '', activities: '', meals: 'Breakfast, Lunch, Dinner', accommodation_property: '', images: [] });
            this.durationDays = this.days.length;
        },
        removeDay(i) {
            if (this.days.length > 1) {
                this.days.splice(i, 1);
                this.durationDays = this.days.length;
            }
        },
        uploadDayImages(event, dayIndex) {
            const files = Array.from(event.target.files);
            if (!files || files.length === 0) return;
            if (!this.days[dayIndex].images) this.days[dayIndex].images = [];
            files.forEach(file => {
                const formData = new FormData();
                formData.append('image', file);
                formData.append('_token', '{{ csrf_token() }}');
                fetch('{{ route('admin.proposals.upload-day-image') }}', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.url) {
                        this.days[dayIndex].images.push({ url: data.url, caption: '' });
                    } else {
                        const reader = new FileReader();
                        reader.onload = (e) => { this.days[dayIndex].images.push({ url: e.target.result, caption: '' }); };
                        reader.readAsDataURL(file);
                    }
                })
                .catch(() => {
                    const reader = new FileReader();
                    reader.onload = (e) => { this.days[dayIndex].images.push({ url: e.target.result, caption: '' }); };
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
            fetch('{{ route('admin.proposals.upload-day-image') }}', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.url) {
                    this.days[dayIndex].images[imgIndex].url = data.url;
                } else {
                    const reader = new FileReader();
                    reader.onload = (e) => { this.days[dayIndex].images[imgIndex].url = e.target.result; };
                    reader.readAsDataURL(file);
                }
            })
            .catch(() => {
                const reader = new FileReader();
                reader.onload = (e) => { this.days[dayIndex].images[imgIndex].url = e.target.result; };
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
        }
    }
}
</script>
@endsection
