<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('returns.index') }}" class="text-gray-400 hover:text-gray-600">← Back</a>
            <h2 class="text-xl font-semibold text-gray-800">Process Return</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Step 1: Search Invoice --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">
                    Step 1 — Find the original sale
                </h3>
                <form method="GET" action="{{ route('returns.create') }}"
                      class="flex gap-3 items-end">
                    <div class="flex-1">
                        <label class="text-xs text-gray-500 block mb-1">Invoice Number</label>
                        <input type="text" name="invoice_no"
                            value="{{ request('invoice_no') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                            placeholder="e.g. INV-NYAK-000001" />
                    </div>
                    <button type="submit"
                        class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
                        Find Sale
                    </button>
                </form>
            </div>

            {{-- Step 2: Process Return --}}
            @if($sale)
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4 text-sm">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-semibold text-green-800">{{ $sale->invoice_no }}</p>
                            <p class="text-green-600 text-xs mt-0.5">
                                {{ $sale->created_at->format('d M Y, h:i A') }} •
                                {{ $sale->customer->name ?? 'Walk-in Customer' }} •
                                KES {{ number_format($sale->total, 2) }}
                            </p>
                        </div>
                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">
                            Found
                        </span>
                    </div>
                </div>

                <form method="POST" action="{{ route('returns.store') }}">
                    @csrf
                    <input type="hidden" name="sale_id" value="{{ $sale->id }}" />

                    {{-- Return Items --}}
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-4">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h3 class="text-sm font-semibold text-gray-700">
                                Step 2 — Select items to return
            </h3>
                        </div>
                        <table class="min-w-full divide-y divide-gray-100 text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sold Qty</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Return Qty</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Restock</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Refund</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($sale->items as $i => $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <input type="hidden"
                                                name="items[{{ $i }}][sale_item_id]"
                                                value="{{ $item->id }}" />
                                            <p class="font-medium text-gray-800">{{ $item->product_name }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-gray-500">{{ $item->qty }}</td>
                                        <td class="px-6 py-4 text-gray-500">
                                            KES {{ number_format($item->unit_price, 2) }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="number"
                                                name="items[{{ $i }}][qty]"
                                                min="0" max="{{ $item->qty }}" value="0"
                                                class="w-20 border border-gray-300 rounded-lg px-2 py-1 text-sm"
                                                onchange="calcRefund()" />
                                        </td>
                                        <td class="px-6 py-4">
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox"
                                                    name="items[{{ $i }}][restock]"
                                                    value="1" checked
                                                    class="rounded border-gray-300 text-indigo-600" />
                                                <span class="text-xs text-gray-500">Return to stock</span>
                                            </label>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-700"
                                            id="refund-{{ $i }}"
                                            data-price="{{ $item->unit_price }}">
                                            KES 0.00
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="5" class="px-6 py-3 text-right text-sm font-semibold text-gray-700">
                                        Total Refund:
                                    </td>
                                    <td class="px-6 py-3 font-bold text-indigo-600 text-base" id="total-refund">
                                        KES 0.00
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- Return Details --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
                        <h3 class="text-sm font-semibold text-gray-700 mb-4">
                            Step 3 — Return details
                        </h3>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="text-xs text-gray-500 block mb-1">Reason for Return *</label>
                                <select name="reason" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                    <option value="">-- Select reason --</option>
                                    <option value="Defective product">Defective product</option>
                                    <option value="Wrong item delivered">Wrong item delivered</option>
                                    <option value="Customer changed mind">Customer changed mind</option>
                                    <option value="Expired product">Expired product</option>
                                    <option value="Damaged on delivery">Damaged on delivery</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 block mb-1">Refund Method *</label>
                                <select name="refund_method" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                    <option value="cash">Cash</option>
                                    <option value="card">Card</option>
                                    <option value="mobile">M-Pesa</option>
                                    <option value="credit">Store Credit</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="text-xs text-gray-500 block mb-1">Additional Notes</label>
                            <textarea name="notes" rows="2"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                                placeholder="Any additional information about this return"></textarea>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                            class="bg-red-600 text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-red-700">
                            Process Return & Refund
                        </button>
                        <a href="{{ route('returns.index') }}"
                           class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancel
                        </a>
                    </div>
                </form>

            @elseif(request('invoice_no'))
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-sm text-red-700">
                    No sale found with invoice number <strong>{{ request('invoice_no') }}</strong>.
                    Please check and try again.
                </div>
            @endif
        </div>
    </div>

<script>
function calcRefund() {
    let total = 0;
    document.querySelectorAll('[id^="refund-"]').forEach((cell, i) => {
        const row   = cell.closest('tr');
        const qty   = parseFloat(row.querySelector('input[type="number"]').value) || 0;
        const price = parseFloat(cell.dataset.price) || 0;
        const sub   = qty * price;
        cell.textContent = 'KES ' + sub.toFixed(2);
        total += sub;
    });
    document.getElementById('total-refund').textContent = 'KES ' + total.toFixed(2);
}
</script>
</x-app-layout>