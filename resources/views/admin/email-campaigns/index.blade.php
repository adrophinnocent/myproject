@extends('admin.layouts.app')

@section('title', 'Email Campaigns')
@section('page-title', 'Newsletter & Campaigns')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <div>
                <div class="text-2xl font-bold text-gray-900">{{ $totalActive }}</div>
                <div class="text-xs text-gray-500 uppercase font-semibold tracking-wider">Active Subscribers</div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2 bg-gradient-to-r from-amber-500 to-amber-600 rounded-2xl p-6 text-white flex items-center justify-between shadow-lg shadow-amber-500/20">
        <div>
            <h3 class="text-lg font-bold">Ready to announce a deal?</h3>
            <p class="text-white/80 text-sm">Send a custom email to all your active subscribers instantly.</p>
        </div>
        <a href="{{ route('admin.email-campaigns.create') }}" class="bg-white text-amber-600 px-6 py-3 rounded-xl font-bold hover:bg-amber-50 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            New Campaign
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h3 class="font-bold text-gray-900">Recent Subscribers</h3>
        <button class="text-xs text-blue-600 font-semibold hover:underline">Export CSV</button>
    </div>
    <table class="w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="text-left px-6 py-4 text-xs font-bold text-gray-400 uppercase">Email Address</th>
                <th class="text-left px-6 py-4 text-xs font-bold text-gray-400 uppercase">Date Joined</th>
                <th class="text-left px-6 py-4 text-xs font-bold text-gray-400 uppercase">Status</th>
                <th class="text-right px-6 py-4 text-xs font-bold text-gray-400 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach($subscribers as $sub)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4">
                    <div class="text-sm font-semibold text-gray-900">{{ $sub->email }}</div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">
                    {{ $sub->subscribed_at->format('M d, Y') }}
                </td>
                <td class="px-6 py-4">
                    <form action="{{ route('admin.email-campaigns.toggle', $sub) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $sub->active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $sub->active ? 'Active' : 'Unsubscribed' }}
                        </button>
                    </form>
                </td>
                <td class="px-6 py-4 text-right">
                    <form action="{{ route('admin.email-campaigns.destroy', $sub) }}" method="POST" onsubmit="return confirm('Delete subscriber?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="p-6 bg-gray-50 border-t border-gray-100">
        {{ $subscribers->links() }}
    </div>
</div>
@endsection
