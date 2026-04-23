<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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

Route::get('/admin/dashboard',   fn() => view('dashboards.admin'))
    ->middleware(['auth', 'role:super_admin'])->name('admin.dashboard');

Route::get('/manager/dashboard', fn() => view('dashboards.manager'))
    ->middleware(['auth', 'role:tour_manager'])->name('manager.dashboard');

Route::get('/customer/dashboard', fn() => view('dashboards.customer'))
    ->middleware(['auth', 'role:customer'])->name('customer.dashboard');

// All authenticated users can browse packages
Route::middleware('auth')->group(function () {
    Route::get('/packages', [\App\Http\Controllers\PackageController::class, 'index'])->name('packages.index');
    Route::get('/packages/{package}', [\App\Http\Controllers\PackageController::class, 'show'])->name('packages.show');
    Route::resource('bookings', \App\Http\Controllers\BookingController::class)->only(['index', 'show']);
});

// Tour Manager + Super Admin
Route::middleware(['auth', 'role:tour_manager|super_admin'])->group(function () {
    Route::get('/packages/create', [\App\Http\Controllers\PackageController::class, 'create'])->name('packages.create');
    Route::post('/packages', [\App\Http\Controllers\PackageController::class, 'store'])->name('packages.store');
    Route::get('/packages/{package}/edit', [\App\Http\Controllers\PackageController::class, 'edit'])->name('packages.edit');
    Route::put('/packages/{package}', [\App\Http\Controllers\PackageController::class, 'update'])->name('packages.update');
    Route::delete('/packages/{package}', [\App\Http\Controllers\PackageController::class, 'destroy'])->name('packages.destroy');
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    Route::resource('reviews', \App\Http\Controllers\ReviewController::class)->only(['index', 'destroy']);
    Route::patch('/reviews/{review}/approve', [\App\Http\Controllers\ReviewController::class, 'approve'])->name('reviews.approve');
    Route::patch('/bookings/{booking}/confirm', [\App\Http\Controllers\BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::patch('/bookings/{booking}/complete', [\App\Http\Controllers\BookingController::class, 'complete'])->name('bookings.complete');
});

// Customers
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::post('/bookings', [\App\Http\Controllers\BookingController::class, 'store'])->name('bookings.store');
    Route::patch('/bookings/{booking}/cancel', [\App\Http\Controllers\BookingController::class, 'cancel'])->name('bookings.cancel');
});

// Super Admin
Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/admin/users', [\App\Http\Controllers\UserController::class, 'index'])->name('admin.users.index');
    Route::patch('/admin/users/{user}/toggle', [\App\Http\Controllers\UserController::class, 'toggle'])->name('admin.users.toggle');
});




require __DIR__.'/auth.php';