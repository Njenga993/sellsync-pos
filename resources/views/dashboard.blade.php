<x-app-layout>

    @php
    if (!function_exists('pieSlice')) {
        function pieSlice($cx, $cy, $r, $startAngle, $endAngle) {
            $start = ['x' => $cx + $r * cos(deg2rad($startAngle - 90)), 'y' => $cy + $r * sin(deg2rad($startAngle - 90))];
            $end   = ['x' => $cx + $r * cos(deg2rad($endAngle - 90)),   'y' => $cy + $r * sin(deg2rad($endAngle - 90))];
            $largeArc = ($endAngle - $startAngle) > 180 ? 1 : 0;
            return "M {$start['x']} {$start['y']} A {$r} {$r} 0 {$largeArc} 1 {$end['x']} {$end['y']} L {$cx} {$cy} Z";
        }
    }

    $tenantId = auth()->user()->tenant_id;

    $branches = \App\Models\Branch::where('tenant_id', $tenantId)
        ->where('status', 'active')->orderBy('is_main', 'desc')->orderBy('name')->get();

    $branchStats = [];
    foreach ($branches as $branch) {
        $bs = \App\Models\Sale::where('tenant_id', $tenantId)->where('branch_id', $branch->id)->whereDate('created_at', now()->toDateString());
        $bProducts = \App\Models\Product::where('tenant_id', $tenantId)->where('branch_id', $branch->id)->where('status', 'active');
        $branchStats[] = [
            'branch'       => $branch,
            'revenue'      => $bs->sum('total'),
            'transactions' => $bs->count(),
            'products'     => $bProducts->count(),
            'low_stock'    => \App\Models\Product::where('tenant_id', $tenantId)->where('branch_id', $branch->id)->where('track_stock', true)->whereColumn('stock_qty', '<=', 'low_stock_alert')->where('stock_qty', '>', 0)->count(),
            'out_of_stock' => \App\Models\Product::where('tenant_id', $tenantId)->where('branch_id', $branch->id)->where('track_stock', true)->where('stock_qty', '<=', 0)->count(),
        ];
    }

    $totalRevenue      = collect($branchStats)->sum('revenue');
    $totalTransactions = collect($branchStats)->sum('transactions');
    $totalProducts     = \App\Models\Product::where('tenant_id', $tenantId)->where('status', 'active')->count();
    $totalCustomers    = \App\Models\Customer::where('tenant_id', $tenantId)->where('status', 'active')->count();
    $totalLowStock     = collect($branchStats)->sum('low_stock');
    $totalOutOfStock   = collect($branchStats)->sum('out_of_stock');
    $totalAlerts       = $totalLowStock + $totalOutOfStock;

    // Yesterday comparison
    $yesterdayRevenue = \App\Models\Sale::where('tenant_id', $tenantId)->whereDate('created_at', now()->subDay())->sum('total');
    $revenueChange    = $yesterdayRevenue > 0 ? round((($totalRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100, 1) : null;

    // Weekly bars
    $weeklyData = []; $weeklyLabels = []; $weeklyDays = [];
    for ($i = 6; $i >= 0; $i--) {
        $d = now()->subDays($i);
        $weeklyLabels[] = $d->format('D');
        $weeklyDays[]   = $d->format('j M');
        $weeklyData[]   = (float) \App\Models\Sale::where('tenant_id', $tenantId)->whereDate('created_at', $d)->sum('total');
    }
    $maxWeekly = max($weeklyData) ?: 1;

    // Payment breakdown
    $cashTotal   = (float) \App\Models\Sale::where('tenant_id', $tenantId)->where('payment_method', 'cash')->sum('total');
    $cardTotal   = (float) \App\Models\Sale::where('tenant_id', $tenantId)->where('payment_method', 'card')->sum('total');
    $mobileTotal = (float) \App\Models\Sale::where('tenant_id', $tenantId)->where('payment_method', 'mobile')->sum('total');
    $pieTotal    = $cashTotal + $cardTotal + $mobileTotal ?: 1;
    $cashPct     = round(($cashTotal / $pieTotal) * 100);
    $cardPct     = round(($cardTotal / $pieTotal) * 100);
    $mobilePct   = round(($mobileTotal / $pieTotal) * 100);

    // Monthly
    $monthRevenue  = \App\Models\Sale::where('tenant_id', $tenantId)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('total');
    $monthCount    = \App\Models\Sale::where('tenant_id', $tenantId)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
    $monthExpenses = \App\Models\Expense::where('tenant_id', $tenantId)->whereMonth('expense_date', now()->month)->whereYear('expense_date', now()->year)->sum('amount');
    $netProfit     = $monthRevenue - $monthExpenses;

    // Recent sales
    $recentSales = \App\Models\Sale::where('tenant_id', $tenantId)->with('user','branch')->latest()->take(6)->get();

    // Top products this month
    $topProducts = \App\Models\SaleItem::whereHas('sale', fn($q) => $q->where('tenant_id', $tenantId)->whereMonth('created_at', now()->month))
        ->selectRaw('product_name, SUM(qty) as total_qty, SUM(subtotal) as total_revenue')
        ->groupBy('product_name')->orderByDesc('total_revenue')->take(5)->get();

    // Low stock
    $lowStockItems = \App\Models\Product::where('tenant_id', $tenantId)->where('track_stock', true)->whereColumn('stock_qty', '<=', 'low_stock_alert')->where('stock_qty', '>', 0)->with('category')->orderBy('stock_qty')->take(5)->get();
    @endphp

    <x-slot name="header">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
            <div>
                <p style="font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.1em;margin-bottom:4px">
                    {{ now()->format('l, d F Y') }}
                </p>
                <h1 style="font-family:'Outfit',sans-serif;font-size:clamp(18px,2.5vw,22px);font-weight:700;color:#02182F;line-height:1.2">
                    Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ strtok(Auth::user()->name, ' ') }}
                </h1>
            </div>
            <div style="display:flex;align-items:center;gap:10px">
                <a href="{{ route('zreports.create') }}"
                   style="padding:10px 18px;border-radius:10px;border:1.5px solid #e4e7ef;font-size:13px;font-weight:600;color:#374151;text-decoration:none;font-family:'Outfit',sans-serif;background:#FFFEFE;transition:all .15s;white-space:nowrap"
                   onmouseover="this.style.borderColor='#03A737';this.style.color='#03A737';this.style.background='#e6f7eb'"
                   onmouseout="this.style.borderColor='#e4e7ef';this.style.color='#374151';this.style.background='#FFFEFE'">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:5px"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Close Shift
                </a>
                <a href="{{ route('pos.index') }}"
                   style="padding:10px 22px;border-radius:10px;background:#03A737;color:#FFFEFE;font-size:13px;font-weight:600;text-decoration:none;font-family:'Outfit',sans-serif;box-shadow:0 2px 8px rgba(3,167,55,.25);transition:all .15s;white-space:nowrap"
                   onmouseover="this.style.background='#028a2e';this.style.boxShadow='0 4px 14px rgba(3,167,55,.35)'"
                   onmouseout="this.style.background='#03A737';this.style.boxShadow='0 2px 8px rgba(3,167,55,.25)'">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:5px"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Open POS
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap');
        
        :root {
            --g: #03A737; --gl: #e6f7eb; --gd: #028a2e;
            --b: #3D7BE7; --bl: #edf3fd;
            --k: #02182F; --kl: #f0f2f5;
            --w: #FFFEFE; --br: #e4e7ef; --br2: #f1f3f8;
            --t1: #02182F; --t2: #374151; --t3: #6b7280; --t4: #9ca3af;
            --r: #dc2626; --rl: #fef2f2;
        }
        
        .dw { display:flex; flex-direction:column; gap:18px; padding-bottom:8px; }
        .panel { background:var(--w); border:1px solid var(--br); border-radius:16px; overflow:hidden; box-shadow:0 1px 3px rgba(2,24,47,.03); transition:box-shadow .15s; }
        .panel:hover { box-shadow:0 2px 8px rgba(2,24,47,.06); }
        .ph { display:flex; align-items:center; justify-content:space-between; padding:16px 22px; border-bottom:1px solid var(--br2); flex-wrap:wrap; gap:8px; }
        .pt { font-family:'Outfit',sans-serif; font-size:14px; font-weight:700; color:var(--t1); letter-spacing:-0.01em; }
        .pl { font-family:'Outfit',sans-serif; font-size:12px; font-weight:600; color:var(--g); text-decoration:none; transition:color .15s; }
        .pl:hover { color:var(--gd); }
        .mono { font-family:'JetBrains Mono',monospace; }

        /* KPI Strip */
        .kpi-strip { display:grid; grid-template-columns:repeat(4,1fr); gap:0; }
        @media(max-width:1000px){ .kpi-strip{ grid-template-columns:repeat(2,1fr); } }
        @media(max-width:520px){ .kpi-strip{ grid-template-columns:1fr 1fr; } }
        .kpi-cell { padding:24px 22px 20px; border-right:1px solid var(--br2); position:relative; transition:background .15s; cursor:default; }
        .kpi-cell:last-child { border-right:none; }
        .kpi-cell:hover { background:#fafbff; }
        @media(max-width:1000px){ .kpi-cell:nth-child(2){ border-right:none; } .kpi-cell:nth-child(n+3){ border-top:1px solid var(--br2); } }
        .kpi-icon { width:40px; height:40px; border-radius:12px; display:flex; align-items:center; justify-content:center; margin-bottom:16px; }
        .kpi-icon svg { width:20px; height:20px; }
        .kpi-label { font-family:'Outfit',sans-serif; font-size:10px; font-weight:700; color:var(--t4); text-transform:uppercase; letter-spacing:.08em; margin-bottom:8px; }
        .kpi-value { font-family:'JetBrains Mono',monospace; font-size:clamp(22px,2.5vw,28px); font-weight:700; color:var(--t1); line-height:1; margin-bottom:10px; }
        .kpi-sub { font-family:'Outfit',sans-serif; font-size:11px; color:var(--t4); display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
        .kpi-pill { display:inline-flex; align-items:center; gap:3px; padding:2px 9px; border-radius:99px; font-size:10px; font-weight:700; letter-spacing:.02em; }
        .pill-up   { background:var(--gl); color:var(--gd); }
        .pill-down { background:var(--rl); color:#b91c1c; }
        .pill-flat { background:var(--kl); color:var(--t3); }

        /* Grids */
        .g2 { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
        .g3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px; }
        @media(max-width:1100px){ .g3{ grid-template-columns:1fr 1fr; } }
        @media(max-width:768px){ .g2,.g3{ grid-template-columns:1fr; } }

        /* Branch cards */
        .branch-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(210px,1fr)); gap:12px; padding:16px 20px; }
        .bc { border:1.5px solid var(--br); border-radius:14px; padding:18px; background:var(--w); transition:all .15s; }
        .bc:hover { border-color:var(--g); box-shadow:0 0 0 3px rgba(3,167,55,.06); transform:translateY(-1px); }
        .bc-main { border-color:#b8e6c4; background:linear-gradient(135deg,#f0fdf4,#FFFEFE); }
        .bc-current { border-color:var(--g); box-shadow:0 0 0 3px rgba(3,167,55,.1); }
        .bc-header { display:flex; align-items:center; gap:10px; margin-bottom:16px; }
        .bc-icon { width:34px; height:34px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .bc-name { font-size:12px; font-weight:700; color:var(--t1); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .bc-tag { font-size:10px; color:var(--t4); margin-top:1px; }
        .bc-metrics { display:flex; gap:0; }
        .bc-m { flex:1; text-align:center; padding:0 8px; border-right:1px solid var(--br2); }
        .bc-m:last-child { border-right:none; }
        .bc-m-lbl { font-size:9px; font-weight:600; color:var(--t4); text-transform:uppercase; letter-spacing:.06em; margin-bottom:4px; }
        .bc-m-val { font-family:'JetBrains Mono',monospace; font-size:15px; font-weight:700; color:var(--t1); }
        .bc-m-sub { font-size:9px; color:var(--t4); margin-top:2px; }

        /* Bar chart */
        .chart-card { padding:10px 0 4px; }
        .bar-wrap { display:flex; align-items:flex-end; gap:5px; height:160px; padding:0 20px; }
        .bar-col { flex:1; display:flex; flex-direction:column; align-items:center; justify-content:flex-end; height:100%; }
        .bar-bg { width:100%; height:100%; background:var(--br2); border-radius:8px 8px 0 0; position:relative; overflow:hidden; }
        .bar-fill { width:100%; border-radius:8px 8px 0 0; position:absolute; bottom:0; transition:height .5s cubic-bezier(.34,1.56,.64,1); }
        .bar-val { font-family:'JetBrains Mono',monospace; font-size:9px; color:var(--t4); margin-top:5px; text-align:center; }
        .bar-day { font-family:'Outfit',sans-serif; font-size:10px; color:var(--t4); margin-top:2px; }

        /* Payment rows */
        .pm-row { display:flex; align-items:center; gap:14px; padding:12px 22px; border-bottom:1px solid var(--br2); transition:background .1s; }
        .pm-row:hover { background:#fafbff; }
        .pm-row:last-child { border-bottom:none; }
        .pm-icon { width:34px; height:34px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .pm-icon svg { width:16px; height:16px; }
        .pm-name { font-family:'Outfit',sans-serif; font-size:12px; font-weight:600; color:var(--t2); width:55px; flex-shrink:0; }
        .pm-bar-wrap { flex:1; background:var(--br2); border-radius:99px; height:7px; overflow:hidden; }
        .pm-bar-fill { height:100%; border-radius:99px; transition:width .6s ease; }
        .pm-val { font-family:'JetBrains Mono',monospace; font-size:12px; font-weight:600; color:var(--t1); min-width:85px; text-align:right; }
        .pm-pct { font-size:11px; font-weight:600; color:var(--t4); min-width:35px; text-align:right; font-family:'Outfit',sans-serif; }

        /* Data table */
        .dt { width:100%; border-collapse:collapse; font-family:'Outfit',sans-serif; }
        .dt th { text-align:left; font-size:9px; font-weight:700; color:var(--t4); text-transform:uppercase; letter-spacing:.07em; padding:10px 18px; background:#fafbff; border-bottom:1px solid var(--br2); }
        .dt td { padding:12px 18px; font-size:12px; color:var(--t2); border-bottom:1px solid var(--br2); }
        .dt tr:last-child td { border-bottom:none; }
        .dt tbody tr { transition:background .1s; }
        .dt tbody tr:hover td { background:#fafbff; }

        /* Badges */
        .badge { display:inline-flex; align-items:center; padding:3px 10px; border-radius:99px; font-size:10px; font-weight:600; font-family:'Outfit',sans-serif; letter-spacing:.02em; }
        .bg-cash   { background:var(--gl); color:var(--gd); }
        .bg-card   { background:var(--bl); color:#2b5fc4; }
        .bg-mobile { background:#f0fdf4; color:#15803d; }
        .bg-split  { background:#fff7ed; color:#c2410c; }

        /* Biz info grid */
        .biz-grid { display:grid; grid-template-columns:1fr 1fr; }
        @media(max-width:500px){ .biz-grid{ grid-template-columns:1fr; } }
        .biz-row { display:flex; flex-direction:column; gap:3px; padding:14px 18px; border-bottom:1px solid var(--br2); border-right:1px solid var(--br2); }
        .biz-row:nth-child(2n){ border-right:none; }
        .biz-row:nth-last-child(-n+2){ border-bottom:none; }
        .biz-k { font-size:9px; font-weight:700; color:var(--t4); text-transform:uppercase; letter-spacing:.07em; }
        .biz-v { font-size:13px; font-weight:600; color:var(--t1); }

        /* Month P&L */
        .pl-card { padding:20px 22px; }
        .pl-row { display:flex; justify-content:space-between; align-items:center; padding:10px 0; border-bottom:1px solid var(--br2); }
        .pl-row:last-child { border-bottom:none; padding-top:14px; }
        .pl-lbl { font-size:13px; color:var(--t2); font-family:'Outfit',sans-serif; font-weight:500; }
        .pl-val { font-family:'JetBrains Mono',monospace; font-size:13px; font-weight:600; }

        /* Top products */
        .tp-row { display:flex; align-items:center; gap:12px; padding:11px 18px; border-bottom:1px solid var(--br2); transition:background .1s; }
        .tp-row:hover { background:#fafbff; }
        .tp-row:last-child { border-bottom:none; }
        .tp-rank { width:24px; height:24px; border-radius:8px; background:var(--kl); display:flex; align-items:center; justify-content:center; font-size:10px; font-weight:700; color:var(--t3); flex-shrink:0; font-family:'Outfit',sans-serif; }
        .tp-rank-1 { background:var(--gl); color:var(--gd); }
        .tp-rank-2 { background:var(--bl); color:#2b5fc4; }
        .tp-rank-3 { background:#fef9c3; color:#92400e; }
        .tp-name { font-size:12px; font-weight:500; color:var(--t1); flex:1; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .tp-qty { font-size:10px; color:var(--t4); }
        .tp-rev { font-family:'JetBrains Mono',monospace; font-size:12px; font-weight:600; color:var(--t1); white-space:nowrap; }

        /* Low stock */
        .ls-row { display:flex; align-items:center; gap:12px; padding:11px 18px; border-bottom:1px solid var(--br2); transition:background .1s; }
        .ls-row:hover { background:#fafbff; }
        .ls-row:last-child { border-bottom:none; }
        .ls-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
        .ls-name { font-size:12px; font-weight:500; color:var(--t1); flex:1; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .ls-cat  { font-size:10px; color:var(--t4); }
        .ls-qty  { font-family:'JetBrains Mono',monospace; font-size:13px; font-weight:700; white-space:nowrap; }

        /* Alert bar */
        .alert-bar { display:flex; align-items:center; gap:12px; padding:14px 22px; background:#fff7ed; border-bottom:1px solid #fed7aa; }
        .alert-dot { width:9px; height:9px; border-radius:50%; background:#ea580c; flex-shrink:0; animation:pulse 2s infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.5} }
        .alert-txt { font-family:'Outfit',sans-serif; font-size:12px; color:#9a3412; font-weight:500; flex:1; line-height:1.5; }
        .alert-lnk { font-size:12px; font-weight:700; color:#ea580c; text-decoration:none; white-space:nowrap; font-family:'Outfit',sans-serif; }
        .alert-lnk:hover { text-decoration:underline; }

        /* Quick actions */
        .qa-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:10px; padding:16px 20px; }
        @media(max-width:768px){ .qa-grid{ grid-template-columns:repeat(2,1fr); } }
        .qa-btn { display:flex; flex-direction:column; align-items:center; justify-content:center; padding:18px 12px; border-radius:14px; border:1.5px solid var(--br); text-decoration:none; transition:all .15s; background:var(--w); }
        .qa-btn:hover { border-color:var(--g); background:var(--gl); transform:translateY(-1px); box-shadow:0 4px 12px rgba(3,167,55,.08); }
        .qa-icon { width:38px; height:38px; border-radius:12px; display:flex; align-items:center; justify-content:center; margin-bottom:10px; }
        .qa-icon svg { width:18px; height:18px; }
        .qa-lbl { font-family:'Outfit',sans-serif; font-size:11px; font-weight:600; color:var(--t2); text-align:center; }
        .qa-btn:hover .qa-lbl { color:var(--g); }
    </style>

    <div class="dw">

        {{-- ══ 1. HERO KPI STRIP ══ --}}
        <div class="panel">
            <div class="kpi-strip">

                <div class="kpi-cell">
                    <div class="kpi-icon" style="background:var(--gl)">
                        <svg fill="none" stroke="var(--g)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="kpi-label">Today's Revenue</div>
                    <div class="kpi-value">KES {{ number_format($totalRevenue, 2) }}</div>
                    <div class="kpi-sub">
                        <span>{{ $totalTransactions }} transaction{{ $totalTransactions == 1 ? '' : 's' }}</span>
                        @if($revenueChange !== null)
                            <span class="kpi-pill {{ $revenueChange >= 0 ? 'pill-up' : 'pill-down' }}">
                                <svg width="10" height="10" fill="currentColor" viewBox="0 0 24 24"><path d="{{ $revenueChange >= 0 ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/></svg>
                                {{ abs($revenueChange) }}% vs yesterday
                            </span>
                        @else
                            <span class="kpi-pill pill-flat">No data</span>
                        @endif
                    </div>
                </div>

                <div class="kpi-cell">
                    <div class="kpi-icon" style="background:var(--bl)">
                        <svg fill="none" stroke="var(--b)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div class="kpi-label">Active Branches</div>
                    <div class="kpi-value">{{ $branches->count() }}</div>
                    <div class="kpi-sub">
                        <span>{{ $totalProducts }} products</span>
                        <span style="color:#c4c9d6">·</span>
                        <span>{{ $totalCustomers }} customers</span>
                    </div>
                </div>

                <div class="kpi-cell">
                    <div class="kpi-icon" style="background:var(--kl)">
                        <svg fill="none" stroke="var(--k)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div class="kpi-label">Monthly Profit</div>
                    <div class="kpi-value" style="color:{{ $netProfit >= 0 ? 'var(--g)' : 'var(--r)' }}">{{ $netProfit >= 0 ? '+' : '−' }} KES {{ number_format(abs($netProfit), 2) }}</div>
                    <div class="kpi-sub">
                        <span>{{ now()->format('M Y') }}</span>
                        @if($monthRevenue > 0)
                            <span class="kpi-pill {{ $netProfit >= 0 ? 'pill-up' : 'pill-down' }}">{{ round(($netProfit/$monthRevenue)*100,1) }}% margin</span>
                        @endif
                    </div>
                </div>

                <div class="kpi-cell">
                    <div class="kpi-icon" style="background:{{ $totalAlerts > 0 ? 'var(--rl)' : 'var(--gl)' }}">
                        <svg fill="none" stroke="{{ $totalAlerts > 0 ? 'var(--r)' : 'var(--g)' }}" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div class="kpi-label">Stock Alerts</div>
                    <div class="kpi-value" style="color:{{ $totalAlerts > 0 ? 'var(--r)' : 'var(--t1)' }}">{{ $totalAlerts }}</div>
                    <div class="kpi-sub">
                        @if($totalAlerts == 0)
                            <span class="kpi-pill pill-up">All stocked ✓</span>
                        @else
                            <span>{{ $totalOutOfStock }} out</span>
                            <span style="color:#c4c9d6">·</span>
                            <span>{{ $totalLowStock }} low</span>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        {{-- ══ 2. LOW STOCK ALERT ══ --}}
        @if($totalAlerts > 0)
        <div class="panel" style="border-color:#fed7aa">
            <div class="alert-bar">
                <span class="alert-dot"></span>
                <span class="alert-txt">
                    <strong>{{ $totalAlerts }} product{{ $totalAlerts > 1 ? 's' : '' }}</strong> need attention — {{ $totalOutOfStock }} out of stock, {{ $totalLowStock }} running low across all branches.
                </span>
                <a href="{{ route('stock.index') }}" class="alert-lnk">Manage Stock →</a>
            </div>
        </div>
        @endif

        {{-- ══ 3. BRANCH PERFORMANCE ══ --}}
        @if($branches->count() > 1)
        <div class="panel">
            <div class="ph">
                <span class="pt">Branch Performance — Today</span>
                <span style="font-size:11px;color:var(--t4);font-family:'Outfit',sans-serif;font-weight:500">{{ now()->format('d M Y') }}</span>
            </div>
            <div class="branch-grid">
                @foreach($branchStats as $bs)
                    @php $b = $bs['branch']; @endphp
                    <div class="bc {{ $b->is_main ? 'bc-main' : '' }} {{ auth()->user()->branch_id === $b->id ? 'bc-current' : '' }}">
                        <div class="bc-header">
                            <div class="bc-icon" style="background:{{ $b->is_main ? 'var(--gl)' : 'var(--bl)' }}">
                                <svg width="14" height="14" fill="none" stroke="{{ $b->is_main ? 'var(--g)' : 'var(--b)' }}" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div style="flex:1;min-width:0">
                                <div class="bc-name">{{ $b->name }}</div>
                                <div class="bc-tag">
                                    {{ $b->is_main ? 'Main Branch' : 'Branch' }}
                                    @if(auth()->user()->branch_id === $b->id) · <span style="color:var(--g);font-weight:700">Active</span> @endif
                                </div>
                            </div>
                        </div>
                        <div class="bc-metrics">
                            <div class="bc-m">
                                <div class="bc-m-lbl">Revenue</div>
                                <div class="bc-m-val" style="font-size:13px">{{ number_format($bs['revenue'] / 1000, 1) }}k</div>
                                <div class="bc-m-sub">{{ $bs['transactions'] }} sales</div>
                            </div>
                            <div class="bc-m">
                                <div class="bc-m-lbl">Products</div>
                                <div class="bc-m-val" style="font-size:13px">{{ $bs['products'] }}</div>
                                <div class="bc-m-sub">Active</div>
                            </div>
                            <div class="bc-m">
                                <div class="bc-m-lbl">Alerts</div>
                                <div class="bc-m-val" style="font-size:13px;color:{{ ($bs['low_stock'] + $bs['out_of_stock']) > 0 ? 'var(--r)' : 'var(--g)' }}">{{ $bs['low_stock'] + $bs['out_of_stock'] }}</div>
                                <div class="bc-m-sub">{{ ($bs['low_stock'] + $bs['out_of_stock']) > 0 ? 'Needs attention' : 'All OK' }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ══ 4. QUICK ACTIONS ══ --}}
        <div class="panel">
            <div class="ph"><span class="pt">Quick Actions</span></div>
            <div class="qa-grid">
                <a href="{{ route('pos.index') }}" class="qa-btn">
                    <div class="qa-icon" style="background:var(--gl)"><svg fill="none" stroke="var(--g)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
                    <span class="qa-lbl">POS Terminal</span>
                </a>
                <a href="{{ route('products.create') }}" class="qa-btn">
                    <div class="qa-icon" style="background:var(--bl)"><svg fill="none" stroke="var(--b)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg></div>
                    <span class="qa-lbl">Add Product</span>
                </a>
                <a href="{{ route('customers.create') }}" class="qa-btn">
                    <div class="qa-icon" style="background:var(--kl)"><svg fill="none" stroke="var(--k)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg></div>
                    <span class="qa-lbl">Add Customer</span>
                </a>
                <a href="{{ route('zreports.create') }}" class="qa-btn">
                    <div class="qa-icon" style="background:var(--gl)"><svg fill="none" stroke="var(--g)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                    <span class="qa-lbl">Close Shift</span>
                </a>
            </div>
        </div>

        {{-- ══ 5. CHARTS ROW ══ --}}
        <div class="g2">
            <div class="panel">
                <div class="ph">
                    <span class="pt">Revenue — Last 7 Days</span>
                    <span style="font-size:11px;color:var(--t4);font-family:'Outfit',sans-serif;font-weight:500">
                        KES {{ number_format(array_sum($weeklyData) / 1000, 1) }}k total
                    </span>
                </div>
                <div class="chart-card">
                    <div class="bar-wrap">
                        @foreach($weeklyData as $i => $val)
                            @php
                                $h = ($val / $maxWeekly) * 100;
                                $isToday = $i === 6;
                                $isYesterday = $i === 5;
                                $color = $isToday ? 'var(--g)' : ($isYesterday ? 'var(--b)' : 'var(--br)');
                            @endphp
                            <div class="bar-col">
                                <div class="bar-bg">
                                    <div class="bar-fill" style="height:{{ max($h, $val > 0 ? 3 : 0) }}%;background:{{ $color }}" title="{{ $weeklyDays[$i] }}: KES {{ number_format($val,2) }}"></div>
                                </div>
                                <div class="bar-val">{{ $val > 0 ? number_format($val/1000,1).'k' : '' }}</div>
                                <div class="bar-day" style="color:{{ $isToday ? 'var(--g)' : 'var(--t4)' }};font-weight:{{ $isToday ? 600 : 400 }}">{{ $weeklyLabels[$i] }}</div>
                            </div>
                        @endforeach
                    </div>
                    <div style="display:flex;justify-content:center;gap:16px;padding:8px 20px 12px">
                        <div style="display:flex;align-items:center;gap:6px;font-size:10px;color:var(--t4);font-family:'Outfit',sans-serif">
                            <span style="width:10px;height:10px;border-radius:3px;background:var(--g);display:inline-block"></span> Today
                        </div>
                        <div style="display:flex;align-items:center;gap:6px;font-size:10px;color:var(--t4);font-family:'Outfit',sans-serif">
                            <span style="width:10px;height:10px;border-radius:3px;background:var(--b);display:inline-block"></span> Yesterday
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel">
                <div class="ph">
                    <span class="pt">Payment Methods</span>
                    <a href="{{ route('reports.sales') }}" class="pl">View all →</a>
                </div>
                <div style="padding:8px 0">
                    <div class="pm-row">
                        <div class="pm-icon" style="background:var(--gl)"><svg fill="none" stroke="var(--g)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg></div>
                        <div class="pm-name">Cash</div>
                        <div class="pm-bar-wrap"><div class="pm-bar-fill" style="width:{{ $cashPct }}%;background:var(--g)"></div></div>
                        <div class="pm-val">{{ number_format($cashTotal/1000,1) }}k</div>
                        <div class="pm-pct">{{ $cashPct }}%</div>
                    </div>
                    <div class="pm-row">
                        <div class="pm-icon" style="background:var(--bl)"><svg fill="none" stroke="var(--b)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg></div>
                        <div class="pm-name">Card</div>
                        <div class="pm-bar-wrap"><div class="pm-bar-fill" style="width:{{ $cardPct }}%;background:var(--b)"></div></div>
                        <div class="pm-val">{{ number_format($cardTotal/1000,1) }}k</div>
                        <div class="pm-pct">{{ $cardPct }}%</div>
                    </div>
                    <div class="pm-row">
                        <div class="pm-icon" style="background:var(--kl)"><svg fill="none" stroke="var(--k)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg></div>
                        <div class="pm-name">M-Pesa</div>
                        <div class="pm-bar-wrap"><div class="pm-bar-fill" style="width:{{ $mobilePct }}%;background:var(--k)"></div></div>
                        <div class="pm-val">{{ number_format($mobileTotal/1000,1) }}k</div>
                        <div class="pm-pct">{{ $mobilePct }}%</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══ 6. DATA PANELS ROW ══ --}}
        <div class="g3">
            <div class="panel">
                <div class="ph">
                    <span class="pt">Recent Sales</span>
                    <a href="{{ route('reports.sales') }}" class="pl">View all →</a>
                </div>
                @if($recentSales->count())
                    <table class="dt">
                        <thead><tr><th>Invoice</th><th>Branch</th><th>Method</th><th style="text-align:right">Total</th></tr></thead>
                        <tbody>
                            @foreach($recentSales as $sale)
                            <tr>
                                <td>
                                    <div class="mono" style="font-size:10px;color:var(--g);font-weight:700">{{ $sale->invoice_no }}</div>
                                    <div style="font-size:9px;color:var(--t4);margin-top:1px">{{ $sale->created_at->format('d M, H:i') }}</div>
                                </td>
                                <td style="font-size:10px;color:var(--t4)">{{ $sale->branch->name ?? '—' }}</td>
                                <td>
                                    @php $pm = $sale->payment_method; @endphp
                                    <span class="badge {{ str_contains($pm,'cash') ? 'bg-cash' : (str_contains($pm,'card') ? 'bg-card' : (str_contains($pm,'mobile')||str_contains($pm,'mpesa') ? 'bg-mobile' : 'bg-split')) }}">
                                        {{ ucfirst(str_replace('+','',explode(' ',$pm)[0])) }}
                                    </span>
                                </td>
                                <td style="text-align:right" class="mono" style="font-size:11px;font-weight:600;color:var(--t1)">KES {{ number_format($sale->total,2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div style="padding:40px 18px;text-align:center;font-size:12px;color:var(--t4);font-family:'Outfit',sans-serif">
                        <div style="font-size:32px;margin-bottom:10px;opacity:.2">📋</div>
                        No sales yet today.<br>
                        <a href="{{ route('pos.index') }}" style="color:var(--g);font-weight:600;text-decoration:none;margin-top:6px;display:inline-block">Open POS →</a>
                    </div>
                @endif
            </div>

            <div class="panel">
                <div class="ph">
                    <span class="pt">Top Products</span>
                    <span style="font-size:10px;color:var(--t4);font-family:'Outfit',sans-serif;font-weight:500">{{ now()->format('M Y') }}</span>
                </div>
                @forelse($topProducts as $i => $p)
                    <div class="tp-row">
                        <div class="tp-rank tp-rank-{{ $i+1 }}">{{ $i+1 }}</div>
                        <div style="flex:1;min-width:0">
                            <div class="tp-name" title="{{ $p->product_name }}">{{ $p->product_name }}</div>
                            <div class="tp-qty">{{ $p->total_qty }} units</div>
                        </div>
                        <div class="tp-rev" style="color:var(--g)">KES {{ number_format($p->total_revenue/1000,1) }}k</div>
                    </div>
                @empty
                    <div style="padding:40px 18px;text-align:center;font-size:12px;color:var(--t4);font-family:'Outfit',sans-serif">
                        <div style="font-size:32px;margin-bottom:10px;opacity:.2">📦</div>
                        No sales this month yet.
                    </div>
                @endforelse
            </div>

            <div class="panel">
                <div class="ph">
                    <span class="pt">Stock Alerts</span>
                    <a href="{{ route('stock.index') }}" class="pl">Manage →</a>
                </div>
                @if($totalOutOfStock > 0)
                    <div style="display:flex;align-items:center;gap:10px;padding:12px 18px;background:var(--rl);border-bottom:1px solid #fecaca">
                        <div style="width:10px;height:10px;border-radius:50%;background:var(--r);flex-shrink:0"></div>
                        <span style="font-family:'Outfit',sans-serif;font-size:11px;font-weight:600;color:#b91c1c">
                            <strong>{{ $totalOutOfStock }}</strong> item{{ $totalOutOfStock>1?'s':'' }} completely out of stock
                        </span>
                    </div>
                @endif
                @forelse($lowStockItems as $item)
                    <div class="ls-row">
                        <div class="ls-dot" style="background:#f59e0b"></div>
                        <div style="flex:1;min-width:0">
                            <div class="ls-name" title="{{ $item->name }}">{{ $item->name }}</div>
                            <div class="ls-cat">{{ $item->category->name ?? 'Uncategorised' }}</div>
                        </div>
                        <div class="ls-qty" style="color:#d97706">{{ $item->stock_qty }} left</div>
                    </div>
                @empty
                    @if($totalOutOfStock == 0)
                        <div style="padding:40px 18px;text-align:center;font-size:12px;color:var(--t4);font-family:'Outfit',sans-serif">
                            <div style="width:40px;height:40px;border-radius:50%;background:var(--gl);display:inline-flex;align-items:center;justify-content:center;margin-bottom:10px">
                                <svg width="20" height="20" fill="none" stroke="var(--g)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div><br>
                            All stock levels are healthy.
                        </div>
                    @endif
                @endforelse
            </div>
        </div>

        {{-- ══ 7. BOTTOM ROW ══ --}}
        <div class="g2">
            <div class="panel">
                <div class="ph">
                    <span class="pt">{{ now()->format('F Y') }} — P&L Overview</span>
                    <a href="{{ route('reports.profit-loss') }}" class="pl">Full report →</a>
                </div>
                <div class="pl-card">
                    <div class="pl-row">
                        <span class="pl-lbl">Total Revenue</span>
                        <span class="pl-val mono" style="color:var(--g)">+ KES {{ number_format($monthRevenue,2) }}</span>
                    </div>
                    <div class="pl-row">
                        <span class="pl-lbl">Operating Expenses</span>
                        <span class="pl-val mono" style="color:var(--r)">− KES {{ number_format($monthExpenses,2) }}</span>
                    </div>
                    <div class="pl-row">
                        <span style="font-size:14px;font-weight:700;color:var(--t1);font-family:'Outfit',sans-serif">Net Profit</span>
                        <span style="font-family:'JetBrains Mono',monospace;font-size:20px;font-weight:700;color:{{ $netProfit >= 0 ? 'var(--g)' : 'var(--r)' }}">
                            {{ $netProfit >= 0 ? '+' : '−' }} KES {{ number_format(abs($netProfit),2) }}
                        </span>
                    </div>
                    @if($monthRevenue > 0)
                        @php $margin = round(($netProfit / $monthRevenue) * 100, 1); @endphp
                        <div style="margin-top:16px">
                            <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                                <span style="font-size:10px;font-weight:700;color:var(--t4);text-transform:uppercase;letter-spacing:.06em;font-family:'Outfit',sans-serif">Profit Margin</span>
                                <span style="font-size:12px;font-weight:700;color:{{ $margin >= 0 ? 'var(--g)' : 'var(--r)' }};font-family:'Outfit',sans-serif">{{ $margin }}%</span>
                            </div>
                            <div style="background:var(--br2);border-radius:99px;height:8px;overflow:hidden">
                                <div style="height:100%;border-radius:99px;background:{{ $margin >= 0 ? 'var(--g)' : 'var(--r)' }};width:{{ min(abs($margin),100) }}%;transition:width .6s ease"></div>
                            </div>
                            <div style="display:flex;justify-content:space-between;margin-top:6px;font-size:10px;color:var(--t4);font-family:'Outfit',sans-serif">
                                <span>{{ $monthCount }} transactions</span>
                                <span>Avg KES {{ $monthCount > 0 ? number_format($monthRevenue/$monthCount,2) : '0.00' }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="panel">
                <div class="ph">
                    <span class="pt">Business Details</span>
                    <a href="{{ route('settings.index') }}" class="pl">Settings →</a>
                </div>
                <div class="biz-grid">
                    <div class="biz-row"><span class="biz-k">Business</span><span class="biz-v">{{ Auth::user()->tenant->name ?? '—' }}</span></div>
                    <div class="biz-row"><span class="biz-k">Type</span><span class="biz-v">{{ ucfirst(Auth::user()->tenant->business_type ?? '—') }}</span></div>
                    <div class="biz-row"><span class="biz-k">Your Role</span><span class="biz-v">{{ ucfirst(Auth::user()->getRoleNames()->first() ?? 'Staff') }}</span></div>
                    <div class="biz-row"><span class="biz-k">Active Branch</span><span class="biz-v">{{ Auth::user()->branch->name ?? 'Main Branch' }}</span></div>
                    <div class="biz-row"><span class="biz-k">Plan</span><span class="biz-v">{{ ucfirst(Auth::user()->tenant->plan ?? 'Basic') }}</span></div>
                    <div class="biz-row"><span class="biz-k">Status</span><span class="biz-v" style="color:var(--g);display:flex;align-items:center;gap:6px"><span style="width:7px;height:7px;border-radius:50%;background:var(--g);display:inline-block"></span>Active</span></div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>