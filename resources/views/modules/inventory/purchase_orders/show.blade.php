<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('purchase-orders.index') }}" class="text-gray-400 hover:text-gray-600">← Back</a>
                <h2 class="text-xl font-semibold text-gray-800">{{ $purchaseOrder->po_number }}</h2>
                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $purchaseOrder->status_color }}">
                    {{ ucfirst($purchaseOrder->status) }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- PO Info --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                    <div>
                        <p class="text-xs text-gray-400">Supplier</p>
                        <p class="font-medium text-gray-800 mt-1">{{ $purchaseOrder->supplier->name }}</p>
                        <p class="text-xs text-gray-400">{{ $purchaseOrder->supplier->phone }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Order Date</p>
                        <p class="font-medium text-gray-800 mt-1">{{ $purchaseOrder->order_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Expected Date</p>
                        <p class="font-medium text-gray-800 mt-1">
                            {{ $purchaseOrder->expected_date?->format('d M Y') ?? '—' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Created By</p>
                        <p class="font-medium text-gray-800 mt-1">{{ $purchaseOrder->user->name }}</p>
                    </div>
                </div>
                @if($purchaseOrder->notes)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-400">Notes</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $purchaseOrder->notes }}</p>
                    </div>
                @endif
            </div>

            {{-- Items + Receive Form --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-4">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-700">Order Items</h3>
                </div>

                @if(in_array($purchaseOrder->status, ['ordered', 'partial']))
                    <form method="POST" action="{{ route('purchase-orders.receive', $purchaseOrder) }}">
                        @csrf
                @endif

                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ordered</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Received</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Cost</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                            @if(in_array($purchaseOrder->status, ['ordered', 'partial']))
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Receive Now</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($purchaseOrder->items as $i => $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-800">{{ $item->product_name }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $item->qty_ordered }}</td>
                                <td class="px-6 py-4">
                                    <span class="{{ $item->qty_received >= $item->qty_ordered ? 'text-green-600 font-semibold' : 'text-yellow-600' }}">
                                        {{ $item->qty_received }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600">KES {{ number_format($item->unit_cost, 2) }}</td>
                                <td class="px-6 py-4 font-medium text-gray-800">KES {{ number_format($item->subtotal, 2) }}</td>
                                @if(in_array($purchaseOrder->status, ['ordered', 'partial']))
                                    <td class="px-6 py-4">
                                        <input type="hidden" name="items[{{ $i }}][id]" value="{{ $item->id }}" />
                                        <input type="number" name="items[{{ $i }}][qty_received]"
                                            min="0" max="{{ $item->qty_ordered - $item->qty_received }}"
                                            value="0"
                                            class="w-20 border border-gray-300 rounded-lg px-2 py-1 text-sm" />
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="px-6 py-4 border-t border-gray-100 flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        Total: <span class="font-bold text-gray-800 text-base">KES {{ number_format($purchaseOrder->total, 2) }}</span>
                    </div>
                    @if(in_array($purchaseOrder->status, ['ordered', 'partial']))
                        <button type="submit"
                            class="bg-green-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-green-700">
                            ✓ Record Receipt
                        </button>
                    @endif
                </div>

                @if(in_array($purchaseOrder->status, ['ordered', 'partial']))
                    </form>
                @endif
            </div>

            @if($purchaseOrder->status === 'received')
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-sm text-green-700">
                    ✅ All items received on {{ $purchaseOrder->received_date?->format('d M Y') }}.
                    Stock has been automatically updated.
                </div>
            @endif

        </div>
    </div>
</x-app-layout>