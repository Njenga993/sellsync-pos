<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $branch;
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

        $this->cashier = User::create([
            'tenant_id' => $this->tenant->id,
            'branch_id' => $this->branch->id,
            'name' => 'Cashier',
            'email' => 'cashier@test.com',
            'password' => bcrypt('Password-123!'),
            'status' => 'active',
        ]);
        $this->cashier->syncRoles(['cashier']);
    }

    /** @test */
    public function admin_can_create_customer(): void
    {
        $response = $this->actingAs($this->admin)->post('/customers', [
            'name' => 'Jane Doe',
            'phone' => '0712345678',
            'email' => 'jane@example.com',
            'city' => 'Nairobi',
            'status' => 'active',
        ]);

        $response->assertRedirect('/customers');
        $this->assertDatabaseHas('customers', [
            'name' => 'Jane Doe',
            'tenant_id' => $this->tenant->id,
        ]);
    }

    /** @test */
    public function cashier_can_create_customer(): void
    {
        $response = $this->actingAs($this->cashier)->post('/customers', [
            'name' => 'Walk-in Customer',
            'phone' => '0798765432',
            'status' => 'active',
        ]);

        $response->assertRedirect('/customers');
        $this->assertDatabaseHas('customers', ['name' => 'Walk-in Customer']);
    }

    /** @test */
    public function admin_can_update_customer(): void
    {
        $customer = Customer::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Old Name',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->put("/customers/{$customer->id}", [
            'name' => 'Updated Name',
            'status' => 'active',
        ]);

        $response->assertRedirect('/customers');
        $this->assertDatabaseHas('customers', ['id' => $customer->id, 'name' => 'Updated Name']);
    }

    /** @test */
    public function admin_can_delete_customer(): void
    {
        $customer = Customer::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'To Delete',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->delete("/customers/{$customer->id}");

        $response->assertRedirect('/customers');
        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }

    /** @test */
    public function cashier_cannot_delete_customer(): void
    {
        $customer = Customer::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Protected Customer',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->cashier)->delete("/customers/{$customer->id}");

        $response->assertRedirect('/pos');
        $this->assertDatabaseHas('customers', ['id' => $customer->id]);
    }

    /** @test */
    public function customer_validation_requires_name(): void
    {
        $response = $this->actingAs($this->admin)->post('/customers', [
            'name' => '',
            'status' => 'active',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function inventory_cannot_access_customers(): void
    {
        $inventory = User::create([
            'tenant_id' => $this->tenant->id,
            'branch_id' => $this->branch->id,
            'name' => 'Inventory',
            'email' => 'inventory@test.com',
            'password' => bcrypt('Password-123!'),
            'status' => 'active',
        ]);
        $inventory->syncRoles(['inventory']);

        $response = $this->actingAs($inventory)->get('/customers');
        $response->assertStatus(403);
    }
}