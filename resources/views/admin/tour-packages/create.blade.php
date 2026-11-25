@extends('admin.layouts.app')

@section('title', 'Create Tour Package')
@section('page-title', 'Create Accommodation Package')

@section('content')
<div class="max-w-5xl mx-auto bg-white rounded-3xl p-8 border border-stone-200 shadow-sm space-y-8 text-xs" x-data="{
    sellingPrice: 2400,
    hotelCost: 800,
    parkCost: 400,
    transportCost: 300,
    guideCost: 150,
    mealsCost: 100,
    transfersCost: 50,
    otherCost: 50,
    get totalCost() {
        return (parseFloat(this.hotelCost||0) + parseFloat(this.parkCost||0) + parseFloat(this.transportCost||0) + parseFloat(this.guideCost||0) + parseFloat(this.mealsCost||0) + parseFloat(this.transfersCost||0) + parseFloat(this.otherCost||0));
    },
    get expectedProfit() {
        return (parseFloat(this.sellingPrice||0) - this.totalCost);
    },
    get marginPercent() {
        return this.sellingPrice > 0 ? ((this.expectedProfit / this.sellingPrice) * 100).toFixed(1) : 0;
    },
    itineraryDays: [
        { day_number: 1, title: 'Arrival & Transfer to Lodge', accommodation_id: '', accommodation_notes: 'Bed & Breakfast' },
        { day_number: 2, title: 'Tarangire National Park Game Drive', accommodation_id: '', accommodation_notes: 'Full Board Lodge Stay' },
        { day_number: 3, title: 'Serengeti National Park Safari', accommodation_id: '', accommodation_notes: 'Full Board Luxury Camp' }
    ],
    addDay() {
        const nextNum = this.itineraryDays.length + 1;
        this.itineraryDays.push({ day_number: nextNum, title: 'Day ' + nextNum + ' Safari Activity', accommodation_id: '', accommodation_notes: 'Full Board' });
    },
    removeDay(index) {
        this.itineraryDays.splice(index, 1);
        this.itineraryDays.forEach((d, i) => d.day_number = i + 1);
    }
}">

    <div class="flex items-center justify-between pb-4 border-b border-stone-100">
        <div>
            <h2 class="font-serif font-bold text-2xl text-stone-900">Create New Tour Accommodation Package</h2>
            <p class="text-xs text-stone-500 mt-0.5">Build a custom package (Budget, Standard, Luxury, VIP) and assign hotels night-by-night.</p>
        </div>
        <a href="{{ route('admin.tour-packages.index') }}" class="px-4 py-2 border border-stone-300 rounded-xl font-bold text-stone-600">Back</a>
    </div>

    <form action="{{ route('admin.tour-packages.store') }}" method="POST" class="space-y-8">
        @csrf

        {{-- 1. Basic Info & Tour Assignment --}}
        <div class="space-y-4">
            <h3 class="font-bold text-stone-900 text-sm uppercase tracking-wider border-b border-stone-100 pb-2">1. Package Info & Tour Assignment</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block font-bold text-stone-700 uppercase tracking-wider mb-2">Package Name *</label>
                    <input type="text" name="name" required placeholder="e.g. Luxury Serengeti Lodge Package" class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-sm font-semibold outline-none focus:border-amber-500">
                </div>

                <div>
                    <label class="block font-bold text-stone-700 uppercase tracking-wider mb-2">Assign to Tour Package *</label>
                    <select name="tour_id" required class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-xs font-semibold outline-none">
                        <option value="">Select Tour Package</option>
                        @foreach($tours as $t)
                            <option value="{{ $t->id }}" {{ $selectedTourId == $t->id ? 'selected' : '' }}>{{ $t->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-bold text-stone-700 uppercase tracking-wider mb-2">Package Description</label>
                <textarea name="description" rows="2" placeholder="Brief summary of accommodation style, lodge highlights, and inclusions..." class="w-full bg-stone-50 border border-stone-300 rounded-xl p-3 outline-none"></textarea>
            </div>
        </div>

        {{-- 2. Selling Price & Currency --}}
        <div class="space-y-4">
            <h3 class="font-bold text-stone-900 text-sm uppercase tracking-wider border-b border-stone-100 pb-2">2. Guest Selling Prices</h3>
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block font-bold text-stone-700 uppercase tracking-wider mb-1">Selling Price / Person ($) *</label>
                    <input type="number" step="0.01" name="selling_price" x-model="sellingPrice" required class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-2.5 font-black text-stone-900 outline-none">
                </div>
                <div>
                    <label class="block font-bold text-stone-700 uppercase tracking-wider mb-1">Child Price ($)</label>
                    <input type="number" step="0.01" name="child_price" placeholder="e.g. 1680.00" class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-2.5 outline-none font-bold">
                </div>
                <div>
                    <label class="block font-bold text-stone-700 uppercase tracking-wider mb-1">Single Supplement ($)</label>
                    <input type="number" step="0.01" name="single_supplement" placeholder="e.g. 350.00" class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-2.5 outline-none font-bold">
                </div>
                <div>
                    <label class="block font-bold text-stone-700 uppercase tracking-wider mb-1">Currency</label>
                    <select name="currency" class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-2.5 outline-none font-bold">
                        <option value="USD">USD ($)</option>
                        <option value="EUR">EUR (€)</option>
                        <option value="GBP">GBP (£)</option>
                        <option value="TZS">TZS (TSh)</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- 3. Private Internal Costing Breakdown (Strictly Hidden from Guests) --}}
        <div class="p-6 bg-stone-900 text-white rounded-3xl space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-stone-800">
                <div>
                    <h3 class="font-serif font-bold text-lg text-amber-400">🔒 Private Admin Internal Costing & Profit Margin Calculator</h3>
                    <p class="text-[11px] text-stone-400">Supplier costs are strictly confidential and never visible to website guests or clients.</p>
                </div>
                <div class="text-right">
                    <span class="text-[10px] text-stone-400 uppercase block">Expected Profit Margin</span>
                    <span class="font-display text-2xl font-black text-emerald-400" x-text="'$' + expectedProfit.toFixed(2) + ' (' + marginPercent + '%)'"></span>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                <div>
                    <label class="block text-stone-400 font-bold uppercase text-[10px] mb-1">Hotel Cost ($)</label>
                    <input type="number" step="0.01" name="supplier_hotel_cost" x-model="hotelCost" class="w-full bg-stone-800 border border-stone-700 rounded-xl p-2 font-bold text-white outline-none">
                </div>
                <div>
                    <label class="block text-stone-400 font-bold uppercase text-[10px] mb-1">Park Fees ($)</label>
                    <input type="number" step="0.01" name="park_fees_cost" x-model="parkCost" class="w-full bg-stone-800 border border-stone-700 rounded-xl p-2 font-bold text-white outline-none">
                </div>
                <div>
                    <label class="block text-stone-400 font-bold uppercase text-[10px] mb-1">Transport ($)</label>
                    <input type="number" step="0.01" name="transport_cost" x-model="transportCost" class="w-full bg-stone-800 border border-stone-700 rounded-xl p-2 font-bold text-white outline-none">
                </div>
                <div>
                    <label class="block text-stone-400 font-bold uppercase text-[10px] mb-1">Guide Cost ($)</label>
                    <input type="number" step="0.01" name="guide_cost" x-model="guideCost" class="w-full bg-stone-800 border border-stone-700 rounded-xl p-2 font-bold text-white outline-none">
                </div>
                <div>
                    <label class="block text-stone-400 font-bold uppercase text-[10px] mb-1">Meals ($)</label>
                    <input type="number" step="0.01" name="meals_cost" x-model="mealsCost" class="w-full bg-stone-800 border border-stone-700 rounded-xl p-2 font-bold text-white outline-none">
                </div>
                <div>
                    <label class="block text-stone-400 font-bold uppercase text-[10px] mb-1">Transfers ($)</label>
                    <input type="number" step="0.01" name="transfers_cost" x-model="transfersCost" class="w-full bg-stone-800 border border-stone-700 rounded-xl p-2 font-bold text-white outline-none">
                </div>
                <div>
                    <label class="block text-stone-400 font-bold uppercase text-[10px] mb-1">Other Costs ($)</label>
                    <input type="number" step="0.01" name="other_costs" x-model="otherCost" class="w-full bg-stone-800 border border-stone-700 rounded-xl p-2 font-bold text-white outline-none">
                </div>
                <div>
                    <label class="block text-stone-400 font-bold uppercase text-[10px] mb-1">Total Cost ($)</label>
                    <div class="w-full bg-stone-800 border border-stone-700 rounded-xl p-2 font-black text-amber-300" x-text="'$' + totalCost.toFixed(2)"></div>
                </div>
            </div>
        </div>

        {{-- 4. Dynamic Itinerary Editor & Nightly Hotel Assignment --}}
        <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-stone-100 pb-2">
                <div>
                    <h3 class="font-bold text-stone-900 text-sm uppercase tracking-wider">4. Package Itinerary & Hotel Assignment</h3>
                    <p class="text-[11px] text-stone-500">Assign a specific hotel from your accommodation database to each day.</p>
                </div>
                <button type="button" @click="addDay()" class="btn-gold px-4 py-2 rounded-xl font-bold text-xs">
                    + Add Day
                </button>
            </div>

            <div class="space-y-3">
                <template x-for="(day, index) in itineraryDays" :key="index">
                    <div class="p-4 bg-stone-50 border border-stone-200 rounded-2xl flex flex-col md:flex-row gap-4 items-start">
                        <div class="w-12 h-12 rounded-2xl bg-stone-900 text-amber-400 font-black flex items-center justify-center shrink-0" x-text="'Day ' + day.day_number"></div>

                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-3 w-full">
                            <div>
                                <label class="block text-[10px] font-bold text-stone-500 uppercase mb-1">Day Title</label>
                                <input type="text" :name="'itinerary_days[' + index + '][title]'" x-model="day.title" required class="w-full bg-white border border-stone-300 rounded-xl p-2.5 font-bold outline-none">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-stone-500 uppercase mb-1">Assigned Hotel / Lodge</label>
                                <select :name="'itinerary_days[' + index + '][accommodation_id]'" x-model="day.accommodation_id" class="w-full bg-white border border-stone-300 rounded-xl p-2.5 font-bold outline-none">
                                    <option value="">Select Lodge from Accommodation Database</option>
                                    @foreach($accommodations as $acc)
                                        <option value="{{ $acc->id }}">{{ $acc->name }} ({{ ucfirst($acc->type) }} • {{ ucfirst($acc->category_level) }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-[10px] font-bold text-stone-500 uppercase mb-1">Accommodation & Meal Notes</label>
                                <input type="text" :name="'itinerary_days[' + index + '][accommodation_notes]'" x-model="day.accommodation_notes" placeholder="e.g. Full board accommodation, evening camp safari" class="w-full bg-white border border-stone-300 rounded-xl p-2.5 outline-none">
                            </div>
                        </div>

                        <button type="button" @click="removeDay(index)" class="text-rose-600 hover:text-rose-800 p-2 font-bold text-xs shrink-0" title="Remove Day">
                            ✕ Remove
                        </button>
                    </div>
                </template>
            </div>
        </div>

        {{-- 5. Room Type Supplements --}}
        @if($roomTypes->count() > 0)
        <div class="space-y-4">
            <h3 class="font-bold text-stone-900 text-sm uppercase tracking-wider border-b border-stone-100 pb-2">5. Room Type Supplement Options</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach($roomTypes as $rt)
                <div class="p-4 bg-stone-50 border border-stone-200 rounded-2xl flex items-center justify-between">
                    <div>
                        <span class="font-bold text-stone-900 block text-xs">{{ $rt->name }}</span>
                        <span class="text-[10px] text-stone-500">Max {{ $rt->max_occupants }} Guests</span>
                    </div>
                    <div class="w-28">
                        <label class="block text-[9px] font-bold text-stone-500 uppercase">Supplement ($)</label>
                        <input type="number" step="0.01" name="room_supplements[{{ $rt->id }}]" value="{{ $rt->default_supplement_price }}" class="w-full bg-white border border-stone-300 rounded-xl p-2 font-bold text-xs">
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="flex items-center pt-2">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 text-amber-600 rounded">
                <span class="font-bold text-stone-800">Publish Package Immediately (Visible to Public Tour Guests)</span>
            </label>
        </div>

        <div class="flex justify-end gap-3 pt-6 border-t border-stone-100">
            <a href="{{ route('admin.tour-packages.index') }}" class="px-5 py-3 border border-stone-300 rounded-xl text-stone-700 font-bold">Cancel</a>
            <button type="submit" class="btn-gold px-8 py-3 rounded-xl font-black uppercase tracking-wider">Save Tour Package</button>
        </div>
    </form>
</div>
@endsection
