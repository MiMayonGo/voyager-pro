<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;
use App\Models\Package;

class ReviewPolicy
{
    public function create(User $user, Package $package): bool
    {
        return $user->hasPermissionTo('reviews.create')
            && !Review::where('user_id', $user->id)
                ->where('package_id', $package->id)
                ->exists();
    }

    public function update(User $user, Review $review): bool
    {
        return $user->id === $review->user_id;
    }

    public function delete(User $user, Review $review): bool
    {
        return $user->id === $review->user_id;
    }
}
