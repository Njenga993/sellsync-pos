<?php

namespace App\Http\Controllers\Modules\Reports\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Sale;
use App\Models\SaleReturn;
use App\Models\ZReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ZReportController extends Controller
{
    public function index()
    {
        $tenantId = auth()->user()->tenant_id;

        $reports = ZReport::where('tenant_id', $tenantId)
            ->with('user', 'branch')
            ->latest('report_date')
            ->paginate(20);

        $todayReport = ZReport::where('tenant_id', $tenantId)
            ->where('branch_id', auth()->user()->branch_id)
            ->where('report_date', now()->toDateString())
            ->first();

        return view('modules.reports.zreport.index', compact('reports', 'todayReport'));
    }

    public function create(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;
        $branchId = auth()->user()->branch_id;
        $date     = $request->date ?? now()->toDateString();

        $existing = ZReport::where('tenant_id', $tenantId)
            ->where('branch_id', $branchId)
            ->where('report_date', $date)
            ->where('status', 'closed')
            ->first();

        if ($existing) {
            return redirect()->route('zreports.show', $existing)
                ->with('info', 'A closed Z-Report already exists for this date.');
        }

        $summary = $this->calculateSummary($tenantId, $branchId, $date);

        return view('modules.reports.zreport.create', compact('summary', 'date'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'report_date'    => ['required', 'date'],
            'opening_float'  => ['required', 'numeric', 'min:0'],
            'actual_cash'    => ['required', 'numeric', 'min:0'],
            'notes'          => ['nullable', 'string'],
        ]);

        $tenantId = auth()->user()->tenant_id;
        $branchId = auth()->user()->branch_id;
        $date     = $request->report_date;

        $existing = ZReport::where('tenant_id', $tenantId)
            ->where('branch_id', $branchId)
            ->where('report_date', $date)
            ->where('status', 'closed')
            ->first();

        if ($existing) {
            return redirect()->route('zreports.show', $existing)
                ->with('info', 'A Z-Report already exists for this date.');
        }

        $summary      = $this->calculateSummary($tenantId, $branchId, $date);
        $openingFloat = (float) $request->opening_float;
        $actualCash   = (float) $request->actual_cash;
        $expectedCash = $openingFloat
            + $summary['total_cash_sales']
            - $summary['total_cash_refunds']
            - $summary['total_cash_expenses'];
        $variance = $actualCash - $expectedCash;

        $report = ZReport::updateOrCreate(
            [
                'tenant_id'   => $tenantId,
                'branch_id'   => $branchId,
                'report_date' => $date,
            ],
            [
                'user_id'             => auth()->id(),
                'shift'               => 'main',
                'status'              => 'closed',
                'opening_float'       => $openingFloat,
                'total_cash_sales'    => $summary['total_cash_sales'],
                'total_card_sales'    => $summary['total_card_sales'],
                'total_mobile_sales'  => $summary['total_mobile_sales'],
                'total_split_sales'   => $summary['total_split_sales'],
                'total_sales'         => $summary['total_sales'],
                'total_transactions'  => $summary['total_transactions'],
                'total_refunds'       => $summary['total_refunds'],
                'total_cash_refunds'  => $summary['total_cash_refunds'],
                'total_expenses'      => $summary['total_expenses'],
                'total_cash_expenses' => $summary['total_cash_expenses'],
                'expected_cash'       => round($expectedCash, 2),
                'actual_cash'         => $actualCash,
                'cash_variance'       => round($variance, 2),
                'notes'               => $request->notes,
                'closed_at'           => now(),
            ]
        );

        return redirect()->route('zreports.show', $report)
            ->with('success', 'Z-Report closed successfully.');
    }

    public function show(ZReport $zreport)
    {
        $zreport->load('user', 'branch');

        $sales = Sale::where('tenant_id', $zreport->tenant_id)
            ->where('branch_id', $zreport->branch_id)
            ->whereDate('created_at', $zreport->report_date)
            ->with('user')
            ->latest()
            ->get();

        $expenses = Expense::where('tenant_id', $zreport->tenant_id)
            ->where('branch_id', $zreport->branch_id)
            ->where('expense_date', $zreport->report_date)
            ->with('category')
            ->get();

        return view('modules.reports.zreport.show', compact('zreport', 'sales', 'expenses'));
    }

    private function calculateSummary(string $tenantId, int $branchId, string $date): array
    {
        $sales = Sale::where('tenant_id', $tenantId)
            ->where('branch_id', $branchId)
            ->whereDate('created_at', $date)
            ->where('status', 'completed')
            ->get();

        $totalCashSales   = $sales->where('payment_method', 'cash')->sum('total');
        $totalCardSales   = $sales->where('payment_method', 'card')->sum('total');
        $totalMobileSales = $sales->where('payment_method', 'mobile')->sum('total');
        $totalSplitSales  = $sales->filter(fn($s) =>
            str_contains(strtolower($s->payment_method), '+')
        )->sum('total');

        $returns = SaleReturn::where('tenant_id', $tenantId)
            ->whereHas('sale', fn($q) => $q->where('branch_id', $branchId))
            ->whereDate('created_at', $date)
            ->get();

        $totalRefunds     = $returns->sum('total_refund');
        $totalCashRefunds = $returns->where('refund_method', 'cash')->sum('total_refund');

        $expenses = Expense::where('tenant_id', $tenantId)
            ->where('branch_id', $branchId)
            ->where('expense_date', $date)
            ->get();

        $totalExpenses     = $expenses->sum('amount');
        $totalCashExpenses = $expenses->where('payment_method', 'cash')->sum('amount');

        return [
            'total_cash_sales'    => round($totalCashSales, 2),
            'total_card_sales'    => round($totalCardSales, 2),
            'total_mobile_sales'  => round($totalMobileSales, 2),
            'total_split_sales'   => round($totalSplitSales, 2),
            'total_sales'         => round($sales->sum('total'), 2),
            'total_transactions'  => $sales->count(),
            'total_refunds'       => round($totalRefunds, 2),
            'total_cash_refunds'  => round($totalCashRefunds, 2),
            'total_expenses'      => round($totalExpenses, 2),
            'total_cash_expenses' => round($totalCashExpenses, 2),
            'sales_by_cashier'    => $sales->groupBy('user_id')->map(fn($g) => [
                'name'         => $g->first()->user->name ?? 'Unknown',
                'transactions' => $g->count(),
                'total'        => round($g->sum('total'), 2),
            ])->values(),
        ];
    }
}