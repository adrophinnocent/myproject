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
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-100 text-amber-700">{{ $campaign->type }}</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider {{ $campaign->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $campaign->status }}
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $campaign->title }}</h3>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ $campaign->public_url }}" target="_blank" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Live Preview">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        <a href="{{ route('admin.campaigns.analytics', $campaign) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Analytics">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 h-2a2 2 0 01-2-2z"/></svg>
                        </a>
                        <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="p-2 text-gray-600 hover:bg-gray-50 rounded-lg transition-colors" title="Edit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <form action="{{ route('admin.campaigns.destroy', $campaign) }}" method="POST" class="inline" onsubmit="return confirm('Delete this campaign?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
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
                    <a href="{{ $campaign->public_url }}" target="_blank" class="flex-1 bg-gray-100 px-3 py-2 rounded-lg font-mono truncate text-blue-600 hover:bg-blue-50 transition-colors border border-transparent hover:border-blue-200" id="link-{{ $campaign->id }}">
                        {{ $campaign->public_url }}
                    </a>
                    <button onclick="copyToClipboard('link-{{ $campaign->id }}')" class="bg-blue-50 text-blue-600 px-4 py-2 rounded-lg font-black uppercase tracking-widest text-[10px] hover:bg-blue-600 hover:text-white transition-all shadow-sm active:scale-95">
                        Copy Link
                    </button>
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
