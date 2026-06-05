<?php

namespace App\Http\Controllers\Modules\Reports\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        $tenantId  = auth()->user()->tenant_id;
        $from      = $request->from ?? now()->startOfMonth()->toDateString();
        $to        = $request->to   ?? now()->toDateString();

        $sales = Sale::where('tenant_id', $tenantId)
            ->whereBetween(\DB::raw('DATE(created_at)'), [$from, $to])
            ->with('customer', 'user', 'items')
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $summary = Sale::where('tenant_id', $tenantId)
            ->whereBetween(\DB::raw('DATE(created_at)'), [$from, $to])
            ->selectRaw("
                COUNT(*)           as total_transactions,
                SUM(total)         as total_revenue,
                SUM(tax_amount)    as total_tax,
                SUM(discount_amount) as total_discount,
                AVG(total)         as avg_sale,
                SUM(CASE WHEN payment_method = 'cash'   THEN total ELSE 0 END) as cash_total,
                SUM(CASE WHEN payment_method = 'card'   THEN total ELSE 0 END) as card_total,
                SUM(CASE WHEN payment_method = 'mobile' THEN total ELSE 0 END) as mobile_total
            ")
            ->first();

        $topProducts = SaleItem::whereHas('sale', function ($q) use ($tenantId, $from, $to) {
                $q->where('tenant_id', $tenantId)
                  ->whereBetween(\DB::raw('DATE(created_at)'), [$from, $to]);
            })
            ->selectRaw('product_name, SUM(qty) as total_qty, SUM(subtotal) as total_revenue')
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        $dailySales = Sale::where('tenant_id', $tenantId)
            ->whereBetween(\DB::raw('DATE(created_at)'), [$from, $to])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as transactions, SUM(total) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('modules.reports.sales', compact(
            'sales', 'summary', 'topProducts', 'dailySales', 'from', 'to'
        ));
    }

    public function exportPdf(Request $request)
    {
        $tenantId  = auth()->user()->tenant_id;
        $from      = $request->from ?? now()->startOfMonth()->toDateString();
        $to        = $request->to   ?? now()->toDateString();

        $sales = Sale::where('tenant_id', $tenantId)
            ->whereBetween(\DB::raw('DATE(created_at)'), [$from, $to])
            ->with('customer', 'user', 'items')
            ->latest()
            ->get();

        $summary = Sale::where('tenant_id', $tenantId)
            ->whereBetween(\DB::raw('DATE(created_at)'), [$from, $to])
            ->selectRaw("
                COUNT(*)           as total_transactions,
                SUM(total)         as total_revenue,
                SUM(tax_amount)    as total_tax,
                SUM(discount_amount) as total_discount,
                AVG(total)         as avg_sale,
                SUM(CASE WHEN payment_method = 'cash'   THEN total ELSE 0 END) as cash_total,
                SUM(CASE WHEN payment_method = 'card'   THEN total ELSE 0 END) as card_total,
                SUM(CASE WHEN payment_method = 'mobile' THEN total ELSE 0 END) as mobile_total
            ")
            ->first();

        $topProducts = \App\Models\SaleItem::whereHas('sale', function ($q) use ($tenantId, $from, $to) {
                $q->where('tenant_id', $tenantId)
                  ->whereBetween(\DB::raw('DATE(created_at)'), [$from, $to]);
            })
            ->selectRaw('product_name, SUM(qty) as total_qty, SUM(subtotal) as total_revenue')
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get();

        $pdf = Pdf::loadView('modules.reports.exports.sales', compact(
            'sales', 'summary', 'topProducts', 'from', 'to'
        ));
        
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('sales-report-' . $from . '-to-' . $to . '.pdf');
    }
}