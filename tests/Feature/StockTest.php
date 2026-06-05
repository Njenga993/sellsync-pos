<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $branch;
    protected $admin;
    protected $inventory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'PermissionSeeder']);

        $this->tenant = Tenant::create([
            'id' => 'test-tenant',
            'name' => 'Test Shop',
            'email' => 'test@shop.com',
            'plan' => 'basic',
            'status' => 'active',
        ]);

        $this->branch = Branch::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Main Branch',
        ]);

        $this->admin = User::create([
            'tenant_id' => $this->tenant->id,
            'branch_id' => $this->branch->id,
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('Password-123!'),
            'status' => 'active',
        ]);
        $this->admin->syncRoles(['admin']);

        $this->inventory = User::create([
            'tenant_id' => $this->tenant->id,
            'branch_id' => $this->branch->id,
            'name' => 'Inventory',
            'email' => 'inventory@test.com',
            'password' => bcrypt('Password-123!'),
            'status' => 'active',
        ]);
        $this->inventory->syncRoles(['inventory']);
    }

    /** @test */
    public function admin_can_adjust_stock(): void
    {
        $product = Product::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Test Product',
            'price' => 100,
            'cost_price' => 50,
            'stock_qty' => 10,
            'track_stock' => true,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->post('/stock/adjust', [
            'product_id' => $product->id,
            'type' => 'stock_in',
            'qty' => 5,
            'reference' => 'PO-001',
        ]);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock_qty' => 15]);
        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'type' => 'stock_in',
            'qty' => 5,
        ]);
    }

    /** @test */
    public function stock_out_reduces_quantity(): void
    {
        $product = Product::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Test Product',
            'price' => 100,
            'stock_qty' => 20,
            'track_stock' => true,
            'status' => 'active',
        ]);

        $this->actingAs($this->admin)->post('/stock/adjust', [
            'product_id' => $product->id,
            'type' => 'stock_out',
            'qty' => 8,
        ]);

        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock_qty' => 12]);
    }

    /** @test */
    public function inventory_can_access_stock(): void
    {
        $response = $this->actingAs($this->inventory)->get('/stock');
        $response->assertStatus(200);
    }

    /** @test */
    public function cashier_cannot_access_stock(): void
    {
        $cashier = User::create([
            'tenant_id' => $this->tenant->id,
            'branch_id' => $this->branch->id,
            'name' => 'Cashier',
            'email' => 'cashier@test.com',
            'password' => bcrypt('Password-123!'),
            'status' => 'active',
        ]);
        $cashier->syncRoles(['cashier']);

        $response = $this->actingAs($cashier)->get('/stock');
        $response->assertRedirect('/pos');
    }

    /** @test */
    public function low_stock_detection_works(): void
    {
        $product = Product::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Low Stock Item',
            'price' => 50,
            'stock_qty' => 3,
            'low_stock_alert' => 10,
            'track_stock' => true,
            'status' => 'active',
        ]);

        $this->assertTrue($product->isLowStock());

        $product->update(['stock_qty' => 15]);
        $this->assertFalse($product->fresh()->isLowStock());
    }

    /** @test */
    public function stock_movement_is_recorded(): void
    {
        $product = Product::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Tracked Product',
            'price' => 200,
            'stock_qty' => 30,
            'track_stock' => true,
            'status' => 'active',
        ]);

        $this->actingAs($this->admin)->post('/stock/adjust', [
            'product_id' => $product->id,
            'type' => 'stock_in',
            'qty' => 10,
            'reference' => 'Supplier Delivery',
            'notes' => 'Monthly restock',
        ]);

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'qty' => 10,
            'before_qty' => 30,
            'after_qty' => 40,
            'reference' => 'Supplier Delivery',
        ]);
    }
}