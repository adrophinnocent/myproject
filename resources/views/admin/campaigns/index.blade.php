@extends('admin.layouts.app')

@section('title', 'Ad Campaigns')
@section('page-title', 'Advertisement Management')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div class="text-sm text-gray-500">Manage your marketing ads and tracking links.</div>
    <a href="{{ route('admin.campaigns.create') }}" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-xl font-bold transition-all flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Create New Ad
    </a>
</div>

<div class="grid grid-cols-1 gap-6">
    @foreach($campaigns as $campaign)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
        <div class="flex flex-col md:flex-row">
            <div class="md:w-48 h-48 md:h-auto overflow-hidden">
                <img src="{{ $campaign->image_url ?? 'https://via.placeholder.com/400x300?text=No+Image' }}" class="w-full h-full object-cover">
            </div>
            <div class="flex-1 p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-700">{{ $campaign->type }}</span>
                        <h3 class="text-lg font-bold text-gray-900 mt-1">{{ $campaign->title }}</h3>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.campaigns.analytics', $campaign) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Analytics">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </a>
                        <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="p-2 text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                    <div class="bg-gray-50 p-3 rounded-xl">
                        <div class="text-[10px] text-gray-400 font-bold uppercase">Visits</div>
                        <div class="text-xl font-bold text-gray-900">{{ $campaign->stats_count }}</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-xl">
                        <div class="text-[10px] text-gray-400 font-bold uppercase">Leads</div>
                        <div class="text-xl font-bold text-blue-600">{{ $campaign->leads_count }}</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-xl">
                        <div class="text-[10px] text-gray-400 font-bold uppercase">CR%</div>
                        <div class="text-xl font-bold text-green-600">{{ $campaign->stats_count > 0 ? round(($campaign->leads_count / $campaign->stats_count) * 100, 1) : 0 }}%</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-xl">
                        <div class="text-[10px] text-gray-400 font-bold uppercase">Price</div>
                        <div class="text-xl font-bold text-amber-600">${{ number_format($campaign->price, 0) }}</div>
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-4 text-xs">
                    <div class="flex-1 bg-gray-100 px-3 py-2 rounded-lg font-mono truncate text-gray-500" id="link-{{ $campaign->id }}">{{ $campaign->public_url }}</div>
                    <button onclick="copyToClipboard('link-{{ $campaign->id }}')" class="text-blue-600 font-bold hover:underline">Copy Tracking Link</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<script>
function copyToClipboard(id) {
    const text = document.getElementById(id).innerText;
    navigator.clipboard.writeText(text).then(() => {
        alert('Tracking link copied!');
    });
}
</script>
@endsection
