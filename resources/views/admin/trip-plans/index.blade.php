@extends('admin.layouts.app')

@section('title', 'Trip Plans')
@section('page-title', 'Trip Plans')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Phone</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($plans as $plan)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900">{{ $plan->name }}</div>
                </td>
                <td class="px-6 py-4">
                    <span class="text-sm text-gray-600">{{ $plan->email }}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="text-sm text-gray-600">{{ $plan->phone ?? '—' }}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs rounded-full {{ $plan->status == 'new' ? 'bg-yellow-100 text-yellow-700' : ($plan->status == 'reviewing' ? 'bg-blue-100 text-blue-700' : ($plan->status == 'sent' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700')) }}">
                        {{ ucfirst($plan->status) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right text-sm font-medium">
                    <a href="{{ route('admin.trip-plans.show', $plan) }}" class="text-[#D4AF37] hover:text-[#b8920d] mr-3">View</a>
                    <form method="POST" action="{{ route('admin.trip-plans.destroy', $plan) }}" class="inline-block" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $plans->links() }}
    </div>
</div>
@endsection
