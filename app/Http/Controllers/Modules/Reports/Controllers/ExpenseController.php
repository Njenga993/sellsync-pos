<?php

namespace App\Http\Controllers\Modules\Reports\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Sale;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;
        $from     = $request->from ?? now()->startOfMonth()->toDateString();
        $to       = $request->to   ?? now()->toDateString();

        $expenses = Expense::where('tenant_id', $tenantId)
            ->whereBetween('expense_date', [$from, $to])
            ->with('category', 'user')
            ->latest('expense_date')
            ->paginate(20)
            ->withQueryString();

        $categories = ExpenseCategory::where('tenant_id', $tenantId)->get();

        $summary = Expense::where('tenant_id', $tenantId)
            ->whereBetween('expense_date', [$from, $to])
            ->selectRaw('
                SUM(amount) as total_expenses,
                COUNT(*)    as total_count
            ')->first();

        $totalRevenue = Sale::where('tenant_id', $tenantId)
            ->whereBetween(\DB::raw('DATE(created_at)'), [$from, $to])
            ->sum('total');

        $byCategory = Expense::where('tenant_id', $tenantId)
            ->whereBetween('expense_date', [$from, $to])
            ->with('category')
            ->selectRaw('expense_category_id, SUM(amount) as total')
            ->groupBy('expense_category_id')
            ->orderByDesc('total')
            ->get();

        return view('modules.reports.expenses', compact(
            'expenses', 'categories', 'summary',
            'totalRevenue', 'byCategory', 'from', 'to'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'               => ['required', 'string', 'max:255'],
            'amount'              => ['required', 'numeric', 'min:0'],
            'expense_date'        => ['required', 'date'],
            'expense_category_id' => ['nullable', 'exists:expense_categories,id'],
            'payment_method'      => ['required', 'in:cash,card,mobile,bank'],
            'reference'           => ['nullable', 'string', 'max:255'],
            'notes'               => ['nullable', 'string'],
        ]);

        Expense::create([
            'tenant_id'           => auth()->user()->tenant_id,
            'branch_id'           => auth()->user()->branch_id,
            'user_id'             => auth()->id(),
            'expense_category_id' => $request->expense_category_id,
            'title'               => $request->title,
            'amount'              => $request->amount,
            'expense_date'        => $request->expense_date,
            'payment_method'      => $request->payment_method,
            'reference'           => $request->reference,
            'notes'               => $request->notes,
        ]);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense recorded successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted.');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'color' => ['required', 'string'],
        ]);

        ExpenseCategory::create([
            'tenant_id'   => auth()->user()->tenant_id,
            'name'        => $request->name,
            'color'       => $request->color,
            'description' => $request->description,
        ]);

        return redirect()->route('expenses.index')
            ->with('success', 'Category added successfully.');
    }
}