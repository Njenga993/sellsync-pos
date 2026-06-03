<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_tenants'   => Tenant::count(),
            'active_tenants'  => Tenant::where('status', 'active')->count(),
            'total_users'     => User::whereNotNull('tenant_id')->count(),
            'total_revenue'   => Sale::sum('total'),
            'total_sales'     => Sale::count(),
            'total_products'  => Product::count(),
        ];

        $recentTenants = Tenant::latest()->take(5)->get();

        $topTenants = Tenant::withCount(['branches'])
            ->get()
            ->map(function ($tenant) {
                $tenant->revenue = Sale::where('tenant_id', $tenant->id)->sum('total');
                $tenant->sales   = Sale::where('tenant_id', $tenant->id)->count();
                return $tenant;
            })
            ->sortByDesc('revenue')
            ->take(5);

        $monthlySignups = Tenant::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->take(6)
            ->get();

        return view('superadmin.dashboard', compact(
            'stats', 'recentTenants', 'topTenants', 'monthlySignups'
        ));
    }
}