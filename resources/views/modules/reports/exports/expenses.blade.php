@extends('layouts.pdf')

@section('content')
@php
    $settings = \App\Models\BusinessSetting::getForTenant(auth()->user()->tenant_id);
    $currency = $settings->currency_symbol ?? 'KES';
    $net = $totalRevenue - ($summary->total_expenses ?? 0);
@endphp

{{-- Summary Cards --}}
<div class="summary-grid">
    <div class="summary-card red">
        <div class="summary-card-label">Total Expenses</div>
        <div class="summary-card-value">{{ $currency }} {{ number_format($summary->total_expenses ?? 0, 2) }}</div>
        <div class="summary-card-sub">{{ $summary->total_count ?? 0 }} expense records</div>
    </div>
    <div class="summary-card green">
        <div class="summary-card-label">Total Revenue</div>
        <div class="summary-card-value">{{ $currency }} {{ number_format($totalRevenue, 2) }}</div>
        <div class="summary-card-sub">Same period</div>
    </div>
    <div class="summary-card highlight">
        <div class="summary-card-label">Net Profit</div>
        <div class="summary-card-value">{{ $currency }} {{ number_format($net, 2) }}</div>
        <div class="summary-card-sub">Revenue − expenses</div>
    </div>
    <div class="summary-card">
        <div class="summary-card-label" style="color:#6b7280">Profit Margin</div>
        <div class="summary-card-value" style="color:{{ $net >= 0 ? '#16a34a' : '#dc2626' }};font-size:18px">
            {{ $totalRevenue > 0 ? round(($net / $totalRevenue) * 100, 1) : 0 }}%
        </div>
        <div class="summary-card-sub">{{ $net >= 0 ? 'Healthy margin' : 'Needs attention' }}</div>
    </div>
</div>

{{-- Expense Analysis --}}
<div style="display:flex;gap:12px;margin-bottom:16px">
    <div style="flex:1;background:#fff1f2;border:1px solid #fecdd3;border-radius:10px;padding:14px 16px;text-align:center">
        <div style="font-size:7px;font-weight:700;color:#be123c;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px">Daily Avg Expense</div>
        @php
            $days = max(1, now()->parse($from)->diffInDays(now()->parse($to)) + 1);
            $avgDaily = ($summary->total_expenses ?? 0) / $days;
        @endphp
        <div class="font-mono font-bold" style="font-size:16px;color:#881337">{{ $currency }} {{ number_format($avgDaily, 2) }}</div>
        <div style="font-size:8px;color:#e11d48;margin-top:2px">Over {{ $days }} day(s)</div>
    </div>
    <div style="flex:1;background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:14px 16px;text-align:center">
        <div style="font-size:7px;font-weight:700;color:#a16207;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px">Expense Ratio</div>
        <div class="font-mono font-bold" style="font-size:16px;color:#854d0e">
            {{ $totalRevenue > 0 ? round((($summary->total_expenses ?? 0) / $totalRevenue) * 100, 1) : 0 }}%
        </div>
        <div style="font-size:8px;color:#ca8a04;margin-top:2px">Of total revenue</div>
    </div>
    <div style="flex:1;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:14px 16px;text-align:center">
        <div style="font-size:7px;font-weight:700;color:#15803d;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px">Categories</div>
        <div class="font-mono font-bold" style="font-size:16px;color:#14532d">{{ $byCategory->count() }}</div>
        <div style="font-size:8px;color:#22c55e;margin-top:2px">Expense categories</div>
    </div>
</div>

{{-- Expenses by Category --}}
@if($byCategory->count() > 0)
<div class="section-title">
    <span class="section-icon">C</span> Expenses by Category
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
        @foreach($byCategory as $cat)
            @php 
                $total = $summary->total_expenses ?? 1; 
                $pct = $total > 0 ? round(($cat->total / $total) * 100) : 0; 
            @endphp
            <tr>
                <td>
                    <span class="color-dot" style="background:{{ $cat->category->color ?? '#6366f1' }}"></span>
                    <span class="font-bold">{{ $cat->category->name ?? 'Uncategorised' }}</span>
                </td>
                <td class="font-mono text-right font-bold">{{ $currency }} {{ number_format($cat->total, 2) }}</td>
                <td class="font-mono text-right">{{ $pct }}%</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endif

{{-- All Expenses --}}
<div class="section-title">
    <span class="section-icon">E</span> All Expenses ({{ $expenses->count() }} records)
</div>
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Title</th>
            <th>Category</th>
            <th>Method</th>
            <th class="text-right">Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($expenses as $expense)
            <tr>
                <td class="text-sm">{{ $expense->expense_date->format('d M Y') }}</td>
                <td class="font-bold">{{ $expense->title }}</td>
                <td>
                    @if($expense->category)
                        <span class="badge" style="background:{{ $expense->category->color }}20;color:{{ $expense->category->color }}">
                            {{ $expense->category->name }}
                        </span>
                    @else
                        <span class="text-sm" style="color:#9ca3af">—</span>
                    @endif
                </td>
                <td class="text-sm" style="text-transform:capitalize">{{ $expense->payment_method }}</td>
                <td class="font-mono text-right font-bold text-red">{{ $currency }} {{ number_format($expense->amount, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection