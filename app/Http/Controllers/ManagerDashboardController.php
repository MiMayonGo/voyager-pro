<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Review;

class ManagerDashboardController extends Controller
{
    public function index()
    {
        $activePackages  = Package::where('status', 'active')->count();
        $totalPackages   = Package::count();
        $totalBookings   = Booking::count();
        $bookingsToday   = Booking::whereDate('created_at', today())->count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $pendingReviews  = 0; // Reviews are auto-approved (is_approved column removed)
        $revenueMonth    = Payment::where('status', 'paid')
                               ->whereMonth('paid_at', now()->month)
                               ->whereYear('paid_at', now()->year)
                               ->sum('amount');

        // Last 6 months revenue
        $months     = collect(range(5, 0))->map(fn($i) => now()->subMonths($i));
        $revenueRaw = Payment::where('status', 'paid')
            ->where('paid_at', '>=', now()->subMonths(5)->startOfMonth())
            ->selectRaw("DATE_FORMAT(paid_at, '%Y-%m') as month, SUM(amount) as total")
            ->groupBy('month')
            ->pluck('total', 'month');

        $revenueLabels = $months->map(fn($d) => $d->format('M Y'))->values();
        $revenueData   = $months->map(fn($d) => (float) ($revenueRaw[$d->format('Y-m')] ?? 0))->values();

        // Top packages by bookings
        $topPackages = Package::withCount('bookings')
            ->orderByDesc('bookings_count')
            ->limit(5)
            ->get(['id', 'title', 'bookings_count']);

        // Recent bookings
        $recentBookings = Booking::with(['user', 'package'])
            ->latest()
            ->limit(8)
            ->get();

        return view('dashboards.manager', compact(
            'activePackages', 'totalPackages', 'totalBookings', 'bookingsToday',
            'pendingBookings', 'pendingReviews', 'revenueMonth',
            'revenueLabels', 'revenueData',
            'topPackages', 'recentBookings'
        ));
    }
}
