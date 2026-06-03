<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = ['admin', 'manager', 'cashier', 'inventory'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Create super admin if not exists
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@pos.com'],
            [
                'name'           => 'Super Admin',
                'email'          => 'superadmin@pos.com',
                'password'       => Hash::make('SuperAdmin@2026'),
                'is_super_admin' => true,
                'status'         => 'active',
            ]
        );
    }
}