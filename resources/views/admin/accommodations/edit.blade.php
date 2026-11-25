@extends('admin.layouts.app')

@section('title', 'Edit Accommodation')
@section('page-title', 'Edit Hotel Record')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-3xl p-8 border border-stone-200 shadow-sm space-y-6">
    <div>
        <h2 class="font-serif font-bold text-2xl text-stone-900">Edit Accommodation Record</h2>
    </div>

    <form action="{{ route('admin.accommodations.update', $accommodation) }}" method="POST" enctype="multipart/form-data" class="space-y-6 text-xs">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block font-bold text-stone-700 uppercase tracking-wider mb-2">Accommodation Name *</label>
                <input type="text" name="name" value="{{ old('name', $accommodation->name) }}" required class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-sm font-semibold outline-none focus:border-amber-500">
            </div>

            <div>
                <label class="block font-bold text-stone-700 uppercase tracking-wider mb-2">Destination / Region</label>
                <select name="destination_id" class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-xs font-semibold outline-none">
                    <option value="">Select Destination</option>
                    @foreach($destinations as $d)
                        <option value="{{ $d->id }}" {{ $accommodation->destination_id == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block font-bold text-stone-700 uppercase tracking-wider mb-2">Accommodation Type *</label>
                <select name="type" required class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-xs font-semibold outline-none">
                    <option value="lodge" {{ $accommodation->type == 'lodge' ? 'selected' : '' }}>Safari Lodge</option>
                    <option value="camp" {{ $accommodation->type == 'camp' ? 'selected' : '' }}>Tent/Camp</option>
                    <option value="hotel" {{ $accommodation->type == 'hotel' ? 'selected' : '' }}>City Hotel</option>
                    <option value="resort" {{ $accommodation->type == 'resort' ? 'selected' : '' }}>Beach Resort</option>
                    <option value="tent" {{ $accommodation->type == 'tent' ? 'selected' : '' }}>Basic Tent</option>
                </select>
            </div>

            <div>
                <label class="block font-bold text-stone-700 uppercase tracking-wider mb-2">Category / Tier Level *</label>
                <select name="category_level" required class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-xs font-semibold outline-none">
                    <option value="budget" {{ $accommodation->category_level == 'budget' ? 'selected' : '' }}>Budget</option>
                    <option value="standard" {{ $accommodation->category_level == 'standard' ? 'selected' : '' }}>Standard</option>
                    <option value="mid_range" {{ $accommodation->category_level == 'mid_range' ? 'selected' : '' }}>Mid-Range</option>
                    <option value="luxury" {{ $accommodation->category_level == 'luxury' ? 'selected' : '' }}>Luxury</option>
                    <option value="luxury_plus" {{ $accommodation->category_level == 'luxury_plus' ? 'selected' : '' }}>Luxury Plus / Ultra VIP</option>
                </select>
            </div>

            <div>
                <label class="block font-bold text-stone-700 uppercase tracking-wider mb-2">Star Rating (1 - 5)</label>
                <input type="number" step="0.5" min="1" max="5" name="star_rating" value="{{ old('star_rating', $accommodation->star_rating) }}" class="w-full bg-stone-50 border border-stone-300 rounded-xl px-4 py-3 text-xs font-bold outline-none">
            </div>
        </div>

        <div class="p-4 bg-emerald-50/60 border border-emerald-200 rounded-2xl space-y-2">
            <label class="block font-bold text-emerald-900 uppercase tracking-wider text-xs">🔒 Private Supplier Cost per Night ($)</label>
            <input type="number" step="0.01" name="supplier_cost_per_night" value="{{ old('supplier_cost_per_night', $accommodation->supplier_cost_per_night) }}" class="w-full bg-white border border-emerald-300 rounded-xl px-4 py-2.5 text-xs font-bold outline-none">
            <p class="text-[10px] text-emerald-700 italic">* PRIVATE ADMIN COST ONLY. Never displayed to website visitors or guests.</p>
        </div>

        @if(is_array($accommodation->images) && count($accommodation->images) > 0)
        <div>
            <label class="block font-bold text-stone-700 uppercase tracking-wider mb-2">Existing Photos</label>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                @foreach($accommodation->images as $img)
                <div class="relative rounded-xl overflow-hidden border border-stone-200 h-24 bg-stone-900">
                    <img src="{{ str_starts_with($img, 'http') ? $img : asset($img) }}" class="w-full h-full object-cover">
                    <input type="hidden" name="existing_photos[]" value="{{ $img }}">
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div>
            <label class="block font-bold text-stone-700 uppercase tracking-wider mb-2">Upload Additional Photos</label>
            <input type="file" name="photos[]" multiple accept="image/*" class="w-full bg-stone-50 border border-stone-300 rounded-xl p-3 text-xs">
        </div>

        <div>
            <label class="block font-bold text-stone-700 uppercase tracking-wider mb-2">Description & Highlights</label>
            <textarea name="description" rows="4" class="w-full bg-stone-50 border border-stone-300 rounded-xl p-4 text-xs outline-none">{{ old('description', $accommodation->description) }}</textarea>
        </div>

        <div class="flex items-center pt-2">
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ $accommodation->is_active ? 'checked' : '' }} class="w-4 h-4 text-amber-600 rounded">
                <span class="font-bold text-stone-800">Active (Available for Tour Package Assignment)</span>
            </label>
        </div>

        <div class="flex justify-end gap-3 pt-6 border-t border-stone-100">
            <a href="{{ route('admin.accommodations.index') }}" class="px-5 py-3 border border-stone-300 rounded-xl text-stone-700 font-bold">Cancel</a>
            <button type="submit" class="btn-gold px-8 py-3 rounded-xl font-black uppercase tracking-wider">Update Accommodation</button>
        </div>
    </form>
</div>
@endsection
