@extends('admin.layouts.app')

@section('title', 'Accommodations Management')
@section('page-title', 'Accommodations & Lodges')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-serif font-bold text-2xl text-stone-900">Accommodation Database</h2>
            <p class="text-xs text-stone-500 mt-0.5">Manage hotels, lodges, camps, and resort accommodations reusable across all tour packages.</p>
        </div>
        <a href="{{ route('admin.accommodations.create') }}" class="btn-gold px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-wider shadow-md inline-flex items-center gap-2">
            <span>+ Add Accommodation</span>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-bold">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs font-bold">
            ✕ {{ session('error') }}
        </div>
    @endif

    {{-- Filter Bar --}}
    <div class="bg-white p-4 rounded-2xl border border-stone-200 shadow-xs">
        <form action="{{ route('admin.accommodations.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, location..." class="bg-stone-50 border border-stone-300 rounded-xl px-4 py-2.5 outline-none">
            <select name="type" class="bg-stone-50 border border-stone-300 rounded-xl px-4 py-2.5 outline-none">
                <option value="">All Types (Lodge, Camp, Hotel)</option>
                <option value="lodge" {{ request('type') == 'lodge' ? 'selected' : '' }}>Lodge</option>
                <option value="camp" {{ request('type') == 'camp' ? 'selected' : '' }}>Tent/Camp</option>
                <option value="hotel" {{ request('type') == 'hotel' ? 'selected' : '' }}>Hotel</option>
                <option value="resort" {{ request('type') == 'resort' ? 'selected' : '' }}>Resort</option>
            </select>
            <select name="category_level" class="bg-stone-50 border border-stone-300 rounded-xl px-4 py-2.5 outline-none">
                <option value="">All Levels (Budget, Luxury...)</option>
                <option value="budget">Budget</option>
                <option value="standard">Standard</option>
                <option value="mid_range">Mid-Range</option>
                <option value="luxury">Luxury</option>
                <option value="luxury_plus">Luxury Plus / VIP</option>
            </select>
            <button type="submit" class="btn-gold px-4 py-2.5 rounded-xl font-bold uppercase tracking-wider text-[11px]">Filter</button>
        </form>
    </div>

    {{-- Accommodations Table --}}
    <div class="bg-white rounded-3xl border border-stone-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-stone-700">
                <thead class="bg-stone-50 border-b border-stone-200 text-stone-500 uppercase font-black text-[10px] tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Accommodation Name</th>
                        <th class="px-6 py-4">Type & Level</th>
                        <th class="px-6 py-4">Location</th>
                        <th class="px-6 py-4">Private Supplier Cost</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($accommodations as $acc)
                    <tr class="hover:bg-stone-50/50 transition-colors">
                        <td class="px-6 py-4 flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl overflow-hidden bg-stone-900 shrink-0 border border-stone-200">
                                <img src="{{ $acc->primary_image_url }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <span class="font-bold text-stone-900 text-sm block">{{ $acc->name }}</span>
                                <span class="text-amber-600 text-[10px] font-bold">★ {{ number_format($acc->star_rating, 1) }} Stars</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-stone-100 rounded-md font-bold uppercase text-[10px] block w-fit mb-1">{{ $acc->type }}</span>
                            <span class="text-stone-500 capitalize">{{ str_replace('_', ' ', $acc->category_level) }}</span>
                        </td>
                        <td class="px-6 py-4 font-medium text-stone-800">
                            {{ $acc->location_name ?: ($acc->destination->name ?? 'Tanzania') }}
                        </td>
                        <td class="px-6 py-4 font-mono font-bold text-emerald-800">
                            {{ $acc->supplier_cost_per_night ? '$' . number_format($acc->supplier_cost_per_night, 2) . ' / night' : 'Private' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full font-bold text-[10px] uppercase {{ $acc->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-stone-100 text-stone-500' }}">
                                {{ $acc->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.accommodations.edit', $acc) }}" class="px-3 py-1.5 bg-stone-100 hover:bg-stone-200 text-stone-800 font-bold rounded-lg transition-colors">Edit</a>
                                <form method="POST" action="{{ route('admin.accommodations.destroy', $acc) }}" onsubmit="return confirm('Delete accommodation?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 text-rose-600 hover:bg-rose-50 rounded-lg font-bold">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center text-stone-400 italic">No accommodation records found. Click "+ Add Accommodation" to create your first hotel/lodge.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
