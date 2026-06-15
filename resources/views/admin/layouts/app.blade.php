<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Twinasafaris') }} - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#F8FAFC] min-h-screen">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gradient-to-br from-[#166534] to-[#14532D] text-white flex flex-col">
            <div class="p-6 border-b border-[#22C55E]/30">
                <h1 class="text-xl font-bold text-[#F59E0B] flex items-center gap-2">
                    @if(\App\Models\Setting::get('logo'))
                        <img src="{{ asset('storage/' . \App\Models\Setting::get('logo')) }}" alt="Logo" class="w-16 h-16 object-contain">
                    @else
                        <div class="w-10 h-10 bg-[#F59E0B] rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-[#166534]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9-9c1.657 0 3 1.343 3 3s-1.343 3-3 3m0-6H7m10 0v6m0 6H7m10 0v-6M3 12a9 9 0 019-9m-9 9a9 9 0 009 9m-9-9c1.657 0-3 1.343-3 3s1.343 3 3 3m0-6h10"></path></svg>
                        </div>
                    @endif
                    {{ \App\Models\Setting::get('site_name', 'Twinasafaris') }}
                </h1>
                <p class="text-xs text-[#22C55E]/80 mt-1">Admin Panel</p>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-[#22C55E]/90 hover:text-white hover:bg-[#F59E0B]/10 rounded-lg transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-[#F59E0B]/20 text-[#F59E0B]' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.sliders.index') }}" class="flex items-center gap-3 px-4 py-2 text-[#22C55E]/90 hover:text-white hover:bg-[#F59E0B]/10 rounded-lg transition-all {{ request()->routeIs('admin.sliders.*') ? 'bg-[#F59E0B]/20 text-[#F59E0B]' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                    </svg>
                    Home Sliders
                </a>
                <a href="{{ route('admin.tours.index') }}" class="flex items-center gap-3 px-4 py-2 text-[#22C55E]/90 hover:text-white hover:bg-[#F59E0B]/10 rounded-lg transition-all {{ request()->routeIs('admin.tours.*') ? 'bg-[#F59E0B]/20 text-[#F59E0B]' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Tours
                </a>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-4 py-2 text-[#22C55E]/90 hover:text-white hover:bg-[#F59E0B]/10 rounded-lg transition-all {{ request()->routeIs('admin.categories.*') ? 'bg-[#F59E0B]/20 text-[#F59E0B]' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    Categories
                </a>
                <a href="{{ route('admin.destinations.index') }}" class="flex items-center gap-3 px-4 py-2 text-[#22C55E]/90 hover:text-white hover:bg-[#F59E0B]/10 rounded-lg transition-all {{ request()->routeIs('admin.destinations.*') ? 'bg-[#F59E0B]/20 text-[#F59E0B]' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Destinations
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="flex items-center gap-3 px-4 py-2 text-[#22C55E]/90 hover:text-white hover:bg-[#F59E0B]/10 rounded-lg transition-all {{ request()->routeIs('admin.bookings.*') ? 'bg-[#F59E0B]/20 text-[#F59E0B]' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Bookings
                </a>
                <a href="{{ route('admin.gallery.index') }}" class="flex items-center gap-3 px-4 py-2 text-[#22C55E]/90 hover:text-white hover:bg-[#F59E0B]/10 rounded-lg transition-all {{ request()->routeIs('admin.gallery.*') ? 'bg-[#F59E0B]/20 text-[#F59E0B]' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Gallery
                </a>
                <a href="{{ route('admin.testimonials.index') }}" class="flex items-center gap-3 px-4 py-2 text-[#22C55E]/90 hover:text-white hover:bg-[#F59E0B]/10 rounded-lg transition-all {{ request()->routeIs('admin.testimonials.*') ? 'bg-[#F59E0B]/20 text-[#F59E0B]' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Testimonials
                </a>
                <a href="{{ route('admin.trip-plans.index') }}" class="flex items-center gap-3 px-4 py-2 text-[#22C55E]/90 hover:text-white hover:bg-[#F59E0B]/10 rounded-lg transition-all {{ request()->routeIs('admin.trip-plans.*') ? 'bg-[#F59E0B]/20 text-[#F59E0B]' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Trip Plans
                </a>
                <a href="{{ route('admin.reviews.index') }}" class="flex items-center gap-3 px-4 py-2 text-[#22C55E]/90 hover:text-white hover:bg-[#F59E0B]/10 rounded-lg transition-all {{ request()->routeIs('admin.reviews.*') ? 'bg-[#F59E0B]/20 text-[#F59E0B]' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    Reviews
                </a>
                <a href="{{ route('admin.faqs.index') }}" class="flex items-center gap-3 px-4 py-2 text-[#22C55E]/90 hover:text-white hover:bg-[#F59E0B]/10 rounded-lg transition-all {{ request()->routeIs('admin.faqs.*') ? 'bg-[#F59E0B]/20 text-[#F59E0B]' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    FAQs
                </a>
                <a href="{{ route('admin.blog.index') }}" class="flex items-center gap-3 px-4 py-2 text-[#22C55E]/90 hover:text-white hover:bg-[#F59E0B]/10 rounded-lg transition-all {{ request()->routeIs('admin.blog.*') ? 'bg-[#F59E0B]/20 text-[#F59E0B]' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    Blog Posts
                </a>
                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-2 text-[#22C55E]/90 hover:text-white hover:bg-[#F59E0B]/10 rounded-lg transition-all {{ request()->routeIs('admin.settings.*') ? 'bg-[#F59E0B]/20 text-[#F59E0B]' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Settings
                </a>
                <a href="{{ route('admin.marketing.index') }}" class="flex items-center gap-3 px-4 py-2 text-[#22C55E]/90 hover:text-white hover:bg-[#F59E0B]/10 rounded-lg transition-all {{ request()->routeIs('admin.marketing.*') ? 'bg-[#F59E0B]/20 text-[#F59E0B]' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    Marketing & SEO
                </a>
                <a href="{{ route('admin.email-campaigns.index') }}" class="flex items-center gap-3 px-4 py-2 text-[#22C55E]/90 hover:text-white hover:bg-[#F59E0B]/10 rounded-lg transition-all {{ request()->routeIs('admin.email-campaigns.*') ? 'bg-[#F59E0B]/20 text-[#F59E0B]' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Subscribers
                </a>
                <a href="{{ route('admin.notifications.index') }}" class="flex items-center gap-3 px-4 py-2 text-[#22C55E]/90 hover:text-white hover:bg-[#F59E0B]/10 rounded-lg transition-all {{ request()->routeIs('admin.notifications.*') ? 'bg-[#F59E0B]/20 text-[#F59E0B]' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    Notifications
                    @php $unreadCount = \App\Models\AdminNotification::unread()->count(); @endphp
                    @if($unreadCount > 0)
                        <span class="ml-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $unreadCount }}</span>
                    @endif
                </a>
            </nav>
            <div class="p-4 border-t border-[#22C55E]/30">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-[#F59E0B] flex items-center justify-center text-[#166534] font-semibold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-[#22C55E]/80 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-green-200 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>
        <main class="flex-1">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h2>
                    <p class="text-sm text-gray-500 mt-1">Welcome back, {{ auth()->user()->name }}!</p>
                </div>
                <div class="flex items-center gap-4">
                    {{-- Notification Bell --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="relative p-2 text-gray-400 hover:text-amber-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                            @if($unreadCount > 0)
                                <span class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full bg-red-500 ring-2 ring-white"></span>
                            @endif
                        </button>

                        <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-2xl shadow-2xl z-50 overflow-hidden">
                            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                                <h4 class="font-bold text-gray-900">Notifications</h4>
                                <a href="{{ route('admin.notifications.mark-all-read') }}" class="text-[10px] font-bold text-amber-600 uppercase hover:underline">Mark all read</a>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                @forelse(\App\Models\AdminNotification::latest()->take(5)->get() as $notif)
                                    <a href="{{ $notif->link ?? route('admin.notifications.index') }}" class="block p-4 hover:bg-gray-50 border-b border-gray-50 {{ !$notif->is_read ? 'bg-amber-50/30' : '' }}">
                                        <p class="text-sm font-bold text-gray-800">{{ $notif->title }}</p>
                                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $notif->message }}</p>
                                        <p class="text-[10px] text-gray-400 mt-2">{{ $notif->created_at->diffForHumans() }}</p>
                                    </a>
                                @empty
                                    <div class="p-8 text-center text-gray-400 italic text-sm">No notifications</div>
                                @endforelse
                            </div>
                            <a href="{{ route('admin.notifications.index') }}" class="block p-3 text-center text-xs font-bold text-gray-500 bg-gray-50 hover:text-amber-600">View All Notifications</a>
                        </div>
                    </div>

                    <a href="{{ route('home') }}" target="_blank" class="text-sm text-gray-600 hover:text-amber-600 transition-colors flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        View Website
                    </a>
                </div>
            </header>
            <div class="p-6">
                @if (session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 flex items-center gap-2 text-green-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 text-red-700">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm font-bold">There were some problems with your input:</span>
                        </div>
                        <ul class="list-disc list-inside text-xs space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
