<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Purchase Orders</h2>
            <div class="flex gap-2">
                <a href="{{ route('suppliers.index') }}"
                   class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50">
                    Suppliers
                </a>
                <a href="{{ route('purchase-orders.create') }}"
                   class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
                    + New PO
                </a>
            </div>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">PO Number</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expected</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-mono text-xs text-indigo-600 font-semibold">
                                    {{ $order->po_number }}
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-800">{{ $order->supplier->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $order->user->name }}</p>
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    {{ $order->order_date->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    {{ $order->expected_date?->format('d M Y') ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $order->items_count }} items</td>
                                <td class="px-6 py-4 font-semibold text-gray-800">
                                    KES {{ number_format($order->total, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $order->status_color }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 flex gap-3">
                                    <a href="{{ route('purchase-orders.show', $order) }}"
                                       class="text-indigo-600 hover:underline text-xs">View</a>
                                    @if($order->status === 'draft')
                                        <form method="POST" action="{{ route('purchase-orders.destroy', $order) }}"
                                              onsubmit="return confirm('Delete this PO?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:underline text-xs">Delete</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                    No purchase orders yet.
                                    <a href="{{ route('purchase-orders.create') }}"
                                       class="text-indigo-600 hover:underline ml-1">Create your first PO</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($orders->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">{{ $orders->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>