@extends('layouts.pdf')

@section('content')
@php
    $settings = \App\Models\BusinessSetting::getForTenant(auth()->user()->tenant_id);
    $currency = $settings->currency_symbol ?? 'KES';
@endphp

{{-- Summary Cards --}}
<div class="summary-grid">
    <div class="summary-card highlight">
        <div class="summary-card-label">Total Revenue</div>
        <div class="summary-card-value">{{ $currency }} {{ number_format($summary->total_revenue ?? 0, 2) }}</div>
        <div class="summary-card-sub">{{ $summary->total_transactions ?? 0 }} transactions</div>
    </div>
    <div class="summary-card">
        <div class="summary-card-label" style="color:#6b7280">Average Sale</div>
        <div class="summary-card-value" style="color:#111827">{{ $currency }} {{ number_format($summary->avg_sale ?? 0, 2) }}</div>
        <div class="summary-card-sub">Per transaction</div>
    </div>
    <div class="summary-card green">
        <div class="summary-card-label">Total Tax</div>
        <div class="summary-card-value">{{ $currency }} {{ number_format($summary->total_tax ?? 0, 2) }}</div>
        <div class="summary-card-sub">Collected</div>
    </div>
    <div class="summary-card red">
        <div class="summary-card-label">Discounts</div>
        <div class="summary-card-value">{{ $currency }} {{ number_format($summary->total_discount ?? 0, 2) }}</div>
        <div class="summary-card-sub">Total deductions</div>
    </div>
</div>

{{-- Payment Breakdown --}}
<div class="section-title">
    <span class="section-icon">P</span> Payment Methods
</div>
<table>
    <thead>
        <tr>
            <th>Method</th>
            <th class="text-right">Amount</th>
            <th class="text-right">% Share</th>
        </tr>
    </thead>
    <tbody>
        @php $totalRev = $summary->total_revenue ?? 1; @endphp
        @foreach([
            ['Cash', $summary->cash_total ?? 0, '#16a34a'],
            ['Card', $summary->card_total ?? 0, '#1a56db'],
            ['M-Pesa', $summary->mobile_total ?? 0, '#7c3aed'],
        ] as [$label, $amount, $color])
            <tr>
                <td>
                    <span class="color-dot" style="background:{{ $color }}"></span>
                    {{ $label }}
                </td>
                <td class="font-mono text-right">{{ $currency }} {{ number_format($amount, 2) }}</td>
                <td class="font-mono text-right">{{ $totalRev > 0 ? round(($amount / $totalRev) * 100) : 0 }}%</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- Top Products --}}
<div class="section-title">
    <span class="section-icon">★</span> Top Selling Products
</div>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Product</th>
            <th class="text-right">Qty Sold</th>
            <th class="text-right">Revenue</th>
        </tr>
    </thead>
    <tbody>
        @foreach($topProducts as $i => $product)
            <tr>
                <td class="text-sm" style="color:#9ca3af">{{ $i + 1 }}</td>
                <td class="font-bold">{{ $product->product_name }}</td>
                <td class="font-mono text-right">{{ $product->total_qty }}</td>
                <td class="font-mono text-right text-brand font-bold">{{ $currency }} {{ number_format($product->total_revenue, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- All Transactions --}}
<div class="section-title">
    <span class="section-icon">≡</span> All Transactions ({{ $sales->count() }} records)
</div>
<table>
    <thead>
        <tr>
            <th>Invoice</th>
            <th>Date</th>
            <th>Customer</th>
            <th>Cashier</th>
            <th class="text-center">Items</th>
            <th>Payment</th>
            <th class="text-right">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sales as $sale)
            <tr>
                <td class="font-mono text-brand" style="font-size:8px">{{ $sale->invoice_no }}</td>
                <td class="text-sm">{{ $sale->created_at->format('d M Y, h:i A') }}</td>
                <td class="text-sm">{{ $sale->customer->name ?? 'Walk-in' }}</td>
                <td class="text-sm">{{ $sale->user->name ?? '—' }}</td>
                <td class="text-center text-sm">{{ $sale->items->count() }}</td>
                <td class="text-sm" style="text-transform:capitalize">
                    @php
                        $payBadge = match($sale->payment_method) {
                            'cash' => 'badge-green',
                            'card' => 'badge-blue',
                            'mobile' => 'badge-yellow',
                            default => 'badge-blue',
                        };
                    @endphp
                    <span class="badge {{ $payBadge }}">{{ $sale->payment_method }}</span>
                </td>
                <td class="font-mono text-right font-bold">{{ $currency }} {{ number_format($sale->total, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection