<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $yearStart = now()->startOfYear()->toDateString();
        $yearEnd   = now()->endOfYear()->toDateString();

        // ═══════════════════════════════════════════════════════════════
        //  FINANCIAL TAB
        // ═══════════════════════════════════════════════════════════════

        // Sales report (existing)
        $salesReport = Booking::with(['user', 'package', 'payment'])
            ->whereBetween('created_at', [$yearStart, $yearEnd . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->get();

        $salesTotalRevenue = $salesReport->filter(fn($b) => $b->payment && $b->payment->status === 'paid')
            ->sum('total_price');
        $salesTotalBookings = $salesReport->count();

        // Monthly trend (existing)
        $months     = collect(range(1, 12))->map(fn($m) => now()->month($m));
        $monthlyRaw = Booking::whereYear('created_at', now()->year)
            ->selectRaw("MONTH(created_at) as m, COUNT(*) as bookings, SUM(total_price) as revenue")
            ->groupBy('m')
            ->get()
            ->keyBy('m');

        $monthlyLabels      = $months->map(fn($d) => $d->format('M'))->values();
        $monthlyBookingData = $months->map(fn($d) => (int) ($monthlyRaw[$d->month]?->bookings ?? 0))->values();
        $monthlyRevenueData = $months->map(fn($d) => (float) ($monthlyRaw[$d->month]?->revenue ?? 0))->values();

        // Payment status breakdown (NEW)
        $paymentStatusRaw = Payment::whereYear('created_at', now()->year)
            ->selectRaw("status, COUNT(*) as count, SUM(amount) as total")
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $paymentStatusLabels = ['paid', 'pending', 'failed', 'refunded'];
        $paymentStatusCounts = collect($paymentStatusLabels)->map(fn($s) => (int) ($paymentStatusRaw[$s]?->count ?? 0));
        $paymentStatusAmounts = collect($paymentStatusLabels)->map(fn($s) => (float) ($paymentStatusRaw[$s]?->total ?? 0));

        // Revenue by package (moved from Package tab)
        $packageRevenueReport = Package::withSum([
                'bookings as total_revenue' => fn($q) => $q->whereHas('payment', fn($p) => $p->where('status', 'paid'))
                    ->whereYear('created_at', now()->year)
            ], 'total_price')
            ->orderByDesc('total_revenue')
            ->get(['id', 'title']);

        $finPkgLabels      = $packageRevenueReport->pluck('title');
        $finPkgRevenueData = $packageRevenueReport->pluck('total_revenue')->map(fn($v) => (float) ($v ?? 0));

        // Refunds / Cancellations summary (NEW)
        $cancelledBookingsCount = Booking::where('status', 'cancelled')
            ->whereYear('created_at', now()->year)->count();
        $refundedPaymentsCount  = Payment::where('status', 'refunded')
            ->whereYear('created_at', now()->year)->count();
        $refundedTotalAmount    = Payment::where('status', 'refunded')
            ->whereYear('created_at', now()->year)->sum('amount');

        // ═══════════════════════════════════════════════════════════════
        //  CUSTOMERS TAB
        // ═══════════════════════════════════════════════════════════════

        // User report (existing, enhanced)
        $userReport = User::withCount([
                'bookings as bookings_count' => fn($q) => $q->whereYear('created_at', now()->year),
            ])
            ->withSum(['bookings as total_spent' => fn($q) => $q->whereHas('payment', fn($p) => $p->where('status', 'paid'))
                ->whereYear('created_at', now()->year)], 'total_price')
            ->orderBy('bookings_count', 'desc')
            ->get()
            ->map(function ($user) {
                $user->role_name = $user->getRoleNames()->first() ?? '—';
                return $user;
            });

        // User registration trend (existing)
        $userRegRaw = User::whereYear('created_at', now()->year)
            ->selectRaw("MONTH(created_at) as m, COUNT(*) as count")
            ->groupBy('m')
            ->get()
            ->keyBy('m');

        $userRegistrationLabels = $months->map(fn($d) => $d->format('M'))->values();
        $userRegistrationData   = $months->map(fn($d) => (int) ($userRegRaw[$d->month]?->count ?? 0))->values();

        // Repeat vs First-time customers (NEW)
        $allUserBookingCounts = User::withCount(['bookings' => fn($q) => $q->whereYear('created_at', now()->year)])
            ->get()
            ->pluck('bookings_count');

        $repeatCustomers   = $allUserBookingCounts->filter(fn($c) => $c > 1)->count();
        $firstTimeCustomers = $allUserBookingCounts->filter(fn($c) => $c === 1)->count();
        $zeroBookingUsers   = $allUserBookingCounts->filter(fn($c) => $c === 0)->count();

        // ═══════════════════════════════════════════════════════════════
        //  PACKAGES TAB
        // ═══════════════════════════════════════════════════════════════

        // Package report (existing)
        $packageReport = Package::withCount([
                'bookings as bookings_count' => fn($q) => $q->whereYear('created_at', now()->year),
                'reviews as review_count',
            ])
            ->withAvg('reviews as avg_rating', 'rating')
            ->withSum(['bookings as total_revenue' => fn($q) => $q->whereHas('payment', fn($p) => $p->where('status', 'paid'))
                ->whereYear('created_at', now()->year)], 'total_price')
            ->orderBy('bookings_count', 'desc')
            ->get();

        $packageLabels      = $packageReport->pluck('title');
        $packageBookingData = $packageReport->pluck('bookings_count')->map(fn($v) => (int) $v);

        // Worst performers (NEW) — packages with 0 bookings or lowest
        $worstPerformers = $packageReport->where('bookings_count', 0)->values();
        if ($worstPerformers->isEmpty()) {
            $worstPerformers = $packageReport->sortBy('bookings_count')->take(5)->values();
        }

        // Seasonal trend (NEW) — bookings by month × package
        $seasonalRaw = Booking::whereYear('created_at', now()->year)
            ->selectRaw("MONTH(created_at) as m, package_id, COUNT(*) as count")
            ->groupBy('m', 'package_id')
            ->get();

        $seasonalMonths = $months->map(fn($d) => $d->format('M'))->values();
        $seasonalPackages = Package::whereIn('id', $seasonalRaw->pluck('package_id')->unique())
            ->pluck('title', 'id');

        $seasonalData = [];
        foreach ($seasonalPackages as $pkgId => $pkgTitle) {
            $row = ['package' => $pkgTitle];
            foreach (range(1, 12) as $m) {
                $row[$m] = (int) ($seasonalRaw->where('m', $m)->where('package_id', $pkgId)->first()?->count ?? 0);
            }
            $seasonalData[] = $row;
        }

        // ═══════════════════════════════════════════════════════════════
        //  OPERATIONS TAB (NEW)
        // ═══════════════════════════════════════════════════════════════

        // Payment success rate
        $totalPayments     = Payment::whereYear('created_at', now()->year)->count();
        $successfulPayments = Payment::where('status', 'paid')->whereYear('created_at', now()->year)->count();
        $paymentSuccessRate = $totalPayments > 0 ? round(($successfulPayments / $totalPayments) * 100, 1) : 0;

        // Booking status breakdown
        $bookingStatusRaw = Booking::whereYear('created_at', now()->year)
            ->selectRaw("status, COUNT(*) as count")
            ->groupBy('status')
            ->pluck('count', 'status');

        $bookingStatusLabels = ['pending', 'confirmed', 'completed', 'cancelled'];
        $bookingStatusData   = collect($bookingStatusLabels)->map(fn($s) => (int) ($bookingStatusRaw[$s] ?? 0));

        // Average time from booking creation to payment (in hours)
        $avgPaymentHours = Payment::where('status', 'paid')
            ->whereNotNull('paid_at')
            ->whereYear('created_at', now()->year)
            ->selectRaw("AVG(TIMESTAMPDIFF(HOUR, created_at, paid_at)) as avg_hours")
            ->value('avg_hours');

        // Cancellation rate
        $totalBookingsYear = Booking::whereYear('created_at', now()->year)->count();
        $cancelledBookingsYear = Booking::where('status', 'cancelled')->whereYear('created_at', now()->year)->count();
        $cancellationRate = $totalBookingsYear > 0 ? round(($cancelledBookingsYear / $totalBookingsYear) * 100, 1) : 0;

        return view('reports.index', compact(
            // Financial
            'salesReport', 'salesTotalRevenue', 'salesTotalBookings',
            'monthlyLabels', 'monthlyBookingData', 'monthlyRevenueData',
            'paymentStatusLabels', 'paymentStatusCounts', 'paymentStatusAmounts',
            'finPkgLabels', 'finPkgRevenueData',
            'cancelledBookingsCount', 'refundedPaymentsCount', 'refundedTotalAmount',
            // Customers
            'userReport',
            'userRegistrationLabels', 'userRegistrationData',
            'repeatCustomers', 'firstTimeCustomers', 'zeroBookingUsers',
            // Packages
            'packageReport',
            'packageLabels', 'packageBookingData',
            'worstPerformers',
            'seasonalMonths', 'seasonalData',
            // Operations
            'paymentSuccessRate',
            'bookingStatusLabels', 'bookingStatusData',
            'avgPaymentHours',
            'cancellationRate',
            'cancelledBookingsYear',
        ));
    }

    public function export()
    {
        $year = now()->year;

        $bookings = Booking::with(['user', 'package', 'payment'])
            ->whereYear('created_at', $year)
            ->orderBy('created_at')
            ->get();

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=full-report-{$year}.csv",
        ];

        $callback = function () use ($bookings, $year) {
            $handle = fopen('php://output', 'w');

            // BOM for Excel UTF-8 compatibility
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // ── SECTION 1: Bookings ──
            fputcsv($handle, ['']);
            fputcsv($handle, ["BOOKINGS REPORT — {$year}"]);
            fputcsv($handle, ['Booking ID', 'Customer', 'Email', 'Package', 'Amount', 'Payment Status', 'Booking Status', 'Travel Date', 'Created At']);

            foreach ($bookings as $b) {
                fputcsv($handle, [
                    $b->id,
                    $b->user?->name ?? '—',
                    $b->user?->email ?? '—',
                    $b->package?->title ?? '—',
                    number_format($b->total_price, 2),
                    $b->payment?->status ?? 'unpaid',
                    $b->status,
                    $b->travel_date?->format('Y-m-d') ?? '—',
                    $b->created_at->format('Y-m-d H:i'),
                ]);
            }

            // ── SECTION 2: Payments ──
            $payments = Payment::whereYear('created_at', $year)->orderBy('created_at')->get();
            fputcsv($handle, ['']);
            fputcsv($handle, ["PAYMENTS REPORT — {$year}"]);
            fputcsv($handle, ['Payment ID', 'Booking ID', 'Amount', 'Gateway', 'Status', 'Transaction ID', 'Paid At', 'Created At']);

            foreach ($payments as $p) {
                fputcsv($handle, [
                    $p->id,
                    $p->booking_id,
                    number_format($p->amount, 2),
                    $p->gateway,
                    $p->status,
                    $p->transaction_id ?? '—',
                    $p->paid_at?->format('Y-m-d H:i') ?? '—',
                    $p->created_at->format('Y-m-d H:i'),
                ]);
            }

            // ── SECTION 3: Packages ──
            $packages = Package::withCount('bookings')->withAvg('reviews as avg_rating', 'rating')->orderBy('title')->get();
            fputcsv($handle, ['']);
            fputcsv($handle, ["PACKAGES REPORT — {$year}"]);
            fputcsv($handle, ['Package ID', 'Title', 'Price', 'Duration (Days)', 'Status', 'Total Bookings', 'Avg Rating']);

            foreach ($packages as $p) {
                fputcsv($handle, [
                    $p->id,
                    $p->title,
                    number_format($p->price, 2),
                    $p->duration_days,
                    $p->status,
                    $p->bookings_count ?? 0,
                    $p->avg_rating ? number_format($p->avg_rating, 1) : '—',
                ]);
            }

            // ── SECTION 4: Users ──
            $users = User::withCount('bookings')->orderBy('name')->get();
            fputcsv($handle, ['']);
            fputcsv($handle, ["USERS REPORT — {$year}"]);
            fputcsv($handle, ['User ID', 'Name', 'Email', 'Role', 'Active', 'Total Bookings', 'Joined At']);

            foreach ($users as $u) {
                fputcsv($handle, [
                    $u->id,
                    $u->name,
                    $u->email,
                    $u->getRoleNames()->first() ?? '—',
                    $u->is_active ? 'Yes' : 'No',
                    $u->bookings_count ?? 0,
                    $u->created_at->format('Y-m-d'),
                ]);
            }

            // ── SECTION 5: Reviews ──
            $reviews = Review::with('user', 'package')->whereYear('created_at', $year)->orderBy('created_at')->get();
            fputcsv($handle, ['']);
            fputcsv($handle, ["REVIEWS REPORT — {$year}"]);
            fputcsv($handle, ['Review ID', 'User', 'Package', 'Rating', 'Comment', 'Created At']);

            foreach ($reviews as $r) {
                fputcsv($handle, [
                    $r->id,
                    $r->user?->name ?? '—',
                    $r->package?->title ?? '—',
                    $r->rating,
                    $r->comment ?? '—',
                    $r->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
