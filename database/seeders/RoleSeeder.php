<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'packages.view',
            'packages.create',
            'packages.edit',
            'packages.delete',
            'packages.restore',
            'categories.manage',
            'bookings.view',
            'bookings.create',
            'bookings.manage',
            'bookings.cancel-own',
            'reviews.create',
            'reviews.approve',
            'reviews.delete',
            'payments.view',
            'invoices.view-own',
            'users.manage',
            'roles.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->syncPermissions(Permission::all());

        $tourManager = Role::firstOrCreate(['name' => 'tour_manager']);
        $tourManager->syncPermissions([
            'packages.view',
            'packages.create',
            'packages.edit',
            'packages.delete',
            'categories.manage',
            'bookings.view',
            'bookings.manage',
            'reviews.approve',
            'reviews.delete',
            'payments.view',
        ]);

        $customer = Role::firstOrCreate(['name' => 'customer']);
        $customer->syncPermissions([
            'packages.view',
            'bookings.create',
            'bookings.view',
            'bookings.cancel-own',
            'reviews.create',
            'invoices.view-own',
        ]);
    }
}
