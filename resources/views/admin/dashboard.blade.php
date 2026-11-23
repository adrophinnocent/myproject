@extends('admin.layouts.app')

@section('page-title', 'Dashboard Command Center')

@section('content')
<div class="space-y-8">

    <!-- SECTION 3: Welcome Hero Banner (Serengeti / Kilimanjaro Landscape Backdrop) -->
    <div class="relative rounded-3xl overflow-hidden border border-[#d4af37]/30 shadow-2xl gold-glow">
        {{-- High-resolution Safari & Kilimanjaro landscape background image --}}
        <div class="absolute inset-0 bg-cover bg-center transition-transform duration-1000 scale-105"
             style="background-image: url('https://images.unsplash.com/photo-1516426122078-c23e76319801?auto=format&fit=crop&w=1800&q=80');">
        </div>
        {{-- Dark Emerald Luxury Gradient Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-r from-[#04120d] via-[#08281e]/95 to-emerald-950/70"></div>

        <div class="relative z-10 p-8 lg:p-12 flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="space-y-3 max-w-2xl">
                <div class="inline-flex items-center gap-2.5 px-3.5 py-1 rounded-full bg-[#d4af37]/20 border border-[#d4af37]/40 text-amber-300 text-xs font-bold uppercase tracking-widest backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
                    <span>Twina Safaris Command Center</span>
                </div>
                <h1 class="font-serif text-3xl lg:text-5xl font-black tracking-tight text-white leading-tight">
                    Jambo & Welcome back, <span class="gold-gradient-text">{{ auth()->user()->name }}</span>!
                </h1>
                <p class="text-slate-300 text-xs lg:text-sm font-medium leading-relaxed">
                    Real-time expedition metrics, booking revenue, tour performance, and tourist search intelligence for Twina Safaris Tanzania.
                </p>

                <div class="pt-2 flex flex-wrap items-center gap-4 text-xs font-bold">
                    <a href="{{ route('admin.bookings.index') }}" class="px-6 py-3 rounded-2xl bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-stone-950 font-black uppercase tracking-wider shadow-lg transition-all hover:scale-105">
                        Manage Bookings
                    </a>
                    <a href="{{ route('admin.tours.create') }}" class="px-6 py-3 rounded-2xl bg-[#092b20]/90 hover:bg-[#0d3b2c] border border-[#d4af37]/40 text-amber-300 font-bold uppercase tracking-wider transition-all">
                        + New Tour Package
                    </a>
                </div>
            </div>

            {{-- Right Side Crest & Live Date --}}
            <div class="hidden md:flex flex-col items-end justify-center shrink-0">
                <div class="p-4 rounded-3xl bg-[#071d16]/80 border border-[#d4af37]/30 backdrop-blur-md text-right space-y-2 gold-glow">
                    <div class="flex items-center justify-end gap-2 text-amber-400 font-serif font-bold text-lg">
                        <span>🦅 Twina Safaris</span>
                    </div>
                    <div class="text-xs text-slate-300 font-medium">
                        {{ now()->format('l, F j, Y') }}
                    </div>
                    <div class="text-[10px] text-amber-400/80 font-mono uppercase tracking-widest">
                        Tanzania E.A.T (UTC+3)
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 4: Statistics Cards (4 Horizontal Cards in 1 Row) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        {{-- Card 1: Total Revenue (Gold) --}}
        <div class="glass-card p-6 relative overflow-hidden group hover:border-[#d4af37]/60 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-black uppercase tracking-wider text-amber-400">Total Revenue</span>
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-amber-400/20 to-amber-600/30 border border-amber-500/40 flex items-center justify-center text-amber-300 shadow-md">
                    💰
                </div>
            </div>
            <div class="mt-4">
                <div class="text-3xl font-black font-serif text-white tracking-tight">${{ number_format($kpis['revenue_total'], 2) }}</div>
                <div class="mt-2 flex items-center gap-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-950 text-emerald-400 border border-emerald-500/30">
                        ↑ +14.2%
                    </span>
                    <span class="text-[11px] text-slate-400 font-medium">vs last month</span>
                </div>
            </div>
            {{-- Miniature Sparkline SVG --}}
            <div class="mt-4 pt-3 border-t border-[#d4af37]/15 flex items-center justify-between">
                <span class="text-[10px] text-slate-400">Month: ${{ number_format($kpis['revenue_month']) }}</span>
                <svg class="w-20 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 100 30">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M0 25 L20 18 L40 22 L60 10 L80 14 L100 4" />
                </svg>
            </div>
        </div>

        {{-- Card 2: Active Safari Bookings (Emerald) --}}
        <div class="glass-card p-6 relative overflow-hidden group hover:border-emerald-500/60 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-black uppercase tracking-wider text-emerald-400">Safari Bookings</span>
                <div class="w-10 h-10 rounded-2xl bg-emerald-500/20 border border-emerald-500/40 flex items-center justify-center text-emerald-300 shadow-md">
                    📋
                </div>
            </div>
            <div class="mt-4">
                <div class="text-3xl font-black font-serif text-white tracking-tight">{{ number_format($kpis['total_bookings']) }}</div>
                <div class="mt-2 flex items-center gap-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-950 text-emerald-400 border border-emerald-500/30">
                        {{ $kpis['confirmed_bookings'] }} Confirmed
                    </span>
                    <span class="text-[11px] text-slate-400 font-medium">{{ $kpis['pending_bookings'] }} Pending</span>
                </div>
            </div>
            {{-- Miniature Sparkline SVG --}}
            <div class="mt-4 pt-3 border-t border-[#d4af37]/15 flex items-center justify-between">
                <span class="text-[10px] text-slate-400">Growth: +{{ $kpis['bookings_growth'] }}%</span>
                <svg class="w-20 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 100 30">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M0 20 L25 15 L50 25 L75 8 L100 2" />
                </svg>
            </div>
        </div>

        {{-- Card 3: Published Tour Packages (Purple) --}}
        <div class="glass-card p-6 relative overflow-hidden group hover:border-purple-500/60 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-black uppercase tracking-wider text-purple-400">Tour Packages</span>
                <div class="w-10 h-10 rounded-2xl bg-purple-500/20 border border-purple-500/40 flex items-center justify-center text-purple-300 shadow-md">
                    🏕️
                </div>
            </div>
            <div class="mt-4">
                <div class="text-3xl font-black font-serif text-white tracking-tight">{{ number_format($kpis['active_tours']) }}</div>
                <div class="mt-2 flex items-center gap-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-purple-950 text-purple-300 border border-purple-500/30">
                        Live Packages
                    </span>
                    <span class="text-[11px] text-slate-400 font-medium">Out of {{ $kpis['total_tours'] }} Total</span>
                </div>
            </div>
            {{-- Miniature Sparkline SVG --}}
            <div class="mt-4 pt-3 border-t border-[#d4af37]/15 flex items-center justify-between">
                <span class="text-[10px] text-slate-400">Kilimanjaro & Safaris</span>
                <svg class="w-20 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 100 30">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M0 28 L30 18 L60 22 L80 12 L100 6" />
                </svg>
            </div>
        </div>

        {{-- Card 4: Conversion & Inquiry Rate (Blue) --}}
        <div class="glass-card p-6 relative overflow-hidden group hover:border-sky-500/60 transition-all">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-black uppercase tracking-wider text-sky-400">Conversion Rate</span>
                <div class="w-10 h-10 rounded-2xl bg-sky-500/20 border border-sky-500/40 flex items-center justify-center text-sky-300 shadow-md">
                    📊
                </div>
            </div>
            <div class="mt-4">
                <div class="text-3xl font-black font-serif text-white tracking-tight">{{ $kpis['conversion_rate'] }}%</div>
                <div class="mt-2 flex items-center gap-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-sky-950 text-sky-300 border border-sky-500/30">
                        Inquiries to Booking
                    </span>
                    <span class="text-[11px] text-slate-400 font-medium">{{ $kpis['inquiries'] }} Monthly</span>
                </div>
            </div>
            {{-- Miniature Sparkline SVG --}}
            <div class="mt-4 pt-3 border-t border-[#d4af37]/15 flex items-center justify-between">
                <span class="text-[10px] text-slate-400">Direct & WhatsApp Leads</span>
                <svg class="w-20 h-6 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 100 30">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M0 25 L35 20 L65 10 L85 15 L100 4" />
                </svg>
            </div>
        </div>

    </div>

    <!-- SECTION 5: Main Analytics Section (3-Column Layout: Line Chart, Doughnut Chart, Recent Notifications) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Column 1: Bookings & Revenue Overview (Large Line Chart - lg:col-span-6) --}}
        <div class="lg:col-span-6 glass-card p-6 md:p-8 flex flex-col justify-between">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-[#d4af37]/20">
                <div>
                    <h3 class="font-serif font-bold text-xl text-white">Bookings & Revenue Overview</h3>
                    <p class="text-xs text-slate-400 mt-0.5">12-Month income trend & booking volume analytics</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-400 px-3 py-1 rounded-full bg-emerald-950 border border-emerald-500/30">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        Revenue ($)
                    </span>
                </div>
            </div>

            <div class="relative h-72 w-full">
                <canvas id="revenueLineChart"></canvas>
            </div>
        </div>

        {{-- Column 2: Popular Destinations (Circular Doughnut Chart - lg:col-span-3) --}}
        <div class="lg:col-span-3 glass-card p-6 flex flex-col justify-between">
            <div class="mb-4 pb-3 border-b border-[#d4af37]/20">
                <h3 class="font-serif font-bold text-lg text-white">Popular Destinations</h3>
                <p class="text-xs text-slate-400 mt-0.5">Booking distribution %</p>
            </div>

            <div class="relative h-56 w-full my-auto flex items-center justify-center">
                <canvas id="destinationsDoughnutChart"></canvas>
            </div>

            <div class="grid grid-cols-2 gap-2 pt-4 border-t border-[#d4af37]/15 text-[11px] font-medium">
                <div class="flex items-center gap-2 text-slate-300">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <span>Serengeti (42%)</span>
                </div>
                <div class="flex items-center gap-2 text-slate-300">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                    <span>Kilimanjaro (28%)</span>
                </div>
                <div class="flex items-center gap-2 text-slate-300">
                    <span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span>
                    <span>Ngorongoro (18%)</span>
                </div>
                <div class="flex items-center gap-2 text-slate-300">
                    <span class="w-2.5 h-2.5 rounded-full bg-sky-400"></span>
                    <span>Zanzibar (12%)</span>
                </div>
            </div>
        </div>

        {{-- Column 3: Recent Notifications & System Activity (lg:col-span-3) --}}
        <div class="lg:col-span-3 glass-card p-6 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-[#d4af37]/20">
                    <h3 class="font-serif font-bold text-lg text-white">Recent Notifications</h3>
                    <a href="{{ route('admin.notifications.index') }}" class="text-[11px] font-bold text-amber-400 hover:underline">View All</a>
                </div>

                @php
                    $latestNotifications = \App\Models\AdminNotification::latest()->take(5)->get();
                @endphp

                <div class="space-y-3.5">
                    @forelse($latestNotifications as $notif)
                        <div class="p-3 rounded-2xl bg-[#08221a] border border-[#d4af37]/20 text-xs hover:border-amber-400/50 transition-all flex items-start gap-3">
                            <div class="w-7 h-7 rounded-xl bg-amber-500/20 text-amber-300 flex items-center justify-center shrink-0 mt-0.5 text-xs font-bold">
                                🔔
                            </div>
                            <div class="overflow-hidden">
                                <h5 class="font-bold text-slate-100 text-xs leading-snug truncate">{{ $notif->title }}</h5>
                                <p class="text-slate-400 text-[11px] line-clamp-1 mt-0.5">{{ $notif->message }}</p>
                                <span class="text-[9px] text-amber-400/80 font-mono mt-1 block">{{ $notif->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 rounded-2xl bg-[#08221a] border border-slate-800 text-slate-400 text-xs italic text-center">
                            No recent notifications.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="pt-4 mt-4 border-t border-[#d4af37]/15">
                <form method="POST" action="{{ route('admin.notifications.mark-all-read') }}">
                    @csrf
                    <button type="submit" class="w-full block text-center py-2.5 rounded-xl bg-gradient-to-r from-amber-500/20 to-amber-600/20 hover:from-amber-500/30 text-amber-300 font-bold text-xs border border-amber-500/30 transition-all">
                        Mark All Notifications Read
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- SECTION 6: Bottom Section (3 Cards: Tours Performance, Customer Sources, Upcoming Bookings) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Card 1: Tours Performance --}}
        <div class="glass-card p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-[#d4af37]/20">
                <h3 class="font-serif font-bold text-lg text-white">Tours Performance</h3>
                <a href="{{ route('admin.tours.index') }}" class="text-xs font-bold text-amber-400 hover:underline">All Tours</a>
            </div>

            <div class="space-y-3">
                @foreach($topTours as $t)
                    <div class="p-3.5 rounded-2xl bg-[#08221a] border border-[#d4af37]/15 hover:border-amber-400/40 transition-all">
                        <div class="flex items-center justify-between mb-1">
                            <h5 class="font-bold text-slate-100 text-xs truncate max-w-[200px]">{{ $t->title }}</h5>
                            <span class="text-amber-400 font-black text-xs">${{ number_format($t->bookings_sum_total_price ?? 0) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-[11px] text-slate-400">
                            <span>{{ $t->bookings_count }} Bookings</span>
                            <span class="text-amber-400 font-bold">★ 5.0</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Card 2: Customer Acquisition Sources --}}
        <div class="glass-card p-6 space-y-4">
            <div class="pb-3 border-b border-[#d4af37]/20">
                <h3 class="font-serif font-bold text-lg text-white">Customer Acquisition Sources</h3>
                <p class="text-xs text-slate-400 mt-0.5">Booking channel metrics</p>
            </div>

            <div class="space-y-4 pt-1 text-xs font-medium">
                <div>
                    <div class="flex justify-between text-slate-200 mb-1.5">
                        <span>Direct Website Bookings</span>
                        <span class="font-bold text-emerald-400">55%</span>
                    </div>
                    <div class="w-full h-2 rounded-full bg-slate-800 overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full" style="width: 55%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-slate-200 mb-1.5">
                        <span>WhatsApp & Direct Chat</span>
                        <span class="font-bold text-amber-400">25%</span>
                    </div>
                    <div class="w-full h-2 rounded-full bg-slate-800 overflow-hidden">
                        <div class="h-full bg-amber-400 rounded-full" style="width: 25%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-slate-200 mb-1.5">
                        <span>Google Search & AI Intelligence</span>
                        <span class="font-bold text-purple-400">12%</span>
                    </div>
                    <div class="w-full h-2 rounded-full bg-slate-800 overflow-hidden">
                        <div class="h-full bg-purple-500 rounded-full" style="width: 12%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-slate-200 mb-1.5">
                        <span>Agent & Partner Referrals</span>
                        <span class="font-bold text-sky-400">8%</span>
                    </div>
                    <div class="w-full h-2 rounded-full bg-slate-800 overflow-hidden">
                        <div class="h-full bg-sky-400 rounded-full" style="width: 8%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 3: Upcoming Safari Bookings --}}
        <div class="glass-card p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-[#d4af37]/20">
                <h3 class="font-serif font-bold text-lg text-white">Upcoming Bookings</h3>
                <a href="{{ route('admin.bookings.index') }}" class="text-xs font-bold text-amber-400 hover:underline">View All</a>
            </div>

            <div class="space-y-3">
                @forelse($recentBooks->take(4) as $rb)
                    <div class="p-3.5 rounded-2xl bg-[#08221a] border border-[#d4af37]/15 text-xs hover:border-amber-400/40 transition-all">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-bold text-slate-100">{{ $rb->first_name }} {{ $rb->last_name }}</span>
                            <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase
                                {{ $rb->status === 'confirmed' ? 'bg-emerald-950 text-emerald-400 border border-emerald-500/30' : '' }}
                                {{ $rb->status === 'pending' ? 'bg-amber-950 text-amber-400 border border-amber-500/30' : '' }}
                                {{ $rb->status === 'completed' ? 'bg-purple-950 text-purple-400 border border-purple-500/30' : '' }}">
                                {{ $rb->status }}
                            </span>
                        </div>
                        <p class="text-slate-400 text-[11px] truncate">{{ $rb->tour->title ?? ($rb->safari->title ?? 'Custom Safari') }}</p>
                        <div class="flex justify-between text-[10px] text-slate-400 mt-2">
                            <span>Travel: {{ $rb->start_date ? $rb->start_date->format('M d, Y') : $rb->created_at->format('M d, Y') }}</span>
                            <span class="text-amber-400 font-bold">${{ number_format($rb->total_price) }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 italic py-6 text-center">No upcoming safari bookings.</p>
                @endforelse
            </div>
        </div>

    </div>

</div>

{{-- Chart Initialization Scripts --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Line Chart: Revenue & Bookings Overview
    const ctxRevenue = document.getElementById('revenueLineChart').getContext('2d');

    const gradientRevenue = ctxRevenue.createLinearGradient(0, 0, 0, 300);
    gradientRevenue.addColorStop(0, 'rgba(16, 185, 129, 0.4)');
    gradientRevenue.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

    const gradientGold = ctxRevenue.createLinearGradient(0, 0, 0, 300);
    gradientGold.addColorStop(0, 'rgba(212, 175, 55, 0.3)');
    gradientGold.addColorStop(1, 'rgba(212, 175, 55, 0.0)');

    const revenueLabels = @json($revenueData['labels']);
    const revenueValues = @json($revenueData['revenues']);

    new Chart(ctxRevenue, {
        type: 'line',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Monthly Revenue ($)',
                data: revenueValues,
                borderColor: '#10b981',
                borderWidth: 3,
                backgroundColor: gradientRevenue,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#d4af37',
                pointBorderColor: '#061812',
                pointRadius: 4,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(212, 175, 55, 0.08)' },
                    ticks: { color: '#94a3b8', font: { size: 11 } }
                },
                y: {
                    grid: { color: 'rgba(212, 175, 55, 0.08)' },
                    ticks: {
                        color: '#94a3b8',
                        font: { size: 11 },
                        callback: function(val) { return '$' + val; }
                    }
                }
            }
        }
    });

    // 2. Doughnut Chart: Popular Destinations
    const ctxDestinations = document.getElementById('destinationsDoughnutChart').getContext('2d');
    new Chart(ctxDestinations, {
        type: 'doughnut',
        data: {
            labels: ['Serengeti', 'Kilimanjaro', 'Ngorongoro', 'Zanzibar'],
            datasets: [{
                data: [42, 28, 18, 12],
                backgroundColor: ['#10b981', '#fbbf24', '#8b5cf6', '#38bdf8'],
                borderColor: '#071d16',
                borderWidth: 3,
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            cutout: '72%'
        }
    });
});
</script>
@endsection
