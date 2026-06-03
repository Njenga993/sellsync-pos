<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Expense;
use App\Models\Sale;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;

class TenantManagementController extends Controller
{
    public function index(Request $request)
    {
        $tenants = Tenant::withCount(['branches'])
            ->when($request->search, fn($q) =>
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
            )
            ->when($request->status, fn($q) =>
                $q->where('status', $request->status)
            )
            ->when($request->type, fn($q) =>
                $q->where('business_type', $request->type)
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $tenants->each(function ($tenant) {
            $tenant->revenue      = Sale::where('tenant_id', $tenant->id)->sum('total');
            $tenant->sales_count  = Sale::where('tenant_id', $tenant->id)->count();
            $tenant->users_count  = User::where('tenant_id', $tenant->id)->count();
        });

        return view('superadmin.tenants.index', compact('tenants'));
    }

    public function show(Tenant $tenant)
    {
        $tenant->load('branches');

        $stats = [
            'total_revenue'  => Sale::where('tenant_id', $tenant->id)->sum('total'),
            'total_sales'    => Sale::where('tenant_id', $tenant->id)->count(),
            'total_users'    => User::where('tenant_id', $tenant->id)->count(),
            'total_branches' => Branch::where('tenant_id', $tenant->id)->count(),
            'total_expenses' => Expense::where('tenant_id', $tenant->id)->sum('amount'),
        ];

        $users = User::where('tenant_id', $tenant->id)
            ->with('roles', 'branch')
            ->get();

        $recentSales = Sale::where('tenant_id', $tenant->id)
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        $monthlySales = Sale::where('tenant_id', $tenant->id)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total) as revenue, COUNT(*) as transactions')
            ->groupBy('month')
            ->orderBy('month')
            ->take(6)
            ->get();

        return view('superadmin.tenants.show', compact(
            'tenant', 'stats', 'users', 'recentSales', 'monthlySales'
        ));
    }

    public function toggleStatus(Tenant $tenant)
    {
        $tenant->update([
            'status' => $tenant->status === 'active' ? 'suspended' : 'active',
        ]);

        return back()->with('success',
            'Business ' . ($tenant->status === 'active' ? 'activated' : 'suspended') . ' successfully.'
        );
    }

    public function updatePlan(Request $request, Tenant $tenant)
    {
        $request->validate([
            'plan' => ['required', 'in:basic,professional,enterprise'],
        ]);

        $tenant->update(['plan' => $request->plan]);

        return back()->with('success', 'Plan updated to ' . ucfirst($request->plan) . '.');
    }

    public function destroy(Tenant $tenant)
    {
        $tenant->delete();

        return redirect()->route('superadmin.tenants.index')
            ->with('success', 'Business deleted successfully.');
    }
}