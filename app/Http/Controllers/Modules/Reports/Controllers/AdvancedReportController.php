<?php

namespace App\Http\Controllers\Modules\Reports\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SaleReturn;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdvancedReportController extends Controller
{
    public function profitLoss(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;
        $from     = $request->from ?? now()->startOfMonth()->toDateString();
        $to       = $request->to   ?? now()->toDateString();

        $revenue = Sale::where('tenant_id', $tenantId)
            ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
            ->where('status', 'completed')
            ->sum('total');

        $cogs = SaleItem::whereHas('sale', fn($q) => $q
            ->where('tenant_id', $tenantId)
            ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
            ->where('status', 'completed')
        )->selectRaw('SUM(cost_price * qty) as total')->value('total') ?? 0;

        $expenses = Expense::where('tenant_id', $tenantId)
            ->whereBetween('expense_date', [$from, $to])
            ->sum('amount');

        $refunds = SaleReturn::where('tenant_id', $tenantId)
            ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
            ->sum('total_refund');

        $grossProfit = $revenue - $cogs - $refunds;
        $netProfit   = $grossProfit - $expenses;

        $monthlySales = Sale::where('tenant_id', $tenantId)
            ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total) as revenue, COUNT(*) as transactions')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $expensesByCategory = Expense::where('tenant_id', $tenantId)
            ->whereBetween('expense_date', [$from, $to])
            ->with('category')
            ->selectRaw('expense_category_id, SUM(amount) as total')
            ->groupBy('expense_category_id')
            ->orderByDesc('total')
            ->get();

        return view('modules.reports.profit_loss', compact(
            'revenue', 'cogs', 'expenses', 'refunds',
            'grossProfit', 'netProfit', 'monthlySales',
            'expensesByCategory', 'from', 'to'
        ));
    }

    public function stockValuation(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;

        $products = Product::where('tenant_id', $tenantId)
            ->where('track_stock', true)
            ->with('category')
            ->orderBy('stock_qty')
            ->get()
            ->map(function ($product) {
                $product->cost_value  = $product->stock_qty * $product->cost_price;
                $product->sell_value  = $product->stock_qty * $product->price;
                $product->potential   = $product->sell_value - $product->cost_value;
                return $product;
            });

        $summary = [
            'total_cost_value' => $products->sum('cost_value'),
            'total_sell_value' => $products->sum('sell_value'),
            'total_potential'  => $products->sum('potential'),
            'total_products'   => $products->count(),
            'out_of_stock'     => $products->where('stock_qty', '<=', 0)->count(),
            'low_stock'        => $products->filter(fn($p) => $p->isLowStock() && $p->stock_qty > 0)->count(),
        ];

        $byCategory = $products->groupBy('category.name')
            ->map(fn($group) => [
                'cost_value' => $group->sum('cost_value'),
                'sell_value' => $group->sum('sell_value'),
                'count'      => $group->count(),
            ]);

        return view('modules.reports.stock_valuation', compact(
            'products', 'summary', 'byCategory'
        ));
    }

    public function cashierPerformance(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;
        $from     = $request->from ?? now()->startOfMonth()->toDateString();
        $to       = $request->to   ?? now()->toDateString();

        $cashiers = User::where('tenant_id', $tenantId)
            ->with('roles', 'branch')
            ->get()
            ->map(function ($user) use ($tenantId, $from, $to) {
                $sales = Sale::where('tenant_id', $tenantId)
                    ->where('user_id', $user->id)
                    ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
                    ->where('status', 'completed');

                $user->total_sales       = $sales->count();
                $user->total_revenue     = $sales->sum('total');
                $user->avg_sale          = $sales->count() > 0
                    ? round($sales->sum('total') / $sales->count(), 2) : 0;
                $user->total_items       = SaleItem::whereHas('sale', fn($q) => $q
                    ->where('tenant_id', $tenantId)
                    ->where('user_id', $user->id)
                    ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
                )->sum('qty');
                $user->total_returns     = SaleReturn::where('tenant_id', $tenantId)
                    ->where('user_id', $user->id)
                    ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
                    ->count();

                return $user;
            })
            ->sortByDesc('total_revenue');

        $topProducts = SaleItem::whereHas('sale', fn($q) => $q
            ->where('tenant_id', $tenantId)
            ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
            ->where('status', 'completed')
        )
        ->selectRaw('product_name, SUM(qty) as total_qty, SUM(subtotal) as total_revenue, SUM((unit_price - cost_price) * qty) as total_profit')
        ->groupBy('product_name')
        ->orderByDesc('total_revenue')
        ->take(10)
        ->get();

        $hourly = Sale::where('tenant_id', $tenantId)
            ->whereBetween(DB::raw('DATE(created_at)'), [$from, $to])
            ->where('status', 'completed')
            ->selectRaw('HOUR(created_at) as hour, COUNT(*) as transactions, SUM(total) as revenue')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        return view('modules.reports.cashier_performance', compact(
            'cashiers', 'topProducts', 'hourly', 'from', 'to'
        ));
    }
}