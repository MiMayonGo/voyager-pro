<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', [\App\Http\Controllers\WelcomeController::class, 'index']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', function () {
    return match(true) {
        auth()->user()->hasRole('super_admin')  => redirect()->route('admin.dashboard'),
        auth()->user()->hasRole('tour_manager') => redirect()->route('manager.dashboard'),
        default                                 => redirect()->route('customer.dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/admin/dashboard', [\App\Http\Controllers\AdminDashboardController::class, 'index'])
    ->middleware(['auth', 'role:super_admin'])->name('admin.dashboard');

Route::get('/manager/dashboard', [\App\Http\Controllers\ManagerDashboardController::class, 'index'])
    ->middleware(['auth', 'role:tour_manager'])->name('manager.dashboard');

Route::get('/customer/dashboard', function () {
    $userId = auth()->id();

    $pendingCount            = \App\Models\Booking::where('user_id', $userId)->where('status', 'pending')->count();
    $confirmedCompletedCount = \App\Models\Booking::where('user_id', $userId)->whereIn('status', ['confirmed', 'completed'])->count();
    $cancelledCount          = \App\Models\Booking::where('user_id', $userId)->where('status', 'cancelled')->count();
    $upcomingCount           = \App\Models\Booking::where('user_id', $userId)->whereIn('status', ['pending', 'confirmed'])->where('travel_date', '>=', now())->count();
    $nextTravelDate          = \App\Models\Booking::where('user_id', $userId)->whereIn('status', ['pending', 'confirmed'])->where('travel_date', '>=', now())->orderBy('travel_date')->value('travel_date');
    $recentBookings          = \App\Models\Booking::with('package')->where('user_id', $userId)->latest()->take(5)->get();

    return view('dashboards.customer', compact(
        'pendingCount', 'confirmedCompletedCount', 'cancelledCount',
        'upcomingCount', 'nextTravelDate', 'recentBookings'
    ));
})->middleware(['auth', 'role:customer'])->name('customer.dashboard');

// Tour Manager + Super Admin (read-only reviews index)
Route::middleware(['auth', 'role:tour_manager|super_admin'])->group(function () {
    Route::get('/packages/create', [\App\Http\Controllers\PackageController::class, 'create'])->name('packages.create');
    Route::post('/packages', [\App\Http\Controllers\PackageController::class, 'store'])->name('packages.store');
    Route::get('/packages/{package}/edit', [\App\Http\Controllers\PackageController::class, 'edit'])->name('packages.edit');
    Route::put('/packages/{package}', [\App\Http\Controllers\PackageController::class, 'update'])->name('packages.update');
    Route::delete('/packages/{package}', [\App\Http\Controllers\PackageController::class, 'destroy'])->name('packages.destroy');
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    Route::get('/reviews', [\App\Http\Controllers\ReviewController::class, 'index'])->name('reviews.index');
    Route::patch('/bookings/{booking}/confirm', [\App\Http\Controllers\BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::patch('/bookings/{booking}/complete', [\App\Http\Controllers\BookingController::class, 'complete'])->name('bookings.complete');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::patch('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
});

// All authenticated users (wildcard /packages/{package} must come AFTER /packages/create)
Route::middleware('auth')->group(function () {
    Route::get('/packages', [\App\Http\Controllers\PackageController::class, 'index'])->name('packages.index');
    Route::get('/packages/{package}', [\App\Http\Controllers\PackageController::class, 'show'])->name('packages.show');
    Route::resource('bookings', \App\Http\Controllers\BookingController::class)->only(['index', 'show']);
});

// Customers
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::post('/bookings', [\App\Http\Controllers\BookingController::class, 'store'])->name('bookings.store');
    Route::patch('/bookings/{booking}/cancel', [\App\Http\Controllers\BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/my-reviews', [\App\Http\Controllers\ReviewController::class, 'myReviews'])->name('reviews.my');
    Route::get('/reviews/{review}/edit', [\App\Http\Controllers\ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [\App\Http\Controllers\ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [\App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Super Admin
Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/admin/users', [\App\Http\Controllers\UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [\App\Http\Controllers\UserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users', [\App\Http\Controllers\UserController::class, 'store'])->name('admin.users.store');
    Route::patch('/admin/users/{user}/toggle', [\App\Http\Controllers\UserController::class, 'toggle'])->name('admin.users.toggle');
    Route::get('/admin/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/admin/reports/export', [\App\Http\Controllers\ReportController::class, 'export'])->name('reports.export');
});

require __DIR__.'/auth.php';
