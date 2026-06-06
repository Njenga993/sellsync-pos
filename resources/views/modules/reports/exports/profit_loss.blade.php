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
            <div class="summary-card-label">Gross Revenue</div>
            <div class="summary-card-value">{{ $currency }} {{ number_format($revenue, 2) }}</div>
            <div class="summary-card-sub">Total sales collected</div>
        </div>
    </div>
    <div class="summary-card summary-card-red">
        <div class="summary-card-icon">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <div class="summary-card-content">
            <div class="summary-card-label">COGS + Refunds</div>
            <div class="summary-card-value">{{ $currency }} {{ number_format($cogs + $refunds, 2) }}</div>
            <div class="summary-card-sub">Cost of goods & returns</div>
        </div>
    </div>
    <div class="summary-card summary-card-red">
        <div class="summary-card-icon">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
        </div>
        <div class="summary-card-content">
            <div class="summary-card-label">Expenses</div>
            <div class="summary-card-value">{{ $currency }} {{ number_format($expenses, 2) }}</div>
            <div class="summary-card-sub">Operating costs</div>
        </div>
    </div>
    <div class="summary-card {{ $netProfit >= 0 ? 'summary-card-green' : 'summary-card-red' }}">
        <div class="summary-card-icon">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        </div>
        <div class="summary-card-content">
            <div class="summary-card-label">Net Profit</div>
            <div class="summary-card-value">{{ $currency }} {{ number_format($netProfit, 2) }}</div>
            <div class="summary-card-sub">{{ $netProfit >= 0 ? 'Profitable period' : 'Loss period' }}</div>
        </div>
    </div>
</div>

{{-- Margin Analysis --}}
<div class="section-card">
    <div class="section-card-header">
        <div class="section-card-icon">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <span class="section-card-title">Margin Analysis</span>
    </div>
    <div style="display:flex;gap:10px;padding:14px 16px">
        <div style="flex:1;background:#e6f7eb;border:1px solid #b8e6c4;border-radius:10px;padding:14px 16px;text-align:center">
            <div style="font-size:7px;font-weight:700;color:#028a2e;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px">Gross Margin</div>
            <div class="font-mono font-bold" style="font-size:18px;color:#03A737">
                {{ $revenue > 0 ? round(($grossProfit / $revenue) * 100, 1) : 0 }}%
            </div>
        </div>
        <div style="flex:1;background:#edf3fd;border:1px solid #c4d9fb;border-radius:10px;padding:14px 16px;text-align:center">
            <div style="font-size:7px;font-weight:700;color:#2b5fc4;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px">Net Margin</div>
            <div class="font-mono font-bold" style="font-size:18px;color:{{ $netProfit >= 0 ? '#03A737' : '#dc2626' }}">
                {{ $revenue > 0 ? round(($netProfit / $revenue) * 100, 1) : 0 }}%
            </div>
        </div>
        <div style="flex:1;background:#f0f2f5;border:1px solid #d4d8e0;border-radius:10px;padding:14px 16px;text-align:center">
            <div style="font-size:7px;font-weight:700;color:#4b5563;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px">Expense Ratio</div>
            <div class="font-mono font-bold" style="font-size:18px;color:#d97706">
                {{ $revenue > 0 ? round(($expenses / $revenue) * 100, 1) : 0 }}%
            </div>
        </div>
    </div>
</div>

{{-- P&L Statement --}}
<div class="section-card">
    <div class="section-card-header">
        <div class="section-card-icon">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <span class="section-card-title">Profit & Loss Statement</span>
    </div>
    <table class="pl-table">
        <tbody>
            <tr>
                <td class="pl-label">Gross Revenue</td>
                <td class="font-mono text-right text-green font-bold">+ {{ $currency }} {{ number_format($revenue, 2) }}</td>
            </tr>
            <tr>
                <td class="pl-label">Cost of Goods Sold (COGS)</td>
                <td class="font-mono text-right text-red">− {{ $currency }} {{ number_format($cogs, 2) }}</td>
            </tr>
            <tr>
                <td class="pl-label">Refunds Issued</td>
                <td class="font-mono text-right text-red">− {{ $currency }} {{ number_format($refunds, 2) }}</td>
            </tr>
            <tr style="background:#edf3fd">
                <td class="pl-label" style="color:#2b5fc4;font-size:11px">Gross Profit</td>
                <td class="font-mono text-right" style="color:#3D7BE7;font-weight:700;font-size:12px">{{ $currency }} {{ number_format($grossProfit, 2) }}</td>
            </tr>
            <tr>
                <td class="pl-label">Operating Expenses</td>
                <td class="font-mono text-right text-red">− {{ $currency }} {{ number_format($expenses, 2) }}</td>
            </tr>
            <tr class="pl-total {{ $netProfit >= 0 ? 'pl-total-green' : 'pl-total-red' }}">
                <td class="pl-label" style="font-size:13px">Net Profit</td>
                <td class="font-mono text-right font-bold {{ $netProfit >= 0 ? 'text-green' : 'text-red' }}" style="font-size:16px">
                    {{ $currency }} {{ number_format($netProfit, 2) }}
                </td>
            </tr>
        </tbody>
    </table>
</div>

{{-- Monthly Breakdown --}}
@if($monthlySales->count() > 0)
<div class="section-card">
    <div class="section-card-header">
        <div class="section-card-icon">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 2v4M8 2v4M3 10h18"/></svg>
        </div>
        <span class="section-card-title">Monthly Revenue Breakdown</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th class="text-right">Transactions</th>
                <th class="text-right">Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlySales as $month)
                <tr>
                    <td class="font-bold">{{ \Carbon\Carbon::parse($month->month . '-01')->format('F Y') }}</td>
                    <td class="font-mono text-right">{{ $month->transactions }}</td>
                    <td class="font-mono text-right text-green font-bold">{{ $currency }} {{ number_format($month->revenue, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- Expenses by Category --}}
@if($expensesByCategory->count() > 0)
<div class="section-card">
    <div class="section-card-header">
        <div class="section-card-icon">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <span class="section-card-title">Expenses by Category</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th class="text-right">Amount</th>
                <th class="text-right">% of Total</th>
                <th class="text-center" style="width:30%">Distribution</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expensesByCategory as $cat)
                @php 
                    $pct = $expenses > 0 ? round(($cat->total / $expenses) * 100) : 0;
                    $color = $cat->category->color ?? '#03A737';
                @endphp
                <tr>
                    <td>
                        <span class="dot-indicator" style="background:{{ $color }}"></span>
                        {{ $cat->category->name ?? 'Uncategorised' }}
                    </td>
                    <td class="font-mono text-right font-bold">{{ $currency }} {{ number_format($cat->total, 2) }}</td>
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
@endif
@endsection