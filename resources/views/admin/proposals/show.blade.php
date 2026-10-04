@extends('admin.layouts.app')

@section('title', 'Proposal Workspace - ' . $proposal->full_reference)
@section('page-title', 'Proposal Workspace')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <div class="flex items-center gap-3 flex-wrap">
            <span class="px-3 py-1 bg-gray-900 text-amber-400 rounded-lg text-xs font-black tracking-wider uppercase">Ref: {{ $proposal->full_reference }}</span>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">{{ $proposal->title }}</h2>
            <span class="px-3 py-1 rounded-full text-[10px] font-black border uppercase tracking-wider {{ $proposal->status_badge_class }}">
                {{ str_replace('_', ' ', $proposal->status) }}
            </span>
        </div>
        <p class="text-gray-400 font-bold uppercase text-[10px] tracking-widest mt-1">
            Prepared for {{ $proposal->client_name }} ({{ $proposal->client_email }})
            @if($proposal->subtitle) &bull; {{ $proposal->subtitle }} @endif
        </p>
    </div>

    <div class="flex items-center gap-2 flex-wrap" x-data="{ copied: false }">
        {{-- Copy Secure Link --}}
        <button @click="navigator.clipboard.writeText('{{ $proposal->public_url }}'); copied = true; setTimeout(() => copied = false, 2500)"
                class="px-4 py-2.5 neo-btn text-xs font-bold text-gray-700 uppercase flex items-center gap-2 relative">
            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 005.656-5.656l-1.1 1.1"/></svg>
            <span x-text="copied ? 'Link Copied!' : 'Copy Client Link'"></span>
        </button>

        {{-- Open WhatsApp --}}
        <a href="{{ $proposal->whatsapp_message_url }}" target="_blank" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black uppercase shadow transition-all flex items-center gap-2">
            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.105 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
            WhatsApp
        </a>

        {{-- Open Client View --}}
        <a href="{{ $proposal->public_url }}" target="_blank" class="px-4 py-2.5 neo-btn text-xs font-bold text-blue-600 uppercase flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            Open Proposal
        </a>

        {{-- Duplicate / New Version --}}
        <form action="{{ route('admin.proposals.duplicate-version', $proposal) }}" method="POST" class="inline">
            @csrf
            <button type="submit" onclick="return confirm('Create new version V{{ $proposal->version + 1 }} from this proposal?')" class="px-4 py-2.5 neo-btn text-xs font-bold text-purple-700 uppercase flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"/></svg>
                + New Version (V{{ $proposal->version + 1 }})
            </button>
        </form>

        {{-- Save as Template --}}
        <form action="{{ route('admin.proposals.save-as-template', $proposal) }}" method="POST" class="inline">
            @csrf
            <button type="submit" onclick="return confirm('Save this proposal as a reusable itinerary template?')" class="px-4 py-2.5 neo-btn text-xs font-bold text-indigo-700 uppercase flex items-center gap-2">
                Save as Template
            </button>
        </form>

        {{-- Send Email --}}
        <form action="{{ route('admin.proposals.send', $proposal) }}" method="POST" class="inline">
            @csrf
            <button type="submit" onclick="return confirm('Send proposal email to {{ $proposal->client_email }}?')" class="px-4 py-2.5 bg-amber-500 text-white rounded-xl text-xs font-black uppercase shadow hover:bg-amber-600 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Send Email
            </button>
        </form>

        {{-- Download PDF --}}
        <a href="{{ route('admin.proposals.download-pdf', $proposal) }}" class="px-4 py-2.5 neo-btn text-xs font-bold text-gray-700 uppercase flex items-center gap-2">
            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            PDF
        </a>

        {{-- Edit --}}
        <a href="{{ route('admin.proposals.edit', $proposal) }}" class="px-4 py-2.5 neo-btn text-xs font-bold text-amber-600 uppercase flex items-center gap-2">
            Edit
        </a>
    </div>
</div>

{{-- Top Metrics Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="neo-card p-5">
        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Selling Price</div>
        <div class="text-2xl font-black text-amber-600 mt-1">{{ $proposal->formatted_total_price }}</div>
        @if($proposal->deposit_required)
            <div class="text-[10px] text-gray-500 mt-1">Deposit: {{ $proposal->formatted_deposit_required }}</div>
        @endif
    </div>

    <div class="neo-card p-5">
        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Travelers & Duration</div>
        <div class="text-lg font-black text-gray-900 mt-1">{{ $proposal->duration_days }} Days / {{ $proposal->duration_nights }} Nights</div>
        <div class="text-[10px] text-gray-500 mt-1">{{ $proposal->total_travelers }} Traveler(s) ({{ $proposal->adults }} Adults, {{ $proposal->children }} Children)</div>
    </div>

    <div class="neo-card p-5">
        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Travel Dates & Route</div>
        <div class="text-xs font-black text-gray-900 mt-1">
            @if($proposal->start_date)
                {{ $proposal->start_date->format('M d, Y') }}
                @if($proposal->end_date) - {{ $proposal->end_date->format('M d, Y') }} @endif
            @else
                <span class="text-gray-400">Dates pending</span>
            @endif
        </div>
        <div class="text-[10px] text-gray-500 mt-1 truncate">{{ $proposal->start_location }} → {{ $proposal->end_location }}</div>
    </div>

    <div class="neo-card p-5 bg-emerald-50/50 border border-emerald-100">
        <div class="text-[10px] font-black text-emerald-800 uppercase tracking-widest">Profit & Profit Margin</div>
        <div class="text-xl font-black text-emerald-600 mt-1">+{{ $proposal->currency_symbol }}{{ number_format($proposal->calculated_profit, 2) }}</div>
        <div class="text-[11px] font-bold text-emerald-700 mt-1">{{ $proposal->calculated_profit_margin }}% Gross Margin</div>
    </div>
</div>

{{-- Client Feedback Banner --}}
@if($proposal->client_feedback)
<div class="neo-card p-6 mb-8 border-l-4 border-amber-500 bg-amber-50/30">
    <h3 class="text-xs font-black text-amber-900 uppercase tracking-widest mb-2 flex items-center gap-2">
        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
        Client Notes / Feedback
    </h3>
    <div class="text-xs text-gray-800 bg-white p-4 rounded-xl border border-amber-200 whitespace-pre-line font-medium">
        {{ $proposal->client_feedback }}
    </div>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    {{-- Left 2 cols: Itinerary & Inclusions --}}
    <div class="lg:col-span-2 space-y-8">
        {{-- Itinerary Overview --}}
        <div class="neo-card p-6">
            <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest mb-4">Day-by-Day Itinerary</h3>
            <div class="space-y-6">
                @if(is_array($proposal->itinerary) && count($proposal->itinerary) > 0)
                    @foreach($proposal->itinerary as $day)
                    <div class="p-4 bg-white/70 rounded-2xl border border-gray-100">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-black text-amber-600 uppercase">Day {{ $day['day'] ?? $loop->iteration }}: {{ $day['title'] ?? '' }}</span>
                            @if(!empty($day['meals']))
                                <span class="text-[10px] font-bold text-gray-500 bg-gray-100 px-2.5 py-0.5 rounded-full">{{ $day['meals'] }}</span>
                            @endif
                        </div>
                        @if(!empty($day['description']))
                            <p class="text-xs text-gray-700 mb-3 leading-relaxed">{{ $day['description'] }}</p>
                        @endif
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-[11px] text-gray-600">
                            @if(!empty($day['accommodation_property']))
                                <div><strong>Lodge:</strong> {{ $day['accommodation_property'] }} @if(!empty($day['room_type'])) ({{ $day['room_type'] }}) @endif</div>
                            @endif
                            @if(!empty($day['driving_time']))
                                <div><strong>Drive:</strong> {{ $day['driving_time'] }}</div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-xs text-gray-400 italic">No itinerary days added.</div>
                @endif
            </div>
        </div>

        {{-- Inclusions & Exclusions --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="neo-card p-6">
                <h4 class="text-xs font-black text-emerald-700 uppercase tracking-widest mb-3">Included Services</h4>
                <ul class="space-y-2">
                    @if(is_array($proposal->inclusions) && count($proposal->inclusions) > 0)
                        @foreach($proposal->inclusions as $inc)
                        <li class="text-xs text-gray-700 flex items-start gap-2">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>{{ $inc }}</span>
                        </li>
                        @endforeach
                    @else
                        <li class="text-xs text-gray-400 italic">None listed</li>
                    @endif
                </ul>
            </div>

            <div class="neo-card p-6">
                <h4 class="text-xs font-black text-rose-700 uppercase tracking-widest mb-3">Excluded Services</h4>
                <ul class="space-y-2">
                    @if(is_array($proposal->exclusions) && count($proposal->exclusions) > 0)
                        @foreach($proposal->exclusions as $exc)
                        <li class="text-xs text-gray-700 flex items-start gap-2">
                            <svg class="w-4 h-4 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            <span>{{ $exc }}</span>
                        </li>
                        @endforeach
                    @else
                        <li class="text-xs text-gray-400 italic">None listed</li>
                    @endif
                </ul>
            </div>
        </div>
    </div>

    {{-- Right Col: Internal Costing & Activity Timeline --}}
    <div class="space-y-8">
        {{-- Internal Costing Breakdown --}}
        @php
            $costing = $proposal->internal_costing ?? [];
        @endphp
        <div class="neo-card p-6 border-2 border-indigo-200 bg-indigo-50/20">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xs font-black text-indigo-950 uppercase tracking-widest">Internal Cost Breakdown</h3>
                <span class="bg-rose-100 text-rose-800 text-[9px] font-black uppercase px-2 py-0.5 rounded-full">ADMIN ONLY</span>
            </div>

            <div class="space-y-2 text-xs font-medium divide-y divide-indigo-100 mb-6">
                <div class="flex justify-between py-1.5"><span class="text-gray-600">Supplier Costs:</span><span class="font-bold text-gray-900">{{ $proposal->currency_symbol }}{{ number_format($costing['supplier_costs'] ?? 0, 2) }}</span></div>
                <div class="flex justify-between py-1.5"><span class="text-gray-600">Vehicle Costs:</span><span class="font-bold text-gray-900">{{ $proposal->currency_symbol }}{{ number_format($costing['vehicle_costs'] ?? 0, 2) }}</span></div>
                <div class="flex justify-between py-1.5"><span class="text-gray-600">Accommodation Costs:</span><span class="font-bold text-gray-900">{{ $proposal->currency_symbol }}{{ number_format($costing['accommodation_costs'] ?? 0, 2) }}</span></div>
                <div class="flex justify-between py-1.5"><span class="text-gray-600">Guide Costs:</span><span class="font-bold text-gray-900">{{ $proposal->currency_symbol }}{{ number_format($costing['guide_costs'] ?? 0, 2) }}</span></div>
                <div class="flex justify-between py-1.5"><span class="text-gray-600">Park Fees:</span><span class="font-bold text-gray-900">{{ $proposal->currency_symbol }}{{ number_format($costing['park_fees'] ?? 0, 2) }}</span></div>
                <div class="flex justify-between py-1.5"><span class="text-gray-600">Activity Costs:</span><span class="font-bold text-gray-900">{{ $proposal->currency_symbol }}{{ number_format($costing['activities_costs'] ?? 0, 2) }}</span></div>
                <div class="flex justify-between py-1.5"><span class="text-gray-600">Other Expenses:</span><span class="font-bold text-gray-900">{{ $proposal->currency_symbol }}{{ number_format($costing['other_expenses'] ?? 0, 2) }}</span></div>
            </div>

            <div class="p-4 bg-indigo-900 text-white rounded-2xl space-y-2">
                <div class="flex justify-between text-xs">
                    <span class="text-indigo-300 font-bold uppercase">Total Costs:</span>
                    <span class="font-black">{{ $proposal->currency_symbol }}{{ number_format($proposal->total_internal_cost, 2) }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-indigo-300 font-bold uppercase">Selling Price:</span>
                    <span class="font-black text-amber-400">{{ $proposal->formatted_total_price }}</span>
                </div>
                <div class="border-t border-indigo-700 pt-2 flex justify-between text-sm">
                    <span class="text-emerald-400 font-black uppercase">Net Profit:</span>
                    <span class="font-black text-emerald-400">+{{ $proposal->currency_symbol }}{{ number_format($proposal->calculated_profit, 2) }}</span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-indigo-300 font-bold uppercase">Profit Margin:</span>
                    <span class="font-black text-amber-300">{{ $proposal->calculated_profit_margin }}%</span>
                </div>
            </div>
        </div>

        {{-- Activity & View Tracking --}}
        <div class="neo-card p-6">
            <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest mb-4">View Tracking & Activity</h3>
            <div class="space-y-4 text-xs">
                <div class="p-3 bg-purple-50 rounded-xl border border-purple-200 flex items-center justify-between font-bold text-purple-900">
                    <span>Total Client Views:</span>
                    <span class="text-sm font-black">{{ $proposal->view_count ?? 0 }} time(s)</span>
                </div>

                <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                    <span class="text-gray-500 font-bold">Reference Code:</span>
                    <span class="font-black text-amber-600">{{ $proposal->full_reference }}</span>
                </div>

                <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                    <span class="text-gray-500 font-bold">Created:</span>
                    <span class="font-black text-gray-800">{{ $proposal->created_at->format('M d, Y H:i') }}</span>
                </div>

                <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                    <span class="text-gray-500 font-bold">Sent to Client:</span>
                    <span class="font-black text-gray-800">{{ $proposal->sent_at ? $proposal->sent_at->format('M d, Y H:i') : 'Not sent yet' }}</span>
                </div>

                <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                    <span class="text-gray-500 font-bold">First Viewed:</span>
                    <span class="font-black text-gray-800">{{ $proposal->first_viewed_at ? $proposal->first_viewed_at->format('M d, Y H:i') : ($proposal->viewed_at ? $proposal->viewed_at->format('M d, Y H:i') : 'Not viewed yet') }}</span>
                </div>

                <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                    <span class="text-gray-500 font-bold">Last Viewed:</span>
                    <span class="font-black text-gray-800">{{ $proposal->last_viewed_at ? $proposal->last_viewed_at->format('M d, Y H:i') : ($proposal->viewed_at ? $proposal->viewed_at->format('M d, Y H:i') : 'N/A') }}</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-gray-500 font-bold">Accepted at:</span>
                    <span class="font-black text-emerald-600">{{ $proposal->accepted_at ? $proposal->accepted_at->format('M d, Y H:i') : 'Pending' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
