<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Products</h2>
            <a href="{{ route('products.create') }}"
               class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
                + Add Product
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

            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($products as $product)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-800">{{ $product->name }}</p>
                                    @if($product->barcode)
                                        <p class="text-xs text-gray-400">{{ $product->barcode }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-500 font-mono text-xs">{{ $product->sku }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $product->category->name ?? '—' }}</td>
                                <td class="px-6 py-4 text-gray-800 font-medium">KES {{ number_format($product->price, 2) }}</td>
                                <td class="px-6 py-4">
                                    @if($product->track_stock)
                                        <span class="{{ $product->isLowStock() ? 'text-red-500 font-semibold' : 'text-gray-700' }}">
                                            {{ $product->stock_qty }}
                                        </span>
                                        @if($product->isLowStock())
                                            <span class="ml-1 text-xs text-red-400">Low</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400 text-xs">Not tracked</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        {{ $product->status === 'active'
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-gray-100 text-gray-500' }}">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 flex gap-3">
                                    <a href="{{ route('products.edit', $product) }}"
                                       class="text-indigo-600 hover:underline text-xs">Edit</a>
                                    <form method="POST" action="{{ route('products.destroy', $product) }}"
                                          onsubmit="return confirm('Delete this product?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline text-xs">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                    No products yet.
                                    <a href="{{ route('products.create') }}"
                                       class="text-indigo-600 hover:underline ml-1">Add your first product</a>
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
</x-app-layout>