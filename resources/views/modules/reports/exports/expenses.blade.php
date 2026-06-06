@extends('layouts.pdf')

@section('content')
@php
    $settings = \App\Models\BusinessSetting::getForTenant(auth()->user()->tenant_id);
    $currency = $settings->currency_symbol ?? 'KES';
    $net = $totalRevenue - ($summary->total_expenses ?? 0);
@endphp

{{-- Summary Cards --}}
<div class="summary-grid">
    <div class="summary-card summary-card-red">
        <div class="summary-card-icon">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
        </div>
        <div class="summary-card-content">
            <div class="summary-card-label">Total Expenses</div>
            <div class="summary-card-value">{{ $currency }} {{ number_format($summary->total_expenses ?? 0, 2) }}</div>
            <div class="summary-card-sub">{{ $summary->total_count ?? 0 }} expense records</div>
        </div>
    </div>
    <div class="summary-card summary-card-green">
        <div class="summary-card-icon">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="summary-card-content">
            <div class="summary-card-label">Total Revenue</div>
            <div class="summary-card-value">{{ $currency }} {{ number_format($totalRevenue, 2) }}</div>
            <div class="summary-card-sub">Same period</div>
        </div>
    </div>
    <div class="summary-card summary-card-blue">
        <div class="summary-card-icon">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        </div>
        <div class="summary-card-content">
            <div class="summary-card-label">Net Profit</div>
            <div class="summary-card-value">{{ $currency }} {{ number_format($net, 2) }}</div>
            <div class="summary-card-sub">Revenue − expenses</div>
        </div>
    </div>
    <div class="summary-card summary-card-dark">
        <div class="summary-card-icon">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <div class="summary-card-content">
            <div class="summary-card-label">Profit Margin</div>
            <div class="summary-card-value" style="color:{{ $net >= 0 ? '#03A737' : '#dc2626' }};font-size:16px">
                {{ $totalRevenue > 0 ? round(($net / $totalRevenue) * 100, 1) : 0 }}%
            </div>
            <div class="summary-card-sub">{{ $net >= 0 ? 'Healthy margin' : 'Needs attention' }}</div>
        </div>
    </div>
</div>

{{-- Expense Analysis --}}
<div class="section-card">
    <div class="section-card-header">
        <div class="section-card-icon">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
        </div>
        <span class="section-card-title">Expense Analysis</span>
    </div>
    <div style="display:flex;gap:10px;padding:14px 16px">
        <div style="flex:1;background:#fef2f2;border:1px solid #fecdd3;border-radius:10px;padding:14px 16px;text-align:center">
            <div style="font-size:7px;font-weight:700;color:#be123c;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px">Daily Avg Expense</div>
            @php
                $days = max(1, now()->parse($from)->diffInDays(now()->parse($to)) + 1);
                $avgDaily = ($summary->total_expenses ?? 0) / $days;
            @endphp
            <div class="font-mono font-bold" style="font-size:16px;color:#02182F">{{ $currency }} {{ number_format($avgDaily, 2) }}</div>
            <div style="font-size:8px;color:#e11d48;margin-top:2px">Over {{ $days }} day(s)</div>
        </div>
        <div style="flex:1;background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:14px 16px;text-align:center">
            <div style="font-size:7px;font-weight:700;color:#a16207;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px">Expense Ratio</div>
            <div class="font-mono font-bold" style="font-size:16px;color:#02182F">
                {{ $totalRevenue > 0 ? round((($summary->total_expenses ?? 0) / $totalRevenue) * 100, 1) : 0 }}%
            </div>
            <div style="font-size:8px;color:#ca8a04;margin-top:2px">Of total revenue</div>
        </div>
        <div style="flex:1;background:#e6f7eb;border:1px solid #b8e6c4;border-radius:10px;padding:14px 16px;text-align:center">
            <div style="font-size:7px;font-weight:700;color:#028a2e;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px">Categories</div>
            <div class="font-mono font-bold" style="font-size:16px;color:#02182F">{{ $byCategory->count() }}</div>
            <div style="font-size:8px;color:#03A737;margin-top:2px">Expense categories</div>
        </div>
    </div>
</div>

{{-- Expenses by Category --}}
@if($byCategory->count() > 0)
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
            @foreach($byCategory as $cat)
                @php 
                    $total = $summary->total_expenses ?? 1; 
                    $pct = $total > 0 ? round(($cat->total / $total) * 100) : 0;
                    $color = $cat->category->color ?? '#03A737';
                @endphp
                <tr>
                    <td>
                        <span class="dot-indicator" style="background:{{ $color }}"></span>
                        <span class="font-bold">{{ $cat->category->name ?? 'Uncategorised' }}</span>
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

{{-- All Expenses --}}
<div class="section-card">
    <div class="section-card-header">
        <div class="section-card-icon">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        </div>
        <span class="section-card-title">All Expenses</span>
        <span class="section-card-badge">{{ $expenses->count() }} records</span>
    </div>
    @if($expenses->count())
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
                            <span class="text-muted text-sm">—</span>
                        @endif
                    </td>
                    <td class="text-sm" style="text-transform:capitalize">{{ $expense->payment_method }}</td>
                    <td class="font-mono text-right font-bold text-red">{{ $currency }} {{ number_format($expense->amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <div class="empty-state">No expenses recorded for this period.</div>
    @endif
</div>
@endsection