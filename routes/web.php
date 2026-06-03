<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Modules\Inventory\Controllers\CategoryController;
use App\Http\Controllers\Modules\Inventory\Controllers\ProductController;
use App\Http\Controllers\Modules\CRM\Controllers\CustomerController;
use App\Http\Controllers\Modules\Staff\Controllers\StaffController;
use App\Http\Controllers\Modules\Sales\Controllers\PosController;
use App\Http\Controllers\Modules\Reports\Controllers\SalesReportController;
use App\Http\Controllers\Modules\Inventory\Controllers\StockController;
use App\Http\Controllers\Modules\Inventory\Controllers\SupplierController;
use App\Http\Controllers\Modules\Inventory\Controllers\PurchaseOrderController;
use App\Http\Controllers\Modules\Reports\Controllers\ExpenseController;
use App\Http\Controllers\Modules\Sales\Controllers\SaleReturnController;
use App\Http\Controllers\Modules\Reports\Controllers\AdvancedReportController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;
use App\Http\Controllers\SuperAdmin\TenantManagementController;
use App\Http\Controllers\Modules\Settings\SettingsController;


// Welcome page
Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', function () {
    $tenantId = auth()->user()->tenant_id;
    $today    = now()->toDateString();

    $todaySales = \App\Models\Sale::where('tenant_id', $tenantId)
        ->whereDate('created_at', $today)
        ->get();

    $stats = [
        'today_total'        => $todaySales->sum('total'),
        'today_transactions' => $todaySales->count(),
        'products'           => \App\Models\Product::where('tenant_id', $tenantId)->where('status', 'active')->count(),
        'customers'          => \App\Models\Customer::where('tenant_id', $tenantId)->count(),
        'low_stock'          => \App\Models\Product::where('tenant_id', $tenantId)
                                    ->where('track_stock', true)
                                    ->whereColumn('stock_qty', '<=', 'low_stock_alert')
                                    ->count(),
    ];

    return view('dashboard', compact('stats'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ── Super Admin Panel ──────────────────────────────────────────
Route::prefix('superadmin')
    ->middleware(['auth', 'super_admin'])
    ->name('superadmin.')
    ->group(function () {
        Route::get('/dashboard',                          [SuperAdminController::class,      'dashboard'])->name('dashboard');
        Route::get('/tenants',                            [TenantManagementController::class, 'index'])->name('tenants.index');
        Route::get('/tenants/{tenant}',                   [TenantManagementController::class, 'show'])->name('tenants.show');
        Route::post('/tenants/{tenant}/toggle-status',    [TenantManagementController::class, 'toggleStatus'])->name('tenants.toggle-status');
        Route::post('/tenants/{tenant}/update-plan',      [TenantManagementController::class, 'updatePlan'])->name('tenants.update-plan');
        Route::delete('/tenants/{tenant}',                [TenantManagementController::class, 'destroy'])->name('tenants.destroy');
    });

// ── All authenticated tenant routes ───────────────────────────
Route::middleware(['auth'])->group(function () {

    // POS
    Route::get('/pos',                [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/sale',          [PosController::class, 'store'])->name('pos.store');
    Route::get('/pos/receipt/{sale}', [PosController::class, 'receipt'])->name('pos.receipt');

    // Inventory
    Route::resource('categories',     CategoryController::class);
    Route::resource('products',       ProductController::class);

    // Stock
    Route::get('/stock',         [StockController::class, 'index'])->name('stock.index');
    Route::get('/stock/history', [StockController::class, 'history'])->name('stock.history');
    Route::post('/stock/adjust', [StockController::class, 'adjust'])->name('stock.adjust');

    // Suppliers & Purchase Orders
    Route::resource('suppliers',       SupplierController::class)->except(['show']);
    Route::resource('purchase-orders', PurchaseOrderController::class)->except(['edit', 'update']);
    Route::post('/purchase-orders/{purchaseOrder}/receive',
        [PurchaseOrderController::class, 'receive'])->name('purchase-orders.receive');

    // CRM
    Route::resource('customers', CustomerController::class);

    // Staff
    Route::resource('staff', StaffController::class)->except(['show']);

    // Sales Reports
    Route::get('/reports/sales',               [SalesReportController::class,    'index'])->name('reports.sales');
    Route::get('/reports/profit-loss',         [AdvancedReportController::class, 'profitLoss'])->name('reports.profit-loss');
    Route::get('/reports/stock-valuation',     [AdvancedReportController::class, 'stockValuation'])->name('reports.stock-valuation');
    Route::get('/reports/cashier-performance', [AdvancedReportController::class, 'cashierPerformance'])->name('reports.cashier-performance');

    // Expenses
    Route::get('/expenses',               [ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('/expenses',              [ExpenseController::class, 'store'])->name('expenses.store');
    Route::delete('/expenses/{expense}',  [ExpenseController::class, 'destroy'])->name('expenses.destroy');
    Route::post('/expense-categories',    [ExpenseController::class, 'storeCategory'])->name('expense-categories.store');

    // Returns
    Route::get('/returns',           [SaleReturnController::class, 'index'])->name('returns.index');
    Route::get('/returns/create',    [SaleReturnController::class, 'create'])->name('returns.create');
    Route::post('/returns',          [SaleReturnController::class, 'store'])->name('returns.store');
    Route::get('/returns/{return}',  [SaleReturnController::class, 'show'])->name('returns.show');

    // Settings
    Route::get('/settings',  [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
});

require __DIR__.'/auth.php';
