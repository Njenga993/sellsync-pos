<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Sales
            'access_pos',
            'view_sales',
            'process_returns',
            'apply_discount',

            // Inventory
            'view_products',
            'manage_products',
            'manage_categories',
            'manage_stock',
            'manage_suppliers',

            // Customers
            'view_customers',
            'manage_customers',
            'delete_customers',

            // Finance & Reports
            'view_reports',
            'view_profit_loss',
            'view_stock_valuation',
            'view_cashier_performance',
            'manage_expenses',

            // Administration
            'view_dashboard',
            'manage_staff',
            'manage_settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ── ADMIN — full access ──
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions($permissions);

        // ── MANAGER — everything except staff & settings ──
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $manager->syncPermissions([
            'access_pos',
            'view_sales',
            'process_returns',
            'apply_discount',
            'view_products',
            'manage_products',
            'manage_categories',
            'manage_stock',
            'manage_suppliers',
            'view_customers',
            'manage_customers',
            'delete_customers',
            'view_reports',
            'view_profit_loss',
            'view_stock_valuation',
            'view_cashier_performance',
            'manage_expenses',
            'view_dashboard',
        ]);

        // ── CASHIER — POS + customers + view products ──
        $cashier = Role::firstOrCreate(['name' => 'cashier']);
        $cashier->syncPermissions([
            'access_pos',
            'view_sales',
            'view_products',
            'view_customers',
            'manage_customers',
        ]);

        // ── INVENTORY — stock & products only ──
        $inventory = Role::firstOrCreate(['name' => 'inventory']);
        $inventory->syncPermissions([
            'view_products',
            'manage_products',
            'manage_categories',
            'manage_stock',
            'manage_suppliers',
            'view_stock_valuation',
        ]);

        $this->command->info('Permissions seeded successfully.');
    }
}