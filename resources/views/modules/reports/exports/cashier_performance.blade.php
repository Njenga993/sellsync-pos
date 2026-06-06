@extends('layouts.pdf')

@section('content')
@php
    $settings = \App\Models\BusinessSetting::getForTenant(auth()->user()->tenant_id);
    $currency = $settings->currency_symbol ?? 'KES';
    
    $totalRevenue = $cashiers->sum('total_revenue');
    $totalSales = $cashiers->sum('total_sales');
    $totalItems = $cashiers->sum('total_items');
    $topCashier = $cashiers->first();
@endphp

{{-- Summary Cards --}}
<div class="summary-grid">
    <div class="summary-card summary-card-green">
        <div class="summary-card-icon">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="summary-card-content">
            <div class="summary-card-label">Total Revenue</div>
            <div class="summary-card-value">{{ $currency }} {{ number_format($totalRevenue, 2) }}</div>
            <div class="summary-card-sub">All staff combined</div>
        </div>
    </div>
    <div class="summary-card summary-card-blue">
        <div class="summary-card-icon">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <div class="summary-card-content">
            <div class="summary-card-label">Total Sales</div>
            <div class="summary-card-value">{{ $totalSales }}</div>
            <div class="summary-card-sub">{{ $totalItems }} items sold</div>
        </div>
    </div>
    <div class="summary-card summary-card-dark">
        <div class="summary-card-icon">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div class="summary-card-content">
            <div class="summary-card-label">Active Staff</div>
            <div class="summary-card-value">{{ $cashiers->count() }}</div>
            <div class="summary-card-sub">Team members</div>
        </div>
    </div>
    @if($topCashier)
    <div class="summary-card summary-card-green">
        <div class="summary-card-icon">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
        </div>
        <div class="summary-card-content">
            <div class="summary-card-label">Top Performer</div>
            <div class="summary-card-value" style="font-size:11px">{{ $topCashier->name }}</div>
            <div class="summary-card-sub">{{ $currency }} {{ number_format($topCashier->total_revenue, 2) }}</div>
        </div>
    </div>
    @endif
</div>

{{-- Staff Performance Leaderboard --}}
<div class="section-card">
    <div class="section-card-header">
        <div class="section-card-icon">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <span class="section-card-title">Staff Performance Leaderboard</span>
        <span class="section-card-badge">{{ $cashiers->count() }} staff</span>
    </div>
    @if($cashiers->count())
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width:35px">Rank</th>
                <th>Staff Member</th>
                <th>Role</th>
                <th>Branch</th>
                <th class="text-right">Sales</th>
                <th class="text-right">Items</th>
                <th class="text-right">Avg Sale</th>
                <th class="text-right">Returns</th>
                <th class="text-right">Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cashiers as $rank => $cashier)
                @php
                    $roleName = ucfirst($cashier->roles->first()?->name ?? '—');
                    $roleBadge = match($cashier->roles->first()?->name) {
                        'admin' => 'badge-red',
                        'manager' => 'badge-blue',
                        'cashier' => 'badge-green',
                        default => 'badge-gray',
                    };
                    $isTop3 = $rank < 3;
                @endphp
                <tr style="{{ $rank === 0 ? 'background:#fffbeb' : '' }}">
                    <td class="text-center" style="padding:10px 8px">
                        @if($rank < 3)
                            <span class="rank-badge rank-badge-{{ $rank === 0 ? 'gold' : ($rank === 1 ? 'silver' : 'bronze') }}">{{ $rank + 1 }}</span>
                        @else
                            <span class="font-mono text-muted" style="font-size:9px">{{ $rank + 1 }}</span>
                        @endif
                    </td>
                    <td class="font-bold">{{ $cashier->name }}</td>
                    <td><span class="badge {{ $roleBadge }}">{{ $roleName }}</span></td>
                    <td class="text-sm">{{ $cashier->branch->name ?? '—' }}</td>
                    <td class="font-mono text-right">{{ $cashier->total_sales }}</td>
                    <td class="font-mono text-right text-sm">{{ $cashier->total_items }}</td>
                    <td class="font-mono text-right text-sm">{{ $currency }} {{ number_format($cashier->avg_sale, 2) }}</td>
                    <td class="font-mono text-right" style="color:{{ $cashier->total_returns > 0 ? '#dc2626' : '#9ca3af' }}">
                        {{ $cashier->total_returns }}
                    </td>
                    <td class="font-mono text-right font-bold {{ $rank === 0 ? 'text-brand' : '' }}" style="{{ $rank === 0 ? 'font-size:11px' : 'font-size:9px' }}">
                        {{ $currency }} {{ number_format($cashier->total_revenue, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <div class="empty-state">No staff performance data for this period.</div>
    @endif
</div>

{{-- Top Products --}}
@if($topProducts->count() > 0)
<div class="section-card">
    <div class="section-card-header">
        <div class="section-card-icon">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
        </div>
        <span class="section-card-title">Top 10 Products by Revenue</span>
    </div>
    <table>
        <thead>
            <tr>
                <th style="width:35px">#</th>
                <th>Product</th>
                <th class="text-right">Qty Sold</th>
                <th class="text-right">Revenue</th>
                <th class="text-right">Profit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topProducts as $i => $product)
                <tr>
                    <td class="text-center">
                        @if($i < 3)
                            <span class="rank-badge rank-badge-{{ $i === 0 ? 'gold' : ($i === 1 ? 'silver' : 'bronze') }}">{{ $i + 1 }}</span>
                        @else
                            <span class="text-muted text-sm">{{ $i + 1 }}</span>
                        @endif
                    </td>
                    <td class="font-bold">{{ $product->product_name }}</td>
                    <td class="font-mono text-right">{{ $product->total_qty }}</td>
                    <td class="font-mono text-right text-brand font-bold">{{ $currency }} {{ number_format($product->total_revenue, 2) }}</td>
                    <td class="font-mono text-right text-green font-bold">{{ $currency }} {{ number_format($product->total_profit, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection