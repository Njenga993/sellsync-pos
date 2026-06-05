<x-app-layout>


    @php
    if (!function_exists('pieSlice')) {
        function pieSlice($cx, $cy, $r, $startAngle, $endAngle) {
            $start = [
                'x' => $cx + $r * cos(deg2rad($startAngle - 90)),
                'y' => $cy + $r * sin(deg2rad($startAngle - 90)),
            ];
            $end = [
                'x' => $cx + $r * cos(deg2rad($endAngle - 90)),
                'y' => $cy + $r * sin(deg2rad($endAngle - 90)),
            ];
            $largeArc = ($endAngle - $startAngle) > 180 ? 1 : 0;
            return "M {$start['x']} {$start['y']} A {$r} {$r} 0 {$largeArc} 1 {$end['x']} {$end['y']} L {$cx} {$cy} Z";
        }
    }
    @endphp

    <x-slot name="header">
        <div class="flex items-center justify-between" style="flex-wrap:wrap;gap:12px">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Overview</p>
                <h1 style="font-family:'Outfit',sans-serif;font-size:clamp(18px,3vw,22px);font-weight:700;color:#111827;line-height:1.2">
                    Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ Auth::user()->name }}
                </h1>
            </div>
            <a href="{{ route('pos.index') }}"
               style="background:#1a56db;color:white;padding:10px 22px;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;font-family:'Outfit',sans-serif;letter-spacing:.01em;box-shadow:0 2px 8px rgba(26,86,219,.25);transition:all .15s;white-space:nowrap">
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
            padding: 20px 18px 16px;
            position: relative;
            overflow: hidden;
            border: 1px solid transparent;
        }
        @media(max-width:640px){ .stat-card{ padding:16px 14px 14px; } }
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
            font-size: clamp(20px,3vw,26px);
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
            right: 14px;
            top: 14px;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        @media(max-width:640px){ .stat-icon{ width:28px;height:28px;right:10px;top:10px; } }
        .stat-icon svg { width:16px; height:16px; }
        @media(max-width:640px){ .stat-icon svg{ width:14px;height:14px; } }

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
            padding: 16px 20px;
            border-bottom: 1px solid #f1f3f8;
            flex-wrap: wrap; gap: 8px;
        }
        @media(max-width:640px){ .panel-header{ padding:14px 16px; } }
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
        .actions-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:10px; padding:16px 20px; }
        @media(max-width:900px){ .actions-grid{ grid-template-columns:repeat(2,1fr); } }
        @media(max-width:640px){ .actions-grid{ padding:12px 14px;gap:8px; } }
        .action-btn {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            padding: 16px 10px; border-radius: 12px; border: 1.5px solid #e4e7ef;
            text-decoration: none; transition: all .15s; background: #fafbff;
        }
        @media(max-width:640px){ .action-btn{ padding:14px 8px; } }
        .action-btn:hover { border-color: #1a56db; background: #eff4ff; }
        .action-icon {
            width: 38px; height: 38px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 8px;
        }
        @media(max-width:640px){ .action-icon{ width:34px;height:34px; } }
        .action-icon svg { width:18px; height:18px; }
        @media(max-width:640px){ .action-icon svg{ width:16px;height:16px; } }
        .action-label {
            font-family: 'Outfit', sans-serif;
            font-size: 12px; font-weight: 600; color: #374151; text-align: center;
        }
        @media(max-width:640px){ .action-label{ font-size:11px; } }
        .action-btn:hover .action-label { color: #1a56db; }

        /* ── Charts layout ── */
        .charts-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        @media(max-width:900px){ .charts-grid{ grid-template-columns: 1fr; } }

        .chart-container { padding: 16px 20px; position: relative; }
        @media(max-width:640px){ .chart-container{ padding:12px 14px; } }

        .bar-chart-row { display: flex; align-items: flex-end; gap: 6px; height: 180px; padding-top: 20px; }
        @media(max-width:640px){ .bar-chart-row{ height:140px;gap:4px; } }
        .bar-item { flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; justify-content: flex-end; }
        .bar-fill {
            width: 100%; max-width: 48px; border-radius: 6px 6px 0 0;
            transition: height 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
            min-height: 4px; position: relative;
        }
        .bar-value { font-family: 'JetBrains Mono', monospace; font-size: 10px; color: #6b7280; margin-top: 4px; white-space: nowrap; }
        .bar-label { font-family: 'Outfit', sans-serif; font-size: 10px; color: #9ca3af; margin-top: 2px; text-align: center; }

        .pie-legend { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 14px; justify-content: center; }
        .pie-legend-item { display: flex; align-items: center; gap: 6px; font-family: 'Outfit', sans-serif; font-size: 11px; color: #6b7280; }
        .pie-legend-dot { width: 10px; height: 10px; border-radius: 3px; flex-shrink: 0; }

        .pie-chart-wrap { display: flex; justify-content: center; align-items: center; padding: 10px 0; }
        .pie-svg { width: 180px; height: 180px; border-radius: 50%; position: relative; }
        @media(max-width:640px){ .pie-svg{ width:140px;height:140px; } }
        .pie-center { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; }
        .pie-center-value { font-family: 'JetBrains Mono', monospace; font-size: 16px; font-weight: 700; color: #111827; }
        .pie-center-label { font-family: 'Outfit', sans-serif; font-size: 10px; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em; }

        /* ── Three-col layout ── */
        .three-col { display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px; }
        @media(max-width:1200px){ .three-col{ grid-template-columns:1fr 1fr; } }
        @media(max-width:768px){ .three-col{ grid-template-columns:1fr; } }

        /* ── Two-col layout ── */
        .two-col { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
        @media(max-width:900px){ .two-col{ grid-template-columns:1fr; } }

        /* ── Table rows ── */
        .data-table { width:100%; border-collapse:collapse; font-family:'Outfit',sans-serif; }
        .data-table th {
            text-align:left; font-size:10px; font-weight:600;
            color:#9ca3af; text-transform:uppercase; letter-spacing:.06em;
            padding:10px 16px; background:#fafbff; border-bottom:1px solid #f1f3f8;
        }
        @media(max-width:640px){ .data-table th{ padding:8px 12px;font-size:9px; } }
        .data-table td { padding:12px 16px; font-size:13px; color:#374151; border-bottom:1px solid #f8f9fb; }
        @media(max-width:640px){ .data-table td{ padding:10px 12px;font-size:12px; } }
        .data-table tr:last-child td { border-bottom:none; }
        .data-table tr:hover td { background:#fafbff; }

        .badge { display:inline-flex; align-items:center; padding:3px 10px; border-radius:99px; font-size:11px; font-weight:600; font-family:'Outfit',sans-serif; }
        .badge-green  { background:#dcfce7; color:#15803d; }
        .badge-blue   { background:#dbeafe; color:#1d4ed8; }
        .badge-yellow { background:#fef9c3; color:#a16207; }
        .badge-red    { background:#fee2e2; color:#b91c1c; }

        .mono { font-family:'JetBrains Mono',monospace; font-size:13px; }
        @media(max-width:640px){ .mono{ font-size:12px; } }
        .text-brand { color:#1a56db; }
        .fw6 { font-weight:600; }

        /* ── Business info ── */
        .biz-grid { display:grid; grid-template-columns:1fr 1fr; gap:0; }
        @media(max-width:640px){ .biz-grid{ grid-template-columns:1fr; } }
        .biz-row {
            display:flex; flex-direction:column; gap:2px;
            padding:12px 18px; border-bottom:1px solid #f1f3f8;
            border-right:1px solid #f1f3f8;
        }
        @media(max-width:640px){ .biz-row{ border-right:none;padding:10px 14px; } }
        .biz-row:nth-child(2n){ border-right:none; }
        .biz-row:nth-last-child(-n+2){ border-bottom:none; }
        @media(max-width:640px){ .biz-row:nth-last-child(-n+2){ border-bottom:1px solid #f1f3f8; } }
        .biz-key { font-size:10px; font-weight:600; color:#9ca3af; text-transform:uppercase; letter-spacing:.06em; }
        .biz-val { font-size:13px; font-weight:600; color:#111827; font-family:'Outfit',sans-serif; }

        /* ── Stock alert bar ── */
        .alert-bar {
            display:flex; align-items:center; gap:12px;
            padding:14px 20px; background:#fff7ed; border-bottom:1px solid #fed7aa; flex-wrap: wrap;
        }
        @media(max-width:640px){ .alert-bar{ padding:12px 14px;gap:8px; } }
        .alert-dot { width:8px; height:8px; border-radius:50%; background:#ea580c; flex-shrink:0; }
        .alert-text { font-family:'Outfit',sans-serif; font-size:13px; color:#c2410c; font-weight:500; flex:1;min-width:200px; }
        @media(max-width:640px){ .alert-text{ font-size:12px; } }
        .alert-link { font-size:12px; font-weight:600; color:#ea580c; text-decoration:none; white-space:nowrap; }
        .alert-link:hover { text-decoration:underline; }

        /* ── Month summary ── */
        .month-summary-inner { padding:16px 20px; display:flex; flex-direction:column; gap:12px; }
        @media(max-width:640px){ .month-summary-inner{ padding:14px 16px; } }
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

        {{-- ── CHARTS: Weekly Revenue + Payment Breakdown ── --}}
        <div class="charts-grid">
            @php
                $weeklyLabels = [];
                $weeklyData = [];
                for ($i = 6; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $weeklyLabels[] = $date->format('D');
                    $weeklyData[] = \App\Models\Sale::where('tenant_id', auth()->user()->tenant_id)
                        ->whereDate('created_at', $date)
                        ->sum('total');
                }
                $maxWeekly = max($weeklyData) ?: 1;

                $cashTotal = \App\Models\Sale::where('tenant_id', auth()->user()->tenant_id)
                    ->where('payment_method', 'cash')->sum('total');
                $cardTotal = \App\Models\Sale::where('tenant_id', auth()->user()->tenant_id)
                    ->where('payment_method', 'card')->sum('total');
                $mobileTotal = \App\Models\Sale::where('tenant_id', auth()->user()->tenant_id)
                    ->where('payment_method', 'mobile')->sum('total');
                $pieTotal = $cashTotal + $cardTotal + $mobileTotal ?: 1;
                $cashPct = round(($cashTotal / $pieTotal) * 100);
                $cardPct = round(($cardTotal / $pieTotal) * 100);
                $mobilePct = round(($mobileTotal / $pieTotal) * 100);
            @endphp

            {{-- Weekly Revenue Bar Chart --}}
            <div class="panel">
                <div class="panel-header">
                    <span class="panel-title">Weekly Revenue</span>
                    <span class="panel-link" style="font-size:11px;color:#9ca3af;text-decoration:none;cursor:default">Last 7 days</span>
                </div>
                <div class="chart-container">
                    <div class="bar-chart-row">
                        @foreach($weeklyData as $i => $val)
                            @php $height = ($val / $maxWeekly) * 100; @endphp
                            <div class="bar-item">
                                <div class="bar-fill" style="height:{{ max($height, 3) }}%;background:#1a56db" title="KES {{ number_format($val, 2) }}"></div>
                                <div class="bar-value">{{ $val > 0 ? number_format($val / 1000, 1) . 'k' : '' }}</div>
                                <div class="bar-label">{{ $weeklyLabels[$i] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Payment Methods Pie Chart --}}
            <div class="panel">
                <div class="panel-header">
                    <span class="panel-title">Payment Methods</span>
                    <a href="{{ route('reports.sales') }}" class="panel-link">View sales</a>
                </div>
                <div class="chart-container">
                    <div class="pie-chart-wrap">
                        <svg viewBox="0 0 36 36" class="pie-svg">
                            @php
                                $cashDeg = ($cashPct / 100) * 360;
                                $cardDeg = ($cardPct / 100) * 360;
                                $mobileDeg = ($mobilePct / 100) * 360;
                                $offset = 0;
                            @endphp
                            @if($cashPct > 0)
                                <path d="{{ pieSlice(18, 18, 15.9, $offset, $offset + $cashDeg) }}" fill="#16a34a" />
                                @php $offset += $cashDeg; @endphp
                            @endif
                            @if($cardPct > 0)
                                <path d="{{ pieSlice(18, 18, 15.9, $offset, $offset + $cardDeg) }}" fill="#1a56db" />
                                @php $offset += $cardDeg; @endphp
                            @endif
                            @if($mobilePct > 0)
                                <path d="{{ pieSlice(18, 18, 15.9, $offset, $offset + $mobileDeg) }}" fill="#7c3aed" />
                            @endif
                            <circle cx="18" cy="18" r="10" fill="white" />
                        </svg>
                        <div class="pie-center" style="position:absolute">
                            <div class="pie-center-value">{{ $pieTotal > 0 ? number_format($pieTotal / 1000, 1) . 'k' : '0' }}</div>
                            <div class="pie-center-label">Total</div>
                        </div>
                    </div>
                    <div class="pie-legend">
                        <div class="pie-legend-item">
                            <span class="pie-legend-dot" style="background:#16a34a"></span>
                            Cash ({{ $cashPct }}%)
                        </div>
                        <div class="pie-legend-item">
                            <span class="pie-legend-dot" style="background:#1a56db"></span>
                            Card ({{ $cardPct }}%)
                        </div>
                        <div class="pie-legend-item">
                            <span class="pie-legend-dot" style="background:#7c3aed"></span>
                            M-Pesa ({{ $mobilePct }}%)
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── THREE COLUMN: Recent Sales + Business Info + Month Summary ── --}}
        <div class="three-col">

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
                        ->take(5)
                        ->get();
                @endphp
                @if($recentSales->count())
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Time</th>
                                <th style="text-align:right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentSales as $sale)
                            <tr>
                                <td class="mono text-brand fw6" style="font-size:11px">{{ $sale->invoice_no }}</td>
                                <td style="color:#9ca3af;font-size:11px;white-space:nowrap">{{ $sale->created_at->format('d M, H:i') }}</td>
                                <td style="text-align:right" class="mono fw6" style="font-size:12px">{{ number_format($sale->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div style="padding:40px 20px;text-align:center;font-size:13px;color:#9ca3af;font-family:'Outfit',sans-serif">
                        No transactions yet today.
                        <br><a href="{{ route('pos.index') }}" style="color:#1a56db;font-weight:600;text-decoration:none">Open the POS to start selling</a>
                    </div>
                @endif
            </div>

            {{-- Business Info --}}
            <div class="panel">
                <div class="panel-header">
                    <span class="panel-title">Busines Details</span>
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

            {{-- Month summary --}}
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
                <div class="month-summary-inner">
                    <div style="display:flex;justify-content:space-between;align-items:center;padding-bottom:10px;border-bottom:1px solid #f1f3f8">
                        <div>
                            <div style="font-size:10px;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;font-family:'Outfit',sans-serif">Revenue</div>
                            <div style="font-family:'JetBrains Mono',monospace;font-size:18px;font-weight:700;color:#111827">{{ number_format($monthRevenue, 2) }}</div>
                            <div style="font-size:10px;color:#9ca3af;font-family:'Outfit',sans-serif;margin-top:2px">{{ $monthCount }} sales</div>
                        </div>
                        <div style="text-align:right">
                            <div style="font-size:10px;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;font-family:'Outfit',sans-serif">Expenses</div>
                            <div style="font-family:'JetBrains Mono',monospace;font-size:18px;font-weight:700;color:#dc2626">{{ number_format($monthExpenses, 2) }}</div>
                            <div style="font-size:10px;color:#9ca3af;font-family:'Outfit',sans-serif;margin-top:2px">Recorded</div>
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
</x-app-layout>
