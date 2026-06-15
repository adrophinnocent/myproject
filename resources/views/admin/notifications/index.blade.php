@extends('admin.layouts.app')

@section('title', 'Admin Notifications')
@section('page-title', 'Notification Center')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Notification Center</h2>
        @if(\App\Models\AdminNotification::unread()->count() > 0)
        <a href="{{ route('admin.notifications.mark-all-read') }}" class="text-sm font-bold text-gold-600 hover:text-gold-700 transition-colors uppercase tracking-widest bg-gold-50 px-4 py-2 rounded-xl">
            Mark all as read
        </a>
        @endif
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="divide-y divide-gray-50">
            @forelse($notifications as $notification)
            <div class="p-6 md:p-8 flex items-start gap-6 transition-colors {{ !$notification->is_read ? 'bg-gold-50/20' : '' }}">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 shadow-sm
                    @if($notification->type == 'booking') bg-green-100 text-green-600
                    @elseif($notification->type == 'inquiry') bg-blue-100 text-blue-600
                    @elseif($notification->type == 'contact') bg-purple-100 text-purple-600
                    @elseif($notification->type == 'newsletter') bg-amber-100 text-amber-600
                    @else bg-gray-100 text-gray-600
                    @endif">
                    @if($notification->type == 'booking') 🎫
                    @elseif($notification->type == 'inquiry') ✉️
                    @elseif($notification->type == 'contact') 💬
                    @elseif($notification->type == 'newsletter') 📧
                    @else 🔔
                    @endif
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-4 mb-1">
                        <h3 class="text-base font-black text-gray-900 truncate">{{ $notification->title }}</h3>
                        <span class="text-[10px] font-bold text-gray-400 uppercase whitespace-nowrap">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-gray-600 leading-relaxed mb-4">{{ $notification->message }}</p>

                    @if($notification->link)
                    <a href="{{ $notification->link }}" class="inline-flex items-center gap-2 text-xs font-black text-gold-600 hover:text-gold-700 transition-all uppercase tracking-widest group">
                        View Details
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    @endif
                </div>

                @if(!$notification->is_read)
                <div class="w-2 h-2 rounded-full bg-gold-500 mt-2 shrink-0 animate-pulse"></div>
                @endif
            </div>
            @empty
            <div class="py-20 text-center">
                <div class="text-6xl mb-4">🔔</div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">All caught up!</h3>
                <p class="text-gray-500 text-sm">No notifications to show at the moment.</p>
            </div>
            @endforelse
        </div>
    </div>

    <div class="mt-8">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
