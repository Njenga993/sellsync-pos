<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // For each tenant's main branch, seed existing product stock
        $tenants = DB::table('tenants')->get();
        
        foreach ($tenants as $tenant) {
            $mainBranch = DB::table('branches')
                ->where('tenant_id', $tenant->id)
                ->where('is_main', true)
                ->first();
                
            if (!$mainBranch) continue;
            
            $products = DB::table('products')
                ->where('tenant_id', $tenant->id)
                ->where('track_stock', true)
                ->get();
                
            foreach ($products as $product) {
                DB::table('branch_product')->insert([
                    'tenant_id'       => $tenant->id,
                    'branch_id'       => $mainBranch->id,
                    'product_id'      => $product->id,
                    'stock_qty'       => $product->stock_qty ?? 0,
                    'low_stock_alert' => $product->low_stock_alert ?? 5,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('branch_product')->truncate();
    }
};