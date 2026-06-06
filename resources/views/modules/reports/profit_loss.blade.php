<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Finance</p>
                <h1 style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:700;color:#02182F;line-height:1.2">
                    Profit & Loss Report
                </h1>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('reports.stock-valuation') }}"
                   style="background:transparent;color:#6b7280;padding:9px 16px;border-radius:10px;font-size:12px;font-weight:600;text-decoration:none;font-family:'Outfit',sans-serif;border:1.5px solid #e4e7ef;transition:all .15s"
                   onmouseover="this.style.borderColor='#03A737';this.style.color='#03A737';this.style.background='#e6f7eb'"
                   onmouseout="this.style.borderColor='#e4e7ef';this.style.color='#6b7280';this.style.background='transparent'">
                    Stock Valuation
                </a>
                <a href="{{ route('reports.cashier-performance') }}"
                   style="background:transparent;color:#6b7280;padding:9px 16px;border-radius:10px;font-size:12px;font-weight:600;text-decoration:none;font-family:'Outfit',sans-serif;border:1.5px solid #e4e7ef;transition:all .15s"
                   onmouseover="this.style.borderColor='#03A737';this.style.color='#03A737';this.style.background='#e6f7eb'"
                   onmouseout="this.style.borderColor='#e4e7ef';this.style.color='#6b7280';this.style.background='transparent'">
                    Cashier Performance
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
        
        .dash-wrap { display: flex; flex-direction: column; gap: 20px; }
        
        .filter-bar {
            background: #FFFEFE;
            border: 1px solid #e4e7ef;
            border-radius: 14px;
            padding: 18px 22px;
            display: flex;
            align-items: flex-end;
            gap: 14px;
            flex-wrap: wrap;
        }
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .filter-label {
            font-family: 'Outfit', sans-serif;
            font-size: 10px;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .filter-input {
            padding: 9px 12px;
            border: 1.5px solid #e4e7ef;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            color: #02182F;
            background: #fafbff;
            transition: all 0.15s;
            outline: none;
        }
        .filter-input:focus {
            border-color: #03A737;
            background: #FFFEFE;
            box-shadow: 0 0 0 3px rgba(3, 167, 55, 0.08);
        }
        
        .btn-filter {
            padding: 9px 18px;
            background: #03A737;
            color: #FFFEFE;
            border: none;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(3, 167, 55, 0.25);
            transition: all 0.15s;
        }
        .btn-filter:hover {
            background: #028a2e;
            box-shadow: 0 4px 12px rgba(3, 167, 55, 0.35);
        }
        
        .btn-reset {
            padding: 9px 18px;
            background: transparent;
            color: #6b7280;
            border: 1.5px solid #e4e7ef;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s;
            display: inline-block;
        }
        .btn-reset:hover {
            border-color: #03A737;
            color: #03A737;
            background: #e6f7eb;
        }
        
        .panel {
            background: #FFFEFE;
            border: 1px solid #e4e7ef;
            border-radius: 14px;
            overflow: hidden;
        }
        .panel-padded { padding: 22px; }
        
        .panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 22px;
            border-bottom: 1px solid #f1f3f8;
            background: #fafbff;
        }
        .panel-title {
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            font-weight: 700;
            color: #02182F;
        }
        .panel-subtitle {
            font-family: 'Outfit', sans-serif;
            font-size: 11px;
            color: #9ca3af;
            font-weight: 500;
            margin-left: 8px;
        }
        
        .pl-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 22px;
            border-bottom: 1px solid #f8f9fb;
        }
        .pl-row:last-child { border-bottom: none; }
        
        .pl-row-green  { background: #e6f7eb; }
        .pl-row-red    { background: #fef2f2; }
        .pl-row-blue   { background: #edf3fd; }
        .pl-row-yellow { background: #fffbeb; }
        
        .pl-label {
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 600;
            color: #02182F;
        }
        .pl-description {
            font-family: 'Outfit', sans-serif;
            font-size: 11px;
            color: #9ca3af;
            margin-top: 2px;
        }
        .pl-amount {
            font-family: 'JetBrains Mono', monospace;
            font-size: 15px;
            font-weight: 700;
            text-align: right;
        }
        .pl-amount.positive { color: #03A737; }
        .pl-amount.negative { color: #dc2626; }
        .pl-amount.brand   { color: #3D7BE7; }
        
        .pl-row-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 22px;
        }
        .pl-row-total.green-bg { background: #e6f7eb; }
        .pl-row-total.red-bg   { background: #fef2f2; }
        
        .pl-total-label {
            font-family: 'Outfit', sans-serif;
            font-size: 15px;
            font-weight: 700;
            color: #02182F;
        }
        .pl-total-amount {
            font-family: 'JetBrains Mono', monospace;
            font-size: 22px;
            font-weight: 700;
        }
        .pl-total-amount.positive { color: #03A737; }
        .pl-total-amount.negative { color: #dc2626; }
        
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        @media (max-width: 900px) { .two-col { grid-template-columns: 1fr; } }
        
        .progress-bar {
            height: 8px;
            background: #f1f3f8;
            border-radius: 99px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            border-radius: 99px;
            transition: width 0.4s ease;
        }
        
        .data-table { 
            width: 100%; 
            border-collapse: collapse; 
            font-family: 'Outfit', sans-serif; 
        }
        .data-table th {
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 12px 20px;
            background: #fafbff;
            border-bottom: 1px solid #f1f3f8;
        }
        .data-table td {
            padding: 14px 20px;
            font-size: 13px;
            color: #374151;
            border-bottom: 1px solid #f8f9fb;
        }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tbody tr:hover td { background: #fafbff; }
        
        .mono { font-family: 'JetBrains Mono', monospace; }
        .fw6 { font-weight: 600; }
        .fw7 { font-weight: 700; }
        
        .expense-row {
            margin-bottom: 12px;
        }
        .expense-row:last-child { margin-bottom: 0; }
        
        .color-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
            flex-shrink: 0;
        }
    </style>

    <div class="dash-wrap" style="padding:0 0 20px">

        {{-- Date Filter --}}
        <div class="filter-bar">
            <form method="GET" action="{{ route('reports.profit-loss') }}" style="display:flex;align-items:flex-end;gap:14px;flex-wrap:wrap;width:100%">
                <div class="filter-group">
                    <span class="filter-label">From</span>
                    <input type="date" name="from" value="{{ $from }}" class="filter-input" />
                </div>
                <div class="filter-group">
                    <span class="filter-label">To</span>
                    <input type="date" name="to" value="{{ $to }}" class="filter-input" />
                </div>
                <button type="submit" class="btn-filter">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:4px">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Apply
                </button>
                <a href="{{ route('reports.profit-loss') }}" class="btn-reset">Reset</a>
            </form>
            <a href="{{ route('reports.profit-loss.export', request()->query()) }}" 
   style="background:transparent;color:#6b7280;padding:8px 16px;border-radius:10px;font-size:12px;font-weight:600;text-decoration:none;font-family:'Outfit',sans-serif;border:1.5px solid #e4e7ef;transition:all .15s;display:inline-flex;align-items:center;gap:6px"
   onmouseover="this.style.borderColor='#03A737';this.style.color='#03A737';this.style.background='#e6f7eb'"
   onmouseout="this.style.borderColor='#e4e7ef';this.style.color='#6b7280';this.style.background='transparent'">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
    </svg>
    Export PDF
</a>
        </div>

        {{-- P&L Statement --}}
        <div class="panel">
            <div class="panel-header">
                <div>
                    <span class="panel-title">Profit & Loss Statement</span>
                    <span class="panel-subtitle">
                        {{ \Carbon\Carbon::parse($from)->format('d M Y') }} — {{ \Carbon\Carbon::parse($to)->format('d M Y') }}
                    </span>
                </div>
            </div>

            {{-- Revenue --}}
            <div class="pl-row pl-row-green">
                <div>
                    <div class="pl-label">Gross Revenue</div>
                    <div class="pl-description">Total sales collected</div>
                </div>
                <span class="pl-amount positive">KES {{ number_format($revenue, 2) }}</span>
            </div>

            {{-- COGS --}}
            <div class="pl-row pl-row-red">
                <div>
                    <div class="pl-label">Cost of Goods Sold (COGS)</div>
                    <div class="pl-description">Cost price × quantity sold</div>
                </div>
                <span class="pl-amount negative">− KES {{ number_format($cogs, 2) }}</span>
            </div>

            {{-- Refunds --}}
            <div class="pl-row pl-row-red">
                <div>
                    <div class="pl-label">Refunds Issued</div>
                    <div class="pl-description">Total returned amount</div>
                </div>
                <span class="pl-amount negative">− KES {{ number_format($refunds, 2) }}</span>
            </div>

            {{-- Gross Profit --}}
            <div class="pl-row pl-row-blue">
                <div>
                    <div class="pl-label">Gross Profit</div>
                    <div class="pl-description">Revenue − COGS − Refunds</div>
                </div>
                <span class="pl-amount brand" style="font-size:17px">KES {{ number_format($grossProfit, 2) }}</span>
            </div>

            {{-- Expenses --}}
            <div class="pl-row pl-row-red">
                <div>
                    <div class="pl-label">Operating Expenses</div>
                    <div class="pl-description">Rent, utilities, salaries, etc.</div>
                </div>
                <span class="pl-amount negative">− KES {{ number_format($expenses, 2) }}</span>
            </div>

            {{-- Net Profit --}}
            <div class="pl-row-total {{ $netProfit >= 0 ? 'green-bg' : 'red-bg' }}">
                <span class="pl-total-label">Net Profit</span>
                <span class="pl-total-amount {{ $netProfit >= 0 ? 'positive' : 'negative' }}">
                    KES {{ number_format($netProfit, 2) }}
                </span>
            </div>
        </div>

        {{-- Monthly Breakdown + Expenses by Category --}}
        <div class="two-col">

            {{-- Monthly Revenue --}}
            @if($monthlySales->count() > 0)
                <div class="panel">
                    <div class="panel-header">
                        <span class="panel-title">Monthly Revenue</span>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Transactions</th>
                                <th style="text-align:right">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlySales as $month)
                                <tr>
                                    <td class="fw6" style="color:#02182F">
                                        {{ \Carbon\Carbon::parse($month->month . '-01')->format('F Y') }}
                                    </td>
                                    <td class="mono" style="font-size:12px;color:#6b7280">{{ $month->transactions }}</td>
                                    <td class="mono fw6" style="text-align:right;color:#03A737">
                                        KES {{ number_format($month->revenue, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            {{-- Expenses by Category --}}
            @if($expensesByCategory->count() > 0)
                <div class="panel panel-padded">
                    <div style="font-family:'Outfit',sans-serif;font-size:14px;font-weight:700;color:#02182F;margin-bottom:16px">
                        Expenses by Category
                    </div>
                    @php
                        $catColors = ['#03A737','#3D7BE7','#02182F','#d97706','#dc2626','#0891b2','#7c3aed','#028a2e'];
                    @endphp
                    @foreach($expensesByCategory as $i => $cat)
                        @php
                            $pct = $expenses > 0 ? round(($cat->total / $expenses) * 100) : 0;
                            $color = $cat->category->color ?? $catColors[$i % count($catColors)];
                        @endphp
                        <div class="expense-row">
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
                                <div style="display:flex;align-items:center;gap:6px">
                                    <span class="color-dot" style="background:{{ $color }}"></span>
                                    <span style="font-size:13px;color:#374151;font-weight:500;font-family:'Outfit',sans-serif">
                                        {{ $cat->category->name ?? 'Uncategorised' }}
                                    </span>
                                </div>
                                <span style="font-size:12px;color:#6b7280;font-family:'JetBrains Mono',monospace;font-weight:600">
                                    KES {{ number_format($cat->total, 2) }}
                                    <span style="color:#9ca3af;font-weight:400;margin-left:4px">({{ $pct }}%)</span>
                                </span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width:{{ $pct }}%;background:{{ $color }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>