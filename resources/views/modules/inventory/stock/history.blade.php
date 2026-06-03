<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('stock.index') }}" class="text-gray-400 hover:text-gray-600">← Back</a>
            <h2 class="text-xl font-semibold text-gray-800">Stock Movement History</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Filters --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
                <form method="GET" action="{{ route('stock.history') }}"
                      class="flex items-end gap-4 flex-wrap">
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Product</label>
                        <select name="product_id"
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            <option value="">All Products</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}"
                                    {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">Movement Type</label>
                        <select name="type"
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            <option value="">All Types</option>
                            <option value="stock_in"   {{ request('type') === 'stock_in'   ? 'selected' : '' }}>Stock In</option>
                            <option value="stock_out"  {{ request('type') === 'stock_out'  ? 'selected' : '' }}>Stock Out</option>
                            <option value="adjustment" {{ request('type') === 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                            <option value="sale"       {{ request('type') === 'sale'       ? 'selected' : '' }}>Sale</option>
                            <option value="return"     {{ request('type') === 'return'     ? 'selected' : '' }}>Return</option>
                        </select>
                    </div>
                    <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
                        Filter
                    </button>
                    <a href="{{ route('stock.history') }}"
                        class="px-4 py-2 text-sm text-gray-500 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Reset
                    </a>
                </form>
            </div>

            {{-- Movement Table --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-sm font-semibold text-gray-700">Stock Movements</h3>
                    <span class="text-xs text-gray-400">{{ $movements->total() }} records</span>
                </div>
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Before</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">After</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">By</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($movements as $movement)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-gray-500">
                                    {{ $movement->created_at->format('d M Y') }}
                                    <span class="block text-xs text-gray-400">
                                        {{ $movement->created_at->format('h:i A') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-800">
                                        {{ $movement->product->name ?? '—' }}
                                    </p>
                                    <p class="text-xs text-gray-400 font-mono">
                                        {{ $movement->product->sku ?? '' }}
                                    </p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $movement->type_color }}">
                                        {{ $movement->type_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-bold
                                    {{ $movement->qty > 0 ? 'text-green-600' : 'text-red-500' }}">
                                    {{ $movement->qty > 0 ? '+' : '' }}{{ $movement->qty }}
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $movement->before_qty }}</td>
                                <td class="px-6 py-4 font-semibold text-gray-800">{{ $movement->after_qty }}</td>
                                <td class="px-6 py-4 text-gray-500 text-xs">
                                    {{ $movement->reference ?? '—' }}
                                    @if($movement->notes)
                                        <span class="block text-gray-400 mt-0.5">{{ $movement->notes }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-500 text-xs">
                                    {{ $movement->user->name ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                    No stock movements recorded yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($movements->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $movements->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>