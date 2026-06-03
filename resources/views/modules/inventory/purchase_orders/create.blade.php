<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('purchase-orders.index') }}" class="text-gray-400 hover:text-gray-600">← Back</a>
            <h2 class="text-xl font-semibold text-gray-800">New Purchase Order</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('purchase-orders.store') }}" id="po-form">
                @csrf

                {{-- PO Details --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Order Details</h3>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="supplier_id" value="Supplier *" />
                            <select id="supplier_id" name="supplier_id" required
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="">-- Select supplier --</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('supplier_id')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="order_date" value="Order Date *" />
                            <x-text-input id="order_date" name="order_date" type="date"
                                class="block mt-1 w-full"
                                :value="old('order_date', now()->toDateString())" required />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="expected_date" value="Expected Delivery Date" />
                            <x-text-input id="expected_date" name="expected_date" type="date"
                                class="block mt-1 w-full" :value="old('expected_date')" />
                        </div>
                    </div>
                    <div>
                        <x-input-label for="notes" value="Notes (optional)" />
                        <textarea id="notes" name="notes" rows="2"
                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('notes') }}</textarea>
                    </div>
                </div>

                {{-- Items --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold text-gray-700">Order Items</h3>
                        <button type="button" onclick="addItem()"
                            class="text-sm text-indigo-600 hover:underline">+ Add Item</button>
                    </div>

                    <div id="items-container">
                        <div class="grid grid-cols-12 gap-2 mb-2 text-xs text-gray-500 font-medium uppercase">
                            <div class="col-span-5">Product</div>
                            <div class="col-span-2">Qty</div>
                            <div class="col-span-3">Unit Cost (KES)</div>
                            <div class="col-span-2">Subtotal</div>
                        </div>
                        <div id="items-list"></div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-100 flex justify-end">
                        <div class="text-right">
                            <p class="text-xs text-gray-500">Total</p>
                            <p class="text-xl font-bold text-gray-800" id="po-total">KES 0.00</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-indigo-700">
                        Create Purchase Order
                    </button>
                    <a href="{{ route('purchase-orders.index') }}"
                       class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

<script>
const products = @json($products->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'cost_price' => $p->cost_price]));
let itemCount = 0;

function addItem() {
    const list = document.getElementById('items-list');
    const idx  = itemCount++;
    const opts = products.map(p => `<option value="${p.id}" data-cost="${p.cost_price}">${p.name}</option>`).join('');

    const row = document.createElement('div');
    row.className = 'grid grid-cols-12 gap-2 mb-2 items-center';
    row.id = `item-row-${idx}`;
    row.innerHTML = `
        <div class="col-span-5">
            <select name="items[${idx}][product_id]" required onchange="setCost(${idx}, this)"
                class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm">
                <option value="">-- Product --</option>${opts}
            </select>
        </div>
        <div class="col-span-2">
            <input type="number" name="items[${idx}][qty_ordered]" min="1" value="1" required
                onchange="calcSubtotal(${idx})"
                class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm" />
        </div>
        <div class="col-span-3">
            <input type="number" name="items[${idx}][unit_cost]" id="cost-${idx}" min="0" step="0.01" value="0" required
                onchange="calcSubtotal(${idx})"
                class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-sm" />
        </div>
        <div class="col-span-1 text-sm font-medium text-gray-700" id="sub-${idx}">0.00</div>
        <div class="col-span-1 text-center">
            <button type="button" onclick="removeItem(${idx})" class="text-red-400 hover:text-red-600 text-lg">✕</button>
        </div>`;
    list.appendChild(row);
    updateTotal();
}

function setCost(idx, sel) {
    const opt  = sel.options[sel.selectedIndex];
    const cost = opt.dataset.cost || 0;
    document.getElementById(`cost-${idx}`).value = parseFloat(cost).toFixed(2);
    calcSubtotal(idx);
}

function calcSubtotal(idx) {
    const row  = document.getElementById(`item-row-${idx}`);
    const qty  = parseFloat(row.querySelector('[name*="qty_ordered"]').value) || 0;
    const cost = parseFloat(row.querySelector('[name*="unit_cost"]').value) || 0;
    const sub  = qty * cost;
    document.getElementById(`sub-${idx}`).textContent = sub.toFixed(2);
    updateTotal();
}

function removeItem(idx) {
    document.getElementById(`item-row-${idx}`).remove();
    updateTotal();
}

function updateTotal() {
    const subs  = [...document.querySelectorAll('[id^="sub-"]')];
    const total = subs.reduce((s, el) => s + parseFloat(el.textContent || 0), 0);
    document.getElementById('po-total').textContent = 'KES ' + total.toFixed(2);
}

addItem();
</script>
</x-app-layout>