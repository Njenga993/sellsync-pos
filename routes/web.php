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

// ── Welcome ──
Route::get('/', function () {
    return view('welcome');
});

// ── Dashboard ──
Route::get('/dashboard', function () {
    $tenantId   = auth()->user()->tenant_id;
    $today      = now()->toDateString();
    $todaySales = \App\Models\Sale::where('tenant_id', $tenantId)
        ->whereDate('created_at', $today)->get();

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
})->middleware(['auth', 'verified', 'role_redirect', 'check_permission:view_dashboard'])->name('dashboard');

// ── Profile ──
Route::middleware('auth')->group(function () {
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ── Super Admin ──
Route::prefix('superadmin')
    ->middleware(['auth', 'super_admin'])
    ->name('superadmin.')
    ->group(function () {
        Route::get('/dashboard',                       [SuperAdminController::class,      'dashboard'])->name('dashboard');
        Route::get('/tenants',                         [TenantManagementController::class, 'index'])->name('tenants.index');
        Route::get('/tenants/{tenant}',                [TenantManagementController::class, 'show'])->name('tenants.show');
        Route::post('/tenants/{tenant}/toggle-status', [TenantManagementController::class, 'toggleStatus'])->name('tenants.toggle-status');
        Route::post('/tenants/{tenant}/update-plan',   [TenantManagementController::class, 'updatePlan'])->name('tenants.update-plan');
        Route::delete('/tenants/{tenant}',             [TenantManagementController::class, 'destroy'])->name('tenants.destroy');
    });

// ── Authenticated tenant routes ──
Route::middleware(['auth'])->group(function () {

    // POS
    Route::get('/pos',                [PosController::class, 'index'])->name('pos.index')->middleware('check_permission:access_pos');
    Route::post('/pos/sale',          [PosController::class, 'store'])->name('pos.store')->middleware('check_permission:access_pos');
    Route::get('/pos/receipt/{sale}', [PosController::class, 'receipt'])->name('pos.receipt')->middleware('check_permission:access_pos');

    // Products
    Route::get('products',               [ProductController::class, 'index'])->name('products.index')->middleware('check_permission:view_products');
    Route::get('products/create',        [ProductController::class, 'create'])->name('products.create')->middleware('check_permission:manage_products');
    Route::post('products',              [ProductController::class, 'store'])->name('products.store')->middleware('check_permission:manage_products');
    Route::get('products/import',        [ProductController::class, 'importForm'])->name('products.import')->middleware('check_permission:manage_products');
    Route::post('products/import',       [ProductController::class, 'import'])->name('products.import.process')->middleware('check_permission:manage_products');
    Route::get('products/{product}',     [ProductController::class, 'show'])->name('products.show')->middleware('check_permission:view_products');
    Route::get('products/{product}/edit',[ProductController::class, 'edit'])->name('products.edit')->middleware('check_permission:manage_products');
    Route::put('products/{product}',     [ProductController::class, 'update'])->name('products.update')->middleware('check_permission:manage_products');
    Route::delete('products/{product}',  [ProductController::class, 'destroy'])->name('products.destroy')->middleware('check_permission:manage_products');

    // Categories
    Route::get('categories',                  [CategoryController::class, 'index'])->name('categories.index')->middleware('check_permission:view_products');
    Route::get('categories/create',           [CategoryController::class, 'create'])->name('categories.create')->middleware('check_permission:manage_categories');
    Route::post('categories',                 [CategoryController::class, 'store'])->name('categories.store')->middleware('check_permission:manage_categories');
    Route::get('categories/{category}/edit',  [CategoryController::class, 'edit'])->name('categories.edit')->middleware('check_permission:manage_categories');
    Route::put('categories/{category}',       [CategoryController::class, 'update'])->name('categories.update')->middleware('check_permission:manage_categories');
    Route::delete('categories/{category}',    [CategoryController::class, 'destroy'])->name('categories.destroy')->middleware('check_permission:manage_categories');

    // Stock
    Route::get('/stock',         [StockController::class, 'index'])->name('stock.index')->middleware('check_permission:manage_stock');
    Route::get('/stock/history', [StockController::class, 'history'])->name('stock.history')->middleware('check_permission:manage_stock');
    Route::post('/stock/adjust', [StockController::class, 'adjust'])->name('stock.adjust')->middleware('check_permission:manage_stock');

    // Suppliers & POs
    Route::get('suppliers',           [SupplierController::class, 'index'])->name('suppliers.index')->middleware('check_permission:manage_suppliers');
    Route::get('suppliers/create',    [SupplierController::class, 'create'])->name('suppliers.create')->middleware('check_permission:manage_suppliers');
    Route::post('suppliers',          [SupplierController::class, 'store'])->name('suppliers.store')->middleware('check_permission:manage_suppliers');
    Route::get('suppliers/{supplier}/edit',  [SupplierController::class, 'edit'])->name('suppliers.edit')->middleware('check_permission:manage_suppliers');
    Route::put('suppliers/{supplier}',       [SupplierController::class, 'update'])->name('suppliers.update')->middleware('check_permission:manage_suppliers');
    Route::delete('suppliers/{supplier}',    [SupplierController::class, 'destroy'])->name('suppliers.destroy')->middleware('check_permission:manage_suppliers');

    Route::get('purchase-orders',                    [PurchaseOrderController::class, 'index'])->name('purchase-orders.index')->middleware('check_permission:manage_suppliers');
    Route::get('purchase-orders/create',             [PurchaseOrderController::class, 'create'])->name('purchase-orders.create')->middleware('check_permission:manage_suppliers');
    Route::post('purchase-orders',                   [PurchaseOrderController::class, 'store'])->name('purchase-orders.store')->middleware('check_permission:manage_suppliers');
    Route::get('purchase-orders/{purchaseOrder}',    [PurchaseOrderController::class, 'show'])->name('purchase-orders.show')->middleware('check_permission:manage_suppliers');
    Route::delete('purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'destroy'])->name('purchase-orders.destroy')->middleware('check_permission:manage_suppliers');
    Route::post('/purchase-orders/{purchaseOrder}/receive', [PurchaseOrderController::class, 'receive'])->name('purchase-orders.receive')->middleware('check_permission:manage_suppliers');

    // Customers
    Route::get('customers',                  [CustomerController::class, 'index'])->name('customers.index')->middleware('check_permission:view_customers');
    Route::get('customers/create',           [CustomerController::class, 'create'])->name('customers.create')->middleware('check_permission:manage_customers');
    Route::post('customers',                 [CustomerController::class, 'store'])->name('customers.store')->middleware('check_permission:manage_customers');
    Route::get('customers/{customer}',       [CustomerController::class, 'show'])->name('customers.show')->middleware('check_permission:view_customers');
    Route::get('customers/{customer}/edit',  [CustomerController::class, 'edit'])->name('customers.edit')->middleware('check_permission:manage_customers');
    Route::put('customers/{customer}',       [CustomerController::class, 'update'])->name('customers.update')->middleware('check_permission:manage_customers');
    Route::delete('customers/{customer}',    [CustomerController::class, 'destroy'])->name('customers.destroy')->middleware('check_permission:delete_customers');

    // Staff
    Route::get('staff',              [StaffController::class, 'index'])->name('staff.index')->middleware('check_permission:manage_staff');
    Route::get('staff/create',       [StaffController::class, 'create'])->name('staff.create')->middleware('check_permission:manage_staff');
    Route::post('staff',             [StaffController::class, 'store'])->name('staff.store')->middleware('check_permission:manage_staff');
    Route::get('staff/{staff}/edit', [StaffController::class, 'edit'])->name('staff.edit')->middleware('check_permission:manage_staff');
    Route::put('staff/{staff}',      [StaffController::class, 'update'])->name('staff.update')->middleware('check_permission:manage_staff');
    Route::delete('staff/{staff}',   [StaffController::class, 'destroy'])->name('staff.destroy')->middleware('check_permission:manage_staff');

    // Reports
    Route::get('/reports/sales',               [SalesReportController::class,    'index'])->name('reports.sales')->middleware('check_permission:view_reports');
    Route::get('/reports/profit-loss',         [AdvancedReportController::class, 'profitLoss'])->name('reports.profit-loss')->middleware('check_permission:view_profit_loss');
    Route::get('/reports/stock-valuation',     [AdvancedReportController::class, 'stockValuation'])->name('reports.stock-valuation')->middleware('check_permission:view_stock_valuation');
    Route::get('/reports/cashier-performance', [AdvancedReportController::class, 'cashierPerformance'])->name('reports.cashier-performance')->middleware('check_permission:view_cashier_performance');

    // Report Exports — admin & manager
    Route::get('/reports/sales/export', [SalesReportController::class, 'exportPdf'])->name('reports.sales.export')->middleware('check_permission:view_reports');
    Route::get('/reports/profit-loss/export', [AdvancedReportController::class, 'exportProfitLossPdf'])->name('reports.profit-loss.export')->middleware('check_permission:view_profit_loss');
    Route::get('/reports/stock-valuation/export', [AdvancedReportController::class, 'exportStockValuationPdf'])->name('reports.stock-valuation.export')->middleware('check_permission:view_stock_valuation');
    Route::get('/reports/cashier-performance/export', [AdvancedReportController::class, 'exportCashierPerformancePdf'])->name('reports.cashier-performance.export')->middleware('check_permission:view_cashier_performance');
    Route::get('/expenses/export', [ExpenseController::class, 'exportPdf'])->name('expenses.export')->middleware('check_permission:manage_expenses');

    // Expenses
    Route::get('/expenses',               [ExpenseController::class, 'index'])->name('expenses.index')->middleware('check_permission:manage_expenses');
    Route::post('/expenses',              [ExpenseController::class, 'store'])->name('expenses.store')->middleware('check_permission:manage_expenses');
    Route::delete('/expenses/{expense}',  [ExpenseController::class, 'destroy'])->name('expenses.destroy')->middleware('check_permission:manage_expenses');
    Route::post('/expense-categories',    [ExpenseController::class, 'storeCategory'])->name('expense-categories.store')->middleware('check_permission:manage_expenses');

    // Returns
    Route::get('/returns',           [SaleReturnController::class, 'index'])->name('returns.index')->middleware('check_permission:process_returns');
    Route::get('/returns/create',    [SaleReturnController::class, 'create'])->name('returns.create')->middleware('check_permission:process_returns');
    Route::post('/returns',          [SaleReturnController::class, 'store'])->name('returns.store')->middleware('check_permission:process_returns');
    Route::get('/returns/{return}',  [SaleReturnController::class, 'show'])->name('returns.show')->middleware('check_permission:process_returns');

    // Settings
    Route::get('/settings',  [SettingsController::class, 'index'])->name('settings.index')->middleware('check_permission:manage_settings');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update')->middleware('check_permission:manage_settings');
});

require __DIR__.'/auth.php';