<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\Review;
use App\Policies\BookingPolicy;
use App\Policies\InvoicePolicy;
use App\Policies\PackagePolicy;
use App\Policies\ReviewPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Booking::class => BookingPolicy::class,
        Package::class => PackagePolicy::class,
        Review::class  => ReviewPolicy::class,
        Invoice::class => InvoicePolicy::class,
    ];

    public function register(): void
    {
        //
    }

   
    public function boot(): void
    {
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        Gate::policy(\App\Models\Booking::class, \App\Policies\BookingPolicy::class);
        Gate::policy(\App\Models\Package::class, \App\Policies\PackagePolicy::class);
        Gate::policy(\App\Models\Review::class,  \App\Policies\ReviewPolicy::class);
        Gate::policy(\App\Models\Invoice::class, \App\Policies\InvoicePolicy::class);

        // Super admin bypasses all authorization checks except for review ownership
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('super_admin')) {
                // Do not bypass review update/delete — ownership must be respected
                if (in_array($ability, ['update', 'delete'])) {
                    $review = request()->route('review');
                    if ($review instanceof \App\Models\Review) {
                        return $user->id === $review->user_id;
                    }
                }
                return true;
            }
        });
    }
}
