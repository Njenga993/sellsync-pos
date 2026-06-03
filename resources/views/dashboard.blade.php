<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Overview</p>
                <h1 style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:700;color:#111827;line-height:1.2">
                    Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ Auth::user()->name }}
                </h1>
            </div>
            <a href="{{ route('pos.index') }}"
               style="background:#1a56db;color:white;padding:10px 22px;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;font-family:'Outfit',sans-serif;letter-spacing:.01em;box-shadow:0 2px 8px rgba(26,86,219,.25);transition:all .15s">
                Open POS Terminal
            </a>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');

        .dash-wrap { display:flex; flex-direction:column; gap:20px; }

        /* ── Stat cards ── */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
        }
        @media(max-width:1100px){ .stat-grid{ grid-template-columns: repeat(2,1fr); } }
        @media(max-width:640px){  .stat-grid{ grid-template-columns: 1fr; } }

        .stat-card {
            border-radius: 14px;
            padding: 22px 22px 18px;
            position: relative;
            overflow: hidden;
            border: 1px solid transparent;
        }
        .stat-card-label {
            font-family: 'Outfit', sans-serif;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .07em;
            margin-bottom: 10px;
        }
        .stat-card-value {
            font-family: 'JetBrains Mono', monospace;
            font-size: 26px;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 8px;
        }
        .stat-card-sub {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
        }
        .stat-icon {
            position: absolute;
            right: 18px;
            top: 18px;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .stat-icon svg { width:18px; height:18px; }

        /* Card colour themes */
        .card-blue   { background:#eff4ff; border-color:#c7d7fb; }
        .card-blue   .stat-card-label  { color:#3b5fd6; }
        .card-blue   .stat-card-value  { color:#1a3fad; }
        .card-blue   .stat-card-sub    { color:#6b85d6; }
        .card-blue   .stat-icon        { background:#dbeafe; }
        .card-blue   .stat-icon svg    { color:#1a56db; }

        .card-teal   { background:#f0fdfa; border-color:#99f6e4; }
        .card-teal   .stat-card-label  { color:#0f766e; }
        .card-teal   .stat-card-value  { color:#134e4a; }
        .card-teal   .stat-card-sub    { color:#2dd4bf; }
        .card-teal   .stat-icon        { background:#ccfbf1; }
        .card-teal   .stat-icon svg    { color:#0d9488; }

        .card-indigo { background:#f5f3ff; border-color:#c4b5fd; }
        .card-indigo .stat-card-label  { color:#5b21b6; }
        .card-indigo .stat-card-value  { color:#3b0764; }
        .card-indigo .stat-card-sub    { color:#7c3aed; }
        .card-indigo .stat-icon        { background:#ede9fe; }
        .card-indigo .stat-icon svg    { color:#7c3aed; }

        .card-red    { background:#fff1f2; border-color:#fecdd3; }
        .card-red    .stat-card-label  { color:#be123c; }
        .card-red    .stat-card-value  { color:#881337; }
        .card-red    .stat-card-sub    { color:#e11d48; }
        .card-red    .stat-icon        { background:#ffe4e6; }
        .card-red    .stat-icon svg    { color:#e11d48; }

        .card-green  { background:#f0fdf4; border-color:#bbf7d0; }
        .card-green  .stat-card-label  { color:#15803d; }
        .card-green  .stat-card-value  { color:#14532d; }
        .card-green  .stat-card-sub    { color:#22c55e; }
        .card-green  .stat-icon        { background:#dcfce7; }
        .card-green  .stat-icon svg    { color:#16a34a; }

        /* ── Panels ── */
        .panel {
            background: #ffffff;
            border: 1px solid #e4e7ef;
            border-radius: 14px;
            overflow: hidden;
        }
        .panel-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 18px 22px;
            border-bottom: 1px solid #f1f3f8;
        }
        .panel-title {
            font-family: 'Outfit', sans-serif;
            font-size: 14px; font-weight: 700; color: #111827;
        }
        .panel-link {
            font-family: 'Outfit', sans-serif;
            font-size: 12px; font-weight: 600; color: #1a56db;
            text-decoration: none;
        }
        .panel-link:hover { text-decoration: underline; }

        /* ── Quick Actions ── */
        .actions-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:10px; padding:18px 22px; }
        @media(max-width:900px){ .actions-grid{ grid-template-columns:repeat(2,1fr); } }
        .action-btn {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            padding: 18px 10px; border-radius: 12px; border: 1.5px solid #e4e7ef;
            text-decoration: none; transition: all .15s; background: #fafbff;
        }
        .action-btn:hover { border-color: #1a56db; background: #eff4ff; }
        .action-icon {
            width: 40px; height: 40px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 10px;
        }
        .action-icon svg { width:20px; height:20px; }
        .action-label {
            font-family: 'Outfit', sans-serif;
            font-size: 12px; font-weight: 600; color: #374151; text-align: center;
        }
        .action-btn:hover .action-label { color: #1a56db; }

        /* ── Two-col layout ── */
        .two-col { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
        @media(max-width:900px){ .two-col{ grid-template-columns:1fr; } }

        /* ── Table rows ── */
        .data-table { width:100%; border-collapse:collapse; font-family:'Outfit',sans-serif; }
        .data-table th {
            text-align:left; font-size:11px; font-weight:600;
            color:#9ca3af; text-transform:uppercase; letter-spacing:.06em;
            padding:10px 22px; background:#fafbff; border-bottom:1px solid #f1f3f8;
        }
        .data-table td {
            padding:12px 22px; font-size:13px; color:#374151;
            border-bottom:1px solid #f8f9fb;
        }
        .data-table tr:last-child td { border-bottom:none; }
        .data-table tr:hover td { background:#fafbff; }

        .badge {
            display:inline-flex; align-items:center;
            padding:3px 10px; border-radius:99px;
            font-size:11px; font-weight:600; font-family:'Outfit',sans-serif;
        }
        .badge-green  { background:#dcfce7; color:#15803d; }
        .badge-blue   { background:#dbeafe; color:#1d4ed8; }
        .badge-yellow { background:#fef9c3; color:#a16207; }
        .badge-red    { background:#fee2e2; color:#b91c1c; }

        .mono { font-family:'JetBrains Mono',monospace; font-size:13px; }
        .text-brand { color:#1a56db; }
        .fw6 { font-weight:600; }

        /* ── Business info ── */
        .biz-grid { display:grid; grid-template-columns:1fr 1fr; gap:0; }
        .biz-row {
            display:flex; flex-direction:column; gap:2px;
            padding:14px 22px; border-bottom:1px solid #f1f3f8;
            border-right:1px solid #f1f3f8;
        }
        .biz-row:nth-child(2n){ border-right:none; }
        .biz-row:nth-last-child(-n+2){ border-bottom:none; }
        .biz-key { font-size:11px; font-weight:600; color:#9ca3af; text-transform:uppercase; letter-spacing:.06em; }
        .biz-val { font-size:14px; font-weight:600; color:#111827; font-family:'Outfit',sans-serif; }

        /* ── Stock alert bar ── */
        .alert-bar {
            display:flex; align-items:center; gap:14px;
            padding:14px 22px;
            background:#fff7ed; border-bottom:1px solid #fed7aa;
        }
        .alert-dot { width:8px; height:8px; border-radius:50%; background:#ea580c; flex-shrink:0; }
        .alert-text { font-family:'Outfit',sans-serif; font-size:13px; color:#c2410c; font-weight:500; }
        .alert-link { font-size:12px; font-weight:600; color:#ea580c; text-decoration:none; margin-left:auto; }
        .alert-link:hover { text-decoration:underline; }

        /* ── Divider ── */
        .section-divider {
            font-family:'Outfit',sans-serif;
            font-size:11px; font-weight:700; color:#9ca3af;
            text-transform:uppercase; letter-spacing:.08em;
            margin-bottom:4px;
        }
    </style>

    <div class="dash-wrap">

        {{-- ── STAT CARDS ── --}}
        <div class="stat-grid">

            {{-- Today's Revenue --}}
            <div class="stat-card card-blue">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="stat-card-label">Today's Revenue</div>
                <div class="stat-card-value">{{ number_format($stats['today_total'], 2) }}</div>
                <div class="stat-card-sub">{{ $stats['today_transactions'] }} transaction{{ $stats['today_transactions'] == 1 ? '' : 's' }} today</div>
            </div>

            {{-- Products --}}
            <div class="stat-card card-teal">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div class="stat-card-label">Active Products</div>
                <div class="stat-card-value">{{ number_format($stats['products']) }}</div>
                <div class="stat-card-sub">Items in inventory</div>
            </div>

            {{-- Customers --}}
            <div class="stat-card card-indigo">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="stat-card-label">Customers</div>
                <div class="stat-card-value">{{ number_format($stats['customers']) }}</div>
                <div class="stat-card-sub">Registered profiles</div>
            </div>

            {{-- Low Stock --}}
            <div class="stat-card {{ $stats['low_stock'] > 0 ? 'card-red' : 'card-green' }}">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="stat-card-label">Stock Alerts</div>
                <div class="stat-card-value">{{ number_format($stats['low_stock']) }}</div>
                <div class="stat-card-sub">{{ $stats['low_stock'] > 0 ? 'Items need reordering' : 'All stock levels OK' }}</div>
            </div>

        </div>

        {{-- ── LOW STOCK ALERT BAR ── --}}
        @if($stats['low_stock'] > 0)
        <div class="panel" style="overflow:visible;border-color:#fed7aa">
            <div class="alert-bar">
                <span class="alert-dot"></span>
                <span class="alert-text">
                    <strong>{{ $stats['low_stock'] }} product{{ $stats['low_stock'] > 1 ? 's' : '' }}</strong>
                    {{ $stats['low_stock'] > 1 ? 'are' : 'is' }} running low on stock and may need reordering soon.
                </span>
                <a href="{{ route('stock.index') }}" class="alert-link">View Stock →</a>
            </div>
        </div>
        @endif

        {{-- ── QUICK ACTIONS ── --}}
        <div class="panel">
            <div class="panel-header">
                <span class="panel-title">Quick Actions</span>
            </div>
            <div class="actions-grid">
                <a href="{{ route('pos.index') }}" class="action-btn">
                    <div class="action-icon" style="background:#eff4ff">
                        <svg fill="none" stroke="#1a56db" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <span class="action-label">Open POS Terminal</span>
                </a>
                <a href="{{ route('products.create') }}" class="action-btn">
                    <div class="action-icon" style="background:#f0fdfa">
                        <svg fill="none" stroke="#0d9488" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <span class="action-label">Add Product</span>
                </a>
                <a href="{{ route('customers.create') }}" class="action-btn">
                    <div class="action-icon" style="background:#f5f3ff">
                        <svg fill="none" stroke="#7c3aed" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <span class="action-label">Add Customer</span>
                </a>
                <a href="{{ route('reports.profit-loss') }}" class="action-btn">
                    <div class="action-icon" style="background:#f0fdf4">
                        <svg fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <span class="action-label">P&L Report</span>
                </a>
            </div>
        </div>

        {{-- ── TWO COLUMN: RECENT SALES + BUSINESS INFO ── --}}
        <div class="two-col">

            {{-- Recent Transactions --}}
            <div class="panel">
                <div class="panel-header">
                    <span class="panel-title">Recent Transactions</span>
                    <a href="{{ route('reports.sales') }}" class="panel-link">View all</a>
                </div>
                @php
                    $recentSales = \App\Models\Sale::where('tenant_id', auth()->user()->tenant_id)
                        ->with('user')
                        ->latest()
                        ->take(6)
                        ->get();
                @endphp
                @if($recentSales->count())
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Time</th>
                                <th>Method</th>
                                <th style="text-align:right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentSales as $sale)
                            <tr>
                                <td class="mono text-brand fw6">{{ $sale->invoice_no }}</td>
                                <td style="color:#9ca3af;font-size:12px">{{ $sale->created_at->format('d M, H:i') }}</td>
                                <td>
                                    <span class="badge {{ $sale->payment_method === 'cash' ? 'badge-green' : ($sale->payment_method === 'mobile' ? 'badge-yellow' : 'badge-blue') }}">
                                        {{ ucfirst($sale->payment_method) }}
                                    </span>
                                </td>
                                <td style="text-align:right" class="mono fw6">{{ number_format($sale->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div style="padding:40px 22px;text-align:center;font-size:13px;color:#9ca3af;font-family:'Outfit',sans-serif">
                        No transactions yet today.
                        <br><a href="{{ route('pos.index') }}" style="color:#1a56db;font-weight:600;text-decoration:none">Open the POS to start selling</a>
                    </div>
                @endif
            </div>

            {{-- Right column: Business info + mini stats --}}
            <div style="display:flex;flex-direction:column;gap:14px">

                {{-- Business Info --}}
                <div class="panel">
                    <div class="panel-header">
                        <span class="panel-title">Business Details</span>
                        <a href="{{ route('settings.index') }}" class="panel-link">Edit</a>
                    </div>
                    <div class="biz-grid">
                        <div class="biz-row">
                            <span class="biz-key">Business</span>
                            <span class="biz-val">{{ Auth::user()->tenant->name ?? '—' }}</span>
                        </div>
                        <div class="biz-row">
                            <span class="biz-key">Type</span>
                            <span class="biz-val">{{ ucfirst(Auth::user()->tenant->business_type ?? '—') }}</span>
                        </div>
                        <div class="biz-row">
                            <span class="biz-key">Your Role</span>
                            <span class="biz-val">{{ ucfirst(Auth::user()->getRoleNames()->first() ?? 'Staff') }}</span>
                        </div>
                        <div class="biz-row">
                            <span class="biz-key">Branch</span>
                            <span class="biz-val">{{ Auth::user()->branch->name ?? 'Main Branch' }}</span>
                        </div>
                        <div class="biz-row">
                            <span class="biz-key">Plan</span>
                            <span class="biz-val">{{ ucfirst(Auth::user()->tenant->plan ?? 'Basic') }}</span>
                        </div>
                        <div class="biz-row">
                            <span class="biz-key">Status</span>
                            <span class="biz-val" style="color:#16a34a">Active</span>
                        </div>
                    </div>
                </div>

                {{-- Month summary mini card --}}
                @php
                    $monthRevenue = \App\Models\Sale::where('tenant_id', auth()->user()->tenant_id)
                        ->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->sum('total');
                    $monthCount = \App\Models\Sale::where('tenant_id', auth()->user()->tenant_id)
                        ->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->count();
                    $monthExpenses = \App\Models\Expense::where('tenant_id', auth()->user()->tenant_id)
                        ->whereMonth('expense_date', now()->month)
                        ->whereYear('expense_date', now()->year)
                        ->sum('amount');
                @endphp
                <div class="panel">
                    <div class="panel-header">
                        <span class="panel-title">{{ now()->format('F') }} Summary</span>
                        <a href="{{ route('reports.profit-loss') }}" class="panel-link">Full report</a>
                    </div>
                    <div style="padding:16px 22px;display:flex;flex-direction:column;gap:12px">
                        <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid #f1f3f8">
                            <div>
                                <div style="font-size:11px;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;font-family:'Outfit',sans-serif">Revenue</div>
                                <div style="font-family:'JetBrains Mono',monospace;font-size:20px;font-weight:700;color:#111827">{{ number_format($monthRevenue, 2) }}</div>
                                <div style="font-size:11px;color:#9ca3af;font-family:'Outfit',sans-serif;margin-top:2px">{{ $monthCount }} sales this month</div>
                            </div>
                            <div style="text-align:right">
                                <div style="font-size:11px;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;font-family:'Outfit',sans-serif">Expenses</div>
                                <div style="font-family:'JetBrains Mono',monospace;font-size:20px;font-weight:700;color:#dc2626">{{ number_format($monthExpenses, 2) }}</div>
                                <div style="font-size:11px;color:#9ca3af;font-family:'Outfit',sans-serif;margin-top:2px">Recorded this month</div>
                            </div>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center">
                            <span style="font-size:13px;font-weight:600;color:#374151;font-family:'Outfit',sans-serif">Net Profit</span>
                            @php $net = $monthRevenue - $monthExpenses; @endphp
                            <span style="font-family:'JetBrains Mono',monospace;font-size:16px;font-weight:700;color:{{ $net >= 0 ? '#16a34a' : '#dc2626' }}">
                                {{ $net >= 0 ? '+' : '' }}{{ number_format($net, 2) }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</x-app-layout>