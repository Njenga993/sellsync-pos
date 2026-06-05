@extends('layouts.pdf')

@section('content')
@php
    $settings = \App\Models\BusinessSetting::getForTenant(auth()->user()->tenant_id);
    $currency = $settings->currency_symbol ?? 'KES';
@endphp

{{-- Summary Cards --}}
<div class="summary-grid">
    <div class="summary-card highlight">
        <div class="summary-card-label">Gross Revenue</div>
        <div class="summary-card-value">{{ $currency }} {{ number_format($revenue, 2) }}</div>
        <div class="summary-card-sub">Total sales collected</div>
    </div>
    <div class="summary-card red">
        <div class="summary-card-label">COGS + Refunds</div>
        <div class="summary-card-value">{{ $currency }} {{ number_format($cogs + $refunds, 2) }}</div>
        <div class="summary-card-sub">Cost of goods & returns</div>
    </div>
    <div class="summary-card red">
        <div class="summary-card-label">Expenses</div>
        <div class="summary-card-value">{{ $currency }} {{ number_format($expenses, 2) }}</div>
        <div class="summary-card-sub">Operating costs</div>
    </div>
    <div class="summary-card {{ $netProfit >= 0 ? 'green' : 'red' }}">
        <div class="summary-card-label">Net Profit</div>
        <div class="summary-card-value">{{ $currency }} {{ number_format($netProfit, 2) }}</div>
        <div class="summary-card-sub">{{ $netProfit >= 0 ? 'Profitable period' : 'Loss period' }}</div>
    </div>
</div>

{{-- P&L Statement --}}
<div class="section-title">
    <span class="section-icon">P</span> Profit & Loss Statement
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
        <tr style="background:#eff4ff">
            <td class="pl-label" style="color:#1a56db;font-size:11px">Gross Profit</td>
            <td class="font-mono text-right text-brand font-bold" style="font-size:12px">{{ $currency }} {{ number_format($grossProfit, 2) }}</td>
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

{{-- Margin Analysis --}}
<div style="display:flex;gap:12px;margin-top:16px">
    <div style="flex:1;background:#fafbff;border:1px solid #e4e7ef;border-radius:10px;padding:14px 16px;text-align:center">
        <div style="font-size:7px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px">Gross Margin</div>
        <div class="font-mono font-bold" style="font-size:18px;color:#1a56db">
            {{ $revenue > 0 ? round(($grossProfit / $revenue) * 100, 1) : 0 }}%
        </div>
    </div>
    <div style="flex:1;background:#fafbff;border:1px solid #e4e7ef;border-radius:10px;padding:14px 16px;text-align:center">
        <div style="font-size:7px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px">Net Margin</div>
        <div class="font-mono font-bold" style="font-size:18px;color:{{ $netProfit >= 0 ? '#16a34a' : '#dc2626' }}">
            {{ $revenue > 0 ? round(($netProfit / $revenue) * 100, 1) : 0 }}%
        </div>
    </div>
    <div style="flex:1;background:#fafbff;border:1px solid #e4e7ef;border-radius:10px;padding:14px 16px;text-align:center">
        <div style="font-size:7px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px">Expense Ratio</div>
        <div class="font-mono font-bold" style="font-size:18px;color:#d97706">
            {{ $revenue > 0 ? round(($expenses / $revenue) * 100, 1) : 0 }}%
        </div>
    </div>
</div>

{{-- Monthly Breakdown --}}
@if($monthlySales->count() > 0)
<div class="section-title">
    <span class="section-icon">M</span> Monthly Revenue Breakdown
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
@endif

{{-- Expenses by Category --}}
@if($expensesByCategory->count() > 0)
<div class="section-title">
    <span class="section-icon">E</span> Expenses by Category
</div>
<table>
    <thead>
        <tr>
            <th>Category</th>
            <th class="text-right">Amount</th>
            <th class="text-right">% of Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($expensesByCategory as $cat)
            @php $pct = $expenses > 0 ? round(($cat->total / $expenses) * 100) : 0; @endphp
            <tr>
                <td>
                    <span class="color-dot" style="background:{{ $cat->category->color ?? '#6366f1' }}"></span>
                    {{ $cat->category->name ?? 'Uncategorised' }}
                </td>
                <td class="font-mono text-right font-bold">{{ $currency }} {{ number_format($cat->total, 2) }}</td>
                <td class="font-mono text-right">{{ $pct }}%</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endif
@endsection