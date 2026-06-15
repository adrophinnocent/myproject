@extends('admin.layouts.app')

@section('title', 'Trip Plan Details')
@section('page-title', 'Trip Plan Details')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Contact Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Name</p>
                <p class="text-gray-900">{{ $tripPlan->name }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Email</p>
                <p class="text-gray-900">{{ $tripPlan->email }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Phone</p>
                <p class="text-gray-900">{{ $tripPlan->phone ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Nationality</p>
                <p class="text-gray-900">{{ $tripPlan->nationality ?? '—' }}</p>
            </div>
        </div>
        
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Trip Preferences</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Travel Style</p>
                <p class="text-gray-900">{{ $tripPlan->travel_style ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Budget Range</p>
                <p class="text-gray-900">{{ $tripPlan->budget_range ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Duration</p>
                <p class="text-gray-900">{{ $tripPlan->duration ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Accommodation Level</p>
                <p class="text-gray-900">{{ $tripPlan->accommodation_level ?? '—' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Adults</p>
                <p class="text-gray-900">{{ $tripPlan->adults }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Children</p>
                <p class="text-gray-900">{{ $tripPlan->children ?? '0' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Travel Date</p>
                <p class="text-gray-900">{{ $tripPlan->travel_date ? $tripPlan->travel_date->format('F j, Y') : '—' }}</p>
            </div>
        </div>
        
        @if($tripPlan->message)
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Message</h3>
        <p class="text-gray-600">{{ $tripPlan->message }}</p>
        @endif
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Status</h3>
        <form method="POST" action="{{ route('admin.trip-plans.update-status', $tripPlan) }}">
            @csrf
            @method('PATCH')
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">
                    <option value="new" {{ $tripPlan->status == 'new' ? 'selected' : '' }}>New</option>
                    <option value="reviewing" {{ $tripPlan->status == 'reviewing' ? 'selected' : '' }}>Reviewing</option>
                    <option value="sent" {{ $tripPlan->status == 'sent' ? 'selected' : '' }}>Sent</option>
                    <option value="closed" {{ $tripPlan->status == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Admin Notes</label>
                <textarea name="admin_notes" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]/50 focus:border-[#D4AF37]">{{ old('admin_notes', $tripPlan->admin_notes) }}</textarea>
            </div>
            
            <button type="submit" class="w-full bg-[#D4AF37] hover:bg-[#b8920d] text-[#1a1209] font-semibold px-4 py-2 rounded-lg transition-colors">Update Status</button>
        </form>
        
        <div class="mt-6 pt-6 border-t border-gray-200">
            <a href="{{ route('admin.trip-plans.index') }}" class="text-[#D4AF37] hover:text-[#b8920d] text-sm font-medium flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Trip Plans
            </a>
        </div>
    </div>
</div>
@endsection
