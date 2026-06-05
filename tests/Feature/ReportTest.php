<?php

namespace Tests\Feature;

use App\Models\Branch;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $branch;
    protected $admin;
    protected $manager;
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

        $this->manager = User::create([
            'tenant_id' => $this->tenant->id,
            'branch_id' => $this->branch->id,
            'name' => 'Manager',
            'email' => 'manager@test.com',
            'password' => bcrypt('Password-123!'),
            'status' => 'active',
        ]);
        $this->manager->syncRoles(['manager']);

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
    public function admin_can_access_sales_report(): void
    {
        $response = $this->actingAs($this->admin)->get('/reports/sales');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_access_profit_loss(): void
    {
        $response = $this->actingAs($this->admin)->get('/reports/profit-loss');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_access_stock_valuation(): void
    {
        $response = $this->actingAs($this->admin)->get('/reports/stock-valuation');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_access_cashier_performance(): void
    {
        $response = $this->actingAs($this->admin)->get('/reports/cashier-performance');
        $response->assertStatus(200);
    }

    /** @test */
    public function cashier_cannot_access_reports(): void
    {
        $response = $this->actingAs($this->cashier)->get('/reports/sales');
        $response->assertRedirect('/pos');
    }

    /** @test */
    public function manager_can_access_all_reports(): void
    {
        $response = $this->actingAs($this->manager)->get('/reports/sales');
        $response->assertStatus(200);

        $response = $this->actingAs($this->manager)->get('/reports/profit-loss');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_access_expenses(): void
    {
        $response = $this->actingAs($this->admin)->get('/expenses');
        $response->assertStatus(200);
    }

    /** @test */
    public function cashier_cannot_access_expenses(): void
    {
        $response = $this->actingAs($this->cashier)->get('/expenses');
        $response->assertRedirect('/pos');
    }
}