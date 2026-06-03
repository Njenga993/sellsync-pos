<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('customers.index') }}" class="text-gray-400 hover:text-gray-600">← Back</a>
            <h2 class="text-xl font-semibold text-gray-800">{{ $customer->name }}</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Customer Stats --}}
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-xs text-gray-500 uppercase">Total Spent</p>
                    <p class="text-2xl font-semibold text-gray-800 mt-1">
                        KES {{ number_format($customer->total_spent, 2) }}
                    </p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-xs text-gray-500 uppercase">Loyalty Points</p>
                    <p class="text-2xl font-semibold text-yellow-600 mt-1">
                        {{ number_format($customer->loyalty_points) }} pts
                    </p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-xs text-gray-500 uppercase">Credit Limit</p>
                    <p class="text-2xl font-semibold text-gray-800 mt-1">
                        KES {{ number_format($customer->credit_limit, 2) }}
                    </p>
                </div>
            </div>

            {{-- Customer Details --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-sm font-semibold text-gray-700">Customer Details</h3>
                    <a href="{{ route('customers.edit', $customer) }}"
                       class="text-indigo-600 text-sm hover:underline">Edit</a>
                </div>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-400 text-xs">Phone</p>
                        <p class="text-gray-800 mt-1">{{ $customer->phone ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs">Email</p>
                        <p class="text-gray-800 mt-1">{{ $customer->email ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs">City</p>
                        <p class="text-gray-800 mt-1">{{ $customer->city ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs">Address</p>
                        <p class="text-gray-800 mt-1">{{ $customer->address ?? '—' }}</p>
                    </div>
                </div>
            </div>

            {{-- Recent Sales --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-700">Recent Purchases</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentSales as $sale)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-mono text-xs text-gray-600">{{ $sale->invoice_no }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $sale->created_at->format('d M Y, h:i A') }}</td>
                                <td class="px-6 py-4 font-medium text-gray-800">KES {{ number_format($sale->total, 2) }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ ucfirst($sale->payment_method) }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        {{ ucfirst($sale->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                                    No purchases yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>