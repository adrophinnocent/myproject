@extends('admin.layouts.app')

@section('title', 'Trip Plan Workspace')
@section('page-title', 'Trip Plan Requests')

@section('content')
<div class="mb-6 flex flex-wrap gap-2">
    @php
        $allStatuses = ['new', 'reviewing', 'preparing_plan', 'sent', 'viewed', 'changes_requested', 'accepted', 'booking', 'closed', 'declined', 'cancelled'];
    @endphp
    <a href="{{ route('admin.trip-plans.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ !request('status') ? 'bg-safari-dark text-white' : 'bg-white text-gray-600 border border-gray-200' }}">
        All ({{ array_sum($counts->toArray()) }})
    </a>
    @foreach($allStatuses as $status)
        @if(isset($counts[$status]) && $counts[$status] > 0)
        <a href="{{ route('admin.trip-plans.index', ['status' => $status]) }}"
           class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') == $status ? 'bg-safari-dark text-white' : 'bg-white text-gray-600 border border-gray-200' }}">
            {{ ucfirst(str_replace('_', ' ', $status)) }} ({{ $counts[$status] }})
        </a>
        @endif
    @endforeach
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <form action="{{ route('admin.trip-plans.index') }}" method="GET" class="flex gap-2 w-full max-w-md">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="flex-1 bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 text-sm outline-none focus:border-gold-500">
            <button type="submit" class="bg-safari-dark text-white px-4 py-2 rounded-lg text-sm font-bold">Search</button>
        </form>
    </div>

    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Customer</th>
                <th class="text-left px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Trip Details</th>
                <th class="text-left px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="text-left px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Next Action</th>
                <th class="text-right px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($plans as $plan)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gold-100 text-gold-700 flex items-center justify-center font-bold text-xs uppercase">
                            {{ substr($plan->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="text-sm font-bold text-gray-900">{{ $plan->name }}</div>
                            <div class="text-xs text-gray-500">{{ $plan->email }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900">
                        {{ $plan->trip_title ?: 'Untitled Trip' }}
                    </div>
                    <div class="text-xs text-gray-500 flex items-center gap-2 mt-1">
                        <span>{{ $plan->travel_date ? $plan->travel_date->format('d M Y') : 'TBD' }}</span>
                        <span>•</span>
                        <span>{{ $plan->adults + $plan->children }} Pax</span>
                        @if($plan->budget_range)
                            <span>•</span>
                            <span class="text-gold-600 font-bold">{{ $plan->budget_range }}</span>
                        @endif
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @php
                        $statusClasses = [
                            'new' => 'bg-amber-100 text-amber-700 border-amber-200',
                            'reviewing' => 'bg-blue-100 text-blue-700 border-blue-200',
                            'preparing_plan' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                            'sent' => 'bg-purple-100 text-purple-700 border-purple-200',
                            'viewed' => 'bg-pink-100 text-pink-700 border-pink-200',
                            'changes_requested' => 'bg-orange-100 text-orange-700 border-orange-200',
                            'accepted' => 'bg-green-100 text-green-700 border-green-200',
                            'booking' => 'bg-teal-100 text-teal-700 border-teal-200',
                            'closed' => 'bg-gray-100 text-gray-700 border-gray-200',
                            'declined' => 'bg-red-100 text-red-700 border-red-200',
                        ];
                        $class = $statusClasses[$plan->status] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                    @endphp
                    <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full border {{ $class }}">
                        {{ str_replace('_', ' ', $plan->status) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @php
                        $nextAction = match($plan->status) {
                            'new' => ['label' => 'Start Reviewing', 'class' => 'bg-blue-50 text-blue-700'],
                            'reviewing' => ['label' => 'Create Plan', 'class' => 'bg-indigo-50 text-indigo-700'],
                            'preparing_plan' => ['label' => 'Finish & Send', 'class' => 'bg-gold-50 text-gold-700'],
                            'sent' => ['label' => 'Wait for Client', 'class' => 'text-gray-400 italic'],
                            'viewed' => ['label' => 'Follow up', 'class' => 'bg-pink-50 text-pink-700'],
                            'changes_requested' => ['label' => 'Review Changes', 'class' => 'bg-orange-50 text-orange-700'],
                            'accepted' => ['label' => 'Create Booking', 'class' => 'bg-green-600 text-white'],
                            'booking' => ['label' => 'Record Payment', 'class' => 'bg-teal-50 text-teal-700'],
                            default => ['label' => 'No action needed', 'class' => 'text-gray-300'],
                        };
                    @endphp
                    <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider {{ $nextAction['class'] }}">
                        {{ $nextAction['label'] }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right whitespace-nowrap">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.trip-plans.show', $plan) }}" class="p-2 bg-gray-50 text-gray-600 hover:bg-gold-500 hover:text-white rounded-lg transition-all" title="Manage Workspace">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <form method="POST" action="{{ route('admin.trip-plans.destroy', $plan) }}" onsubmit="return confirm('Archive this request?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 bg-gray-50 text-red-500 hover:bg-red-500 hover:text-white rounded-lg transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center text-gray-500 font-medium italic">
                    No trip plan requests found matching your criteria.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($plans->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $plans->links() }}
    </div>
    @endif
</div>
@endsection
