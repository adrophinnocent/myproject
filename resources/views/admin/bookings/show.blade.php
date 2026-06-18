@extends('admin.layouts.app')

@section('title', 'Booking Details: ' . $booking->booking_reference)
@section('page-title', 'Booking Management')

@section('content')
<div class="max-w-6xl mx-auto space-y-8 pb-12">

    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="flex items-center gap-5">
            <a href="{{ route('admin.bookings.index') }}" class="w-12 h-12 bg-white border border-gray-200 rounded-2xl flex items-center justify-center text-gray-400 hover:text-gold-600 hover:border-gold-500 transition-all shadow-sm group">
                <svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Booking Details</h1>
                <div class="flex items-center gap-3 mt-1">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Reference:</span>
                    <span class="text-sm font-mono font-bold text-gold-600 bg-gold-50 px-2 py-0.5 rounded-lg">{{ $booking->booking_reference }}</span>
                    <span class="text-gray-300">|</span>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Placed:</span>
                    <span class="text-sm font-bold text-gray-700">{{ $booking->created_at->format('M d, Y \a\t H:i') }}</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            @if($booking->status !== 'confirmed')
            <form method="POST" action="{{ route('admin.bookings.update-status', $booking) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="confirmed">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold px-6 py-3 rounded-2xl transition-all shadow-xl shadow-green-600/20 flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Confirm Order
                </button>
            </form>
            @endif

            <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" onsubmit="return confirm('Permanently delete this booking?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-white border border-red-100 text-red-500 hover:bg-red-50 font-bold px-6 py-3 rounded-2xl transition-all text-sm">
                    Delete
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        {{-- Main Column --}}
        <div class="lg:col-span-8 space-y-8">

            {{-- Tour Info Card --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden relative group">
                <div class="bg-gray-50/80 px-10 py-6 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-black text-gray-800 uppercase tracking-tighter text-lg flex items-center gap-2">
                        <span class="w-2 h-6 bg-gold-500 rounded-full"></span>
                        Tour Information
                    </h3>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 bg-white border border-gold-200 text-gold-700 text-[10px] font-bold uppercase tracking-widest rounded-full shadow-sm">
                            {{ $booking->tour->category->name ?? 'Safari' }}
                        </span>
                    </div>
                </div>
                <div class="p-10 flex flex-col md:flex-row gap-10">
                    <div class="w-full md:w-56 h-40 rounded-3xl overflow-hidden shadow-2xl relative">
                        <img src="{{ $booking->tour->featured_image_url }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <span class="text-[10px] text-white/80 font-bold uppercase tracking-widest">{{ $booking->tour->destination->name ?? 'Tanzania' }}</span>
                        </div>
                    </div>
                    <div class="flex-1 space-y-6">
                        <h4 class="text-2xl font-black text-gray-900 leading-tight">{{ $booking->tour->title }}</h4>

                        <div class="grid grid-cols-2 gap-x-12 gap-y-6 pt-4 border-t border-gray-50">
                            <div class="space-y-1">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.15em]">Travel Date</p>
                                <div class="flex items-center gap-2">
                                    <p class="text-base font-black text-gray-800">{{ $booking->travel_date->format('M d, Y') }}</p>
                                    @php
                                        $days = now()->startOfDay()->diffInDays($booking->travel_date->startOfDay(), false);
                                    @endphp
                                    @if($days > 0)
                                        <span class="px-2 py-0.5 bg-green-100 text-green-700 text-[10px] font-bold rounded-lg">In {{ $days }} days</span>
                                    @elseif($days == 0)
                                        <span class="px-2 py-0.5 bg-orange-100 text-orange-700 text-[10px] font-bold rounded-lg animate-pulse">Today</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-500 text-[10px] font-bold rounded-lg">Past Event</span>
                                    @endif
                                </div>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.15em]">Duration</p>
                                <p class="text-base font-black text-gray-800">{{ $booking->tour->duration_text }}</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.15em]">Guests</p>
                                <p class="text-base font-black text-gray-800">{{ $booking->number_of_adults }} Adults @if($booking->number_of_children > 0), {{ $booking->number_of_children }} Children @endif</p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.15em]">Destination</p>
                                <p class="text-base font-black text-gray-800">{{ $booking->tour->destination->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Customer Details Card --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gray-50/80 px-10 py-6 border-b border-gray-100">
                    <h3 class="font-black text-gray-800 uppercase tracking-tighter text-lg flex items-center gap-2">
                        <span class="w-2 h-6 bg-gold-500 rounded-full"></span>
                        Customer Details
                    </h3>
                </div>
                <div class="p-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                        <div class="space-y-1 pb-4 border-b border-gray-50">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.15em]">Full Name</p>
                            <p class="text-lg font-black text-gray-900">{{ $booking->full_name }}</p>
                        </div>
                        <div class="space-y-1 pb-4 border-b border-gray-50">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.15em]">Email Address</p>
                            <a href="mailto:{{ $booking->email }}" class="text-lg font-black text-gold-600 hover:text-gold-700 transition-colors">{{ $booking->email }}</a>
                        </div>
                        <div class="space-y-1 pb-4 border-b border-gray-50">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.15em]">Phone Number</p>
                            <p class="text-lg font-black text-gray-900">{{ $booking->phone }}</p>
                        </div>
                        <div class="space-y-1 pb-4 border-b border-gray-50">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.15em]">Nationality</p>
                            <p class="text-lg font-black text-gray-900">{{ $booking->nationality ?? 'Not Specified' }}</p>
                        </div>
                    </div>

                    @if($booking->special_requests)
                    <div class="mt-10 p-8 bg-gold-50/50 border border-gold-100 rounded-[2rem] relative">
                        <div class="absolute -top-4 left-8 px-4 py-1 bg-gold-500 text-safari-dark text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg">Special Request</div>
                        <p class="text-gray-700 italic leading-relaxed text-sm">
                            "{{ $booking->special_requests }}"
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar Content --}}
        <div class="lg:col-span-4 space-y-8">

            {{-- Management Card --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-10">
                <h3 class="font-black text-gray-800 uppercase tracking-tighter text-lg mb-8 flex items-center gap-2">
                    <span class="w-2 h-6 bg-gold-500 rounded-full"></span>
                    Manage Status
                </h3>

                <form method="POST" action="{{ route('admin.bookings.update-status', $booking) }}" class="space-y-8">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-3">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Booking Status</label>
                        <select name="status" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-4 font-bold text-gray-900 focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 outline-none transition-all appearance-none cursor-pointer">
                            <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>🟡 Pending Review</option>
                            <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>🟢 Confirmed</option>
                            <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>🔵 Completed</option>
                            <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>🔴 Cancelled</option>
                        </select>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Payment Status</label>
                        <select name="payment_status" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-4 font-bold text-gray-900 focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 outline-none transition-all appearance-none cursor-pointer">
                            <option value="unpaid" {{ $booking->payment_status == 'unpaid' ? 'selected' : '' }}>❌ Unpaid</option>
                            <option value="partial" {{ $booking->payment_status == 'partial' ? 'selected' : '' }}>🌗 Partial Payment</option>
                            <option value="paid" {{ $booking->payment_status == 'paid' ? 'selected' : '' }}>✅ Fully Paid</option>
                            <option value="refunded" {{ $booking->payment_status == 'refunded' ? 'selected' : '' }}>💸 Refunded</option>
                        </select>
                    </div>

                    <div class="space-y-3">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Admin Notes (Private)</label>
                        <textarea name="admin_notes" rows="4" placeholder="Internal notes about this booking..." class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-4 text-sm focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 outline-none transition-all resize-none">{{ old('admin_notes', $booking->admin_notes) }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-safari-dark hover:bg-black text-white font-black py-5 rounded-2xl transition-all shadow-2xl shadow-black/20 uppercase tracking-widest text-xs">
                        Update All Status
                    </button>
                </form>
            </div>

            {{-- Financial & PDF Documents Card --}}
            <div class="bg-safari-dark rounded-[2.5rem] shadow-2xl p-10 text-white relative overflow-hidden group">
                <div class="relative z-10">
                    <h3 class="font-black uppercase tracking-tighter text-lg mb-8 flex items-center gap-2">
                        <span class="w-2 h-6 bg-gold-500 rounded-full"></span>
                        Order Documents
                    </h3>

                    <div class="space-y-4">
                        <a href="{{ route('admin.bookings.download-invoice', $booking) }}" class="w-full bg-white/10 hover:bg-white/20 border border-white/10 flex items-center justify-between p-4 rounded-2xl transition-all group/btn">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gold-500/20 rounded-xl flex items-center justify-center text-gold-500 group-hover/btn:bg-gold-500 group-hover/btn:text-safari-dark transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div>
                                    <span class="block text-xs font-black uppercase tracking-widest text-white">Download Invoice</span>
                                    <span class="text-[10px] text-white/40">Official PDF for traveler</span>
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-white/20 group-hover/btn:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                        </a>

                        <a href="{{ route('admin.bookings.download-itinerary', $booking) }}" class="w-full bg-white/10 hover:bg-white/20 border border-white/10 flex items-center justify-between p-4 rounded-2xl transition-all group/btn">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-500/20 rounded-xl flex items-center justify-center text-blue-400 group-hover/btn:bg-blue-500 group-hover/btn:text-white transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                </div>
                                <div>
                                    <span class="block text-xs font-black uppercase tracking-widest text-white">Tour Itinerary</span>
                                    <span class="text-[10px] text-white/40">Daily program PDF</span>
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-white/20 group-hover/btn:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                        </a>
                    </div>

                    <div class="mt-10 pt-10 border-t border-white/5 space-y-6">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-white/40 font-bold uppercase tracking-widest text-[10px]">Payment Method</span>
                            <span class="font-black text-gold-400 capitalize">{{ $booking->payment_method ?: 'Not set' }}</span>
                        </div>
                        <div class="space-y-1">
                            <span class="text-white/40 font-bold uppercase tracking-widest text-[10px]">Total Order Value</span>
                            <div class="text-4xl font-black text-white tracking-tighter">${{ number_format($booking->total_price, 2) }}</div>
                        </div>
                    </div>
                </div>

                {{-- Accent --}}
                <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-gold-500/10 rounded-full blur-[100px] pointer-events-none group-hover:bg-gold-500/20 transition-all duration-700"></div>
            </div>

            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $booking->phone) }}" target="_blank" class="w-full bg-[#25D366] hover:bg-[#128C7E] text-white font-black py-5 rounded-2xl transition-all shadow-xl shadow-green-500/20 uppercase tracking-widest text-xs flex items-center justify-center gap-3 group">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.483 8.413-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.308 1.654zm6.757-4.051c1.535.913 3.041 1.389 4.617 1.389 5.234 0 9.493-4.258 9.495-9.493.002-5.233-4.258-9.491-9.493-9.491-5.235 0-9.493 4.258-9.495 9.493-.001 1.761.492 3.393 1.458 4.789l-.953 3.483 3.593-.941zm11.234-6.44c-.301-.15-1.782-.88-2.056-.981-.275-.1-.475-.15-.675.15-.199.301-.775.981-.95 1.179-.175.199-.35.225-.651.076-.301-.15-1.269-.468-2.418-1.581-.893-.796-1.496-1.78-1.671-2.08-.175-.301-.019-.464.132-.613.135-.134.301-.351.451-.526.15-.175.199-.301.301-.501.1-.199.05-.375-.025-.526-.05-.15-.675-1.625-.925-2.225-.244-.583-.491-.503-.675-.513-.175-.01-.375-.011-.575-.011s-.525.075-.8.375c-.275.301-1.05 1.026-1.05 2.501s1.075 2.901 1.225 3.101c.15.199 2.115 3.227 5.124 4.532.715.31 1.274.496 1.709.634.719.227 1.373.196 1.89.117.577-.088 1.782-.726 2.032-1.426.25-.7.25-1.301.175-1.426-.075-.125-.275-.199-.575-.349z"/></svg>
                Chat on WhatsApp
            </a>
        </div>
    </div>
</div>
@endsection
