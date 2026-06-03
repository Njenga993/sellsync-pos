<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Stock Valuation Report</h2>
            <div class="flex gap-2">
                <a href="{{ route('reports.profit-loss') }}"
                   class="border border-gray-300 text-gray-600 px-3 py-2 rounded-lg text-sm hover:bg-gray-50">
                    P&L Report
                </a>
                <a href="{{ route('reports.cashier-performance') }}"
                   class="border border-gray-300 text-gray-600 px-3 py-2 rounded-lg text-sm hover:bg-gray-50">
                    Cashier Performance
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Summary Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-xs text-gray-500 uppercase">Cost Value</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">
                        KES {{ number_format($summary['total_cost_value'], 2) }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">What you paid</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-xs text-gray-500 uppercase">Retail Value</p>
                    <p class="text-2xl font-bold text-indigo-600 mt-1">
                        KES {{ number_format($summary['total_sell_value'], 2) }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">If all sold at price</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-xs text-gray-500 uppercase">Potential Profit</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">
                        KES {{ number_format($summary['total_potential'], 2) }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">Retail − cost value</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-xs text-gray-500 uppercase">Stock Alerts</p>
                    <p class="text-2xl font-bold text-red-500 mt-1">
                        {{ $summary['out_of_stock'] }} / {{ $summary['low_stock'] }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">Out of stock / Low stock</p>
                </div>
            </div>

            {{-- By Category --}}
            @if($byCategory->count() > 0)
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-700">Valuation by Category</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Products</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cost Value</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Retail Value</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Margin</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($byCategory as $catName => $data)
                            @php
                                $margin = $data['sell_value'] > 0
                                    ? round((($data['sell_value'] - $data['cost_value']) / $data['sell_value']) * 100, 1)
                                    : 0;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-800">{{ $catName ?? 'Uncategorised' }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $data['count'] }}</td>
                                <td class="px-6 py-4 text-gray-700">KES {{ number_format($data['cost_value'], 2) }}</td>
                                <td class="px-6 py-4 text-indigo-600 font-medium">KES {{ number_format($data['sell_value'], 2) }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        {{ $margin >= 30 ? 'bg-green-100 text-green-700' : ($margin >= 10 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                        {{ $margin }}%
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            {{-- Full Product List --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between">
                    <h3 class="text-sm font-semibold text-gray-700">All Products</h3>
                    <span class="text-xs text-gray-400">{{ $products->count() }} tracked products</span>
                </div>
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cost Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sell Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cost Value</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Retail Value</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($products as $product)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-800">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-400 font-mono">{{ $product->sku }}</p>
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $product->category->name ?? '—' }}</td>
                                <td class="px-6 py-4 font-bold
                                    {{ $product->stock_qty <= 0 ? 'text-red-500' : ($product->isLowStock() ? 'text-yellow-500' : 'text-gray-800') }}">
                                    {{ $product->stock_qty }}
                                </td>
                                <td class="px-6 py-4 text-gray-500">KES {{ number_format($product->cost_price, 2) }}</td>
                                <td class="px-6 py-4 text-gray-700">KES {{ number_format($product->price, 2) }}</td>
                                <td class="px-6 py-4 text-gray-700">KES {{ number_format($product->cost_value, 2) }}</td>
                                <td class="px-6 py-4 text-indigo-600 font-medium">KES {{ number_format($product->sell_value, 2) }}</td>
                                <td class="px-6 py-4">
                                    @if($product->stock_qty <= 0)
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">Out of Stock</span>
                                    @elseif($product->isLowStock())
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Low Stock</span>
                                    @else
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">In Stock</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                    No tracked products found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>