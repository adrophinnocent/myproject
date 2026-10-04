@extends('admin.layouts.app')

@section('title', 'Custom Safari Requests')
@section('page-title', 'Custom Safari Requests')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div>
        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Custom Safari Requests</h2>
        <p class="text-gray-400 font-bold uppercase text-[10px] tracking-widest mt-1">Inquiries submitted via the Request Custom Safari form</p>
    </div>
</div>

{{-- Filters --}}
<div class="mb-6 neo-card p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <form action="{{ route('admin.custom-inquiries.index') }}" method="GET" class="flex-1 flex flex-col md:flex-row gap-4">
        <div class="relative flex-1">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search by client name, email, or country..."
                   class="w-full bg-white border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-xs font-bold outline-none focus:ring-2 focus:ring-amber-500/20">
            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>

        <select name="status" onchange="this.form.submit()" class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-bold text-gray-700 outline-none">
            <option value="">All Statuses</option>
            <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
            <option value="reviewed" {{ request('status') === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
            <option value="converted" {{ request('status') === 'converted' ? 'selected' : '' }}>Converted to Proposal</option>
            <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
        </select>
    </form>
</div>

{{-- Inquiries Table --}}
<div class="neo-card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-900/5 border-b border-gray-200/50">
                <tr>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Client</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Trip Type & Group</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Duration & Dates</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Budget & Style</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Status</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200/50">
                @forelse($inquiries as $inquiry)
                <tr class="hover:bg-white/40 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-900 text-xs">{{ $inquiry->full_name }}</div>
                        <div class="text-[11px] text-gray-500">{{ $inquiry->email }}</div>
                        <div class="text-[10px] text-gray-400">{{ $inquiry->country ?: 'Country not specified' }}</div>
                    </td>

                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-900 text-xs">{{ $inquiry->trip_type }}</div>
                        <div class="text-[10px] text-amber-600 font-bold uppercase mt-0.5">{{ $inquiry->group_type ?: 'Private Group' }}</div>
                    </td>

                    <td class="px-6 py-4">
                        <div class="text-xs font-bold text-gray-800">{{ $inquiry->duration_days }} Days</div>
                        <div class="text-[10px] text-gray-500">
                            {{ $inquiry->travel_date ? $inquiry->travel_date->format('d M Y') : 'Flexible dates' }}
                            ({{ $inquiry->total_travelers }} Guests)
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <div class="text-xs font-bold text-emerald-600">{{ $inquiry->budget_per_person ?: 'Flexible' }}</div>
                        <div class="text-[10px] text-gray-500">{{ $inquiry->accommodation_preference }}</div>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border
                            @if($inquiry->status === 'new') bg-amber-100 text-amber-800 border-amber-300
                            @elseif($inquiry->status === 'converted') bg-emerald-100 text-emerald-800 border-emerald-300
                            @else bg-gray-100 text-gray-800 border-gray-300 @endif">
                            {{ $inquiry->status }}
                        </span>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="flex items-center justify-end gap-2">
                            {{-- CONVERT TO PROPOSAL BUTTON --}}
                            <form action="{{ route('admin.custom-inquiries.convert', $inquiry) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-amber-500 text-white rounded-xl text-[11px] font-black uppercase shadow hover:bg-amber-600 transition-all flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    Convert to Proposal
                                </button>
                            </form>

                            <a href="{{ route('admin.custom-inquiries.show', $inquiry) }}" class="p-2 neo-btn text-gray-600 hover:text-blue-600" title="View Details">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>

                            <form action="{{ route('admin.custom-inquiries.destroy', $inquiry) }}" method="POST" onsubmit="return confirm('Delete this inquiry?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 neo-btn text-gray-400 hover:text-rose-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 font-bold text-xs">
                        No custom safari inquiries found yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($inquiries->hasPages())
        <div class="p-4 border-t border-gray-200/50">
            {{ $inquiries->links() }}
        </div>
    @endif
</div>
@endsection
