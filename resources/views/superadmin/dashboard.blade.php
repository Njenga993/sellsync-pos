<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin — POS Platform</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

    {{-- Top Bar --}}
    <div class="bg-gray-900 text-white px-6 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <span class="text-lg font-bold text-indigo-400">⚡ POS Platform</span>
            <span class="text-xs bg-indigo-600 px-2 py-0.5 rounded-full">Super Admin</span>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('superadmin.tenants.index') }}"
               class="text-sm text-gray-300 hover:text-white">Businesses</a>
            <a href="{{ route('dashboard') }}"
               class="text-sm text-gray-300 hover:text-white">My Account</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-300 hover:text-white">Logout</button>
            </form>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Platform Overview</h1>
            <p class="text-sm text-gray-500 mt-1">All businesses registered on the POS platform</p>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <p class="text-xs text-gray-500 uppercase">Total Businesses</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_tenants'] }}</p>
                <p class="text-xs text-green-500 mt-1">{{ $stats['active_tenants'] }} active</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <p class="text-xs text-gray-500 uppercase">Total Users</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_users'] }}</p>
                <p class="text-xs text-gray-400 mt-1">Across all businesses</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <p class="text-xs text-gray-500 uppercase">Platform Revenue</p>
                <p class="text-3xl font-bold text-green-600 mt-1">
                    KES {{ number_format($stats['total_revenue'], 0) }}
                </p>
                <p class="text-xs text-gray-400 mt-1">{{ number_format($stats['total_sales']) }} transactions</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-5">
                <p class="text-xs text-gray-500 uppercase">Total Products</p>
                <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($stats['total_products']) }}</p>
                <p class="text-xs text-gray-400 mt-1">Across all inventories</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

            {{-- Top Businesses --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-sm font-semibold text-gray-700">Top Businesses by Revenue</h3>
                </div>
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Business</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sales</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($topTenants as $rank => $tenant)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 text-gray-400 font-medium">{{ $rank + 1 }}</td>
                                <td class="px-6 py-3">
                                    <p class="font-medium text-gray-800">{{ $tenant->name }}</p>
                                    <p class="text-xs text-gray-400">{{ ucfirst($tenant->business_type) }}</p>
                                </td>
                                <td class="px-6 py-3 text-gray-500">{{ $tenant->sales }}</td>
                                <td class="px-6 py-3 font-semibold text-green-600">
                                    KES {{ number_format($tenant->revenue, 0) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-400">No businesses yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Recent Signups --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-sm font-semibold text-gray-700">Recent Signups</h3>
                    <a href="{{ route('superadmin.tenants.index') }}"
                       class="text-xs text-indigo-600 hover:underline">View all</a>
                </div>
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Business</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentTenants as $tenant)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3">
                                    <a href="{{ route('superadmin.tenants.show', $tenant) }}"
                                       class="font-medium text-indigo-600 hover:underline">
                                        {{ $tenant->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-3 text-gray-500">{{ ucfirst($tenant->business_type) }}</td>
                                <td class="px-6 py-3 text-gray-500">{{ $tenant->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        {{ $tenant->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ ucfirst($tenant->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-400">No signups yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Monthly Signups --}}
        @if($monthlySignups->count() > 0)
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Monthly Business Signups</h3>
            <div class="flex items-end gap-4">
                @foreach($monthlySignups as $month)
                    @php $max = $monthlySignups->max('count'); $h = $max > 0 ? round(($month->count / $max) * 120) : 0; @endphp
                    <div class="flex flex-col items-center gap-1 flex-1">
                        <span class="text-xs font-semibold text-gray-700">{{ $month->count }}</span>
                        <div class="w-full bg-indigo-500 rounded-t" style="height:{{ $h }}px"></div>
                        <span class="text-xs text-gray-400">
                            {{ \Carbon\Carbon::parse($month->month . '-01')->format('M Y') }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</body>
</html>