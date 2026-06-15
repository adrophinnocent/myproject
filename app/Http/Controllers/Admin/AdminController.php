<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Package;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalBookings = Booking::count();
        $totalPackages = Package::count();
        $totalUsers = User::count();
        $totalRevenue = Booking::where('status', 'confirmed')->sum('total_price');
        $recentBookings = Booking::latest()->take(5)->get();

        return view('admin.dashboard', compact('totalBookings', 'totalPackages', 'totalUsers', 'totalRevenue', 'recentBookings'));
    }
}
