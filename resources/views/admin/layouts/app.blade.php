<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Twinasafaris') }} - Admin Dashboard</title>

    {{-- Favicon --}}
    @if(\App\Models\Setting::get('favicon'))
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . \App\Models\Setting::get('favicon')) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @endif

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Tailwind & Scripts --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #061812;
            color: #e2e8f0;
        }

        .font-serif {
            font-family: 'Cormorant Garamond', Georgia, serif;
        }

        /* Glassmorphism Styles */
        .glass-panel {
            background: rgba(12, 38, 30, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(212, 175, 55, 0.22);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
        }

        .glass-card {
            background: rgba(15, 46, 36, 0.75);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(212, 175, 55, 0.18);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            border-radius: 1.5rem;
        }

        .glass-card-sm {
            background: rgba(18, 54, 42, 0.65);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(212, 175, 55, 0.15);
            border-radius: 1rem;
        }

        .neo-base {
            background-color: #061812 !important;
        }

        .neo-card {
            background: rgba(15, 46, 36, 0.85) !important;
            border-radius: 1.25rem !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.4) !important;
            border: 1px solid rgba(212, 175, 55, 0.2) !important;
            color: #f1f5f9 !important;
        }

        .neo-btn {
            background: rgba(20, 60, 47, 0.9) !important;
            border-radius: 0.75rem !important;
            border: 1px solid rgba(212, 175, 55, 0.3) !important;
            color: #fbbf24 !important;
            transition: all 0.2s ease !important;
        }

        .neo-btn:hover {
            background-color: rgba(212, 175, 55, 0.2) !important;
            border-color: #f59e0b !important;
            color: #ffffff !important;
        }

        /* Gold Glow Highlights */
        .gold-glow {
            box-shadow: 0 0 25px rgba(212, 175, 55, 0.25);
        }

        .gold-border {
            border-color: rgba(212, 175, 55, 0.35) !important;
        }

        .gold-gradient-text {
            background: linear-gradient(135deg, #fef08a 0%, #d4af37 50%, #b45309 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        input, textarea, select {
            color: #f8fafc !important;
            background-color: rgba(6, 24, 18, 0.9) !important;
            border-color: rgba(212, 175, 55, 0.3) !important;
        }
        label { color: #cbd5e1 !important; }
    </style>
</head>
<body class="bg-[#061812] text-slate-100 min-h-screen" x-data="{ sidebarOpen: false }">
    @php
        $unreadCount = \App\Models\AdminNotification::unread()->count();
    @endphp

    <div class="flex min-h-screen relative">
        @include('admin.media.partials.picker-modal')

        {{-- Mobile Backdrop --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/80 z-40 lg:hidden backdrop-blur-sm" x-cloak></div>

        <!-- SECTION 1: Dark Emerald & Gold Crest Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="w-72 fixed lg:relative lg:translate-x-0 inset-y-0 left-0 z-50 flex flex-col bg-[#071d16] border-r border-[#d4af37]/25 transition-transform duration-300 shadow-2xl shrink-0">

            {{-- Brand Crest --}}
            <div class="p-6 border-b border-[#d4af37]/20 flex items-center justify-between bg-[#051610]/80">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-amber-400 via-amber-500 to-amber-700 flex items-center justify-center font-black text-xl text-stone-950 shadow-lg gold-glow group-hover:scale-105 transition-transform">
                        🦅
                    </div>
                    <div>
                        <h1 class="font-serif text-lg font-black text-amber-300 tracking-wide leading-tight group-hover:text-amber-200 transition-colors">
                            Twina Safaris
                        </h1>
                        <p class="text-[10px] text-amber-400/70 font-bold uppercase tracking-widest">Admin Command</p>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-stone-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Sidebar Navigation --}}
            <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto no-scrollbar">
                @php
                    $sidebarLinks = [
                        ['route' => 'admin.dashboard', 'label' => 'Dashboard Overview', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                        ['route' => 'admin.bookings.index', 'label' => 'Safari Bookings', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                        ['route' => 'admin.tours.index', 'label' => 'Tour Packages', 'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z'],
                        ['route' => 'admin.proposals.index', 'label' => 'Client Proposals', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                        ['route' => 'admin.proposal-templates.index', 'label' => 'Proposal Templates', 'icon' => 'M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2'],
                        ['route' => 'admin.custom-inquiries.index', 'label' => 'Custom Safari Inquiries', 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
                        ['route' => 'admin.ai-search.dashboard', 'label' => 'AI Search Intelligence', 'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
                        ['route' => 'admin.sliders.index', 'label' => 'Home Hero Sliders', 'icon' => 'M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z'],
                        ['route' => 'admin.media.index', 'label' => 'Media Manager', 'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                        ['route' => 'admin.categories.index', 'label' => 'Safari Categories', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2'],
                        ['route' => 'admin.destinations.index', 'label' => 'Destinations', 'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z'],
                        ['route' => 'admin.gallery.index', 'label' => 'Photo Gallery', 'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16'],
                        ['route' => 'admin.testimonials.index', 'label' => 'Guest Reviews & Testimonials', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
                        ['route' => 'admin.faqs.index', 'label' => 'FAQs Management', 'icon' => 'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['route' => 'admin.blog.index', 'label' => 'Blog Articles', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'],
                        ['route' => 'admin.marketing.index', 'label' => 'Marketing & Leads', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'],
                        ['route' => 'admin.settings.index', 'label' => 'System Settings', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
                    ];
                @endphp

                @foreach($sidebarLinks as $link)
                    @php $isActive = request()->routeIs($link['route'] . '*'); @endphp
                    <a href="{{ route($link['route']) }}"
                       class="flex items-center gap-3 px-4 py-2.5 text-xs font-semibold rounded-xl transition-all duration-200 {{ $isActive ? 'bg-gradient-to-r from-amber-500/20 via-amber-500/10 to-transparent text-amber-300 font-bold border-l-4 border-amber-400 gold-glow' : 'text-slate-300 hover:text-white hover:bg-white/5' }}">
                        <svg class="w-4 h-4 {{ $isActive ? 'text-amber-400' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}" /></svg>
                        <span>{{ $link['label'] }}</span>
                    </a>
                @endforeach
            </nav>

            {{-- Bottom Kilimanjaro Promotional Card (Mockup Requirement 1) --}}
            <div class="p-4 m-4 rounded-2xl bg-gradient-to-br from-amber-500/20 via-[#0a2f24] to-[#04120d] border border-[#d4af37]/30 text-white relative overflow-hidden shadow-lg shrink-0">
                <div class="absolute right-0 top-0 w-24 h-24 bg-amber-400/10 rounded-full blur-xl pointer-events-none"></div>
                <div class="relative z-10">
                    <span class="text-[9px] font-black uppercase tracking-widest text-amber-400 block mb-1">🏔️ Expedition Status</span>
                    <h4 class="font-serif font-bold text-sm text-white leading-tight">Kilimanjaro Trekking</h4>
                    <p class="text-[11px] text-slate-300 mt-1 leading-snug">All 8 mountain routes operating normally.</p>
                    <a href="{{ route('admin.tours.index') }}" class="mt-3 inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-amber-300 hover:text-amber-200">
                        <span>View Expeditions</span>
                        <span>→</span>
                    </a>
                </div>
            </div>

            {{-- User Session Bar --}}
            <div class="p-4 border-t border-[#d4af37]/20 bg-[#051610] text-slate-200">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 text-stone-950 font-black text-xs flex items-center justify-center shrink-0 gold-glow">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 truncate text-xs">
                        <span class="font-bold text-white block truncate">{{ auth()->user()->name }}</span>
                        <span class="text-[10px] text-amber-400/80 uppercase font-bold">Administrator</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-slate-400 hover:text-rose-400 transition-colors p-1" title="Sign Out">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 min-h-screen overflow-y-auto w-full flex flex-col bg-[#061812]">

            <!-- SECTION 2: Top Glassmorphism Navigation Bar -->
            <header class="bg-[#09221a]/90 border-b border-[#d4af37]/20 px-6 lg:px-10 py-4 flex items-center justify-between sticky top-0 z-30 backdrop-blur-md shadow-lg">
                <div class="flex items-center gap-4 flex-1">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 text-amber-400 rounded-xl bg-amber-500/10 border border-amber-500/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>

                    {{-- Search Input (Mockup Requirement 2) --}}
                    <div class="relative max-w-md w-full hidden sm:block">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            🔍
                        </span>
                        <input type="text"
                               placeholder="Search safari bookings, tour packages, leads..."
                               class="w-full bg-[#061812]/90 border border-[#d4af37]/30 rounded-2xl pl-10 pr-4 py-2.5 text-xs text-white placeholder-slate-400 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 shadow-inner">
                    </div>
                </div>

                {{-- Right Navigation Items --}}
                <div class="flex items-center gap-4 shrink-0">
                    {{-- Online Status Indicator --}}
                    <div class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-950/80 border border-emerald-500/30 text-emerald-400 text-xs font-bold">
                        <span class="relative flex h-2.5 w-2.5">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>
                        <span>System Online</span>
                    </div>

                    {{-- Notifications Bell --}}
                    <a href="{{ route('admin.notifications.index') }}" class="relative p-2.5 rounded-2xl bg-[#0e3025] border border-[#d4af37]/25 text-amber-300 hover:text-white hover:border-amber-400 transition-all gold-glow">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        @if($unreadCount > 0)
                            <span class="absolute -top-1 -right-1 bg-amber-500 text-stone-950 text-[10px] font-black w-5 h-5 rounded-full flex items-center justify-center border-2 border-[#061812] shadow-sm">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </a>

                    {{-- Administrator Profile --}}
                    <div class="flex items-center gap-3 pl-2 border-l border-slate-700/60">
                        <div class="w-9 h-9 rounded-2xl bg-gradient-to-tr from-amber-400 to-amber-600 text-stone-950 font-black text-sm flex items-center justify-center shadow-md gold-glow">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="hidden xl:block text-left leading-tight">
                            <span class="font-bold text-xs text-white block">{{ auth()->user()->name }}</span>
                            <span class="text-[10px] text-amber-400 font-semibold">Super Admin</span>
                        </div>
                    </div>

                    {{-- Public Site Link --}}
                    <a href="{{ route('home') }}" target="_blank" class="hidden sm:flex items-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-stone-950 font-black text-xs uppercase tracking-wider shadow-md transition-all hover:scale-105">
                        <span>🌐 Public Site</span>
                    </a>
                </div>
            </header>

            {{-- Main Content View Body --}}
            <div class="p-6 lg:p-10 flex-1 space-y-8">
                @if (session('success'))
                    <div class="p-4 rounded-2xl bg-emerald-950/80 border border-emerald-500/40 text-emerald-300 font-bold text-xs uppercase tracking-wider flex items-center gap-3 shadow-lg">
                        <span class="text-emerald-400 text-base">✓</span>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @yield('content')
            </div>

            <!-- SECTION 7: System Status Bar Footer (Mockup Requirement 7) -->
            <footer class="bg-[#04120d] border-t border-[#d4af37]/20 px-6 py-4 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-400">
                <div class="flex items-center gap-3">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="font-medium text-slate-300">
                        System Status: <strong class="text-emerald-400">Operational</strong> • Twina Safaris Admin v3.4 (Laravel {{ app()->version() }})
                    </span>
                </div>
                <div class="flex items-center gap-6 text-[11px]">
                    <span>Last Synced: <strong class="text-amber-400">{{ now()->format('H:i:s T') }}</strong></span>
                    <a href="{{ route('admin.dashboard') }}" class="text-amber-400 hover:text-amber-300 font-bold flex items-center gap-1 transition-colors">
                        <span>🔄 Refresh Metrics</span>
                    </a>
                </div>
            </footer>
        </main>
    </div>
</body>
</html>
