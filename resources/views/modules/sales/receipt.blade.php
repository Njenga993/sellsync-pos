<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt — {{ $sale->invoice_no }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --brand:       #1a56db;
            --text-1:      #111827;
            --text-2:      #4b5563;
            --text-3:      #9ca3af;
            --border:      #e4e7ef;
            --success:     #16a34a;
        }

        /* ── Screen View ── */
        body {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            width: 320px;
            margin: 0 auto;
            padding: 20px 16px;
            color: var(--text-1);
            background: #ffffff;
        }

        .center { text-align: center; }

        .brand-section { margin-bottom: 12px; }

        .logo {
            max-width: 70px;
            max-height: 70px;
            object-fit: contain;
            display: block;
            margin: 0 auto 8px;
        }

        .business-name {
            font-family: 'Outfit', sans-serif;
            font-size: 16px;
            font-weight: 700;
            color: var(--text-1);
            line-height: 1.2;
            margin-bottom: 2px;
        }

        .business-branch {
            font-size: 11px;
            color: var(--text-3);
            font-weight: 500;
        }

        .business-info {
            font-size: 10px;
            color: var(--text-2);
            line-height: 1.5;
            margin-top: 4px;
        }

        .receipt-header-msg {
            font-size: 10px;
            color: var(--brand);
            font-weight: 600;
            margin-top: 6px;
            font-style: italic;
        }

        .divider {
            border-top: 1px dashed var(--border);
            margin: 10px 0;
        }

        .divider-solid {
            border-top: 1px solid var(--border);
            margin: 10px 0;
        }

        .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 3px 0;
            font-size: 11px;
        }

        .row-label { color: var(--text-2); font-weight: 500; }
        .row-value {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: var(--text-1);
            font-weight: 500;
        }

        .bold { font-weight: 700; }
        .mono { font-family: 'JetBrains Mono', monospace; }

        .invoice-no {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            font-weight: 600;
            color: var(--brand);
        }

        .item-block { margin: 6px 0; }

        .item-name {
            font-size: 11px;
            font-weight: 600;
            color: var(--text-1);
            line-height: 1.3;
        }

        .item-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2px;
        }

        .item-qty-price {
            font-size: 10px;
            color: var(--text-3);
            font-family: 'JetBrains Mono', monospace;
        }

        .item-subtotal {
            font-size: 11px;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 600;
            color: var(--text-1);
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 4px 0;
        }

        .total-label { font-size: 14px; font-weight: 700; color: var(--text-1); }
        .total-value {
            font-size: 16px;
            font-weight: 700;
            font-family: 'JetBrains Mono', monospace;
            color: var(--brand);
        }

        .payment-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 3px 0;
            font-size: 11px;
        }

        .payment-label { color: var(--text-2); font-weight: 500; }
        .payment-value { font-family: 'JetBrains Mono', monospace; font-size: 11px; }
        .change-value {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            font-weight: 600;
            color: var(--success);
        }

        .loyalty-section {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 8px 10px;
            margin: 8px 0;
        }

        .loyalty-row { display: flex; justify-content: space-between; align-items: center; }
        .loyalty-label {
            font-size: 10px;
            color: #15803d;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .loyalty-value {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            font-weight: 700;
            color: #15803d;
        }

        .receipt-footer {
            font-size: 10px;
            color: var(--text-3);
            font-style: italic;
            margin-top: 4px;
        }

        /* ── Barcode placeholder ── */
        .barcode-area {
            text-align: center;
            margin: 10px 0;
            padding: 6px 0;
        }
        .barcode-text {
            font-family: 'JetBrains Mono', monospace;
            font-size: 9px;
            letter-spacing: 0.1em;
            color: #111827;
        }

        /* ── Buttons (screen only) ── */
        .btn-group {
            display: flex;
            gap: 8px;
            margin-top: 20px;
        }

        .btn-primary {
            flex: 1;
            padding: 10px 16px;
            background: var(--brand);
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.01em;
            box-shadow: 0 2px 8px rgba(26, 86, 219, 0.25);
            transition: all 0.15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-primary:hover {
            background: #1e40af;
            box-shadow: 0 4px 12px rgba(26, 86, 219, 0.35);
        }

        .btn-secondary {
            flex: 1;
            padding: 10px 16px;
            background: #ffffff;
            color: var(--text-2);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            cursor: pointer;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.15s;
        }

        .btn-secondary:hover {
            border-color: var(--brand);
            color: var(--brand);
            background: #eff4ff;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-25deg);
            font-size: 60px;
            font-weight: 700;
            color: rgba(26, 86, 219, 0.04);
            pointer-events: none;
            z-index: -1;
            white-space: nowrap;
            font-family: 'Outfit', sans-serif;
        }

        /* ══════════════════════════════════════
           PRINT STYLES — Thermal 80mm
        ══════════════════════════════════════ */
        @media print {
            @page { 
                margin: 0; 
                size: 80mm 297mm; /* Standard 80mm thermal roll */
            }
            
            body { 
                width: 72mm; 
                padding: 4mm 4mm 8mm;
                font-size: 9px;
                margin: 0 auto;
                color: #000;
                background: #fff;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            /* Hide everything not needed for print */
            .no-print,
            .btn-group,
            .watermark { display: none !important; }
            
            /* Convert colors to grayscale for thermal */
            .divider { border-color: #000; }
            .divider-solid { border-color: #000; }
            .business-name { font-size: 13px; color: #000; }
            .business-branch { font-size: 9px; color: #000; }
            .business-info { font-size: 8px; color: #000; }
            .receipt-header-msg { font-size: 8px; color: #000; }
            .invoice-no { font-size: 9px; color: #000; font-weight: 700; }
            .row { font-size: 9px; margin: 2px 0; }
            .row-label { color: #000; }
            .row-value { font-size: 9px; color: #000; }
            .item-name { font-size: 9px; color: #000; }
            .item-qty-price { font-size: 8px; color: #333; }
            .item-subtotal { font-size: 9px; color: #000; }
            .total-label { font-size: 12px; color: #000; }
            .total-value { font-size: 14px; color: #000; }
            .payment-label { color: #000; }
            .payment-value { color: #000; }
            .change-value { color: #000; }
            
            .loyalty-section {
                background: none;
                border: 1px dashed #000;
                border-radius: 0;
            }
            .loyalty-label { color: #000; }
            .loyalty-value { color: #000; }
            .receipt-footer { font-size: 8px; color: #000; }
            
            .logo { max-width: 50px; max-height: 50px; }
            .item-block { margin: 4px 0; }
        }
    </style>
</head>
<body>

    {{-- Watermark --}}
    <div class="watermark">SELLSYNC</div>

    @php
        $settings = \App\Models\BusinessSetting::getForTenant($sale->tenant_id);
        $currency = $settings->currency_symbol ?? 'KES';
    @endphp

    {{-- Brand Section --}}
    <div class="center brand-section">
        @if($settings->receipt_show_logo && $settings->logo_path)
            <img src="{{ Storage::url($settings->logo_path) }}" alt="Logo" class="logo" />
        @endif

        <div class="business-name">{{ $sale->branch->tenant->name ?? 'SellSync-POS' }}</div>
        @if($sale->branch->name)
            <div class="business-branch">{{ $sale->branch->name }}</div>
        @endif

        <div class="business-info">
            @if($settings->receipt_show_address)
                @if($settings->address)
                    <div>{{ $settings->address }}</div>
                @endif
                @if($settings->city)
                    <div>{{ $settings->city }}</div>
                @endif
            @endif

            @if($settings->receipt_show_phone && $settings->phone)
                <div>Tel: {{ $settings->phone }}</div>
            @endif

            @if($settings->receipt_show_email && $settings->email)
                <div>{{ $settings->email }}</div>
            @endif

            @if($settings->website)
                <div>{{ $settings->website }}</div>
            @endif

            @if($settings->receipt_show_tax && $settings->tax_number)
                <div style="margin-top:2px;font-weight:600">{{ $settings->tax_name ?? 'VAT' }} No: {{ $settings->tax_number }}</div>
            @endif
        </div>

        @if($settings->receipt_header)
            <div class="receipt-header-msg">{{ $settings->receipt_header }}</div>
        @endif
    </div>

    <div class="divider"></div>

    {{-- Sale Info --}}
    <div class="row">
        <span class="row-label">Invoice</span>
        <span class="invoice-no">{{ $sale->invoice_no }}</span>
    </div>
    <div class="row">
        <span class="row-label">Date</span>
        <span class="row-value">{{ $sale->created_at->format('d/m/Y H:i') }}</span>
    </div>
    <div class="row">
        <span class="row-label">Cashier</span>
        <span class="row-value">{{ $sale->user->name }}</span>
    </div>
    @if($sale->customer)
        <div class="row">
            <span class="row-label">Customer</span>
            <span class="row-value" style="font-family:'Outfit',sans-serif">{{ $sale->customer->name }}</span>
        </div>
    @endif

    <div class="divider"></div>

    {{-- Items --}}
    @foreach($sale->items as $item)
        <div class="item-block">
            <div class="item-name">{{ $item->product_name }}</div>
            <div class="item-details">
                <span class="item-qty-price">{{ $item->qty }} x {{ $currency }} {{ number_format($item->unit_price, 2) }}</span>
                <span class="item-subtotal">{{ $currency }} {{ number_format($item->subtotal, 2) }}</span>
            </div>
        </div>
    @endforeach

    <div class="divider"></div>

    {{-- Totals --}}
    <div class="row">
        <span class="row-label">Subtotal</span>
        <span class="row-value">{{ $currency }} {{ number_format($sale->subtotal, 2) }}</span>
    </div>
    @if($sale->tax_amount > 0)
        <div class="row">
            <span class="row-label">{{ $settings->tax_name ?? 'Tax' }}</span>
            <span class="row-value">{{ $currency }} {{ number_format($sale->tax_amount, 2) }}</span>
        </div>
    @endif
    @if($sale->discount_amount > 0)
        <div class="row">
            <span class="row-label">Discount</span>
            <span class="row-value" style="color:#dc2626">- {{ $currency }} {{ number_format($sale->discount_amount, 2) }}</span>
        </div>
    @endif

    <div class="divider-solid"></div>

    <div class="total-row">
        <span class="total-label">TOTAL</span>
        <span class="total-value">{{ $currency }} {{ number_format($sale->total, 2) }}</span>
    </div>

    <div class="divider"></div>

    {{-- Payment Details --}}
    <div class="payment-row">
        <span class="payment-label">Paid ({{ ucfirst($sale->payment_method) }})</span>
        <span class="payment-value">{{ $currency }} {{ number_format($sale->amount_paid, 2) }}</span>
    </div>
    <div class="payment-row">
        <span class="payment-label">Change</span>
        <span class="change-value">{{ $currency }} {{ number_format($sale->change_amount, 2) }}</span>
    </div>

    {{-- Split Payment Details --}}
    @if($sale->payment_method === 'split' && $sale->split_payments)
        <div class="divider"></div>
        @php $splitPayments = is_string($sale->split_payments) ? json_decode($sale->split_payments, true) : $sale->split_payments; @endphp
        @if(is_array($splitPayments))
            @if(($splitPayments['cash'] ?? 0) > 0)
                <div class="row">
                    <span class="row-label">Cash</span>
                    <span class="row-value">{{ $currency }} {{ number_format($splitPayments['cash'], 2) }}</span>
                </div>
            @endif
            @if(($splitPayments['card'] ?? 0) > 0)
                <div class="row">
                    <span class="row-label">Card</span>
                    <span class="row-value">{{ $currency }} {{ number_format($splitPayments['card'], 2) }}</span>
                </div>
            @endif
            @if(($splitPayments['mobile'] ?? 0) > 0)
                <div class="row">
                    <span class="row-label">M-Pesa</span>
                    <span class="row-value">{{ $currency }} {{ number_format($splitPayments['mobile'], 2) }}</span>
                </div>
            @endif
        @endif
    @endif

    {{-- Barcode (Invoice number as barcode text) --}}
    <div class="barcode-area">
        <div class="barcode-text">*{{ $sale->invoice_no }}*</div>
    </div>

    {{-- Loyalty Points --}}
    @if($settings->receipt_show_loyalty && $sale->customer && $sale->customer->loyalty_points)
        <div class="loyalty-section">
            <div class="loyalty-row">
                <span class="loyalty-label">Loyalty Points</span>
                <span class="loyalty-value">{{ number_format($sale->customer->loyalty_points) }} pts</span>
            </div>
        </div>
    @endif

    {{-- Footer --}}
    @if($settings->receipt_footer)
        <div class="divider"></div>
        <p class="center receipt-footer">{{ $settings->receipt_footer }}</p>
    @endif

    {{-- Buttons (hidden when printing) --}}
    <div class="btn-group no-print">
        <button onclick="window.print()" class="btn-primary">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            Print Receipt
        </button>
        <button onclick="window.close()" class="btn-secondary">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Close
        </button>
    </div>
</body>
</html>