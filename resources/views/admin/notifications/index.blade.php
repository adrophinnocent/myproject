@extends('admin.layouts.app')

@section('title', 'Admin Notifications')
@section('page-title', 'Notification Center')

@section('content')
<div class="max-w-5xl mx-auto space-y-10">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-3xl font-black text-gray-800 tracking-tight">Notification Center</h2>
            <p class="text-gray-400 font-bold uppercase text-[10px] tracking-widest mt-2">Manage alerts for bookings, inquiries, and system events</p>
        </div>

        <div class="flex items-center gap-4">
            @if(\App\Models\AdminNotification::unread()->count() > 0)
                <a href="{{ route('admin.notifications.mark-all-read') }}" class="neo-btn px-6 py-3 text-[10px] font-black text-amber-600 uppercase tracking-widest hover:text-amber-700 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    Mark all as read
                </a>
            @endif
        </div>
    </div>

    {{-- Notifications List --}}
    <div class="space-y-6">
        @forelse($notifications as $notification)
            <div class="neo-card p-1 transition-all duration-300 {{ !$notification->is_read ? 'ring-2 ring-amber-500/20' : 'opacity-80' }}">
                <div class="flex flex-col md:flex-row md:items-center gap-6 p-6">
                    {{-- Type Icon --}}
                    <div class="w-16 h-16 neo-inset rounded-[2rem] flex items-center justify-center shrink-0 text-2xl relative">
                        @if($notification->type == 'booking') 🎫
                        @elseif($notification->type == 'inquiry') ✉️
                        @elseif($notification->type == 'contact') 💬
                        @elseif($notification->type == 'newsletter') 📧
                        @else 🔔
                        @endif

                        @if(!$notification->is_read)
                            <span class="absolute -top-1 -right-1 w-4 h-4 bg-amber-500 rounded-full border-4 border-[#e0e5ec] animate-pulse"></span>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mb-2">
                            <h3 class="text-lg font-black text-gray-800">{{ $notification->title }}</h3>
                            <span class="px-3 py-1 neo-inset rounded-full text-[9px] font-black uppercase text-gray-500 tracking-tighter">
                                {{ $notification->type }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 font-medium leading-relaxed line-clamp-2 md:line-clamp-none">{{ $notification->message }}</p>
                    </div>

                    {{-- Action & Meta --}}
                    <div class="flex flex-row md:flex-col items-center md:items-end justify-between gap-4 md:min-w-[140px] pt-4 md:pt-0 border-t md:border-0 border-gray-200/50">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-tighter">{{ $notification->created_at->diffForHumans() }}</span>

                        @if($notification->link)
                            <a href="{{ $notification->link }}" class="neo-btn px-5 py-2.5 text-[10px] font-black text-amber-600 uppercase tracking-widest hover:text-amber-700 transition-all flex items-center gap-2">
                                View
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="neo-card p-20 text-center">
                <div class="w-24 h-24 neo-inset rounded-full flex items-center justify-center mx-auto mb-8 text-5xl grayscale opacity-30">📭</div>
                <h3 class="text-2xl font-black text-gray-800 mb-2">You're All Caught Up!</h3>
                <p class="text-gray-400 font-bold uppercase text-xs tracking-widest">No new notifications at this time.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="pt-10 flex justify-center">
        <div class="neo-card p-2 inline-block">
            {{ $notifications->links() }}
        </div>
    </div>
</div>

<style>
    /* Styling standard Laravel pagination for Neomorphism */
    .pagination { @apply flex items-center gap-2; }
    .page-item .page-link { @apply neo-btn px-4 py-2 text-xs font-black text-gray-500 border-none !important; }
    .page-item.active .page-link { @apply neo-inset text-amber-600 !important; }
    .page-item.disabled .page-link { @apply opacity-50 cursor-not-allowed !important; }
</style>
@endsection
