@extends('admin.layouts.app')

@section('title', 'Add Accommodation')
@section('page-title', 'Add New Hotel or Lodge')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-3xl p-8 border border-stone-200 shadow-sm space-y-6">
    <div>
        <h2 class="font-serif font-bold text-2xl text-stone-900">Add New Accommodation Record</h2>
        <p class="text-xs text-stone-500 mt-1">This hotel/lodge can be assigned night-by-night across any tour package.</p>
    </div>

    <form action="{{ route('admin.accommodations.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 text-xs">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block font-bold text-stone-700 uppercase tracking-wider mb-2">Accommodation Name *</label>
                <input type="text" name="name" required placeholder="e.g. Serengeti Serena Safari Lodge" class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-sm font-semibold outline-none focus:border-amber-500">
            </div>

            <div>
                <label class="block font-bold text-stone-700 uppercase tracking-wider mb-2">Destination / Region</label>
                <select name="destination_id" class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-xs font-semibold outline-none">
                    <option value="">Select Destination</option>
                    @foreach($destinations as $d)
                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block font-bold text-stone-700 uppercase tracking-wider mb-2">Accommodation Type *</label>
                <select name="type" required class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-xs font-semibold outline-none">
                    <option value="lodge">Safari Lodge</option>
                    <option value="camp">Tented Camp / Camping</option>
                    <option value="hotel">City Hotel</option>
                    <option value="resort">Beach Resort</option>
                    <option value="tent">Basic Tent</option>
                </select>
            </div>

            <div>
                <label class="block font-bold text-stone-700 uppercase tracking-wider mb-2">Category / Tier Level *</label>
                <select name="category_level" required class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-xs font-semibold outline-none">
                    <option value="budget">Budget</option>
                    <option value="standard">Standard</option>
                    <option value="mid_range" selected>Mid-Range</option>
                    <option value="luxury">Luxury</option>
                    <option value="luxury_plus">Luxury Plus / Ultra VIP</option>
                </select>
            </div>

            <div>
                <label class="block font-bold text-stone-700 uppercase tracking-wider mb-2">Star Rating (1 - 5)</label>
                <input type="number" step="0.5" min="1" max="5" name="star_rating" value="4.0" class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-xs font-bold outline-none">
            </div>
        </div>

        <div class="p-4 bg-emerald-50/60 border border-emerald-200 rounded-2xl space-y-2">
            <label class="block font-bold text-emerald-900 uppercase tracking-wider text-xs">🔒 Private Supplier Cost per Night ($)</label>
            <input type="number" step="0.01" name="supplier_cost_per_night" placeholder="e.g. 180.00" class="w-full bg-white border border-emerald-300 rounded-xl px-4 py-2.5 text-xs font-bold outline-none">
            <p class="text-[10px] text-emerald-700 italic">* PRIVATE ADMIN COST ONLY. Never displayed to website visitors or guests.</p>
        </div>

        <div>
            <label class="block font-bold text-stone-700 uppercase tracking-wider mb-2">Upload Hotel Photographs (Multiple)</label>
            <input type="file" name="photos[]" multiple accept="image/*" class="w-full bg-stone-50 border border-stone-300 rounded-xl p-3 text-xs">
        </div>

        <div>
            <label class="block font-bold text-stone-700 uppercase tracking-wider mb-2">Description & Highlights</label>
            <textarea name="description" rows="4" placeholder="Describe the lodge location, view, dining, and atmosphere..." class="w-full bg-stone-50 border border-stone-300 rounded-xl p-4 text-xs outline-none"></textarea>
        </div>

        <div class="flex items-center pt-2">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 text-amber-600 rounded">
                <span class="font-bold text-stone-800">Active (Available for Tour Package Assignment)</span>
            </label>
        </div>

        <div class="flex justify-end gap-3 pt-6 border-t border-stone-100">
            <a href="{{ route('admin.accommodations.index') }}" class="px-5 py-3 border border-stone-300 rounded-xl text-stone-700 font-bold">Cancel</a>
            <button type="submit" class="btn-gold px-8 py-3 rounded-xl font-black uppercase tracking-wider">Save Accommodation</button>
        </div>
    </form>
</div>
@endsection
