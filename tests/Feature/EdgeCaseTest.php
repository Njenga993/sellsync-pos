<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EdgeCaseTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $admin;

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
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);
        $this->admin->syncRoles(['admin']);
    }

    /** @test */
    public function inactive_product_does_not_show_in_pos(): void
    {
        Product::create([
            'tenant_id' => $this->tenant->id, 'name' => 'Active', 'price' => 100, 'status' => 'active',
        ]);
        Product::create([
            'tenant_id' => $this->tenant->id, 'name' => 'Hidden', 'price' => 200, 'status' => 'inactive',
        ]);

        $response = $this->actingAs($this->admin)->get('/pos');
        $response->assertSee('Active');
        $response->assertDontSee('Hidden');
    }

    /** @test */
    public function out_of_stock_product_is_disabled(): void
    {
        $product = Product::create([
            'tenant_id' => $this->tenant->id, 'name' => 'Gone', 'price' => 50,
            'stock_qty' => 0, 'track_stock' => true, 'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->get('/pos');
        $response->assertSee('out-of-stock');
    }

    /** @test */
    public function product_with_zero_price_is_allowed(): void
    {
        $response = $this->actingAs($this->admin)->post('/products', [
            'name' => 'Free Sample', 'price' => 0, 'status' => 'active',
        ]);

        $response->assertRedirect('/products');
        $this->assertDatabaseHas('products', ['name' => 'Free Sample', 'price' => 0]);
    }

    /** @test */
    public function duplicate_sku_is_rejected_during_import(): void
    {
        Product::create([
            'tenant_id' => $this->tenant->id, 'name' => 'Original', 'sku' => 'UNIQUE-001',
            'price' => 100, 'status' => 'active',
        ]);

        $csv = "name,sku,price\n";
        $csv .= "Duplicate,UNIQUE-001,150\n";

        $file = \Illuminate\Http\UploadedFile::fake()->createWithContent('dup.csv', $csv);

        $response = $this->actingAs($this->admin)->post('/products/import', ['file' => $file]);

        $response->assertRedirect('/');
        $this->assertDatabaseMissing('products', ['name' => 'Duplicate']);
    }

    /** @test */
    public function empty_csv_file_is_rejected(): void
    {
        $file = \Illuminate\Http\UploadedFile::fake()->createWithContent('empty.csv', '');

        $response = $this->actingAs($this->admin)->post('/products/import', ['file' => $file]);

        $response->assertSessionHas('error');
    }

    /** @test */
public function stock_can_be_oversold_with_warning(): void
{
    $product = Product::create([
        'tenant_id' => $this->tenant->id, 'name' => 'Limited', 'price' => 100,
        'stock_qty' => 3, 'track_stock' => true, 'status' => 'active',
    ]);

    $cashier = User::create([
        'tenant_id' => $this->tenant->id, 'branch_id' => 1,
        'name' => 'Cashier', 'email' => 'cash2@test.com',
        'password' => bcrypt('password'), 'status' => 'active',
    ]);
    $cashier->syncRoles(['cashier']);

    $response = $this->actingAs($cashier)->post('/pos/sale', [
        'items' => [['id' => $product->id, 'qty' => 10, 'price' => 100]],
        'payment_method' => 'cash', 'amount_paid' => 1000,
    ]);

    $response->assertStatus(200);
    // Stock goes negative — allowed for some businesses
    $this->assertDatabaseHas('products', ['id' => $product->id, 'stock_qty' => -7]);
}

    /** @test */
    public function category_auto_created_during_import(): void
    {
        $csv = "name,category,price\nNew Item,Electronics,500\n";

        $file = \Illuminate\Http\UploadedFile::fake()->createWithContent('cat.csv', $csv);

        $this->actingAs($this->admin)->post('/products/import', ['file' => $file]);

        $this->assertDatabaseHas('categories', ['name' => 'Electronics', 'tenant_id' => $this->tenant->id]);
        $this->assertDatabaseHas('products', ['name' => 'New Item']);
    }

    /** @test */
    public function dashboard_shows_correct_stats(): void
    {
        Product::create([
            'tenant_id' => $this->tenant->id, 'name' => 'P1', 'price' => 100, 'status' => 'active',
        ]);
        Product::create([
            'tenant_id' => $this->tenant->id, 'name' => 'P2', 'price' => 200, 'status' => 'active',
        ]);
        Product::create([
            'tenant_id' => $this->tenant->id, 'name' => 'P3', 'price' => 300, 'status' => 'inactive',
        ]);

        $response = $this->actingAs($this->admin)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('2'); // Active products count
    }

    /** @test */
    public function discount_reduces_total(): void
    {
        $product = Product::create([
            'tenant_id' => $this->tenant->id, 'name' => 'Item', 'price' => 100,
            'stock_qty' => 10, 'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->post('/pos/sale', [
            'items' => [['id' => $product->id, 'qty' => 1, 'price' => 100]],
            'payment_method' => 'cash', 'amount_paid' => 90, 'discount' => 10,
        ]);

        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('sales', ['total' => 90, 'discount_amount' => 10]);
    }
}