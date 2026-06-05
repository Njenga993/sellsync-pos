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
    <div class="summary-card highlight">
        <div class="summary-card-label">Total Revenue</div>
        <div class="summary-card-value">{{ $currency }} {{ number_format($totalRevenue, 2) }}</div>
        <div class="summary-card-sub">All cashiers combined</div>
    </div>
    <div class="summary-card">
        <div class="summary-card-label" style="color:#6b7280">Total Sales</div>
        <div class="summary-card-value" style="color:#111827">{{ $totalSales }}</div>
        <div class="summary-card-sub">{{ $totalItems }} items sold</div>
    </div>
    <div class="summary-card">
        <div class="summary-card-label" style="color:#6b7280">Staff Count</div>
        <div class="summary-card-value" style="color:#111827">{{ $cashiers->count() }}</div>
        <div class="summary-card-sub">Active cashiers</div>
    </div>
    @if($topCashier)
    <div class="summary-card green">
        <div class="summary-card-label">Top Performer</div>
        <div class="summary-card-value" style="font-size:12px">{{ $topCashier->name }}</div>
        <div class="summary-card-sub">{{ $currency }} {{ number_format($topCashier->total_revenue, 2) }}</div>
    </div>
    @endif
</div>

{{-- Staff Performance Leaderboard --}}
<div class="section-title">
    <span class="section-icon">★</span> Staff Performance Leaderboard
</div>
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
                    default => 'badge-blue',
                };
                $isTop3 = $rank < 3;
            @endphp
            <tr style="{{ $rank === 0 ? 'background:#fffbeb' : '' }}">
                <td class="text-center font-mono font-bold" style="font-size:9px;color:{{ $isTop3 ? '#1a56db' : '#9ca3af' }}">
                    @if($rank === 0) 🥇
                    @elseif($rank === 1) 🥈
                    @elseif($rank === 2) 🥉
                    @else {{ $rank + 1 }}
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
                <td class="font-mono text-right font-bold {{ $rank === 0 ? 'text-green' : '' }}" style="{{ $rank === 0 ? 'font-size:11px' : 'font-size:9px' }}">
                    {{ $currency }} {{ number_format($cashier->total_revenue, 2) }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- Top Products --}}
@if($topProducts->count() > 0)
<div class="section-title">
    <span class="section-icon">P</span> Top 10 Products by Revenue
</div>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Product</th>
            <th class="text-right">Qty Sold</th>
            <th class="text-right">Revenue</th>
            <th class="text-right">Profit</th>
        </tr>
    </thead>
    <tbody>
        @foreach($topProducts as $i => $product)
            <tr>
                <td class="text-sm" style="color:#9ca3af">{{ $i + 1 }}</td>
                <td class="font-bold">{{ $product->product_name }}</td>
                <td class="font-mono text-right">{{ $product->total_qty }}</td>
                <td class="font-mono text-right text-brand font-bold">{{ $currency }} {{ number_format($product->total_revenue, 2) }}</td>
                <td class="font-mono text-right text-green font-bold">{{ $currency }} {{ number_format($product->total_profit, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endif
@endsection