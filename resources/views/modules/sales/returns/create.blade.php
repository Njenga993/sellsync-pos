<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('returns.index') }}" 
               style="display:flex;align-items:center;gap:6px;color:#9ca3af;font-size:13px;text-decoration:none;padding:6px 10px;border-radius:8px;border:1px solid #e4e7ef;font-family:'Outfit',sans-serif;transition:all .15s"
               onmouseover="this.style.borderColor='#03A737';this.style.color='#03A737';this.style.background='#e6f7eb'"
               onmouseout="this.style.borderColor='#e4e7ef';this.style.color='#9ca3af';this.style.background='transparent'">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
            </a>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Finance</p>
                <h1 style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:700;color:#02182F;line-height:1.2">
                    Process Return
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
            --brand-blue: #3D7BE7;
            --brand-blue-light: #edf3fd;
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
        
        .step-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 6px;
            background: var(--brand-green);
            color: var(--brand-white);
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            font-weight: 700;
            margin-right: 8px;
            flex-shrink: 0;
        }
        
        .section-title {
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: var(--brand-black);
            display: flex;
            align-items: center;
            margin-bottom: 16px;
        }
        
        .form-group {
            margin-bottom: 14px;
        }
        
        .form-label {
            font-family: 'Outfit', sans-serif;
            font-size: 11px;
            font-weight: 600;
            color: #6b7280;
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
        .form-input::placeholder { color: #c4c9d6; }
        
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
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            font-family: 'Outfit', sans-serif;
            border: none;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(3, 167, 55, 0.25);
            transition: all 0.15s;
        }
        .btn-primary:hover {
            background: var(--brand-green-dark);
            box-shadow: 0 4px 12px rgba(3, 167, 55, 0.35);
            transform: translateY(-1px);
        }
        
        .btn-return {
            background: #dc2626;
            color: var(--brand-white);
            padding: 12px 28px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Outfit', sans-serif;
            border: none;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(220, 38, 38, 0.25);
            transition: all 0.15s;
        }
        .btn-return:hover {
            background: #b91c1c;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.35);
            transform: translateY(-1px);
        }
        
        .btn-cancel {
            background: transparent;
            color: #6b7280;
            padding: 12px 28px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Outfit', sans-serif;
            border: 1.5px solid #e4e7ef;
            text-decoration: none;
            transition: all 0.15s;
            display: inline-block;
        }
        .btn-cancel:hover {
            border-color: #dc2626;
            color: #dc2626;
            background: #fef2f2;
        }
        
        .sale-found {
            background: var(--brand-green-light);
            border: 1px solid #b8e6c4;
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .sale-not-found {
            background: #fef2f2;
            border: 1px solid #fecdd3;
            border-radius: 10px;
            padding: 14px 18px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            color: #b91c1c;
        }
        
        .data-table { 
            width: 100%; 
            border-collapse: collapse; 
            font-family: 'Outfit', sans-serif; 
        }
        .data-table th {
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 10px 16px;
            background: #fafbff;
            border-bottom: 1px solid #f1f3f8;
        }
        .data-table td {
            padding: 12px 16px;
            font-size: 13px;
            color: #374151;
            border-bottom: 1px solid #f8f9fb;
        }
        
        .qty-input {
            width: 70px;
            padding: 6px 8px;
            border: 1.5px solid #e4e7ef;
            border-radius: 8px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            color: var(--brand-black);
            background: #fafbff;
            text-align: center;
            transition: all 0.15s;
            outline: none;
        }
        .qty-input:focus {
            border-color: var(--brand-green);
            box-shadow: 0 0 0 3px rgba(3, 167, 55, 0.08);
        }
        
        .checkbox-custom {
            appearance: none;
            width: 16px;
            height: 16px;
            border: 1.5px solid #d1d5db;
            border-radius: 4px;
            background: var(--brand-white);
            cursor: pointer;
            transition: all 0.15s;
            position: relative;
            flex-shrink: 0;
        }
        .checkbox-custom:checked {
            background: var(--brand-green);
            border-color: var(--brand-green);
        }
        .checkbox-custom:checked::after {
            content: '';
            position: absolute;
            left: 4px;
            top: 1px;
            width: 5px;
            height: 9px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
        
        .error-msg {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            color: #dc2626;
            margin-top: 6px;
        }
        
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 600;
            font-family: 'Outfit', sans-serif;
        }
        .badge-green { background: #e6f7eb; color: #028a2e; }
    </style>

    <div style="padding:0 0 20px">
        <div style="max-width:800px;margin:0 auto;padding:0 16px">

            @if(session('error'))
                <div class="sale-not-found" style="margin-bottom:16px">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right:8px;flex-shrink:0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Step 1: Search Invoice --}}
            <div class="form-panel">
                <div class="section-title">
                    <span class="step-badge">1</span>
                    Find the original sale
                </div>
                <form method="GET" action="{{ route('returns.create') }}" style="display:flex;gap:12px;align-items:flex-end">
                    <div style="flex:1" class="form-group" style="margin-bottom:0">
                        <label class="form-label" for="invoice_search">Invoice Number</label>
                        <input type="text" name="invoice_no" id="invoice_search"
                            value="{{ request('invoice_no') }}"
                            class="form-input" placeholder="e.g. INV-NYAK-000001" />
                    </div>
                    <button type="submit" class="btn-primary" style="padding-top:11px;padding-bottom:11px">Find Sale</button>
                </form>
            </div>

            {{-- Step 2 & 3: Process Return --}}
            @if($sale)
                <div class="sale-found">
                    <div>
                        <div style="font-family:'JetBrains Mono',monospace;font-size:13px;font-weight:700;color:#028a2e">{{ $sale->invoice_no }}</div>
                        <div style="font-size:12px;color:#6b7280;margin-top:2px">
                            {{ $sale->created_at->format('d M Y, h:i A') }} ·
                            {{ $sale->customer->name ?? 'Walk-in Customer' }} ·
                            KES {{ number_format($sale->total, 2) }}
                        </div>
                    </div>
                    <span class="badge badge-green">Found</span>
                </div>

                <form method="POST" action="{{ route('returns.store') }}">
                    @csrf
                    <input type="hidden" name="sale_id" value="{{ $sale->id }}" />

                    <div class="form-panel" style="padding:0;overflow:hidden">
                        <div style="padding:18px 24px;border-bottom:1px solid #f1f3f8">
                            <div class="section-title" style="margin-bottom:0">
                                <span class="step-badge">2</span>
                                Select items to return
                            </div>
                        </div>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Sold Qty</th>
                                    <th>Unit Price</th>
                                    <th>Return Qty</th>
                                    <th>Restock</th>
                                    <th style="text-align:right">Refund</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sale->items as $i => $item)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="items[{{ $i }}][sale_item_id]" value="{{ $item->id }}" />
                                            <span class="fw6" style="color:#02182F">{{ $item->product_name }}</span>
                                        </td>
                                        <td style="font-size:12px;color:#6b7280">{{ $item->qty }}</td>
                                        <td class="mono" style="font-size:12px;color:#6b7280">KES {{ number_format($item->unit_price, 2) }}</td>
                                        <td>
                                            <input type="number" name="items[{{ $i }}][qty]"
                                                min="0" max="{{ $item->qty }}" value="0"
                                                class="qty-input" onchange="calcRefund()" />
                                        </td>
                                        <td>
                                            <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:12px;color:#6b7280;font-family:'Outfit',sans-serif">
                                                <input type="checkbox" name="items[{{ $i }}][restock]" value="1" checked class="checkbox-custom" />
                                                Return to stock
                                            </label>
                                        </td>
                                        <td class="mono fw6" style="text-align:right;font-size:13px;color:#02182F"
                                            id="refund-{{ $i }}" data-price="{{ $item->unit_price }}">
                                            KES 0.00
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="background:#fafbff">
                                    <td colspan="5" style="text-align:right;font-family:'Outfit',sans-serif;font-size:13px;font-weight:700;color:#02182F">
                                        Total Refund:
                                    </td>
                                    <td class="mono fw7" style="text-align:right;font-size:18px;color:#dc2626" id="total-refund">
                                        KES 0.00
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="form-panel">
                        <div class="section-title">
                            <span class="step-badge">3</span>
                            Return details
                        </div>

                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label" for="reason">Reason for Return *</label>
                                <select name="reason" id="reason" required class="form-select">
                                    <option value="">Select reason</option>
                                    <option value="Defective product">Defective product</option>
                                    <option value="Wrong item delivered">Wrong item delivered</option>
                                    <option value="Customer changed mind">Customer changed mind</option>
                                    <option value="Expired product">Expired product</option>
                                    <option value="Damaged on delivery">Damaged on delivery</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="refund_method">Refund Method *</label>
                                <select name="refund_method" id="refund_method" required class="form-select">
                                    <option value="cash">Cash</option>
                                    <option value="card">Card</option>
                                    <option value="mobile">M-Pesa</option>
                                    <option value="credit">Store Credit</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label" for="notes">Additional Notes</label>
                            <textarea name="notes" id="notes" rows="2" class="form-textarea"
                                placeholder="Any additional information about this return"></textarea>
                        </div>
                    </div>

                    <div style="display:flex;gap:12px">
                        <button type="submit" class="btn-return">Process Return & Refund</button>
                        <a href="{{ route('returns.index') }}" class="btn-cancel">Cancel</a>
                    </div>
                </form>

            @elseif(request('invoice_no'))
                <div class="sale-not-found">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-right:8px;flex-shrink:0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    No sale found with invoice number <strong style="margin:0 4px">{{ request('invoice_no') }}</strong>. Please check and try again.
                </div>
            @endif
        </div>
    </div>

<script>
function calcRefund() {
    let total = 0;
    document.querySelectorAll('[id^="refund-"]').forEach(cell => {
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
</div>
</x-app-layout>