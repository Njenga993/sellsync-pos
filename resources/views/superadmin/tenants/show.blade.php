<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tenant->name }} — Super Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

    <div class="bg-gray-900 text-white px-6 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('superadmin.dashboard') }}"
               class="text-lg font-bold text-indigo-400">⚡ POS Platform</a>
            <span class="text-xs bg-indigo-600 px-2 py-0.5 rounded-full">Super Admin</span>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('superadmin.tenants.index') }}"
               class="text-sm text-gray-300 hover:text-white">← All Businesses</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-300 hover:text-white">Logout</button>
            </form>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="flex items-start justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $tenant->name }}</h1>
                <p class="text-sm text-gray-500 mt-1">
                    {{ ucfirst($tenant->business_type) }} •
                    {{ $tenant->email }} •
                    Joined {{ $tenant->created_at->format('d M Y') }}
                </p>
            </div>
            <div class="flex gap-2">
                {{-- Update Plan --}}
                <form method="POST"
                      action="{{ route('superadmin.tenants.update-plan', $tenant) }}"
                      class="flex gap-2">
                    @csrf
                    <select name="plan"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="basic"        {{ $tenant->plan === 'basic'        ? 'selected' : '' }}>Basic</option>
                        <option value="professional" {{ $tenant->plan === 'professional' ? 'selected' : '' }}>Professional</option>
                        <option value="enterprise"   {{ $tenant->plan === 'enterprise'   ? 'selected' : '' }}>Enterprise</option>
                    </select>
                    <button type="submit"
                        class="bg-indigo-600 text-white px-3 py-2 rounded-lg text-sm hover:bg-indigo-700">
                        Update Plan
                    </button>
                </form>

                {{-- Toggle Status --}}
                <form method="POST"
                      action="{{ route('superadmin.tenants.toggle-status', $tenant) }}">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 rounded-lg text-sm font-medium
                            {{ $tenant->status === 'active'
                                ? 'bg-red-100 text-red-700 hover:bg-red-200'
                                : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                        {{ $tenant->status === 'active' ? 'Suspend Business' : 'Activate Business' }}
                    </button>
                </form>

                {{-- Delete --}}
                <form method="POST"
                      action="{{ route('superadmin.tenants.destroy', $tenant) }}"
                      onsubmit="return confirm('Permanently delete this business and all its data?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <p class="text-xs text-gray-500 uppercase">Revenue</p>
                <p class="text-xl font-bold text-green-600 mt-1">KES {{ number_format($stats['total_revenue'], 0) }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <p class="text-xs text-gray-500 uppercase">Sales</p>
                <p class="text-xl font-bold text-gray-800 mt-1">{{ $stats['total_sales'] }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <p class="text-xs text-gray-500 uppercase">Users</p>
                <p class="text-xl font-bold text-gray-800 mt-1">{{ $stats['total_users'] }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <p class="text-xs text-gray-500 uppercase">Branches</p>
                <p class="text-xl font-bold text-gray-800 mt-1">{{ $stats['total_branches'] }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <p class="text-xs text-gray-500 uppercase">Expenses</p>
                <p class="text-xl font-bold text-red-500 mt-1">KES {{ number_format($stats['total_expenses'], 0) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

            {{-- Staff --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-700">Staff Members</h3>
                </div>
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Branch</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $user)
                            <tr>
                                <td class="px-6 py-3">
                                    <p class="font-medium text-gray-800">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                </td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                                        {{ ucfirst($user->roles->first()?->name ?? '—') }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-gray-500 text-xs">{{ $user->branch->name ?? '—' }}</td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        {{ $user->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">No staff yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Branches --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-700">Branches</h3>
                </div>
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Branch</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">City</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Main</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($tenant->branches as $branch)
                            <tr>
                                <td class="px-6 py-3 font-medium text-gray-800">{{ $branch->name }}</td>
                                <td class="px-6 py-3 text-gray-500">{{ $branch->city ?? '—' }}</td>
                                <td class="px-6 py-3">
                                    @if($branch->is_main)
                                        <span class="px-2 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs">Main</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        {{ $branch->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ ucfirst($branch->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">No branches.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Sales --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700">Recent Transactions</h3>
            </div>
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cashier</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentSales as $sale)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 font-mono text-xs text-indigo-600">{{ $sale->invoice_no }}</td>
                            <td class="px-6 py-3 text-gray-500">{{ $sale->created_at->format('d M Y, h:i A') }}</td>
                            <td class="px-6 py-3 text-gray-600">{{ $sale->user->name ?? '—' }}</td>
                            <td class="px-6 py-3 text-gray-500 capitalize">{{ $sale->payment_method }}</td>
                            <td class="px-6 py-3 font-semibold text-gray-800">KES {{ number_format($sale->total, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">No sales yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>