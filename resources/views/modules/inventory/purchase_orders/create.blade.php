<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('purchase-orders.index') }}" 
               style="display:flex;align-items:center;gap:6px;color:#9ca3af;font-size:13px;text-decoration:none;padding:6px 10px;border-radius:8px;border:1px solid #e4e7ef;font-family:'Outfit',sans-serif;transition:all .15s"
               onmouseover="this.style.borderColor='#03A737';this.style.color='#03A737';this.style.background='#e6f7eb'"
               onmouseout="this.style.borderColor='#e4e7ef';this.style.color='#9ca3af';this.style.background='transparent'">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
            </a>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Inventory</p>
                <h1 style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:700;color:#02182F;line-height:1.2">
                    New Purchase Order
                </h1>
            </div>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
        
        :root {
            --brand-green: #03A737;
            --brand-green-light: #e6f7eb;
            --brand-green-dark: #028a2e;
            --brand-black: #02182F;
            --brand-white: #FFFEFE;
        }
        
        .form-panel {
            background: var(--brand-white);
            border: 1px solid #e4e7ef;
            border-radius: 14px;
            padding: 24px;
            margin-bottom: 16px;
        }
        
        .section-title {
            font-family: 'Outfit', sans-serif;
            font-size: 10px;
            font-weight: 700;
            color: #c4c9d6;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f1f3f8;
        }
        
        .form-group { margin-bottom: 14px; }
        
        .form-label {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 6px;
            display: block;
        }
        
        .form-input {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #e4e7ef;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            color: var(--brand-black);
            background: #fafbff;
            transition: all 0.15s ease;
            outline: none;
        }
        .form-input:focus {
            border-color: var(--brand-green);
            background: var(--brand-white);
            box-shadow: 0 0 0 3px rgba(3, 167, 55, 0.08);
        }
        
        .form-select {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #e4e7ef;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            color: var(--brand-black);
            background: #fafbff;
            transition: all 0.15s ease;
            outline: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' fill='none' stroke='%239ca3af' stroke-width='2' viewBox='0 0 24 24'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 40px;
            cursor: pointer;
        }
        .form-select:focus {
            border-color: var(--brand-green);
            background-color: var(--brand-white);
            box-shadow: 0 0 0 3px rgba(3, 167, 55, 0.08);
        }
        
        .form-textarea {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #e4e7ef;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            color: var(--brand-black);
            background: #fafbff;
            transition: all 0.15s ease;
            outline: none;
            resize: vertical;
            min-height: 60px;
        }
        .form-textarea:focus {
            border-color: var(--brand-green);
            background: var(--brand-white);
            box-shadow: 0 0 0 3px rgba(3, 167, 55, 0.08);
        }
        
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        @media (max-width: 640px) { .grid-2 { grid-template-columns: 1fr; } }
        
        .btn-primary {
            background: var(--brand-green);
            color: var(--brand-white);
            padding: 11px 24px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            font-family: 'Outfit', sans-serif;
            letter-spacing: 0.01em;
            box-shadow: 0 2px 8px rgba(3, 167, 55, 0.25);
            transition: all 0.15s;
            border: none;
            cursor: pointer;
        }
        .btn-primary:hover {
            background: var(--brand-green-dark);
            box-shadow: 0 4px 12px rgba(3, 167, 55, 0.35);
            transform: translateY(-1px);
        }
        
        .btn-cancel {
            background: transparent;
            color: #6b7280;
            padding: 11px 24px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            font-family: 'Outfit', sans-serif;
            border: 1.5px solid #e4e7ef;
            text-decoration: none;
            transition: all 0.15s;
            display: inline-block;
        }
        .btn-cancel:hover {
            border-color: var(--brand-green);
            color: var(--brand-green);
            background: var(--brand-green-light);
        }
        
        .btn-add-item {
            background: var(--brand-green-light);
            color: var(--brand-green);
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            font-family: 'Outfit', sans-serif;
            border: 1.5px solid #b8e6c4;
            cursor: pointer;
            transition: all 0.15s;
        }
        .btn-add-item:hover {
            background: #ccf4d6;
            border-color: var(--brand-green);
        }
        
        .item-row-input {
            padding: 8px 10px;
            border: 1.5px solid #e4e7ef;
            border-radius: 8px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            color: var(--brand-black);
            background: #fafbff;
            transition: all 0.15s;
            outline: none;
            width: 100%;
        }
        .item-row-input:focus {
            border-color: var(--brand-green);
            background: var(--brand-white);
            box-shadow: 0 0 0 3px rgba(3, 167, 55, 0.08);
        }
        
        .item-row-select {
            padding: 8px 10px;
            border: 1.5px solid #e4e7ef;
            border-radius: 8px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            color: var(--brand-black);
            background: #fafbff;
            transition: all 0.15s;
            outline: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg width='10' height='7' fill='none' stroke='%239ca3af' stroke-width='2' viewBox='0 0 24 24'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 8px center;
            padding-right: 30px;
            cursor: pointer;
            width: 100%;
        }
        .item-row-select:focus {
            border-color: var(--brand-green);
            background-color: var(--brand-white);
            box-shadow: 0 0 0 3px rgba(3, 167, 55, 0.08);
        }
        
        .btn-remove {
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 16px;
            width: 28px;
            height: 28px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s;
        }
        .btn-remove:hover {
            color: #dc2626;
            background: #fef2f2;
        }
        
        .total-section {
            padding-top: 16px;
            margin-top: 16px;
            border-top: 2px solid #e4e7ef;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 16px;
        }
        .total-label {
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            color: #6b7280;
            font-weight: 500;
        }
        .total-value {
            font-family: 'JetBrains Mono', monospace;
            font-size: 24px;
            font-weight: 700;
            color: var(--brand-green);
        }
        
        .error-msg {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            color: #dc2626;
            margin-top: 6px;
        }
    </style>

    <div style="padding:0 0 20px">
        <div style="max-width:800px;margin:0 auto;padding:0 16px">
            <form method="POST" action="{{ route('purchase-orders.store') }}" id="po-form">
                @csrf

                {{-- Order Details --}}
                <div class="form-panel">
                    <div class="section-title">Order Details</div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label" for="supplier_id">Supplier *</label>
                            <select id="supplier_id" name="supplier_id" required class="form-select">
                                <option value="">Select supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if($errors->has('supplier_id'))
                                <div class="error-msg">{{ $errors->first('supplier_id') }}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="order_date">Order Date *</label>
                            <input id="order_date" name="order_date" type="date" class="form-input"
                                value="{{ old('order_date', now()->toDateString()) }}" required />
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label class="form-label" for="expected_date">Expected Delivery Date</label>
                            <input id="expected_date" name="expected_date" type="date" class="form-input"
                                value="{{ old('expected_date') }}" />
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom:0">
                        <label class="form-label" for="notes">Notes (optional)</label>
                        <textarea id="notes" name="notes" rows="2" class="form-textarea"
                            placeholder="Any special instructions...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                {{-- Items --}}
                <div class="form-panel">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
                        <div class="section-title" style="margin-bottom:0;padding-bottom:0;border-bottom:none">Order Items</div>
                        <button type="button" onclick="addItem()" class="btn-add-item">+ Add Item</button>
                    </div>

                    <div id="items-container">
                        <div style="display:grid;grid-template-columns:1fr 80px 1fr 80px 40px;gap:8px;margin-bottom:8px;padding:0 4px">
                            <span style="font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;font-family:'Outfit',sans-serif">Product</span>
                            <span style="font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;font-family:'Outfit',sans-serif">Qty</span>
                            <span style="font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;font-family:'Outfit',sans-serif">Unit Cost (KES)</span>
                            <span style="font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;font-family:'Outfit',sans-serif">Subtotal</span>
                            <span></span>
                        </div>
                        <div id="items-list"></div>
                    </div>

                    <div class="total-section">
                        <span class="total-label">Total:</span>
                        <span class="total-value" id="po-total">KES 0.00</span>
                    </div>
                </div>

                <div style="display:flex;gap:12px">
                    <button type="submit" class="btn-primary">Create Purchase Order</button>
                    <a href="{{ route('purchase-orders.index') }}" class="btn-cancel">Cancel</a>
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
    row.style.cssText = 'display:grid;grid-template-columns:1fr 80px 1fr 80px 40px;gap:8px;align-items:center;margin-bottom:8px';
    row.id = `item-row-${idx}`;
    row.innerHTML = `
        <select name="items[${idx}][product_id]" required onchange="setCost(${idx}, this)" class="item-row-select">
            <option value="">Product</option>${opts}
        </select>
        <input type="number" name="items[${idx}][qty_ordered]" min="1" value="1" required
            onchange="calcSubtotal(${idx})" class="item-row-input" style="text-align:center" />
        <input type="number" name="items[${idx}][unit_cost]" id="cost-${idx}" min="0" step="0.01" value="0" required
            onchange="calcSubtotal(${idx})" class="item-row-input" />
        <span class="mono fw6" id="sub-${idx}" style="font-size:13px;color:#02182F;text-align:right;padding-right:4px">0.00</span>
        <button type="button" onclick="removeItem(${idx})" class="btn-remove">×</button>`;
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
</div>
</x-app-layout>