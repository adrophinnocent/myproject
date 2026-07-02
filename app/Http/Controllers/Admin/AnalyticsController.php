<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Contact;
use App\Models\Tour;
use App\Models\TripPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function dashboard()
    {
        $kpis = $this->getKPIs();
        $recentBooks = Booking::with(['tour', 'safari'])->latest()->take(8)->get();
        $topTours = $this->getTopTours();
        $tourStats = $this->getTourPerformance();
        $revenueData = $this->getRevenueData();
        $bookingTrend = $this->getBookingTrend();
        $customerGrowth = $this->getCustomerGrowth();
        $revenuePerTour = $this->getRevenuePerTour();
        $bookingStats = $this->getBookingStats();

        // Add Marketing Summary
        $marketingLeads = \App\Models\Lead::whereNotNull('campaign_id')->count();
        $marketingVisits = \App\Models\CampaignStat::where('type', 'visit')->count();
        $topCampaigns = \App\Models\Campaign::withCount(['leads', 'stats'])->orderByDesc('leads_count')->take(3)->get();

        return view('admin.dashboard', compact(
            'kpis', 'recentBooks', 'topTours', 'tourStats',
            'revenueData', 'bookingTrend', 'customerGrowth',
            'revenuePerTour', 'bookingStats', 'marketingLeads', 'marketingVisits', 'topCampaigns'
        ));
    }

    private function getKPIs(): array
    {
        $now = Carbon::now();
        $today = $now->startOfDay();
        $thisWeek = $now->startOfWeek();
        $thisMonth = $now->startOfMonth();
        $thisYear = $now->startOfYear();
        $prevMonth = $now->copy()->subMonth()->startOfMonth();

        $bookingsThisMonth = Booking::whereBetween('created_at', [$thisMonth, $now])->count();
        $bookingsPrevMonth = Booking::whereBetween('created_at', [$prevMonth, $now->copy()->subMonth()])->count();
        $bookingsGrowth = $bookingsPrevMonth > 0
            ? round((($bookingsThisMonth - $bookingsPrevMonth) / $bookingsPrevMonth) * 100, 1)
            : 100;

        $revenueToday = Booking::where('created_at', '>=', $today)
            ->whereIn('status', ['confirmed', 'completed'])->sum('total_price');
        $revenueThisWeek = Booking::where('created_at', '>=', $thisWeek)
            ->whereIn('status', ['confirmed', 'completed'])->sum('total_price');
        $revenueThisMonth = Booking::where('created_at', '>=', $thisMonth)
            ->whereIn('status', ['confirmed', 'completed'])->sum('total_price');
        $revenueThisYear = Booking::where('created_at', '>=', $thisYear)
            ->whereIn('status', ['confirmed', 'completed'])->sum('total_price');
        $totalRevenue = Booking::whereIn('status', ['confirmed', 'completed'])->sum('total_price');

        $customersThisMonth = User::whereBetween('created_at', [$thisMonth, $now])->count();
        $customersPrevMonth = User::whereBetween('created_at', [$prevMonth, $now->copy()->subMonth()])->count();
        $customersGrowth = $customersPrevMonth > 0
            ? round((($customersThisMonth - $customersPrevMonth) / $customersPrevMonth) * 100, 1)
            : 100;

        $contactCount = 0;
        $tripPlanCount = 0;

        try {
            $contactCount = Contact::whereBetween('created_at', [$thisMonth, $now])->count();
        } catch (\Exception $e) {
            $contactCount = 0;
        }

        try {
            $tripPlanCount = TripPlan::whereBetween('created_at', [$thisMonth, $now])->count();
        } catch (\Exception $e) {
            $tripPlanCount = 0;
        }

        $inquiriesThisMonth = $contactCount + $tripPlanCount;
        $conversionRate = $inquiriesThisMonth > 0
            ? round(($bookingsThisMonth / $inquiriesThisMonth) * 100, 1)
            : 0;

        return [
            'revenue_today' => $revenueToday,
            'revenue_week' => $revenueThisWeek,
            'revenue_month' => $revenueThisMonth,
            'revenue_year' => $revenueThisYear,
            'revenue_total' => $totalRevenue,
            'bookings' => $bookingsThisMonth,
            'bookings_growth' => $bookingsGrowth,
            'customers' => $customersThisMonth,
            'customers_growth' => $customersGrowth,
            'total_bookings' => Booking::count(),
            'active_tours' => Tour::where('is_published', true)->count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'confirmed_bookings' => Booking::where('status', 'confirmed')->count(),
            'completed_bookings' => Booking::where('status', 'completed')->count(),
            'cancelled_bookings' => Booking::where('status', 'cancelled')->count(),
            'total_tours' => Tour::count(),
            'inquiries' => $inquiriesThisMonth,
            'conversion_rate' => $conversionRate,
        ];
    }

    private function getTopTours(): Collection
    {
        return Tour::withCount('bookings')
            ->withSum(['bookings' => function ($q) {
                $q->whereIn('status', ['confirmed', 'completed']);
            }], 'total_price')
            ->orderByDesc('bookings_sum_total_price')
            ->take(5)
            ->get();
    }

    private function getTourPerformance(): array
    {
        $tours = Tour::withCount('bookings')
            ->withSum(['bookings' => function ($q) {
                $q->whereIn('status', ['confirmed', 'completed']);
            }], 'total_price')
            ->get();

        $labels = $tours->pluck('title')->take(10)->toArray();
        $revenues = $tours->pluck('bookings_sum_total_price')->take(10)->toArray();
        $bookings = $tours->pluck('bookings_count')->take(10)->toArray();

        return [
            'labels' => $labels,
            'revenues' => $revenues,
            'bookings' => $bookings,
        ];
    }

    private function getRevenueData(): array
    {
        $start = now()->subMonths(11)->startOfMonth();

        $data = Booking::where('created_at', '>=', $start)
            ->whereIn('status', ['confirmed', 'completed'])
            ->select(
                DB::raw($this->getMonthFormatFunction().' as month'),
                DB::raw('SUM(total_price) as revenue')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = [];
        $revenues = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $label = $date->format('M');
            $row = $data->firstWhere('month', $monthKey);
            $months[] = $label;
            $revenues[] = $row ? (float) $row->revenue : 0;
        }

        return [
            'labels' => $months,
            'revenues' => $revenues,
        ];
    }

    private function getBookingTrend(): array
    {
        $start = now()->subMonths(11)->startOfMonth();

        $data = Booking::where('created_at', '>=', $start)
            ->select(
                DB::raw($this->getMonthFormatFunction().' as month'),
                DB::raw('COUNT(*) as bookings')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = [];
        $bookings = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $label = $date->format('M');
            $row = $data->firstWhere('month', $monthKey);
            $months[] = $label;
            $bookings[] = $row ? (int) $row->bookings : 0;
        }

        return [
            'labels' => $months,
            'bookings' => $bookings,
        ];
    }

    private function getCustomerGrowth(): array
    {
        $start = now()->subMonths(11)->startOfMonth();

        $data = User::where('created_at', '>=', $start)
            ->select(
                DB::raw($this->getMonthFormatFunction().' as month'),
                DB::raw('COUNT(*) as customers')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $months = [];
        $customers = [];
        $baseCount = User::where('created_at', '<', $start)->count();
        $cumulative = $baseCount;

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $label = $date->format('M');
            $row = $data->firstWhere('month', $monthKey);
            $count = $row ? (int) $row->customers : 0;
            $cumulative += $count;

            $months[] = $label;
            $customers[] = $cumulative;
        }

        return [
            'labels' => $months,
            'customers' => $customers,
        ];
    }

    private function getMonthFormatFunction(): string
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            return "strftime('%Y-%m', datetime(created_at))";
        }

        return "DATE_FORMAT(created_at, '%Y-%m')";
    }

    private function getRevenuePerTour(): Collection
    {
        return Tour::withCount('bookings')
            ->withSum(['bookings' => function ($q) {
                $q->whereIn('status', ['confirmed', 'completed']);
            }], 'total_price')
            ->orderByDesc('bookings_sum_total_price')
            ->get();
    }

    private function getBookingStats(): array
    {
        return [
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];
    }

    public function bookingsChart()
    {
        return response()->json($this->getBookingTrend());
    }

    public function revenueChart()
    {
        return response()->json($this->getRevenueData());
    }

    public function customerGrowthChart()
    {
        return response()->json($this->getCustomerGrowth());
    }

    public function tourPerformanceChart()
    {
        return response()->json($this->getTourPerformance());
    }
}
