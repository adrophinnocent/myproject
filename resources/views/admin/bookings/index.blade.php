@extends('admin.layouts.app')

@section('title', 'Bookings')
@section('page-title', 'Bookings')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
    <div>
        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Booking List</h2>
        <p class="text-gray-400 font-bold uppercase text-[10px] tracking-widest mt-1">Manage and search your reservations</p>
    </div>

    <div class="w-full md:w-96 relative">
        <form action="{{ route('admin.bookings.index') }}" method="GET">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search by Reference, Name, or Email..."
                   class="w-full bg-white border border-gray-200 rounded-2xl pl-12 pr-4 py-3.5 text-sm focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 outline-none transition-all font-bold">
            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            @if(request('search'))
                <a href="{{ route('admin.bookings.index') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            @endif
        </form>
    </div>
</div>

{{-- Desktop Table View --}}
<div class="hidden lg:block bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Customer</th>
                <th class="text-left px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Package</th>
                <th class="text-left px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Travel Date</th>
                <th class="text-left px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Status</th>
                <th class="text-right px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($bookings as $booking)
            <tr class="hover:bg-gray-50/50 transition-colors group">
                <td class="px-6 py-5">
                    <div class="text-sm font-black text-gray-900 leading-tight">{{ $booking->full_name ?: 'Guest' }}</div>
                    <div class="text-[10px] font-bold text-amber-600 uppercase tracking-widest mt-1">{{ $booking->booking_reference }}</div>
                </td>
                <td class="px-6 py-5">
                    <div class="text-xs font-bold text-gray-600 line-clamp-1 max-w-[200px]">{{ $booking->tour?->title ?? 'N/A' }}</div>
                </td>
                <td class="px-6 py-5 whitespace-nowrap">
                    <span class="text-xs font-black text-gray-800 tracking-tighter">{{ $booking->travel_date?->format('d M, Y') ?? 'TBD' }}</span>
                </td>
                <td class="px-6 py-5">
                    <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest
                        @if($booking->status == 'pending') bg-yellow-50 text-yellow-700 border border-yellow-100
                        @elseif($booking->status == 'confirmed') bg-green-50 text-green-700 border border-green-100
                        @elseif($booking->status == 'cancelled') bg-red-50 text-red-700 border border-red-100
                        @else bg-gray-50 text-gray-600 border border-gray-100
                        @endif">
                        {{ $booking->status }}
                    </span>
                </td>
                <td class="px-6 py-5 text-right">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.bookings.show', $booking) }}" class="inline-flex items-center px-4 py-2 bg-gold-500 text-safari-dark text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-gold-600 transition-all shadow-lg shadow-gold-500/10">
                            Manage
                        </a>
                        <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" class="inline-block" onsubmit="return confirm('Delete booking?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="px-4 py-2 text-[10px] font-black text-red-400 uppercase tracking-widest hover:text-red-600">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="py-20 text-center text-gray-400 italic font-bold">No bookings found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Mobile Card View --}}
<div class="lg:hidden space-y-4">
    @forelse($bookings as $booking)
    <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm relative overflow-hidden">
        <div class="flex justify-between items-start mb-4">
            <div>
                <div class="text-[10px] font-black text-amber-600 uppercase tracking-widest mb-1">{{ $booking->booking_reference }}</div>
                <h3 class="text-lg font-black text-gray-900">{{ $booking->full_name }}</h3>
                <p class="text-xs text-gray-400 font-bold">{{ $booking->email }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest
                @if($booking->status == 'pending') bg-yellow-50 text-yellow-700 border border-yellow-100
                @elseif($booking->status == 'confirmed') bg-green-50 text-green-700 border border-green-100
                @else bg-gray-50 text-gray-600
                @endif">
                {{ $booking->status }}
            </span>
        </div>

        <div class="bg-gray-50 rounded-2xl p-4 mb-6 space-y-2">
            <div class="flex justify-between items-center text-[10px]">
                <span class="font-black text-gray-400 uppercase">Package:</span>
                <span class="font-bold text-gray-700 line-clamp-1 text-right">{{ $booking->tour?->title ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between items-center text-[10px]">
                <span class="font-black text-gray-400 uppercase">Travel Date:</span>
                <span class="font-bold text-gray-800">{{ $booking->travel_date?->format('d M, Y') ?? 'TBD' }}</span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('admin.bookings.show', $booking) }}" class="flex items-center justify-center gap-2 bg-amber-600 text-white py-4 rounded-2xl font-black uppercase text-[10px] tracking-[0.2em] shadow-lg shadow-amber-600/20 active:scale-95 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                View Details
            </a>
            <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" class="w-full" onsubmit="return confirm('Delete?')">
                @csrf @method('DELETE')
                <button type="submit" class="w-full h-full bg-red-50 text-red-500 py-4 rounded-2xl font-black uppercase text-[10px] tracking-[0.2em] border border-red-100">
                    Delete
                </button>
            </form>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-[2rem] p-20 text-center border border-gray-100">
        <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">No bookings found</p>
    </div>
    @endforelse
</div>


<div class="mt-6">
    {{ $bookings->links() }}
</div>
@endsection
