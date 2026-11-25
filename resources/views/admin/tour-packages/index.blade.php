@extends('admin.layouts.app')

@section('title', 'Tour Packages Management')
@section('page-title', 'Tour Accommodation Packages')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-serif font-bold text-2xl text-stone-900">Tour Accommodation Packages</h2>
            <p class="text-xs text-stone-500 mt-0.5">Manage unlimited accommodation packages (Budget, Standard, Luxury, VIP) and assign hotels night-by-night.</p>
        </div>
        <a href="{{ route('admin.tour-packages.create') }}" class="btn-gold px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-wider shadow-md inline-flex items-center gap-2">
            <span>+ Create Package</span>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-bold">
            ✓ {{ session('success') }}
        </div>
    @endif

    {{-- Filter Bar --}}
    <div class="bg-white p-4 rounded-2xl border border-stone-200 shadow-xs">
        <form action="{{ route('admin.tour-packages.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search package name..." class="bg-stone-50 border border-stone-300 rounded-xl px-4 py-2.5 outline-none">
            <select name="tour_id" class="bg-stone-50 border border-stone-300 rounded-xl px-4 py-2.5 outline-none">
                <option value="">All Tours</option>
                @foreach($tours as $t)
                    <option value="{{ $t->id }}" {{ request('tour_id') == $t->id ? 'selected' : '' }}>{{ $t->title }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-gold px-4 py-2.5 rounded-xl font-bold uppercase tracking-wider text-[11px]">Filter Packages</button>
        </form>
    </div>

    {{-- Packages Table --}}
    <div class="bg-white rounded-3xl border border-stone-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-stone-700">
                <thead class="bg-stone-50 border-b border-stone-200 text-stone-500 uppercase font-black text-[10px] tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Package Name</th>
                        <th class="px-6 py-4">Assigned Tour</th>
                        <th class="px-6 py-4">Selling Price</th>
                        <th class="px-6 py-4">Internal Cost & Profit</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100">
                    @forelse($packages as $pkg)
                    <tr class="hover:bg-stone-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-bold text-stone-900 text-sm block">{{ $pkg->name }}</span>
                            <span class="text-stone-400 text-[10px]">{{ $pkg->itineraryDays->count() }} Nightly Hotel Assignments</span>
                        </td>
                        <td class="px-6 py-4 font-medium text-stone-800">
                            @if($pkg->tour)
                                <span class="px-2.5 py-1 bg-amber-50 text-amber-900 font-bold rounded-md border border-amber-200/60 block truncate max-w-xs">
                                    ⛺ {{ $pkg->tour->title }}
                                </span>
                            @else
                                <span class="text-stone-400 italic">Unassigned (Global)</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-mono font-bold text-stone-900 text-sm">
                            {{ $pkg->formatted_selling_price }}
                        </td>
                        <td class="px-6 py-4 font-mono">
                            <span class="block text-stone-500 text-[11px]">Cost: ${{ number_format($pkg->total_internal_cost, 2) }}</span>
                            <span class="block font-bold text-emerald-700 text-xs">Profit: ${{ number_format($pkg->expected_profit, 2) }} ({{ $pkg->profit_margin_percent }}%)</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-full font-bold text-[10px] uppercase {{ $pkg->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-stone-100 text-stone-500' }}">
                                {{ $pkg->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.tour-packages.edit', $pkg) }}" class="px-3 py-1.5 bg-stone-100 hover:bg-stone-200 text-stone-800 font-bold rounded-lg transition-colors">Edit</a>
                                <form method="POST" action="{{ route('admin.tour-packages.duplicate', $pkg) }}">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-900 font-bold rounded-lg transition-colors border border-amber-200">Duplicate</button>
                                </form>
                                <form method="POST" action="{{ route('admin.tour-packages.destroy', $pkg) }}" onsubmit="return confirm('Delete package?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 text-rose-600 hover:bg-rose-50 rounded-lg font-bold">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center text-stone-400 italic">No tour accommodation packages found. Click "+ Create Package" to build one!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
