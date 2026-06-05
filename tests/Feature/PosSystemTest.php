<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PosSystemTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $branch;
    protected $admin;
    protected $cashier;
    protected $manager;
    protected $inventory;

    protected function setUp(): void
    {
        parent::setUp();

        // Run permission seeder
        $this->artisan('db:seed', ['--class' => 'PermissionSeeder']);

        // Create tenant
        $this->tenant = Tenant::create([
            'id' => 'test-tenant',
            'name' => 'Test Shop',
            'email' => 'test@shop.com',
            'plan' => 'basic',
            'status' => 'active',
        ]);

        // Create branch
        $this->branch = Branch::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Main Branch',
        ]);

        // Create users with roles
        $this->admin = User::create([
            'tenant_id' => $this->tenant->id,
            'branch_id' => $this->branch->id,
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('Password-123!'),
            'status' => 'active',
        ]);
        $this->admin->syncRoles(['admin']);

        $this->manager = User::create([
            'tenant_id' => $this->tenant->id,
            'branch_id' => $this->branch->id,
            'name' => 'Manager User',
            'email' => 'manager@test.com',
            'password' => bcrypt('Password-123!'),
            'status' => 'active',
        ]);
        $this->manager->syncRoles(['manager']);

        $this->cashier = User::create([
            'tenant_id' => $this->tenant->id,
            'branch_id' => $this->branch->id,
            'name' => 'Cashier User',
            'email' => 'cashier@test.com',
            'password' => bcrypt('Password-123!'),
            'status' => 'active',
        ]);
        $this->cashier->syncRoles(['cashier']);

        $this->inventory = User::create([
            'tenant_id' => $this->tenant->id,
            'branch_id' => $this->branch->id,
            'name' => 'Inventory User',
            'email' => 'inventory@test.com',
            'password' => bcrypt('Password-123!'),
            'status' => 'active',
        ]);
        $this->inventory->syncRoles(['inventory']);
    }

    /** @test */
    public function admin_can_access_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get('/dashboard');
        $response->assertStatus(200);
    }

    /** @test */
    public function cashier_is_redirected_to_pos(): void
    {
        $response = $this->actingAs($this->cashier)->get('/dashboard');
        $response->assertRedirect('/pos');
    }

    /** @test */
    public function inventory_is_redirected_to_stock(): void
    {
        $response = $this->actingAs($this->inventory)->get('/dashboard');
        $response->assertRedirect('/stock');
    }

    /** @test */
public function cashier_cannot_access_staff_page(): void
{
    $response = $this->actingAs($this->cashier)->get('/staff');
    $response->assertRedirect('/pos');
}

   /** @test */
public function cashier_cannot_access_settings(): void
{
    $response = $this->actingAs($this->cashier)->get('/settings');
    $response->assertRedirect('/pos');
}

    /** @test */
    public function manager_cannot_access_staff_page(): void
    {
        $response = $this->actingAs($this->manager)->get('/staff');
        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_access_staff_page(): void
    {
        $response = $this->actingAs($this->admin)->get('/staff');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_create_product(): void
    {
        $category = Category::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Beverages',
            'slug' => 'beverages',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->post('/products', [
            'name' => 'Test Product',
            'category_id' => $category->id,
            'price' => 150.00,
            'cost_price' => 100.00,
            'stock_qty' => 50,
            'low_stock_alert' => 10,
            'track_stock' => true,
            'status' => 'active',
        ]);

        $response->assertRedirect('/products');
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'tenant_id' => $this->tenant->id,
        ]);
    }

    /** @test */
    public function cashier_can_access_pos(): void
    {
        $response = $this->actingAs($this->cashier)->get('/pos');
        $response->assertStatus(200);
    }

    /** @test */
    public function inventory_cannot_access_pos(): void
    {
        $response = $this->actingAs($this->inventory)->get('/pos');
        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_import_products(): void
    {
        $csvContent = "name,sku,barcode,category,price,cost_price,tax_rate,stock_qty,low_stock_alert\n";
        $csvContent .= "Coca Cola,BEV-001,123,Drinks,150,100,16,50,10\n";
        $csvContent .= "Fanta,BEV-002,124,Drinks,150,100,16,40,10\n";

        $file = \Illuminate\Http\UploadedFile::fake()->createWithContent(
            'products.csv',
            $csvContent
        );

        $response = $this->actingAs($this->admin)->post('/products/import', [
            'file' => $file,
        ]);

        $response->assertRedirect('/products');
        $this->assertDatabaseHas('products', ['name' => 'Coca Cola']);
        $this->assertDatabaseHas('products', ['name' => 'Fanta']);
    }
}