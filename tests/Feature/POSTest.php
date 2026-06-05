<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class POSTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $admin;
    protected $cashier;

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

        $branch = Branch::create(['tenant_id' => $this->tenant->id, 'name' => 'Main']);

        $this->admin = User::create([
            'tenant_id' => $this->tenant->id,
            'branch_id' => $branch->id,
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('Password-123!'),
            'status' => 'active',
        ]);
        $this->admin->syncRoles(['admin']);

        $this->cashier = User::create([
            'tenant_id' => $this->tenant->id,
            'branch_id' => $branch->id,
            'name' => 'Cashier',
            'email' => 'cashier@test.com',
            'password' => bcrypt('Password-123!'),
            'status' => 'active',
        ]);
        $this->cashier->syncRoles(['cashier']);
    }

    /** @test */
    public function pos_sale_creates_transaction(): void
    {
        $product = Product::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Test Item',
            'price' => 100,
            'cost_price' => 50,
            'stock_qty' => 50,
            'track_stock' => true,
            'status' => 'active',
        ]);

        $customer = Customer::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'POS Customer',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->cashier)->post('/pos/sale', [
            'items' => [
                ['id' => $product->id, 'qty' => 2, 'price' => 100],
            ],
            'payment_method' => 'cash',
            'amount_paid' => 200,
            'customer_id' => $customer->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('sales', [
            'tenant_id' => $this->tenant->id,
            'total' => 200,
            'payment_method' => 'cash',
        ]);

        $this->assertDatabaseHas('sale_items', [
            'product_name' => 'Test Item',
            'qty' => 2,
        ]);
    }

    /** @test */
    public function pos_sale_reduces_stock(): void
    {
        $product = Product::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Stock Item',
            'price' => 50,
            'stock_qty' => 30,
            'track_stock' => true,
            'status' => 'active',
        ]);

        $this->actingAs($this->cashier)->post('/pos/sale', [
            'items' => [
                ['id' => $product->id, 'qty' => 5, 'price' => 50],
            ],
            'payment_method' => 'mobile',
            'amount_paid' => 250,
        ]);

        $this->assertDatabaseHas('products', ['id' => $product->id, 'stock_qty' => 25]);
    }

    /** @test */
    public function pos_sale_records_stock_movement(): void
    {
        $product = Product::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Movement Item',
            'price' => 100,
            'stock_qty' => 20,
            'track_stock' => true,
            'status' => 'active',
        ]);

        $this->actingAs($this->cashier)->post('/pos/sale', [
            'items' => [
                ['id' => $product->id, 'qty' => 3, 'price' => 100],
            ],
            'payment_method' => 'card',
            'amount_paid' => 300,
        ]);

        $this->assertDatabaseHas('stock_movements', [
            'product_id' => $product->id,
            'type' => 'sale',
            'qty' => -3,
        ]);
    }

    /** @test */
    public function pos_sale_awards_loyalty_points(): void
    {
        $product = Product::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Loyalty Item',
            'price' => 500,
            'stock_qty' => 10,
            'status' => 'active',
        ]);

        $customer = Customer::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Loyal Customer',
            'loyalty_points' => 0,
            'status' => 'active',
        ]);

        $this->actingAs($this->cashier)->post('/pos/sale', [
            'items' => [
                ['id' => $product->id, 'qty' => 1, 'price' => 500],
            ],
            'payment_method' => 'cash',
            'amount_paid' => 500,
            'customer_id' => $customer->id,
        ]);

        $this->assertDatabaseHas('customers', ['id' => $customer->id]);
        $this->assertTrue(Customer::find($customer->id)->loyalty_points > 0);
    }

    /** @test */
    public function pos_sale_with_split_payment(): void
    {
        $product = Product::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Split Payment Item',
            'price' => 1000,
            'stock_qty' => 5,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->cashier)->post('/pos/sale', [
            'items' => [
                ['id' => $product->id, 'qty' => 1, 'price' => 1000],
            ],
            'payment_method' => 'split',
            'split_payments' => ['cash' => 400, 'mobile' => 600],
            'amount_paid' => 1000,
        ]);

        $response->assertJson(['success' => true]);
    }

    /** @test */
    public function pos_sale_rejects_underpayment(): void
    {
        $product = Product::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Pricey Item',
            'price' => 1000,
            'stock_qty' => 5,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->cashier)->post('/pos/sale', [
            'items' => [
                ['id' => $product->id, 'qty' => 1, 'price' => 1000],
            ],
            'payment_method' => 'cash',
            'amount_paid' => 500,
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function inventory_cannot_make_sale(): void
    {
        $inventory = User::create([
            'tenant_id' => $this->tenant->id,
            'branch_id' => 1,
            'name' => 'Inventory',
            'email' => 'inv@test.com',
            'password' => bcrypt('Password-123!'),
            'status' => 'active',
        ]);
        $inventory->syncRoles(['inventory']);

        $response = $this->actingAs($inventory)->get('/pos');
        $response->assertStatus(403);
    }
}