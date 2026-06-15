@extends('admin.layouts.app')

@section('title', 'Analytics Dashboard')
@section('page-title', 'Analytics Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Tabs -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <button onclick="showTab('dashboard')" id="tab-dashboard" class="tab-btn py-4 px-1 border-b-2 border-amber-500 text-amber-600 font-medium text-sm">
                    Dashboard
                </button>
                <button onclick="showTab('tours')" id="tab-tours" class="tab-btn py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm">
                    Tours
                </button>
                <button onclick="showTab('revenue')" id="tab-revenue" class="tab-btn py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm">
                    Revenue
                </button>
                <button onclick="showTab('bookings')" id="tab-bookings" class="tab-btn py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm">
                    Bookings
                </button>
            </nav>
        </div>

        <!-- Dashboard Tab -->
        <div id="content-dashboard" class="tab-content p-6">
            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-green-700 text-sm font-medium">Revenue Today</div>
                        <div class="w-8 h-8 bg-green-500/20 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8v1m0 8v1m0-11c1.11 0 2.08.402 2.599 1M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900">${{ number_format($kpis['revenue_today'], 2) }}</div>
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-blue-700 text-sm font-medium">Revenue This Week</div>
                        <div class="w-8 h-8 bg-blue-500/20 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6l-4 4m4-4l4 4m-4-4v-4"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900">${{ number_format($kpis['revenue_week'], 2) }}</div>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-purple-700 text-sm font-medium">Revenue This Month</div>
                        <div class="w-8 h-8 bg-purple-500/20 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900">${{ number_format($kpis['revenue_month'], 2) }}</div>
                </div>
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-orange-700 text-sm font-medium">Revenue This Year</div>
                        <div class="w-8 h-8 bg-orange-500/20 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900">${{ number_format($kpis['revenue_year'], 2) }}</div>
                </div>
                <div class="bg-gradient-to-br from-amber-50 to-amber-100 border border-amber-200 rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-amber-700 text-sm font-medium">Total Revenue</div>
                        <div class="w-8 h-8 bg-amber-500/20 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900">${{ number_format($kpis['revenue_total'], 2) }}</div>
                </div>
            </div>

            <!-- Bookings Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-500 text-sm font-medium">Pending Bookings</div>
                            <div class="text-3xl font-bold text-yellow-600 mt-1">{{ $kpis['pending_bookings'] }}</div>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-500 text-sm font-medium">Confirmed Bookings</div>
                            <div class="text-3xl font-bold text-green-600 mt-1">{{ $kpis['confirmed_bookings'] }}</div>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-500 text-sm font-medium">Completed Bookings</div>
                            <div class="text-3xl font-bold text-blue-600 mt-1">{{ $kpis['completed_bookings'] }}</div>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6l-4 4m4-4l4 4m-4-4v-4"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-500 text-sm font-medium">Cancelled Bookings</div>
                            <div class="text-3xl font-bold text-red-600 mt-1">{{ $kpis['cancelled_bookings'] }}</div>
                        </div>
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Monthly Revenue Trend</h3>
                    </div>
                    <div class="p-6">
                        <canvas id="revenueChart" height="280"></canvas>
                    </div>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Monthly Bookings Trend</h3>
                    </div>
                    <div class="p-6">
                        <canvas id="bookingsChart" height="280"></canvas>
                    </div>
                </div>
            </div>

            <!-- Pie Chart and Recent Bookings -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Booking Status Distribution</h3>
                    </div>
                    <div class="p-6">
                        <canvas id="bookingPieChart" height="280"></canvas>
                    </div>
                </div>
                <div class="lg:col-span-2 bg-white border border-gray-200 rounded-xl shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Bookings</h3>
                    </div>
                    <div class="p-6">
                        @if($recentBooks && $recentBooks->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentBooks as $booking)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors border border-transparent hover:border-gray-200">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2">
                                                <div class="text-sm font-bold text-gray-900">{{ $booking->full_name }}</div>
                                                @php
                                                    $days = now()->startOfDay()->diffInDays($booking->travel_date->startOfDay(), false);
                                                @endphp
                                                @if($days > 0 && $days <= 7)
                                                    <span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
                                                    <span class="text-[9px] font-bold text-red-600 uppercase">Soon</span>
                                                @endif
                                            </div>
                                            <div class="text-[10px] font-bold text-gold-600 uppercase tracking-widest">{{ $booking->booking_reference }}</div>
                                            <div class="flex items-center gap-4 mt-2">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="text-[10px] text-gray-400 font-bold uppercase">Tour:</span>
                                                    <span class="text-xs font-bold text-gray-700">{{ $booking->tour->title ?? 'Custom' }}</span>
                                                </div>
                                                <div class="flex items-center gap-1.5">
                                                    <span class="text-[10px] text-gray-400 font-bold uppercase">Trip:</span>
                                                    <span class="text-xs font-bold text-amber-600">{{ $booking->travel_date->format('d M') }} ({{ $days > 0 ? $days.' days left' : ($days == 0 ? 'Today' : 'Past') }})</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-6">
                                            <div class="text-right">
                                                <span class="px-2 py-1 text-[10px] font-bold rounded-full uppercase tracking-wider
                                                    @if($booking->status == 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($booking->status == 'confirmed') bg-green-100 text-green-800
                                                    @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                                                    @else bg-blue-100 text-blue-800
                                                    @endif">
                                                    {{ $booking->status }}
                                                </span>
                                                <div class="text-sm font-bold text-gray-900 mt-1">${{ number_format($booking->total_price, 2) }}</div>
                                            </div>
                                            <a href="{{ route('admin.bookings.show', $booking) }}" class="w-10 h-10 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gold-600 hover:bg-gold-500 hover:text-white transition-all shadow-sm">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-center text-gray-500 py-8">No recent bookings</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Tours Tab -->
        <div id="content-tours" class="tab-content hidden p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Tour Performance</h3>
                    </div>
                    <div class="p-6">
                        <canvas id="tourPerformanceChart" height="280"></canvas>
                    </div>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Top Tours (By Revenue)</h3>
                    </div>
                    <div class="p-6">
                        @if($topTours && $topTours->count() > 0)
                            <div class="space-y-4">
                                @foreach($topTours as $tour)
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 font-semibold text-sm">
                                                {{ $loop->iteration }}
                                            </div>
                                            <div class="flex-1">
                                                <div class="text-sm font-medium text-gray-900">{{ $tour->title }}</div>
                                                <div class="text-xs text-gray-500">{{ $tour->bookings_count }} bookings</div>
                                            </div>
                                        </div>
                                        <div class="text-sm font-semibold text-amber-600">${{ number_format($tour->bookings_sum_total_price, 2) }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-center text-gray-500 py-8">No tours yet</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Revenue per Tour Table -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Revenue per Tour</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tour</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Bookings</th>
                                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($revenuePerTour as $tour)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $tour->title }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600">{{ $tour->bookings_count }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-green-600">${{ number_format($tour->bookings_sum_total_price, 2) }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Revenue Tab -->
        <div id="content-revenue" class="tab-content hidden p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-green-700 text-sm font-medium">Revenue Today</div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900">${{ number_format($kpis['revenue_today'], 2) }}</div>
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-blue-700 text-sm font-medium">Revenue This Week</div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900">${{ number_format($kpis['revenue_week'], 2) }}</div>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-purple-700 text-sm font-medium">Revenue This Month</div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900">${{ number_format($kpis['revenue_month'], 2) }}</div>
                </div>
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-orange-700 text-sm font-medium">Revenue This Year</div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900">${{ number_format($kpis['revenue_year'], 2) }}</div>
                </div>
                <div class="bg-gradient-to-br from-amber-50 to-amber-100 border border-amber-200 rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-amber-700 text-sm font-medium">Total Revenue</div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900">${{ number_format($kpis['revenue_total'], 2) }}</div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Monthly Revenue Trend (Last 12 Months)</h3>
                </div>
                <div class="p-6">
                    <canvas id="revenueChart2" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Bookings Tab -->
        <div id="content-bookings" class="tab-content hidden p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-500 text-sm font-medium">Pending Bookings</div>
                            <div class="text-3xl font-bold text-yellow-600 mt-1">{{ $kpis['pending_bookings'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-500 text-sm font-medium">Confirmed Bookings</div>
                            <div class="text-3xl font-bold text-green-600 mt-1">{{ $kpis['confirmed_bookings'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-500 text-sm font-medium">Completed Bookings</div>
                            <div class="text-3xl font-bold text-blue-600 mt-1">{{ $kpis['completed_bookings'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-gray-500 text-sm font-medium">Cancelled Bookings</div>
                            <div class="text-3xl font-bold text-red-600 mt-1">{{ $kpis['cancelled_bookings'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Monthly Bookings Trend</h3>
                    </div>
                    <div class="p-6">
                        <canvas id="bookingsChart2" height="280"></canvas>
                    </div>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Booking Status Distribution</h3>
                    </div>
                    <div class="p-6">
                        <canvas id="bookingPieChart2" height="280"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let charts = [];

async function initializeCharts() {
    // Revenue Chart
    const revenueResponse = await fetch('{{ route('admin.dashboard.revenue-chart') }}');
    const revenueData = await revenueResponse.json();
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        charts.push(new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: revenueData.labels,
                datasets: [{
                    label: 'Revenue',
                    data: revenueData.revenues,
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } }
            }
        }));
    }

    // Revenue Chart 2
    const revenueCtx2 = document.getElementById('revenueChart2');
    if (revenueCtx2) {
        charts.push(new Chart(revenueCtx2, {
            type: 'line',
            data: {
                labels: revenueData.labels,
                datasets: [{
                    label: 'Revenue',
                    data: revenueData.revenues,
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } }
            }
        }));
    }

    // Bookings Chart
    const bookingsResponse = await fetch('{{ route('admin.dashboard.bookings-chart') }}');
    const bookingsData = await bookingsResponse.json();
    const bookingsCtx = document.getElementById('bookingsChart');
    if (bookingsCtx) {
        charts.push(new Chart(bookingsCtx, {
            type: 'line',
            data: {
                labels: bookingsData.labels,
                datasets: [{
                    label: 'Bookings',
                    data: bookingsData.bookings,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } }
            }
        }));
    }

    // Bookings Chart 2
    const bookingsCtx2 = document.getElementById('bookingsChart2');
    if (bookingsCtx2) {
        charts.push(new Chart(bookingsCtx2, {
            type: 'line',
            data: {
                labels: bookingsData.labels,
                datasets: [{
                    label: 'Bookings',
                    data: bookingsData.bookings,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } }
            }
        }));
    }

    // Tour Performance Chart
    const tourResponse = await fetch('{{ route('admin.dashboard.tour-performance-chart') }}');
    const tourData = await tourResponse.json();
    const tourCtx = document.getElementById('tourPerformanceChart');
    if (tourCtx) {
        charts.push(new Chart(tourCtx, {
            type: 'bar',
            data: {
                labels: tourData.labels,
                datasets: [
                    {
                        label: 'Revenue',
                        data: tourData.revenues,
                        backgroundColor: 'rgba(251, 191, 36, 0.8)',
                    },
                    {
                        label: 'Bookings',
                        data: tourData.bookings,
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    }
                ]
            },
            options: {
                responsive: true
            }
        }));
    }

    // Booking Pie Chart
    const pieCtx = document.getElementById('bookingPieChart');
    const pieCtx2 = document.getElementById('bookingPieChart2');
    const pieData = {
        labels: ['Pending', 'Confirmed', 'Completed', 'Cancelled'],
        datasets: [{
            data: [{{ $bookingStats['pending'] }}, {{ $bookingStats['confirmed'] }}, {{ $bookingStats['completed'] }}, {{ $bookingStats['cancelled'] }}],
            backgroundColor: ['rgb(245, 158, 11)', 'rgb(34, 197, 94)', 'rgb(59, 130, 246)', 'rgb(239, 68, 68)']
        }]
    };
    if (pieCtx) {
        charts.push(new Chart(pieCtx, { type: 'doughnut', data: pieData }));
    }
    if (pieCtx2) {
        charts.push(new Chart(pieCtx2, { type: 'doughnut', data: pieData }));
    }
}

function showTab(tabName) {
    // Hide all content
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));

    // Show selected content
    document.getElementById('content-' + tabName).classList.remove('hidden');

    // Update tab styles
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('border-amber-500', 'text-amber-600');
        btn.classList.add('border-transparent', 'text-gray-500');
    });

    const activeBtn = document.getElementById('tab-' + tabName);
    activeBtn.classList.remove('border-transparent', 'text-gray-500');
    activeBtn.classList.add('border-amber-500', 'text-amber-600');
}

// Initialize on load
document.addEventListener('DOMContentLoaded', initializeCharts);
</script>
@endsection
