@extends('admin.layouts.app')

@section('title', 'Preview Proposal Template - ' . $template->title)
@section('page-title', 'Preview Proposal Template')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-black text-gray-900 tracking-tight">{{ $template->title }}</h2>
        <p class="text-gray-400 font-bold uppercase text-[10px] tracking-widest mt-1">Internal Proposal Blueprint Preview</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.proposal-templates.index') }}" class="px-4 py-2 neo-btn text-xs font-bold text-gray-600 uppercase">Back to Templates</a>
        <a href="{{ route('admin.proposal-templates.use', $template) }}" class="px-6 py-2 bg-amber-500 hover:bg-amber-600 text-white font-black text-xs uppercase rounded-xl shadow">
            Use This Template For New Proposal
        </a>
        <a href="{{ route('admin.proposal-templates.edit', $template) }}" class="px-4 py-2 neo-btn text-xs font-bold text-indigo-700 uppercase">
            Edit Template
        </a>
    </div>
</div>

<div class="neo-card p-6 mb-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div>
            <div class="text-[10px] font-bold uppercase text-gray-400">Duration</div>
            <div class="text-sm font-black text-gray-900 mt-0.5">{{ $template->duration_days }} Days / {{ $template->duration_nights }} Nights</div>
        </div>
        <div>
            <div class="text-[10px] font-bold uppercase text-gray-400">Safari Style & Level</div>
            <div class="text-sm font-black text-amber-600 mt-0.5">{{ $template->safari_style ?: 'Private Safari' }} ({{ $template->accommodation_level ?: 'Comfort' }})</div>
        </div>
        <div>
            <div class="text-[10px] font-bold uppercase text-gray-400">Standard Selling Price</div>
            <div class="text-sm font-black text-emerald-600 mt-0.5">${{ number_format($template->default_total_price, 2) }}</div>
        </div>
        <div>
            <div class="text-[10px] font-bold uppercase text-gray-400">Status</div>
            <div class="mt-0.5">
                <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 font-bold text-[10px] uppercase rounded-full">{{ $template->status }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Day-by-day preview --}}
<div class="neo-card p-6 mb-8">
    <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-4">Template Itinerary Days</h3>
    <div class="space-y-6">
        @forelse($template->structured_itinerary as $day)
        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-black text-amber-600 uppercase">Day {{ $day['day'] ?? $loop->iteration }}: {{ $day['title'] ?? '' }}</span>
                @if(!empty($day['destination']))
                    <span class="px-2.5 py-0.5 bg-amber-100 text-amber-900 text-[10px] font-black uppercase rounded-full">{{ $day['destination'] }}</span>
                @endif
            </div>
            @if(!empty($day['cover_image']))
                <div class="mb-3 rounded-xl overflow-hidden max-h-48 bg-slate-200">
                    <img src="{{ $day['cover_image'] }}" class="w-full h-full object-cover">
                </div>
            @endif
            <p class="text-xs text-gray-700 leading-relaxed font-medium mb-3">{{ $day['description'] ?? '' }}</p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-[10px] text-gray-600">
                <div><strong>Activities:</strong> {{ $day['activities'] ?? 'N/A' }}</div>
                <div><strong>Accommodation:</strong> {{ $day['accommodation_property'] ?? 'N/A' }}</div>
                <div><strong>Meals:</strong> {{ $day['meals'] ?? 'N/A' }}</div>
            </div>
        </div>
        @empty
        <div class="text-xs text-gray-500 font-bold">No days specified.</div>
        @endforelse
    </div>
</div>

{{-- Inclusions & Exclusions --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="neo-card p-6">
        <h3 class="text-xs font-black uppercase text-emerald-800 tracking-wider mb-4">Inclusions</h3>
        <ul class="space-y-2 text-xs text-gray-700 font-medium">
            @foreach($template->structured_inclusions as $inc)
            <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> {{ $inc }}</li>
            @endforeach
        </ul>
    </div>
    <div class="neo-card p-6">
        <h3 class="text-xs font-black uppercase text-rose-800 tracking-wider mb-4">Exclusions</h3>
        <ul class="space-y-2 text-xs text-gray-700 font-medium">
            @foreach($template->structured_exclusions as $exc)
            <li class="flex items-center gap-2"><svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg> {{ $exc }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
