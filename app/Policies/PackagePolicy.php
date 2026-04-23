<?php

namespace App\Policies;

use App\Models\Package;
use App\Models\User;

class PackagePolicy
{
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('packages.create');
    }

    public function update(User $user, Package $package): bool
    {
        return $user->hasPermissionTo('packages.edit');
    }

    public function delete(User $user, Package $package): bool
    {
        return $user->hasPermissionTo('packages.delete');
    }

    public function restore(User $user, Package $package): bool
    {
        return $user->hasRole('super_admin');
    }
}
