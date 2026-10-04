@extends('admin.layouts.app')

@section('title', 'Inquiry Details - ' . $inquiry->full_name)
@section('page-title', 'Custom Safari Request')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <div class="flex items-center gap-3">
            <h2 class="text-2xl font-black text-gray-900 tracking-tight">{{ $inquiry->full_name }}</h2>
            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border
                @if($inquiry->status === 'new') bg-amber-100 text-amber-800 border-amber-300
                @elseif($inquiry->status === 'converted') bg-emerald-100 text-emerald-800 border-emerald-300
                @else bg-gray-100 text-gray-800 border-gray-300 @endif">
                {{ $inquiry->status }}
            </span>
        </div>
        <p class="text-gray-400 font-bold uppercase text-[10px] tracking-widest mt-1">Submitted on {{ $inquiry->created_at->format('M d, Y H:i') }}</p>
    </div>

    <div class="flex items-center gap-3">
        {{-- CONVERT TO PROPOSAL BUTTON --}}
        <form action="{{ route('admin.custom-inquiries.convert', $inquiry) }}" method="POST">
            @csrf
            <button type="submit" class="px-6 py-3 bg-amber-500 text-white rounded-xl text-xs font-black uppercase shadow-lg hover:bg-amber-600 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                Convert to Proposal
            </button>
        </form>

        <a href="{{ route('admin.custom-inquiries.index') }}" class="px-4 py-2.5 neo-btn text-xs font-bold text-gray-600 uppercase">Back to List</a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    {{-- Left 2 Cols: Details --}}
    <div class="lg:col-span-2 space-y-8">
        {{-- Client Details --}}
        <div class="neo-card p-6">
            <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest mb-4">Client Contact Details</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div>
                    <span class="text-gray-400 font-bold uppercase block text-[10px]">Full Name</span>
                    <span class="font-black text-gray-900 text-sm">{{ $inquiry->full_name }}</span>
                </div>
                <div>
                    <span class="text-gray-400 font-bold uppercase block text-[10px]">Email Address</span>
                    <a href="mailto:{{ $inquiry->email }}" class="font-bold text-amber-600 underline">{{ $inquiry->email }}</a>
                </div>
                <div>
                    <span class="text-gray-400 font-bold uppercase block text-[10px]">WhatsApp / Phone</span>
                    <span class="font-bold text-gray-900">{{ $inquiry->phone ?: 'Not provided' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 font-bold uppercase block text-[10px]">Country of Residence</span>
                    <span class="font-bold text-gray-900">{{ $inquiry->country ?: 'Not provided' }}</span>
                </div>
            </div>
        </div>

        {{-- Safari Preferences --}}
        <div class="neo-card p-6">
            <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest mb-4">Trip Specifications & Preferences</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs mb-6">
                <div>
                    <span class="text-gray-400 font-bold uppercase block text-[10px]">Trip Type</span>
                    <span class="font-black text-gray-900 text-sm">{{ $inquiry->trip_type }}</span>
                </div>
                <div>
                    <span class="text-gray-400 font-bold uppercase block text-[10px]">Group Style</span>
                    <span class="font-bold text-amber-600">{{ $inquiry->group_type ?: 'Standard' }}</span>
                </div>
                <div>
                    <span class="text-gray-400 font-bold uppercase block text-[10px]">Duration</span>
                    <span class="font-black text-gray-900">{{ $inquiry->duration_days }} Days</span>
                </div>
                <div>
                    <span class="text-gray-400 font-bold uppercase block text-[10px]">Travelers</span>
                    <span class="font-black text-gray-900">{{ $inquiry->adults }} Adults @if($inquiry->children > 0), {{ $inquiry->children }} Children (Ages: {{ $inquiry->children_ages ?: 'N/A' }}) @endif</span>
                </div>
                <div>
                    <span class="text-gray-400 font-bold uppercase block text-[10px]">Preferred Start Date</span>
                    <span class="font-bold text-gray-900">
                        {{ $inquiry->travel_date ? $inquiry->travel_date->format('F j, Y') : 'Flexible' }}
                        @if($inquiry->flexible_dates) <span class="text-emerald-600 font-bold">(Flexible dates)</span> @endif
                    </span>
                </div>
                <div>
                    <span class="text-gray-400 font-bold uppercase block text-[10px]">Accommodation Preference</span>
                    <span class="font-bold text-gray-900">{{ $inquiry->accommodation_preference }}</span>
                </div>
                <div>
                    <span class="text-gray-400 font-bold uppercase block text-[10px]">Target Budget</span>
                    <span class="font-black text-emerald-600">{{ $inquiry->budget_per_person ?: 'Flexible' }}</span>
                </div>
            </div>

            @if(is_array($inquiry->activities) && count($inquiry->activities) > 0)
            <div class="mb-6">
                <span class="text-gray-400 font-bold uppercase block text-[10px] mb-2">Interests & Activities</span>
                <div class="flex flex-wrap gap-2">
                    @foreach($inquiry->activities as $act)
                    <span class="px-3 py-1 bg-amber-50 text-amber-800 border border-amber-200 rounded-full text-[11px] font-bold">
                        {{ $act }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            @if($inquiry->special_requests)
            <div>
                <span class="text-gray-400 font-bold uppercase block text-[10px] mb-2">Special Requests / Message</span>
                <div class="p-4 bg-gray-50 border border-gray-200 rounded-2xl text-xs text-gray-800 leading-relaxed font-medium whitespace-pre-line">
                    {{ $inquiry->special_requests }}
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Right Col: Conversion Box --}}
    <div class="space-y-6">
        <div class="neo-card p-6 border-2 border-amber-500 bg-amber-50/20">
            <h3 class="text-xs font-black text-amber-900 uppercase tracking-widest mb-3">Convert to Proposal</h3>
            <p class="text-xs text-gray-600 mb-4 leading-relaxed">
                Clicking the button below will create a new proposal and automatically copy {{ $inquiry->full_name }}'s details, travel dates, duration, adults, children, and preferences into the Proposal Builder.
            </p>
            <form action="{{ route('admin.custom-inquiries.convert', $inquiry) }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-black uppercase shadow-lg transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    Convert to Proposal
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
