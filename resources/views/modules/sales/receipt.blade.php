<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Receipt — {{ $sale->invoice_no }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: monospace; font-size: 12px; width: 300px; margin: 0 auto; padding: 16px; }
        .center { text-align: center; }
        .divider { border-top: 1px dashed #000; margin: 8px 0; }
        .row { display: flex; justify-content: space-between; margin: 3px 0; }
        .bold { font-weight: bold; }
        .large { font-size: 14px; }
        .logo { max-width: 80px; max-height: 80px; object-fit: contain; display: block; margin: 0 auto 8px; }
        @media print {
            body { width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    @php
        $settings = \App\Models\BusinessSetting::getForTenant($sale->tenant_id);
        $currency = $settings->currency_symbol ?? 'KES';
    @endphp

    <div class="center">
        {{-- Logo --}}
        @if($settings->receipt_show_logo && $settings->logo_path)
            <img src="{{ Storage::url($settings->logo_path) }}" alt="Logo" class="logo" />
        @endif

        {{-- Business Name --}}
        <p class="bold large">{{ $sale->branch->tenant->name ?? 'POS System' }}</p>
        <p>{{ $sale->branch->name ?? '' }}</p>

        {{-- Address --}}
        @if($settings->receipt_show_address)
            @if($settings->address)
                <p>{{ $settings->address }}</p>
            @endif
            @if($settings->city)
                <p>{{ $settings->city }}</p>
            @endif
        @endif

        {{-- Phone --}}
        @if($settings->receipt_show_phone && $settings->phone)
            <p>Tel: {{ $settings->phone }}</p>
        @endif

        {{-- Email --}}
        @if($settings->receipt_show_email && $settings->email)
            <p>{{ $settings->email }}</p>
        @endif

        {{-- Website --}}
        @if($settings->website)
            <p>{{ $settings->website }}</p>
        @endif

        {{-- Tax Number --}}
        @if($settings->receipt_show_tax && $settings->tax_number)
            <p>{{ $settings->tax_name ?? 'VAT' }} No: {{ $settings->tax_number }}</p>
        @endif

        {{-- Header Message --}}
        @if($settings->receipt_header)
            <p style="margin-top:4px;font-style:italic">{{ $settings->receipt_header }}</p>
        @endif
    </div>

    <div class="divider"></div>

    <div class="row"><span>Invoice:</span><span>{{ $sale->invoice_no }}</span></div>
    <div class="row"><span>Date:</span><span>{{ $sale->created_at->format('d/m/Y H:i') }}</span></div>
    <div class="row"><span>Cashier:</span><span>{{ $sale->user->name }}</span></div>
    @if($sale->customer)
        <div class="row"><span>Customer:</span><span>{{ $sale->customer->name }}</span></div>
    @endif

    <div class="divider"></div>

    @foreach($sale->items as $item)
        <div>
            <p>{{ $item->product_name }}</p>
            <div class="row">
                <span>{{ $item->qty }} x {{ $currency }} {{ number_format($item->unit_price, 2) }}</span>
                <span>{{ $currency }} {{ number_format($item->subtotal, 2) }}</span>
            </div>
        </div>
    @endforeach

    <div class="divider"></div>

    <div class="row"><span>Subtotal</span><span>{{ $currency }} {{ number_format($sale->subtotal, 2) }}</span></div>
    @if($sale->tax_amount > 0)
        <div class="row"><span>{{ $settings->tax_name ?? 'Tax' }}</span><span>{{ $currency }} {{ number_format($sale->tax_amount, 2) }}</span></div>
    @endif
    @if($sale->discount_amount > 0)
        <div class="row"><span>Discount</span><span>- {{ $currency }} {{ number_format($sale->discount_amount, 2) }}</span></div>
    @endif

    <div class="divider"></div>
    <div class="row bold large"><span>TOTAL</span><span>{{ $currency }} {{ number_format($sale->total, 2) }}</span></div>
    <div class="row"><span>Paid ({{ ucfirst($sale->payment_method) }})</span><span>{{ $currency }} {{ number_format($sale->amount_paid, 2) }}</span></div>
    <div class="row"><span>Change</span><span>{{ $currency }} {{ number_format($sale->change_amount, 2) }}</span></div>

    {{-- Loyalty Points --}}
    @if($settings->receipt_show_loyalty && $sale->customer)
        <div class="divider"></div>
        <div class="row">
            <span>Loyalty Points Balance</span>
            <span>{{ number_format($sale->customer->loyalty_points) }} pts</span>
        </div>
    @endif

    <div class="divider"></div>

    {{-- Footer --}}
    @if($settings->receipt_footer)
        <p class="center" style="font-style:italic">{{ $settings->receipt_footer }}</p>
    @endif

    <br>
    <div class="center no-print">
        <button onclick="window.print()"
            style="padding:8px 16px;background:#4f46e5;color:white;border:none;border-radius:6px;cursor:pointer;font-size:13px">
            🖨️ Print
        </button>
        <button onclick="window.close()"
            style="padding:8px 16px;background:#e5e7eb;color:#374151;border:none;border-radius:6px;cursor:pointer;font-size:13px;margin-left:8px">
            Close
        </button>
    </div>
</body>
</html>