@extends('layouts.pdf')

@section('content')
@php
    $settings = \App\Models\BusinessSetting::getForTenant(auth()->user()->tenant_id);
    $currency = $settings->currency_symbol ?? 'KES';
@endphp

{{-- Summary Cards --}}
<div class="summary-grid">
    <div class="summary-card summary-card-green">
        <div class="summary-card-icon">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="summary-card-content">
            <div class="summary-card-label">Total Revenue</div>
            <div class="summary-card-value">{{ $currency }} {{ number_format($summary->total_revenue ?? 0, 2) }}</div>
            <div class="summary-card-sub">{{ $summary->total_transactions ?? 0 }} transactions</div>
        </div>
    </div>
    <div class="summary-card summary-card-blue">
        <div class="summary-card-icon">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        </div>
        <div class="summary-card-content">
            <div class="summary-card-label">Average Sale</div>
            <div class="summary-card-value">{{ $currency }} {{ number_format($summary->avg_sale ?? 0, 2) }}</div>
            <div class="summary-card-sub">Per transaction</div>
        </div>
    </div>
    <div class="summary-card summary-card-dark">
        <div class="summary-card-icon">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
        </div>
        <div class="summary-card-content">
            <div class="summary-card-label">Total Tax</div>
            <div class="summary-card-value">{{ $currency }} {{ number_format($summary->total_tax ?? 0, 2) }}</div>
            <div class="summary-card-sub">Collected</div>
        </div>
    </div>
    <div class="summary-card summary-card-red">
        <div class="summary-card-icon">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/></svg>
        </div>
        <div class="summary-card-content">
            <div class="summary-card-label">Discounts Given</div>
            <div class="summary-card-value">{{ $currency }} {{ number_format($summary->total_discount ?? 0, 2) }}</div>
            <div class="summary-card-sub">Total deductions</div>
        </div>
    </div>
</div>

{{-- Payment Breakdown --}}
<div class="section-card">
    <div class="section-card-header">
        <div class="section-card-icon">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <span class="section-card-title">Payment Methods</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Method</th>
                <th class="text-right">Amount</th>
                <th class="text-right">Share (%)</th>
                <th class="text-center" style="width:35%">Distribution</th>
            </tr>
        </thead>
        <tbody>
            @php $totalRev = $summary->total_revenue ?? 1; @endphp
            @foreach([
                ['Cash', $summary->cash_total ?? 0, '#03A737'],
                ['Card', $summary->card_total ?? 0, '#3D7BE7'],
                ['M-Pesa', $summary->mobile_total ?? 0, '#02182F'],
            ] as [$label, $amount, $color])
                @php $pct = $totalRev > 0 ? round(($amount / $totalRev) * 100) : 0; @endphp
                <tr>
                    <td>
                        <span class="dot-indicator" style="background:{{ $color }}"></span>
                        <strong>{{ $label }}</strong>
                    </td>
                    <td class="font-mono text-right">{{ $currency }} {{ number_format($amount, 2) }}</td>
                    <td class="font-mono text-right">{{ $pct }}%</td>
                    <td class="text-center" style="padding:8px 12px">
                        <div class="mini-bar">
                            <div class="mini-bar-fill" style="width:{{ $pct }}%;background:{{ $color }}"></div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Top Products --}}
@if($topProducts->count() > 0)
<div class="section-card">
    <div class="section-card-header">
        <div class="section-card-icon">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
        </div>
        <span class="section-card-title">Top Selling Products</span>
    </div>
    <table>
        <thead>
            <tr>
                <th style="width:40px">#</th>
                <th>Product</th>
                <th class="text-right">Qty Sold</th>
                <th class="text-right">Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topProducts as $i => $product)
                <tr>
                    <td class="text-center">
                        @if($i < 3)
                            <span class="rank-badge rank-badge-{{ $i === 0 ? 'gold' : ($i === 1 ? 'silver' : 'bronze') }}">{{ $i + 1 }}</span>
                        @else
                            <span class="text-muted">{{ $i + 1 }}</span>
                        @endif
                    </td>
                    <td><strong>{{ $product->product_name }}</strong></td>
                    <td class="font-mono text-right">{{ $product->total_qty }}</td>
                    <td class="font-mono text-right text-brand font-bold">{{ $currency }} {{ number_format($product->total_revenue, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- All Transactions --}}
<div class="section-card">
    <div class="section-card-header">
        <div class="section-card-icon">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <span class="section-card-title">All Transactions</span>
        <span class="section-card-badge">{{ $sales->count() }} records</span>
    </div>
    @if($sales->count())
    <table>
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Date & Time</th>
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
                    <td class="font-mono text-brand text-sm">{{ $sale->invoice_no }}</td>
                    <td class="text-sm">{{ $sale->created_at->format('d M Y, h:i A') }}</td>
                    <td class="text-sm">{{ $sale->customer->name ?? 'Walk-in' }}</td>
                    <td class="text-sm">{{ $sale->user->name ?? '—' }}</td>
                    <td class="text-center text-sm">{{ $sale->items->count() }}</td>
                    <td class="text-sm">
                        @php
                            $payBadge = match($sale->payment_method) {
                                'cash' => 'badge-green',
                                'card' => 'badge-blue',
                                'mobile' => 'badge-yellow',
                                default => 'badge-gray',
                            };
                        @endphp
                        <span class="badge {{ $payBadge }}">{{ ucfirst($sale->payment_method) }}</span>
                    </td>
                    <td class="font-mono text-right font-bold">{{ $currency }} {{ number_format($sale->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <div class="empty-state">No transactions found for this period.</div>
    @endif
</div>
@endsection