@extends('admin.layouts.app')

@section('title', 'Ad Analytics')
@section('page-title', 'Analytics: ' . $campaign->title)

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('admin.campaigns.index') }}" class="text-gray-500 hover:text-amber-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h2 class="text-xl font-bold text-gray-900">Performance Report</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Total Visits</div>
            <div class="text-3xl font-black text-gray-900">{{ $campaign->stats()->where('type', 'visit')->count() }}</div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">WhatsApp Clicks</div>
            <div class="text-3xl font-black text-green-600">{{ $campaign->stats()->where('type', 'whatsapp')->count() }}</div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Generated Leads</div>
            <div class="text-3xl font-black text-blue-600">{{ $leads->count() }}</div>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Conversion Rate</div>
            <div class="text-3xl font-black text-amber-600">
                @php $visits = $campaign->stats()->where('type', 'visit')->count(); @endphp
                {{ $visits > 0 ? round(($leads->count() / $visits) * 100, 1) : 0 }}%
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Traffic Sources --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h3 class="font-bold text-gray-900 mb-6">Traffic Platforms</h3>
            <div class="space-y-4">
                @php
                    $platforms = $campaign->stats()->where('type', 'visit')->select('platform', \DB::raw('count(*) as count'))->groupBy('platform')->get();
                @endphp
                @foreach($platforms as $p)
                <div class="flex items-center justify-between">
                    <span class="capitalize text-gray-600 text-sm">{{ $p->platform }}</span>
                    <span class="font-bold text-gray-900">{{ $p->count }}</span>
                </div>
                <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                    <div class="bg-amber-500 h-full" style="width: {{ $visits > 0 ? ($p->count / $visits) * 100 : 0 }}%"></div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Recent Leads --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="font-bold text-gray-900">Recent Leads</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 font-bold text-gray-400 uppercase text-[10px]">Name</th>
                            <th class="px-6 py-4 font-bold text-gray-400 uppercase text-[10px]">Contact</th>
                            <th class="px-6 py-4 font-bold text-gray-400 uppercase text-[10px]">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($leads as $lead)
                        <tr>
                            <td class="px-6 py-4 font-bold text-gray-900">{{ $lead->name }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $lead->email }}<br>{{ $lead->phone }}</td>
                            <td class="px-6 py-4 text-gray-500 text-xs">{{ $lead->created_at->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
