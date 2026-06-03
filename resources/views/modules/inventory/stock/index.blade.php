<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Stock Management</h2>
            <a href="{{ route('stock.history') }}"
               class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50">
                View History
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Summary --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-xs text-gray-500 uppercase">Tracked Products</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $summary['total_products'] }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-xs text-gray-500 uppercase">Low Stock</p>
                    <p class="text-2xl font-bold text-yellow-500 mt-1">{{ $summary['low_stock'] }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-xs text-gray-500 uppercase">Out of Stock</p>
                    <p class="text-2xl font-bold text-red-500 mt-1">{{ $summary['out_of_stock'] }}</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-xs text-gray-500 uppercase">Total Movements</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $summary['total_movements'] }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Stock Adjust Form --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Adjust Stock</h3>
                    <form method="POST" action="{{ route('stock.adjust') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="text-xs text-gray-500 block mb-1">Product *</label>
                            <select name="product_id" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                <option value="">-- Select product --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->name }} (Stock: {{ $product->stock_qty }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="text-xs text-gray-500 block mb-1">Movement Type *</label>
                            <select name="type" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                <option value="stock_in">Stock In (Add stock)</option>
                                <option value="stock_out">Stock Out (Remove stock)</option>
                                <option value="adjustment">Adjustment (Set exact qty)</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="text-xs text-gray-500 block mb-1">Quantity *</label>
                            <input type="number" name="qty" min="1" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                                placeholder="Enter quantity" />
                        </div>

                        <div class="mb-4">
                            <label class="text-xs text-gray-500 block mb-1">Reference (optional)</label>
                            <input type="text" name="reference"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                                placeholder="e.g. PO-001, Supplier name" />
                        </div>

                        <div class="mb-6">
                            <label class="text-xs text-gray-500 block mb-1">Notes (optional)</label>
                            <textarea name="notes" rows="2"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                                placeholder="Reason for adjustment"></textarea>
                        </div>

                        <button type="submit"
                            class="w-full bg-indigo-600 text-white py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-700">
                            Update Stock
                        </button>
                    </form>
                </div>

                {{-- Stock List --}}
                <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-700">Current Stock Levels</h3>
                    </div>
                    <table class="min-w-full divide-y divide-gray-100 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Alert At</th>
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
                                    <td class="px-6 py-4 text-gray-500">
                                        {{ $product->category->name ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-lg font-bold
                                            {{ $product->stock_qty <= 0
                                                ? 'text-red-500'
                                                : ($product->isLowStock() ? 'text-yellow-500' : 'text-gray-800') }}">
                                            {{ $product->stock_qty }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-500">{{ $product->low_stock_alert }}</td>
                                    <td class="px-6 py-4">
                                        @if($product->stock_qty <= 0)
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                                Out of Stock
                                            </span>
                                        @elseif($product->isLowStock())
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                                Low Stock
                                            </span>
                                        @else
                                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                                In Stock
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                        No tracked products found.
                                        <a href="{{ route('products.create') }}"
                                           class="text-indigo-600 hover:underline ml-1">Add products</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if($products->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100">
                            {{ $products->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>