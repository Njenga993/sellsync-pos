<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Supplier;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierTest extends TestCase
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
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);
        $this->admin->syncRoles(['admin']);

        $this->cashier = User::create([
            'tenant_id' => $this->tenant->id,
            'branch_id' => $branch->id,
            'name' => 'Cashier',
            'email' => 'cashier@test.com',
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);
        $this->cashier->syncRoles(['cashier']);
    }

    /** @test */
    public function admin_can_create_supplier(): void
    {
        $response = $this->actingAs($this->admin)->post('/suppliers', [
            'name' => 'ABC Distributors',
            'contact_person' => 'John',
            'phone' => '0711222333',
            'email' => 'abc@suppliers.com',
            'city' => 'Nairobi',
            'status' => 'active',
        ]);

        $response->assertRedirect('/suppliers');
        $this->assertDatabaseHas('suppliers', ['name' => 'ABC Distributors']);
    }

    /** @test */
    public function admin_can_update_supplier(): void
    {
        $supplier = Supplier::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Old Supplier',
            'status' => 'active',
        ]);

        $response = $this->actingAs($this->admin)->put("/suppliers/{$supplier->id}", [
            'name' => 'Updated Supplier',
            'status' => 'active',
        ]);

        $response->assertRedirect('/suppliers');
        $this->assertDatabaseHas('suppliers', ['id' => $supplier->id, 'name' => 'Updated Supplier']);
    }

    /** @test */
    public function admin_can_delete_supplier(): void
    {
        $supplier = Supplier::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Delete Me',
            'status' => 'active',
        ]);

        $this->actingAs($this->admin)->delete("/suppliers/{$supplier->id}");
        $this->assertDatabaseMissing('suppliers', ['id' => $supplier->id]);
    }

    /** @test */
    public function cashier_cannot_access_suppliers(): void
    {
        $response = $this->actingAs($this->cashier)->get('/suppliers');
        $response->assertRedirect('/pos');
    }

    /** @test */
    public function supplier_validation_requires_name(): void
    {
        $response = $this->actingAs($this->admin)->post('/suppliers', [
            'name' => '',
            'status' => 'active',
        ]);

        $response->assertSessionHasErrors('name');
    }
}