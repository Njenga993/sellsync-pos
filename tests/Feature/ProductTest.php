<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
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
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);
        $this->admin->syncRoles(['admin']);

        $this->inventory = User::create([
            'tenant_id' => $this->tenant->id,
            'branch_id' => $this->branch->id,
            'name' => 'Inventory User',
            'email' => 'inventory@test.com',
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);
        $this->inventory->syncRoles(['inventory']);
    }

    /** @test */
    public function admin_can_update_product(): void
    {
        $category = Category::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Drinks',
            'slug' => 'drinks',
            'status' => 'active',
        ]);

        $product = Product::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Old Name',
            'price' => 100,
            'cost_price' => 50,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->put("/products/{$product->id}", [
            'name' => 'Updated Name',
            'price' => 200,
            'cost_price' => 100,
            'status' => 'active',
        ]);

        $response->assertRedirect('/products');
        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'Updated Name', 'price' => 200]);
    }

    /** @test */
    public function admin_can_delete_product(): void
    {
        $product = Product::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'To Delete',
            'price' => 50,
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->delete("/products/{$product->id}");

        $response->assertRedirect('/products');
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    /** @test */
    public function product_validation_requires_name(): void
    {
        $response = $this->actingAs($this->admin)->post('/products', [
            'name' => '',
            'price' => 100,
            'status' => 'active',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function product_validation_requires_price(): void
    {
        $response = $this->actingAs($this->admin)->post('/products', [
            'name' => 'Test',
            'price' => '',
            'status' => 'active',
        ]);

        $response->assertSessionHasErrors('price');
    }

    /** @test */
    public function cashier_cannot_create_product(): void
    {
        $cashier = User::create([
            'tenant_id' => $this->tenant->id,
            'branch_id' => $this->branch->id,
            'name' => 'Cashier',
            'email' => 'cashier@test.com',
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);
        $cashier->syncRoles(['cashier']);

        $response = $this->actingAs($cashier)->post('/products', [
            'name' => 'Test',
            'price' => 100,
            'status' => 'active',
        ]);

        $response->assertRedirect('/pos');
    }

    /** @test */
    public function products_are_scoped_to_tenant(): void
    {
        // Create product for main tenant
        Product::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Tenant A Product',
            'price' => 100,
            'status' => 'active',
        ]);

        // Create another tenant
        $tenantB = Tenant::create([
            'id' => 'tenant-b',
            'name' => 'Tenant B',
            'email' => 'b@test.com',
            'plan' => 'basic',
            'status' => 'active',
        ]);

        $productB = Product::create([
            'tenant_id' => $tenantB->id,
            'name' => 'Tenant B Product',
            'price' => 200,
            'status' => 'active',
        ]);

        // Admin from Tenant A should NOT see Tenant B's product
        $response = $this->actingAs($this->admin)->get('/products');
        $response->assertSee('Tenant A Product');
        $response->assertDontSee('Tenant B Product');
    }
}