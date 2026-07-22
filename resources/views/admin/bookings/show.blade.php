@extends('admin.layouts.app')

@section('title', 'Booking Management - ' . $booking->booking_reference)
@section('page-title', 'Booking Control Center')

@section('content')
<div class="max-w-6xl mx-auto space-y-8 pb-12">

    {{-- Action Bar --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100">
        <div class="flex items-center gap-5">
            <a href="{{ route('admin.bookings.index') }}" class="w-12 h-12 bg-gray-50 border border-gray-200 rounded-2xl flex items-center justify-center text-gray-400 hover:text-gold-600 hover:border-gold-500 transition-all group">
                <svg class="w-6 h-6 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">{{ $booking->booking_reference }}</h1>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="w-2 h-2 rounded-full
                        @if($booking->status == 'confirmed') bg-green-500
                        @elseif($booking->status == 'pending') bg-yellow-500
                        @elseif($booking->status == 'cancelled') bg-red-500
                        @else bg-blue-500 @endif animate-pulse"></span>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $booking->status }} Request</span>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            {{-- Dedicated Action Buttons for Each Status --}}
            @if($booking->status !== 'confirmed')
            <form method="POST" action="{{ route('admin.bookings.update-status', $booking) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="confirmed">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-black px-6 py-3 rounded-2xl transition-all shadow-xl shadow-green-600/20 flex items-center gap-2 text-xs uppercase tracking-widest">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    Confirm Order
                </button>
            </form>
            @endif

            @if($booking->status !== 'completed' && $booking->status === 'confirmed')
            <form method="POST" action="{{ route('admin.bookings.update-status', $booking) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="completed">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-black px-6 py-3 rounded-2xl transition-all shadow-xl shadow-blue-600/20 flex items-center gap-2 text-xs uppercase tracking-widest">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    Mark as Completed
                </button>
            </form>
            @endif

            @if($booking->status !== 'cancelled')
            <form method="POST" action="{{ route('admin.bookings.update-status', $booking) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="cancelled">
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-black px-6 py-3 rounded-2xl transition-all shadow-xl shadow-red-500/20 flex items-center gap-2 text-xs uppercase tracking-widest">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                    Cancel Booking
                </button>
            </form>
            @endif

            <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" onsubmit="return confirm('Permanently delete this record?')">
                @csrf @method('DELETE')
                <button type="submit" class="bg-white border border-gray-200 text-gray-400 hover:text-red-600 hover:border-red-100 font-bold px-6 py-3 rounded-2xl transition-all text-xs uppercase tracking-widest">
                    Archive / Delete
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        {{-- Main Data --}}
        <div class="lg:col-span-8 space-y-8">
            {{-- Package & Itinerary --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden group">
                <div class="bg-gray-50/50 px-10 py-6 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-black text-gray-800 uppercase tracking-tighter text-base">Reserved Adventure</h3>
                    <span class="text-[10px] font-black text-gold-600 uppercase tracking-[0.2em] bg-gold-50 px-3 py-1 rounded-full border border-gold-100">
                        {{ $booking->bookable_item->category->name ?? 'Package' }}
                    </span>
                </div>
                <div class="p-10">
                    <div class="flex flex-col md:flex-row gap-10">
                        <div class="w-full md:w-64 h-44 rounded-[2rem] overflow-hidden shadow-2xl relative bg-gray-100 flex items-center justify-center">
                            @if($booking->bookable_item)
                                <img src="{{ $booking->bookable_item->featured_image_url }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @else
                                <span class="text-4xl">🦁</span>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                            <div class="absolute bottom-5 left-6">
                                <p class="text-[10px] text-white/70 font-bold uppercase tracking-widest">Destination</p>
                                <p class="text-white font-bold">{{ $booking->bookable_item->destination->name ?? 'Tanzania' }}</p>
                            </div>
                        </div>
                        <div class="flex-1 space-y-6">
                            <h4 class="text-2xl font-black text-gray-900 leading-tight tracking-tight">{{ $booking->bookable_item->title ?? 'Deleted or Missing Package' }}</h4>

                            <div class="grid grid-cols-2 gap-8 pt-6 border-t border-gray-100">
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Travel Date</p>
                                    <p class="text-sm font-black text-gray-800">{{ $booking->travel_date ? $booking->travel_date->format('l, M d, Y') : 'Not Set' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Duration</p>
                                    <p class="text-sm font-black text-gray-800">{{ $booking->bookable_item->duration_text ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Party Size</p>
                                    <p class="text-sm font-black text-gray-800">{{ $booking->number_of_adults }} Adults, {{ $booking->number_of_children }} Children</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Package Price</p>
                                    <p class="text-sm font-black text-gold-600">{{ $booking->bookable_item->formatted_price ?? 'N/A' }} / person</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Customer Card --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gray-50/50 px-10 py-6 border-b border-gray-100">
                    <h3 class="font-black text-gray-800 uppercase tracking-tighter text-base">Traveler Information</h3>
                </div>
                <div class="p-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
                        <div class="space-y-1">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Full Name</p>
                            <p class="text-base font-black text-gray-900">{{ $booking->full_name }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Contact Email</p>
                            <a href="mailto:{{ $booking->email }}" class="text-base font-black text-gold-600 hover:underline">{{ $booking->email }}</a>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Phone / WhatsApp</p>
                            <p class="text-base font-black text-gray-900">{{ $booking->phone }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nationality</p>
                            <p class="text-base font-black text-gray-900">{{ $booking->nationality ?? 'Global Citizen' }}</p>
                        </div>
                    </div>

                    @if($booking->special_requests)
                    <div class="mt-12 p-8 bg-gray-50 rounded-[2rem] border border-gray-100">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Messege / Special Requests</p>
                        <p class="text-gray-600 italic leading-relaxed">"{{ $booking->special_requests }}"</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar Data --}}
        <div class="lg:col-span-4 space-y-8">
            {{-- Billing --}}
            <div class="bg-safari-dark rounded-[2.5rem] shadow-2xl p-10 text-white relative overflow-hidden">
                <div class="relative z-10 space-y-8">
                    <div>
                        <h3 class="font-black uppercase tracking-tighter text-lg mb-2">Order Value</h3>
                        <div class="text-5xl font-display font-black text-gold-400 tracking-tighter">${{ number_format($booking->total_price, 2) }}</div>
                    </div>

                    <div class="space-y-4 pt-8 border-t border-white/10">
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-400 font-bold uppercase">Payment Mode</span>
                            <span class="font-black uppercase tracking-widest">{{ $booking->payment_method ?: 'Not set' }}</span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-400 font-bold uppercase">Payment Status</span>
                            <span class="font-black uppercase tracking-widest
                                @if($booking->payment_status === 'paid') text-green-400 @else text-gold-500 @endif">
                                {{ $booking->payment_status ?: 'Unpaid' }}
                            </span>
                        </div>
                    </div>

                    <div class="pt-4 flex flex-col gap-3">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $booking->phone) }}" target="_blank" class="w-full bg-[#25D366] hover:bg-[#128C7E] text-white font-black py-4 rounded-2xl transition-all text-[10px] uppercase tracking-[0.2em] flex items-center justify-center gap-2">
                            Chat on WhatsApp
                        </a>
                    </div>
                </div>
                <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-gold-500/10 rounded-full blur-3xl"></div>
            </div>

            {{-- Notes & Logic --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-10">
                <h3 class="font-black text-gray-800 uppercase tracking-tighter text-base mb-6 text-center">Update Records</h3>
                <form method="POST" action="{{ route('admin.bookings.update-status', $booking) }}" class="space-y-6">
                    @csrf @method('PATCH')

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Internal Notes</label>
                        <textarea name="admin_notes" rows="4" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-4 text-sm focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 outline-none transition-all resize-none">{{ old('admin_notes', $booking->admin_notes) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Payment Update</label>
                        <select name="payment_status" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-4 font-bold text-gray-700 focus:ring-2 focus:ring-gold-500/20 focus:border-gold-500 outline-none transition-all cursor-pointer">
                            <option value="unpaid" {{ $booking->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                            <option value="partial" {{ $booking->payment_status == 'partial' ? 'selected' : '' }}>Partial</option>
                            <option value="paid" {{ $booking->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-safari-dark hover:bg-black text-white font-black py-4 rounded-2xl transition-all shadow-xl shadow-black/10 uppercase tracking-widest text-[10px]">
                        Save Admin Updates
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
