<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('returns.index') }}" class="text-gray-400 hover:text-gray-600">← Back</a>
            <h2 class="text-xl font-semibold text-gray-800">{{ $return->return_number }}</h2>
            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $return->status_color }}">
                {{ ucfirst($return->status) }}
            </span>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Return Summary --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                    <div>
                        <p class="text-xs text-gray-400">Original Invoice</p>
                        <a href="{{ route('pos.receipt', $return->sale) }}" target="_blank"
                           class="font-medium text-indigo-600 hover:underline mt-1 block">
                            {{ $return->sale->invoice_no }}
                        </a>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Customer</p>
                        <p class="font-medium text-gray-800 mt-1">
                            {{ $return->sale->customer->name ?? 'Walk-in' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Processed By</p>
                        <p class="font-medium text-gray-800 mt-1">{{ $return->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Date</p>
                        <p class="font-medium text-gray-800 mt-1">
                            {{ $return->created_at->format('d M Y, h:i A') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Reason</p>
                        <p class="font-medium text-gray-800 mt-1">{{ $return->reason }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Refund Method</p>
                        <p class="font-medium text-gray-800 mt-1 capitalize">{{ $return->refund_method }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400">Total Refund</p>
                        <p class="font-bold text-red-500 text-lg mt-1">
                            KES {{ number_format($return->total_refund, 2) }}
                        </p>
                    </div>
                    @if($return->notes)
                        <div>
                            <p class="text-xs text-gray-400">Notes</p>
                            <p class="font-medium text-gray-800 mt-1">{{ $return->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Returned Items --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-700">Returned Items</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Restocked</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($return->items as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-800">{{ $item->product_name }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $item->qty }}</td>
                                <td class="px-6 py-4 text-gray-600">KES {{ number_format($item->unit_price, 2) }}</td>
                                <td class="px-6 py-4 font-semibold text-gray-800">
                                    KES {{ number_format($item->subtotal, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($item->restock)
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">
                                            ✓ Restocked
                                        </span>
                                    @else
                                        <span class="px-2 py-1 bg-gray-100 text-gray-500 rounded-full text-xs font-medium">
                                            Not restocked
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-6 py-3 text-right text-sm font-semibold text-gray-700">
                                Total Refund
                            </td>
                            <td class="px-6 py-3 font-bold text-red-500 text-base">
                                KES {{ number_format($return->total_refund, 2) }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>