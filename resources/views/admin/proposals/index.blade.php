@extends('admin.layouts.app')

@section('title', 'Client Safari Proposals')
@section('page-title', 'Client Safari Proposals')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div>
        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Client Safari Proposals</h2>
        <p class="text-gray-500 font-bold uppercase text-[10px] tracking-widest mt-1">Manage personalized client itineraries, pricing & proposal links</p>
    </div>

    <div class="flex items-center gap-4 flex-wrap">
        <a href="{{ route('admin.proposals.create') }}" class="px-5 py-3 bg-[#052010] hover:bg-[#08331a] text-[#D4AF37] border border-[#D4AF37]/40 rounded-xl text-xs font-black uppercase tracking-wider flex items-center gap-2 shadow-sm transition-all">
            <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Create Proposal
        </a>
    </div>
</div>

{{-- Filters --}}
<div class="mb-6 neo-card p-4 flex flex-col md:flex-row md:items-center justify-between gap-4 border border-emerald-900/10">
    <form action="{{ route('admin.proposals.index') }}" method="GET" class="flex-1 flex flex-col md:flex-row gap-4">
        <div class="relative flex-1">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search by client name, email, or proposal title..."
                   class="w-full bg-white border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-xs font-bold outline-none focus:ring-2 focus:ring-emerald-900/20">
            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>

        <select name="status" onchange="this.form.submit()" class="bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-xs font-bold text-gray-700 outline-none">
            <option value="">All Statuses</option>
            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
            <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Sent</option>
            <option value="viewed" {{ request('status') === 'viewed' ? 'selected' : '' }}>Viewed</option>
            <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Accepted</option>
            <option value="changes_requested" {{ request('status') === 'changes_requested' ? 'selected' : '' }}>Changes Requested</option>
            <option value="declined" {{ request('status') === 'declined' ? 'selected' : '' }}>Declined</option>
        </select>

        @if(request('search') || request('status'))
            <a href="{{ route('admin.proposals.index') }}" class="px-4 py-2.5 neo-btn text-xs font-bold text-gray-500 flex items-center justify-center gap-1">Reset</a>
        @endif
    </form>
</div>

{{-- Proposals Table --}}
<div class="neo-card overflow-hidden border border-emerald-900/10">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-[#052010] text-[#D4AF37]">
                <tr>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest">Client</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest">Proposal Title</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest">Travel Dates & Group</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest">Selling Price</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest">Profit & Margin</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest">Status</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200/60">
                @forelse($proposals as $proposal)
                <tr class="hover:bg-emerald-50/30 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-black text-gray-900 text-xs">{{ $proposal->client_name }}</div>
                        <div class="text-[11px] text-gray-500">{{ $proposal->client_email }}</div>
                        @if($proposal->client_phone)
                            <div class="text-[10px] text-gray-400">{{ $proposal->client_phone }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="inline-flex items-center gap-1.5 mb-1">
                            <span class="px-2 py-0.5 bg-[#052010] text-[#D4AF37] rounded text-[10px] font-black uppercase tracking-wider">{{ $proposal->full_reference }}</span>
                        </div>
                        <div class="font-bold text-gray-900 text-xs line-clamp-1 max-w-[220px]">{{ $proposal->title }}</div>
                        <div class="text-[10px] text-emerald-800 font-bold uppercase mt-0.5">{{ $proposal->duration_days }} Days</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-xs font-bold text-gray-800">
                            @if($proposal->start_date)
                                {{ $proposal->start_date->format('d M Y') }}
                                @if($proposal->end_date) - {{ $proposal->end_date->format('d M Y') }} @endif
                            @else
                                <span class="text-gray-400">Dates pending</span>
                            @endif
                        </div>
                        <div class="text-[10px] text-gray-500">{{ $proposal->total_travelers }} Traveler(s) ({{ $proposal->adults }}A, {{ $proposal->children }}C)</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-black text-xs text-[#052010]">{{ $proposal->formatted_total_price }}</div>
                        @if($proposal->deposit_required)
                            <div class="text-[10px] text-gray-500">Dep: {{ $proposal->formatted_deposit_required }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-xs font-bold text-emerald-700">+{{ $proposal->currency_symbol }}{{ number_format($proposal->calculated_profit, 2) }}</div>
                        <div class="text-[10px] font-black text-emerald-800 bg-emerald-100/80 px-2.5 py-0.5 rounded-full inline-block mt-0.5">
                            {{ $proposal->calculated_profit_margin }}% Margin
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 rounded-full text-[10px] font-black border uppercase tracking-wider {{ $proposal->status_badge_class }}">
                            {{ str_replace('_', ' ', $proposal->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="flex items-center justify-end gap-2" x-data="{ copied: false }">
                            {{-- Secure Copy Link --}}
                            <button @click="navigator.clipboard.writeText('{{ $proposal->public_url }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                    title="Copy Secure Client Link"
                                    class="p-2 neo-btn text-gray-600 hover:text-emerald-800 relative">
                                <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 005.656-5.656l-1.1 1.1"/></svg>
                                <span x-show="copied" x-cloak class="absolute -top-8 right-0 bg-[#052010] text-[#D4AF37] text-[9px] px-2 py-1 rounded shadow">Copied!</span>
                            </button>

                            {{-- Open WhatsApp --}}
                            <a href="{{ $proposal->whatsapp_message_url }}" target="_blank" class="p-2 neo-btn text-emerald-600 hover:text-emerald-700" title="Share via WhatsApp">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.105 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                            </a>

                            {{-- View Workspace --}}
                            <a href="{{ route('admin.proposals.show', $proposal) }}" class="p-2 neo-btn text-gray-600 hover:text-[#052010]" title="View Proposal Workspace">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </a>

                            {{-- Edit Proposal --}}
                            <a href="{{ route('admin.proposals.edit', $proposal) }}" class="p-2 neo-btn text-gray-600 hover:text-emerald-800" title="Edit Proposal">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>

                            {{-- Delete Proposal --}}
                            <form action="{{ route('admin.proposals.destroy', $proposal) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this proposal?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 neo-btn text-gray-400 hover:text-rose-600" title="Delete Proposal">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500 font-bold text-xs">
                        No proposals found. <a href="{{ route('admin.proposals.create') }}" class="text-[#052010] font-black underline">Create one now</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($proposals->hasPages())
        <div class="p-4 border-t border-gray-200/50">
            {{ $proposals->links() }}
        </div>
    @endif
</div>
@endsection
