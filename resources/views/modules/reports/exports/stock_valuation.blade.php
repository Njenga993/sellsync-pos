@extends('layouts.pdf')

@section('content')
@php
    $settings = \App\Models\BusinessSetting::getForTenant(auth()->user()->tenant_id);
    $currency = $settings->currency_symbol ?? 'KES';
@endphp

{{-- Summary Cards --}}
<div class="summary-grid">
    <div class="summary-card summary-card-dark">
        <div class="summary-card-icon">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <div class="summary-card-content">
            <div class="summary-card-label">Cost Value</div>
            <div class="summary-card-value">{{ $currency }} {{ number_format($summary['total_cost_value'], 2) }}</div>
            <div class="summary-card-sub">Total cost of inventory</div>
        </div>
    </div>
    <div class="summary-card summary-card-blue">
        <div class="summary-card-icon">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="summary-card-content">
            <div class="summary-card-label">Retail Value</div>
            <div class="summary-card-value">{{ $currency }} {{ number_format($summary['total_sell_value'], 2) }}</div>
            <div class="summary-card-sub">If all sold at selling price</div>
        </div>
    </div>
    <div class="summary-card summary-card-green">
        <div class="summary-card-icon">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        </div>
        <div class="summary-card-content">
            <div class="summary-card-label">Potential Profit</div>
            <div class="summary-card-value">{{ $currency }} {{ number_format($summary['total_potential'], 2) }}</div>
            <div class="summary-card-sub">Retail − cost value</div>
        </div>
    </div>
    <div class="summary-card summary-card-dark">
        <div class="summary-card-icon">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <div class="summary-card-content">
            <div class="summary-card-label">Products</div>
            <div class="summary-card-value">{{ $summary['total_products'] }}</div>
            <div class="summary-card-sub">Tracked items</div>
        </div>
    </div>
    <div class="summary-card summary-card-red">
        <div class="summary-card-icon">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <div class="summary-card-content">
            <div class="summary-card-label">Stock Alerts</div>
            <div class="summary-card-value">{{ $summary['out_of_stock'] }} / {{ $summary['low_stock'] }}</div>
            <div class="summary-card-sub">Out of stock / Low stock</div>
        </div>
    </div>
</div>

{{-- Valuation by Category --}}
@if($byCategory->count() > 0)
<div class="section-card">
    <div class="section-card-header">
        <div class="section-card-icon">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
        </div>
        <span class="section-card-title">Valuation by Category</span>
        <span class="section-card-badge">{{ $byCategory->count() }} categories</span>
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
</div>
@endif

{{-- All Products --}}
<div class="section-card">
    <div class="section-card-header">
        <div class="section-card-icon">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <span class="section-card-title">All Tracked Products</span>
        <span class="section-card-badge">{{ $products->count() }} items</span>
    </div>
    @if($products->count())
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
                    $stockColor = '#02182F';
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
    @else
        <div class="empty-state">No tracked products found.</div>
    @endif
</div>
@endsection