<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Businesses — Super Admin</title>
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
            <a href="{{ route('superadmin.dashboard') }}"
               class="text-sm text-gray-300 hover:text-white">Dashboard</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-300 hover:text-white">Logout</button>
            </form>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">All Businesses</h1>
                <p class="text-sm text-gray-500 mt-1">{{ $tenants->total() }} registered businesses</p>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Filters --}}
        <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
            <form method="GET" action="{{ route('superadmin.tenants.index') }}"
                  class="flex items-end gap-4 flex-wrap">
                <div class="flex-1 min-w-48">
                    <label class="text-xs text-gray-500 block mb-1">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                        placeholder="Business name or email..." />
                </div>
                <div>
                    <label class="text-xs text-gray-500 block mb-1">Status</label>
                    <select name="status"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">All</option>
                        <option value="active"    {{ request('status') === 'active'    ? 'selected' : '' }}>Active</option>
                        <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs text-gray-500 block mb-1">Type</label>
                    <select name="type"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="">All Types</option>
                        <option value="retail"     {{ request('type') === 'retail'     ? 'selected' : '' }}>Retail</option>
                        <option value="restaurant" {{ request('type') === 'restaurant' ? 'selected' : '' }}>Restaurant</option>
                        <option value="salon"      {{ request('type') === 'salon'      ? 'selected' : '' }}>Salon</option>
                        <option value="pharmacy"   {{ request('type') === 'pharmacy'   ? 'selected' : '' }}>Pharmacy</option>
                        <option value="wholesale"  {{ request('type') === 'wholesale'  ? 'selected' : '' }}>Wholesale</option>
                    </select>
                </div>
                <button type="submit"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
                    Filter
                </button>
                <a href="{{ route('superadmin.tenants.index') }}"
                    class="px-4 py-2 text-sm text-gray-500 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Reset
                </a>
            </form>
        </div>

        {{-- Tenants Table --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Business</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Users</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Branches</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Revenue</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tenants as $tenant)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <a href="{{ route('superadmin.tenants.show', $tenant) }}"
                                   class="font-medium text-indigo-600 hover:underline">
                                    {{ $tenant->name }}
                                </a>
                                <p class="text-xs text-gray-400">{{ $tenant->email }}</p>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ ucfirst($tenant->business_type) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    {{ $tenant->plan === 'enterprise'    ? 'bg-purple-100 text-purple-700' :
                                      ($tenant->plan === 'professional'  ? 'bg-blue-100 text-blue-700' :
                                                                           'bg-gray-100 text-gray-600') }}">
                                    {{ ucfirst($tenant->plan) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $tenant->users_count }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ $tenant->branches_count }}</td>
                            <td class="px-6 py-4 font-semibold text-green-600">
                                KES {{ number_format($tenant->revenue, 0) }}
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $tenant->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    {{ $tenant->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ ucfirst($tenant->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 flex gap-2 items-center">
                                <a href="{{ route('superadmin.tenants.show', $tenant) }}"
                                   class="text-indigo-600 hover:underline text-xs">View</a>
                                <form method="POST"
                                      action="{{ route('superadmin.tenants.toggle-status', $tenant) }}">
                                    @csrf
                                    <button type="submit"
                                        class="text-xs {{ $tenant->status === 'active' ? 'text-red-500' : 'text-green-600' }} hover:underline">
                                        {{ $tenant->status === 'active' ? 'Suspend' : 'Activate' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-400">
                                No businesses found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if($tenants->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">{{ $tenants->links() }}</div>
            @endif
        </div>
    </div>
</body>
</html>