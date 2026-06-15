@extends('admin.layouts.app')

@section('title', 'Bookings')
@section('page-title', 'Bookings')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Name</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tour</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Travel Date</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="text-right px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($bookings as $booking)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                    <div class="text-sm font-bold text-gray-900 leading-tight">{{ $booking->full_name ?: ($booking->name ?: 'Customer') }}</div>
                    <div class="text-[10px] font-bold text-gold-600 uppercase tracking-widest">{{ $booking->booking_reference }}</div>
                </td>
                <td class="px-6 py-4">
                    <span class="text-sm text-gray-600">{{ $booking->email }}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="text-sm text-gray-600">{{ $booking->tour?->title ?? 'N/A' }}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="text-sm text-gray-600">{{ $booking->travel_date?->format('M j, Y') ?? 'N/A' }}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 text-xs rounded-full
                        @if($booking->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($booking->status == 'confirmed') bg-green-100 text-green-800
                        @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($booking->status) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right text-sm font-medium">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.bookings.show', $booking) }}" class="inline-flex items-center px-3 py-1.5 bg-gold-500 text-safari-dark font-bold rounded-lg hover:bg-gold-600 transition-colors shadow-sm">
                            View
                        </a>
                        <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" class="inline-block" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 font-bold rounded-lg hover:bg-red-100 transition-colors border border-red-100">
                                Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center">
                    <div class="text-4xl mb-4">📭</div>
                    <p class="text-gray-500 font-medium">No bookings found yet.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $bookings->links() }}
</div>
@endsection
