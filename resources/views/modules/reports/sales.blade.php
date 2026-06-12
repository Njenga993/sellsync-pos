<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Finance</p>
                <h1 style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:700;color:#02182F;line-height:1.2">
                    Sales Report
                </h1>
            </div>
            <a href="{{ route('pos.index') }}"
               style="background:#03A737;color:#FFFEFE;padding:10px 22px;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;font-family:'Outfit',sans-serif;letter-spacing:.01em;box-shadow:0 2px 8px rgba(3,167,55,.25);transition:all .15s"
               onmouseover="this.style.background='#028a2e';this.style.boxShadow='0 4px 12px rgba(3,167,55,.35)'"
               onmouseout="this.style.background='#03A737';this.style.boxShadow='0 2px 8px rgba(3,167,55,.25)'">
                Open POS
            </a>
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
        .filter-input:focus, .filter-select:focus {
            border-color: #03A737;
            background: #FFFEFE;
            box-shadow: 0 0 0 3px rgba(3, 167, 55, 0.08);
        }
        .filter-select {
            padding: 9px 12px;
            border: 1.5px solid #e4e7ef;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            color: #02182F;
            background: #fafbff;
            transition: all 0.15s;
            outline: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' fill='none' stroke='%239ca3af' stroke-width='2' viewBox='0 0 24 24'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
            cursor: pointer;
            min-width: 160px;
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
        
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
        }
        @media (max-width: 1100px) { .stat-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 640px)  { .stat-grid { grid-template-columns: 1fr; } }
        
        .stat-card {
            border-radius: 14px;
            padding: 20px 22px 18px;
            border: 1px solid transparent;
        }
        .stat-card-label {
            font-family: 'Outfit', sans-serif;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            margin-bottom: 8px;
        }
        .stat-card-value {
            font-family: 'JetBrains Mono', monospace;
            font-size: 24px;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 4px;
            color: #02182F;
        }
        .stat-card-sub {
            font-family: 'Outfit', sans-serif;
            font-size: 11px;
        }
        
        .card-green  { background: #e6f7eb; border-color: #b8e6c4; }
        .card-green  .stat-card-label  { color: #028a2e; }
        .card-green  .stat-card-sub    { color: #03A737; }
        
        .card-blue   { background: #edf3fd; border-color: #c4d9fb; }
        .card-blue   .stat-card-label  { color: #2b5fc4; }
        .card-blue   .stat-card-sub    { color: #3D7BE7; }
        
        .card-black  { background: #f0f2f5; border-color: #d4d8e0; }
        .card-black  .stat-card-label  { color: #4b5563; }
        .card-black  .stat-card-sub    { color: #6b7280; }
        
        .card-red    { background: #fef2f2; border-color: #fecdd3; }
        .card-red    .stat-card-label  { color: #be123c; }
        .card-red    .stat-card-sub    { color: #e11d48; }
        
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
        }
        .panel-title {
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            font-weight: 700;
            color: #02182F;
        }
        .panel-meta {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            color: #9ca3af;
        }
        
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
        .fill-green  { background: #03A737; }
        .fill-blue   { background: #3D7BE7; }
        .fill-black  { background: #02182F; }
        
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
        
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 600;
            font-family: 'Outfit', sans-serif;
        }
        .badge-green  { background: #e6f7eb; color: #028a2e; }
        .badge-blue   { background: #edf3fd; color: #2b5fc4; }
        .badge-yellow { background: #fef9c3; color: #a16207; }
        
        .mono { font-family: 'JetBrains Mono', monospace; }
        .fw6 { font-weight: 600; }
        .fw7 { font-weight: 700; }
        .text-brand { color: #03A737; }
        
        .action-link {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 600;
            color: #03A737;
            text-decoration: none;
            transition: all 0.15s;
        }
        .action-link:hover { color: #028a2e; text-decoration: underline; }
        
        .top-product-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #f8f9fb;
        }
        .top-product-row:last-child { border-bottom: none; }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            color: #9ca3af;
        }

        .branch-mini-card {
            text-align: center;
            padding: 14px 12px;
            border-radius: 10px;
            border: 1px solid #e4e7ef;
            background: #FFFEFE;
        }
        .branch-mini-card .branch-mini-name {
            font-size: 11px;
            font-weight: 600;
            color: #02182F;
            margin-bottom: 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .branch-mini-card .branch-mini-value {
            font-family: 'JetBrains Mono', monospace;
            font-size: 15px;
            font-weight: 700;
            color: #03A737;
        }
        .branch-mini-card .branch-mini-sub {
            font-size: 10px;
            color: #9ca3af;
            margin-top: 2px;
        }
    </style>

    <div class="dash-wrap" style="padding:0 0 20px">

        {{-- Date Filter + Branch Filter --}}
        <div class="filter-bar">
            <form method="GET" action="{{ route('reports.sales') }}" style="display:flex;align-items:flex-end;gap:14px;flex-wrap:wrap;width:100%">
                <div class="filter-group">
                    <span class="filter-label">Branch</span>
                    <select name="branch_id" class="filter-select">
                        <option value="">All Branches</option>
                        @foreach(\App\Models\Branch::where('tenant_id', auth()->user()->tenant_id)->where('status', 'active')->orderBy('is_main', 'desc')->get() as $br)
                            <option value="{{ $br->id }}" {{ request('branch_id') == $br->id ? 'selected' : '' }}>
                                {{ $br->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
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
                    Apply Filter
                </button>
                <a href="{{ route('reports.sales') }}" class="btn-reset">Reset</a>
            </form>
            <a href="{{ route('reports.sales.export', request()->query()) }}" 
   style="background:transparent;color:#6b7280;padding:8px 16px;border-radius:10px;font-size:12px;font-weight:600;text-decoration:none;font-family:'Outfit',sans-serif;border:1.5px solid #e4e7ef;transition:all .15s;display:inline-flex;align-items:center;gap:6px"
   onmouseover="this.style.borderColor='#03A737';this.style.color='#03A737';this.style.background='#e6f7eb'"
   onmouseout="this.style.borderColor='#e4e7ef';this.style.color='#6b7280';this.style.background='transparent'">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
    </svg>
    Export PDF
</a>
        </div>

        {{-- Summary Cards --}}
        <div class="stat-grid">
            <div class="stat-card card-green">
                <div class="stat-card-label">Total Revenue</div>
                <div class="stat-card-value">KES {{ number_format($summary->total_revenue ?? 0, 2) }}</div>
                <div class="stat-card-sub">{{ $summary->total_transactions ?? 0 }} transactions</div>
            </div>
            <div class="stat-card card-blue">
                <div class="stat-card-label">Average Sale</div>
                <div class="stat-card-value">KES {{ number_format($summary->avg_sale ?? 0, 2) }}</div>
                <div class="stat-card-sub">Per transaction</div>
            </div>
            <div class="stat-card card-black">
                <div class="stat-card-label">Total Tax</div>
                <div class="stat-card-value">KES {{ number_format($summary->total_tax ?? 0, 2) }}</div>
                <div class="stat-card-sub">Collected</div>
            </div>
            <div class="stat-card card-red">
                <div class="stat-card-label">Discounts Given</div>
                <div class="stat-card-value">KES {{ number_format($summary->total_discount ?? 0, 2) }}</div>
                <div class="stat-card-sub">Total deducted</div>
            </div>
        </div>

        {{-- ── PER-BRANCH BREAKDOWN ── --}}
        @php
            $branchBreakdown = \App\Models\Branch::where('tenant_id', auth()->user()->tenant_id)
                ->where('status', 'active')
                ->orderBy('is_main', 'desc')
                ->get()
                ->map(function($branch) use ($from, $to) {
                    $branchSales = \App\Models\Sale::where('tenant_id', auth()->user()->tenant_id)
                        ->where('branch_id', $branch->id)
                        ->whereBetween(\DB::raw('DATE(created_at)'), [$from, $to]);
                    return [
                        'branch' => $branch,
                        'revenue' => $branchSales->sum('total'),
                        'transactions' => $branchSales->count(),
                    ];
                });
            $totalAllBranches = $branchBreakdown->sum('revenue') ?: 1;
        @endphp
        @if($branchBreakdown->count() > 1)
        <div class="panel">
            <div class="panel-header">
                <span class="panel-title">Revenue by Branch</span>
                <span style="font-size:11px;color:#9ca3af;font-family:'Outfit',sans-serif">{{ \Carbon\Carbon::parse($from)->format('d M') }} — {{ \Carbon\Carbon::parse($to)->format('d M Y') }}</span>
            </div>
            <div style="padding:16px 20px;display:grid;grid-template-columns:repeat(auto-fit, minmax(150px, 1fr));gap:12px">
                @foreach($branchBreakdown as $bd)
                    @php $pct = $totalAllBranches > 0 ? round(($bd['revenue'] / $totalAllBranches) * 100) : 0; @endphp
                    <div class="branch-mini-card" style="{{ auth()->user()->branch_id === $bd['branch']->id ? 'border-color:#03A737;box-shadow:0 0 0 2px rgba(3,167,55,0.1)' : '' }}">
                        <div class="branch-mini-name">{{ $bd['branch']->name }}</div>
                        <div class="branch-mini-value">KES {{ number_format($bd['revenue'] / 1000, 1) }}k</div>
                        <div class="branch-mini-sub">{{ $bd['transactions'] }} sales · {{ $pct }}%</div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Payment Breakdown + Top Products --}}
        <div class="two-col">
            <div class="panel panel-padded">
                <div class="panel-title" style="margin-bottom:16px">Payment Methods</div>
                @php
                    $totalRev = $summary->total_revenue ?? 1;
                    $methods = [
                        ['label' => 'Cash',   'amount' => $summary->cash_total   ?? 0, 'fill' => 'fill-green'],
                        ['label' => 'Card',   'amount' => $summary->card_total   ?? 0, 'fill' => 'fill-blue'],
                        ['label' => 'M-Pesa', 'amount' => $summary->mobile_total ?? 0, 'fill' => 'fill-black'],
                    ];
                @endphp
                @foreach($methods as $m)
                    @php $pct = $totalRev > 0 ? round(($m['amount'] / $totalRev) * 100) : 0; @endphp
                    <div style="margin-bottom:14px">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px">
                            <span style="font-size:13px;color:#374151;font-weight:500;font-family:'Outfit',sans-serif">{{ $m['label'] }}</span>
                            <span style="font-size:12px;color:#6b7280;font-family:'JetBrains Mono',monospace;font-weight:600">
                                KES {{ number_format($m['amount'], 2) }}
                                <span style="color:#9ca3af;font-weight:400;margin-left:4px">({{ $pct }}%)</span>
                            </span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill {{ $m['fill'] }}" style="width:{{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="panel panel-padded">
                <div class="panel-title" style="margin-bottom:16px">Top Selling Products</div>
                @forelse($topProducts as $product)
                    <div class="top-product-row">
                        <div>
                            <div style="font-size:13px;font-weight:600;color:#02182F;font-family:'Outfit',sans-serif">{{ $product->product_name }}</div>
                            <div style="font-size:11px;color:#9ca3af;margin-top:2px">{{ $product->total_qty }} units sold</div>
                        </div>
                        <span class="mono fw6" style="color:#03A737">KES {{ number_format($product->total_revenue, 2) }}</span>
                    </div>
                @empty
                    <div class="empty-state">No sales data yet.</div>
                @endforelse
            </div>
        </div>

        {{-- Daily Breakdown --}}
        @if($dailySales->count() > 0)
            <div class="panel">
                <div class="panel-header">
                    <span class="panel-title">Daily Breakdown</span>
                </div>
                <div style="overflow-x:auto">
                    <table class="data-table" style="min-width:500px">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Transactions</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailySales as $day)
                                <tr>
                                    <td style="font-size:12px;color:#374151">{{ \Carbon\Carbon::parse($day->date)->format('D, d M Y') }}</td>
                                    <td class="mono" style="font-size:12px;color:#6b7280">{{ $day->transactions }}</td>
                                    <td class="mono fw6" style="color:#02182F">KES {{ number_format($day->revenue, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        {{-- All Transactions --}}
        <div class="panel">
            <div class="panel-header">
                <span class="panel-title">All Transactions</span>
                <span class="panel-meta">{{ $sales->total() }} records</span>
            </div>
            @if($sales->count())
                <div style="overflow-x:auto">
                    <table class="data-table" style="min-width:1000px">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Date & Time</th>
                                <th>Branch</th>
                                <th>Customer</th>
                                <th>Cashier</th>
                                <th>Items</th>
                                <th>Payment</th>
                                <th>Total</th>
                                <th style="width:70px">Receipt</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales as $sale)
                                @php
                                    $payBadge = match($sale->payment_method) {
                                        'cash'   => 'badge-green',
                                        'card'   => 'badge-blue',
                                        'mobile' => 'badge-yellow',
                                        default  => 'badge-blue',
                                    };
                                @endphp
                                <tr>
                                    <td class="mono fw6 text-brand" style="font-size:12px">{{ $sale->invoice_no }}</td>
                                    <td>
                                        <div style="font-size:12px;color:#374151">{{ $sale->created_at->format('d M Y') }}</div>
                                        <div class="mono" style="font-size:11px;color:#9ca3af">{{ $sale->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td style="font-size:12px;color:#6b7280">{{ $sale->branch->name ?? '—' }}</td>
                                    <td style="font-size:12px;color:#6b7280">{{ $sale->customer->name ?? 'Walk-in' }}</td>
                                    <td style="font-size:12px;color:#6b7280">{{ $sale->user->name ?? '—' }}</td>
                                    <td style="font-size:12px;color:#6b7280">{{ $sale->items->count() }} item(s)</td>
                                    <td><span class="badge {{ $payBadge }}">{{ ucfirst($sale->payment_method) }}</span></td>
                                    <td class="mono fw6" style="color:#02182F">KES {{ number_format($sale->total, 2) }}</td>
                                    <td><a href="{{ route('pos.receipt', $sale) }}" target="_blank" class="action-link">View</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($sales->hasPages())
                    <div style="padding:14px 20px;border-top:1px solid #f1f3f8">{{ $sales->links() }}</div>
                @endif
            @else
                <div class="empty-state" style="padding:60px">No sales found for this period.</div>
            @endif
        </div>

    </div>
</x-app-layout>