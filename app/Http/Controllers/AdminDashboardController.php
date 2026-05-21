<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Review;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // ── PRIMARY STATS ─────────────────────────────────────────────
        $totalUsers     = User::count();
        $activePackages = Package::where('status', 'active')->count();
        $totalBookings  = Booking::count();
        $totalRevenue   = Payment::where('status', 'paid')->sum('amount');

        // ── SECONDARY STATS ───────────────────────────────────────────
        $pendingBookings   = Booking::where('status', 'pending')->count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $bookingsToday     = Booking::whereDate('created_at', today())->count();
        $revenueToday      = Payment::where('status', 'paid')->whereDate('paid_at', today())->sum('amount');
        $newUsersMonth     = User::whereMonth('created_at', now()->month)
                                  ->whereYear('created_at', now()->year)
                                  ->count();

        // ── CHART 1: Revenue — last 6 months ─────────────────────────
        $months    = collect(range(5, 0))->map(fn($i) => now()->subMonths($i));
        $revenueRaw = Payment::where('status', 'paid')
            ->where('paid_at', '>=', now()->subMonths(5)->startOfMonth())
            ->selectRaw("DATE_FORMAT(paid_at, '%Y-%m') as month, SUM(amount) as total")
            ->groupBy('month')
            ->pluck('total', 'month');

        $revenueLabels = $months->map(fn($d) => $d->format('M Y'))->values();
        $revenueData   = $months->map(fn($d) => (float) ($revenueRaw[$d->format('Y-m')] ?? 0))->values();

        // ── CHART 2: Bookings by status (doughnut) ───────────────────
        $bookingsByStatus = Booking::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // ── CHART 3: Daily bookings — last 30 days ────────────────────
        $days     = collect(range(29, 0))->map(fn($i) => now()->subDays($i));
        $dailyRaw = Booking::where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->selectRaw('DATE(created_at) as date, count(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date');

        $dailyLabels = $days->map(fn($d) => $d->format('d M'))->values();
        $dailyData   = $days->map(fn($d) => (int) ($dailyRaw[$d->format('Y-m-d')] ?? 0))->values();

        // ── CHART 4: Top 5 packages by bookings ──────────────────────
        $topPackages = Package::withCount('bookings')
            ->orderByDesc('bookings_count')
            ->limit(5)
            ->get(['id', 'title', 'bookings_count'])
            ->each(fn($p) => $p->title = \Illuminate\Support\Str::limit($p->title, 28));

        // ── RECENT BOOKINGS (last 10) ─────────────────────────────────
        $recentBookings = Booking::with(['user', 'package', 'payment'])
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboards.admin', compact(
            'totalUsers', 'activePackages', 'totalBookings', 'totalRevenue',
            'pendingBookings', 'confirmedBookings', 'bookingsToday', 'revenueToday', 'newUsersMonth',
            'revenueLabels', 'revenueData',
            'bookingsByStatus',
            'dailyLabels', 'dailyData',
            'topPackages',
            'recentBookings'
        ));
    }
}