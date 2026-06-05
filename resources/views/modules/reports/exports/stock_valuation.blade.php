@extends('layouts.pdf')

@section('content')
@php
    $settings = \App\Models\BusinessSetting::getForTenant(auth()->user()->tenant_id);
    $currency = $settings->currency_symbol ?? 'KES';
@endphp

{{-- Summary Cards --}}
<div class="summary-grid">
    <div class="summary-card">
        <div class="summary-card-label" style="color:#6b7280">Cost Value</div>
        <div class="summary-card-value" style="color:#111827">{{ $currency }} {{ number_format($summary['total_cost_value'], 2) }}</div>
        <div class="summary-card-sub">Total cost of inventory</div>
    </div>
    <div class="summary-card highlight">
        <div class="summary-card-label">Retail Value</div>
        <div class="summary-card-value">{{ $currency }} {{ number_format($summary['total_sell_value'], 2) }}</div>
        <div class="summary-card-sub">If all sold at selling price</div>
    </div>
    <div class="summary-card green">
        <div class="summary-card-label">Potential Profit</div>
        <div class="summary-card-value">{{ $currency }} {{ number_format($summary['total_potential'], 2) }}</div>
        <div class="summary-card-sub">Retail − cost value</div>
    </div>
    <div class="summary-card">
        <div class="summary-card-label" style="color:#6b7280">Products</div>
        <div class="summary-card-value" style="color:#111827">{{ $summary['total_products'] }}</div>
        <div class="summary-card-sub">Tracked items</div>
    </div>
    <div class="summary-card red">
        <div class="summary-card-label">Stock Alerts</div>
        <div class="summary-card-value">{{ $summary['out_of_stock'] }} / {{ $summary['low_stock'] }}</div>
        <div class="summary-card-sub">Out of stock / Low stock</div>
    </div>
</div>

{{-- Valuation by Category --}}
@if($byCategory->count() > 0)
<div class="section-title">
    <span class="section-icon">C</span> Valuation by Category
</div>
<table>
    <thead>
        <tr>
            <th>Category</th>
            <th class="text-right">Products</th>
            <th class="text-right">Cost Value</th>
            <th class="text-right">Retail Value</th>
            <th class="text-right">Margin</th>
        </tr>
    </thead>
    <tbody>
        @foreach($byCategory as $catName => $data)
            @php 
                $margin = $data['sell_value'] > 0 
                    ? round((($data['sell_value'] - $data['cost_value']) / $data['sell_value']) * 100, 1) 
                    : 0; 
                $marginBadge = $margin >= 30 ? 'badge-green' : ($margin >= 10 ? 'badge-yellow' : 'badge-red');
            @endphp
            <tr>
                <td class="font-bold">{{ $catName ?? 'Uncategorised' }}</td>
                <td class="font-mono text-right">{{ $data['count'] }}</td>
                <td class="font-mono text-right">{{ $currency }} {{ number_format($data['cost_value'], 2) }}</td>
                <td class="font-mono text-right text-brand font-bold">{{ $currency }} {{ number_format($data['sell_value'], 2) }}</td>
                <td class="text-right">
                    <span class="badge {{ $marginBadge }}">{{ $margin }}%</span>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- All Products --}}
<div class="section-title">
    <span class="section-icon">P</span> All Tracked Products ({{ $products->count() }})
</div>
<table>
    <thead>
        <tr>
            <th>Product</th>
            <th>Category</th>
            <th class="text-right">Stock</th>
            <th class="text-right">Cost Price</th>
            <th class="text-right">Sell Price</th>
            <th class="text-right">Cost Value</th>
            <th class="text-right">Retail Value</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
            @php
                $stockColor = '#111827';
                $statusBadge = 'badge-green';
                $statusLabel = 'In Stock';
                if ($product->stock_qty <= 0) {
                    $stockColor = '#dc2626';
                    $statusBadge = 'badge-red';
                    $statusLabel = 'Out of Stock';
                } elseif ($product->isLowStock()) {
                    $stockColor = '#d97706';
                    $statusBadge = 'badge-yellow';
                    $statusLabel = 'Low Stock';
                }
            @endphp
            <tr>
                <td class="font-bold">{{ $product->name }}</td>
                <td class="text-sm">{{ $product->category->name ?? '—' }}</td>
                <td class="font-mono text-right font-bold" style="color:{{ $stockColor }}">
                    {{ $product->stock_qty }}
                </td>
                <td class="font-mono text-right text-sm">{{ $currency }} {{ number_format($product->cost_price, 2) }}</td>
                <td class="font-mono text-right text-sm">{{ $currency }} {{ number_format($product->price, 2) }}</td>
                <td class="font-mono text-right text-sm">{{ $currency }} {{ number_format($product->cost_value, 2) }}</td>
                <td class="font-mono text-right text-brand font-bold text-sm">{{ $currency }} {{ number_format($product->sell_value, 2) }}</td>
                <td>
                    <span class="badge {{ $statusBadge }}">{{ $statusLabel }}</span>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection