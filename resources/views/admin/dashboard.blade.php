@extends('admin.layouts.app')

@section('title', 'Analytics Dashboard')
@section('page-title', 'Analytics Dashboard')

@section('content')
<div class="space-y-10">
    <!-- Tabs Navigation -->
    <div class="neo-card p-2 inline-flex gap-2">
        <button onclick="showTab('dashboard')" id="tab-dashboard" class="tab-btn neo-btn py-3 px-8 text-sm font-extrabold uppercase tracking-wider text-amber-600 neo-btn-active">
            Dashboard
        </button>
        <button onclick="showTab('tours')" id="tab-tours" class="tab-btn neo-btn py-3 px-8 text-sm font-extrabold uppercase tracking-wider text-gray-500">
            Tours
        </button>
        <button onclick="showTab('revenue')" id="tab-revenue" class="tab-btn neo-btn py-3 px-8 text-sm font-extrabold uppercase tracking-wider text-gray-500">
            Revenue
        </button>
        <button onclick="showTab('bookings')" id="tab-bookings" class="tab-btn neo-btn py-3 px-8 text-sm font-extrabold uppercase tracking-wider text-gray-500">
            Bookings
        </button>
    </div>

    <!-- Dashboard Tab -->
    <div id="content-dashboard" class="tab-content space-y-10">
        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8">
            {{-- Marketing Boost Card --}}
            <div class="neo-card p-6 bg-gradient-to-br from-amber-500 to-amber-700 text-white border-none relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative z-10">
                    <div class="text-[10px] font-black uppercase tracking-widest opacity-80 mb-4">Ad Campaign Leads</div>
                    <div class="text-3xl font-black">{{ $marketingLeads }}</div>
                    <div class="text-[10px] mt-1 font-bold">From {{ $marketingVisits }} total ad visits</div>
                </div>
            </div>
            <div class="neo-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-gray-500 text-[10px] font-black uppercase tracking-widest">Revenue Today</div>
                    <div class="w-8 h-8 neo-inset rounded-full flex items-center justify-center">
                        <span class="text-green-600 text-xs">💰</span>
                    </div>
                </div>
                <div class="text-2xl font-black text-gray-800">${{ number_format($kpis['revenue_today'], 2) }}</div>
            </div>
            <div class="neo-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-gray-500 text-[10px] font-black uppercase tracking-widest">Revenue Week</div>
                    <div class="w-8 h-8 neo-inset rounded-full flex items-center justify-center">
                        <span class="text-blue-600 text-xs">📈</span>
                    </div>
                </div>
                <div class="text-2xl font-black text-gray-800">${{ number_format($kpis['revenue_week'], 2) }}</div>
            </div>
            <div class="neo-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-gray-500 text-[10px] font-black uppercase tracking-widest">Revenue Month</div>
                    <div class="w-8 h-8 neo-inset rounded-full flex items-center justify-center">
                        <span class="text-purple-600 text-xs">🗓️</span>
                    </div>
                </div>
                <div class="text-2xl font-black text-gray-800">${{ number_format($kpis['revenue_month'], 2) }}</div>
            </div>
            <div class="neo-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-gray-500 text-[10px] font-black uppercase tracking-widest">Revenue Year</div>
                    <div class="w-8 h-8 neo-inset rounded-full flex items-center justify-center">
                        <span class="text-orange-600 text-xs">🏛️</span>
                    </div>
                </div>
                <div class="text-2xl font-black text-gray-800">${{ number_format($kpis['revenue_year'], 2) }}</div>
            </div>
            <div class="neo-card p-6 border-2 border-amber-500/10">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-amber-600 text-[10px] font-black uppercase tracking-widest">Total Revenue</div>
                    <div class="w-8 h-8 neo-inset rounded-full flex items-center justify-center">
                        <span class="text-amber-600 text-xs">💎</span>
                    </div>
                </div>
                <div class="text-2xl font-black text-amber-600">${{ number_format($kpis['revenue_total'], 2) }}</div>
            </div>
        </div>

        <!-- Bookings Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="neo-card p-6 flex items-center gap-5">
                <div class="w-14 h-14 neo-inset rounded-2xl flex items-center justify-center text-2xl">⏳</div>
                <div>
                    <div class="text-gray-500 text-[10px] font-black uppercase tracking-widest">Pending</div>
                    <div class="text-3xl font-black text-gray-800">{{ $kpis['pending_bookings'] }}</div>
                </div>
            </div>
            <div class="neo-card p-6 flex items-center gap-5">
                <div class="w-14 h-14 neo-inset rounded-2xl flex items-center justify-center text-2xl">✅</div>
                <div>
                    <div class="text-gray-500 text-[10px] font-black uppercase tracking-widest">Confirmed</div>
                    <div class="text-3xl font-black text-gray-800">{{ $kpis['confirmed_bookings'] }}</div>
                </div>
            </div>
            <div class="neo-card p-6 flex items-center gap-5">
                <div class="w-14 h-14 neo-inset rounded-2xl flex items-center justify-center text-2xl">🏆</div>
                <div>
                    <div class="text-gray-500 text-[10px] font-black uppercase tracking-widest">Completed</div>
                    <div class="text-3xl font-black text-gray-800">{{ $kpis['completed_bookings'] }}</div>
                </div>
            </div>
            <div class="neo-card p-6 flex items-center gap-5">
                <div class="w-14 h-14 neo-inset rounded-2xl flex items-center justify-center text-2xl">❌</div>
                <div>
                    <div class="text-gray-500 text-[10px] font-black uppercase tracking-widest">Cancelled</div>
                    <div class="text-3xl font-black text-gray-800">{{ $kpis['cancelled_bookings'] }}</div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <div class="neo-card p-8">
                <h3 class="text-sm font-black uppercase tracking-widest text-gray-700 mb-8 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500"></span> Monthly Revenue Trend
                </h3>
                <div class="neo-inset p-4 rounded-3xl">
                    <canvas id="revenueChart" height="280"></canvas>
                </div>
            </div>
            <div class="neo-card p-8">
                <h3 class="text-sm font-black uppercase tracking-widest text-gray-700 mb-8 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span> Monthly Bookings Trend
                </h3>
                <div class="neo-inset p-4 rounded-3xl">
                    <canvas id="bookingsChart" height="280"></canvas>
                </div>
            </div>
        </div>

        <!-- Distribution & Recent -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <div class="neo-card p-8">
                <h3 class="text-sm font-black uppercase tracking-widest text-gray-700 mb-8">Status Distribution</h3>
                <div class="neo-inset p-6 rounded-full aspect-square flex items-center justify-center">
                    <canvas id="bookingPieChart"></canvas>
                </div>
            </div>
            <div class="lg:col-span-2 neo-card p-8">
                <h3 class="text-sm font-black uppercase tracking-widest text-gray-700 mb-8">Recent Bookings</h3>
                <div class="space-y-6">
                    @foreach($recentBooks->take(5) as $booking)
                        <div class="neo-card-sm p-5 flex items-center justify-between">
                            <div class="flex items-center gap-5">
                                <div class="w-12 h-12 neo-inset rounded-xl flex items-center justify-center font-black text-brand-600">
                                    {{ substr($booking->full_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-black text-gray-800">{{ $booking->full_name }}</div>
                                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ $booking->booking_reference }}</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="text-right">
                                    <div class="text-xs font-black text-gray-800">${{ number_format($booking->total_price, 2) }}</div>
                                    <div class="text-[9px] font-black uppercase text-amber-600 mb-1">{{ $booking->status }}</div>
                                </div>

                                <div class="flex items-center gap-1">
                                    {{-- View Details --}}
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="w-9 h-9 neo-btn flex items-center justify-center text-blue-500 hover:text-blue-700 transition-all" title="View Details">
                                        👁️
                                    </a>

                                    {{-- Quick Confirm --}}
                                    @if($booking->status === 'pending')
                                    <form action="{{ route('admin.bookings.update-status', $booking) }}" method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="confirmed">
                                        <button type="submit" class="w-9 h-9 neo-btn flex items-center justify-center text-green-500 hover:text-green-700 transition-all" title="Confirm Booking">
                                            ✅
                                        </button>
                                    </form>
                                    @endif

                                    {{-- Quick Cancel --}}
                                    @if($booking->status !== 'cancelled')
                                    <form action="{{ route('admin.bookings.update-status', $booking) }}" method="POST" class="inline" onsubmit="return confirm('Cancel this booking?')">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="cancelled">
                                        <button type="submit" class="w-9 h-9 neo-btn flex items-center justify-center text-red-500 hover:text-red-700 transition-all" title="Cancel Booking">
                                            ❌
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Tours Tab Content -->
    <div id="content-tours" class="tab-content hidden space-y-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <div class="neo-card p-8">
                <h3 class="text-sm font-black uppercase tracking-widest text-gray-700 mb-8">Tour Performance</h3>
                <div class="neo-inset p-4 rounded-3xl">
                    <canvas id="tourPerformanceChart" height="280"></canvas>
                </div>
            </div>
            <div class="neo-card p-8">
                <h3 class="text-sm font-black uppercase tracking-widest text-gray-700 mb-8">Top Revenue Tours</h3>
                <div class="space-y-4">
                    @foreach($topTours as $tour)
                        <div class="neo-card-sm p-4 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 neo-inset rounded-lg flex items-center justify-center text-xs font-black text-amber-600">
                                    {{ $loop->iteration }}
                                </div>
                                <span class="text-xs font-bold text-gray-700">{{ $tour->title }}</span>
                            </div>
                            <span class="text-xs font-black text-green-600">${{ number_format($tour->bookings_sum_total_price, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Tab Content -->
    <div id="content-revenue" class="tab-content hidden space-y-10">
        <div class="neo-card p-8">
             <h3 class="text-sm font-black uppercase tracking-widest text-gray-700 mb-8">Detailed Revenue Growth</h3>
             <div class="neo-inset p-6 rounded-3xl">
                <canvas id="revenueChart2" height="350"></canvas>
             </div>
        </div>
    </div>

    <!-- Bookings Tab Content -->
    <div id="content-bookings" class="tab-content hidden space-y-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <div class="neo-card p-8">
                <h3 class="text-sm font-black uppercase tracking-widest text-gray-700 mb-8">Monthly Volume</h3>
                <div class="neo-inset p-4 rounded-3xl">
                    <canvas id="bookingsChart2" height="300"></canvas>
                </div>
            </div>
            <div class="neo-card p-8">
                <h3 class="text-sm font-black uppercase tracking-widest text-gray-700 mb-8">Success Rate</h3>
                <div class="neo-inset p-4 rounded-3xl">
                    <canvas id="bookingPieChart2" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let charts = [];

async function initializeCharts() {
    const defaultOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { display: false },
            x: { display: true, grid: { display: false } }
        }
    };

    // Revenue Chart
    const revenueResponse = await fetch('{{ route('admin.dashboard.revenue-chart') }}');
    const revenueData = await revenueResponse.json();

    const renderLine = (id, data, color) => {
        const ctx = document.getElementById(id);
        if (ctx) {
            charts.push(new Chart(ctx, {
                type: 'line',
                data: {
                    labels: revenueData.labels,
                    datasets: [{
                        data: data,
                        borderColor: color,
                        borderWidth: 4,
                        pointRadius: 0,
                        tension: 0.4,
                        fill: false
                    }]
                },
                options: defaultOptions
            }));
        }
    };

    renderLine('revenueChart', revenueData.revenues, '#10b981');
    renderLine('revenueChart2', revenueData.revenues, '#10b981');

    // Bookings Chart
    const bookingsResponse = await fetch('{{ route('admin.dashboard.bookings-chart') }}');
    const bookingsData = await bookingsResponse.json();
    renderLine('bookingsChart', bookingsData.bookings, '#3b82f6');
    renderLine('bookingsChart2', bookingsData.bookings, '#3b82f6');

    // Tour Performance
    const tourResponse = await fetch('{{ route('admin.dashboard.tour-performance-chart') }}');
    const tourData = await tourResponse.json();
    const tourCtx = document.getElementById('tourPerformanceChart');
    if (tourCtx) {
        charts.push(new Chart(tourCtx, {
            type: 'bar',
            data: {
                labels: tourData.labels,
                datasets: [{ data: tourData.revenues, backgroundColor: '#fbbf24', borderRadius: 10 }]
            },
            options: defaultOptions
        }));
    }

    // Pie Charts
    const pieData = {
        labels: ['Pending', 'Confirmed', 'Completed', 'Cancelled'],
        datasets: [{
            data: [{{ $bookingStats['pending'] }}, {{ $bookingStats['confirmed'] }}, {{ $bookingStats['completed'] }}, {{ $bookingStats['cancelled'] }}],
            backgroundColor: ['#f59e0b', '#10b981', '#3b82f6', '#ef4444'],
            borderWidth: 0,
            hoverOffset: 20
        }]
    };

    const pieOptions = { responsive: true, plugins: { legend: { position: 'bottom' } } };

    ['bookingPieChart', 'bookingPieChart2'].forEach(id => {
        const ctx = document.getElementById(id);
        if (ctx) charts.push(new Chart(ctx, { type: 'doughnut', data: pieData, options: pieOptions }));
    });
}

function showTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.getElementById('content-' + tabName).classList.remove('hidden');

    document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('neo-btn-active', 'text-amber-600'));

    const activeBtn = document.getElementById('tab-' + tabName);
    activeBtn.classList.add('neo-btn-active', 'text-amber-600');
}

document.addEventListener('DOMContentLoaded', initializeCharts);
</script>
@endsection
