<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Finance</p>
                <h1 style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:700;color:#02182F;line-height:1.2">
                    Cashier Performance
                </h1>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('reports.profit-loss') }}"
                   style="background:transparent;color:#6b7280;padding:9px 16px;border-radius:10px;font-size:12px;font-weight:600;text-decoration:none;font-family:'Outfit',sans-serif;border:1.5px solid #e4e7ef;transition:all .15s"
                   onmouseover="this.style.borderColor='#03A737';this.style.color='#03A737';this.style.background='#e6f7eb'"
                   onmouseout="this.style.borderColor='#e4e7ef';this.style.color='#6b7280';this.style.background='transparent'">
                    P&L Report
                </a>
                <a href="{{ route('reports.stock-valuation') }}"
                   style="background:transparent;color:#6b7280;padding:9px 16px;border-radius:10px;font-size:12px;font-weight:600;text-decoration:none;font-family:'Outfit',sans-serif;border:1.5px solid #e4e7ef;transition:all .15s"
                   onmouseover="this.style.borderColor='#03A737';this.style.color='#03A737';this.style.background='#e6f7eb'"
                   onmouseout="this.style.borderColor='#e4e7ef';this.style.color='#6b7280';this.style.background='transparent'">
                    Stock Valuation
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
        }
        .panel-title {
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            font-weight: 700;
            color: #02182F;
        }
        
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        @media (max-width: 900px) { .two-col { grid-template-columns: 1fr; } }
        
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
            padding: 12px 16px;
            background: #fafbff;
            border-bottom: 1px solid #f1f3f8;
        }
        .data-table td {
            padding: 14px 16px;
            font-size: 13px;
            color: #374151;
            border-bottom: 1px solid #f8f9fb;
        }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tbody tr:hover td { background: #fafbff; }
        
        .rank-cell {
            width: 44px;
            text-align: center;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            font-weight: 700;
            color: #9ca3af;
        }
        .rank-medal {
            font-size: 20px;
            display: inline-block;
        }
        
        .staff-avatar-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .staff-avatar {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-family: 'Outfit', sans-serif;
            font-size: 11px;
            font-weight: 700;
            color: #FFFEFE;
        }
        
        .top-row-highlight {
            background: #fffbeb;
        }
        .top-row-highlight:hover td {
            background: #fef3c7 !important;
        }
        
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 600;
            font-family: 'Outfit', sans-serif;
        }
        .badge-purple { background: #f3e8ff; color: #7c3aed; }
        .badge-blue   { background: #edf3fd; color: #2b5fc4; }
        .badge-teal   { background: #ccfbf1; color: #0f766e; }
        .badge-amber  { background: #fef3c7; color: #b45309; }
        
        .mono { font-family: 'JetBrains Mono', monospace; }
        .fw6 { font-weight: 600; }
        .fw7 { font-weight: 700; }
        .text-brand { color: #03A737; }
        .text-positive { color: #03A737; }
        .text-danger { color: #dc2626; }
        .text-warning { color: #d97706; }
        
        .progress-bar {
            height: 6px;
            background: #f1f3f8;
            border-radius: 99px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            border-radius: 99px;
            transition: width 0.4s ease;
            background: #03A737;
        }
        
        .peak-row {
            margin-bottom: 8px;
        }
        .peak-row:last-child { margin-bottom: 0; }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            color: #9ca3af;
        }
        .empty-state-sm {
            text-align: center;
            padding: 40px 20px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            color: #9ca3af;
        }
    </style>

    <div class="dash-wrap" style="padding:0 0 20px">

        {{-- Date Filter --}}
        <div class="filter-bar">
            <form method="GET" action="{{ route('reports.cashier-performance') }}" style="display:flex;align-items:flex-end;gap:14px;flex-wrap:wrap;width:100%">
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
                <a href="{{ route('reports.cashier-performance') }}" class="btn-reset">Reset</a>
            </form>
            <a href="{{ route('reports.cashier-performance.export', request()->query()) }}" 
   style="background:transparent;color:#6b7280;padding:8px 16px;border-radius:10px;font-size:12px;font-weight:600;text-decoration:none;font-family:'Outfit',sans-serif;border:1.5px solid #e4e7ef;transition:all .15s;display:inline-flex;align-items:center;gap:6px"
   onmouseover="this.style.borderColor='#03A737';this.style.color='#03A737';this.style.background='#e6f7eb'"
   onmouseout="this.style.borderColor='#e4e7ef';this.style.color='#6b7280';this.style.background='transparent'">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
    </svg>
    Export PDF
</a>
        </div>

        {{-- Cashier Leaderboard --}}
        <div class="panel">
            <div class="panel-header">
                <span class="panel-title">Staff Performance</span>
            </div>
            @if($cashiers->count())
                <div style="overflow-x:auto">
                    <table class="data-table" style="min-width:1000px">
                        <thead>
                            <tr>
                                <th style="width:50px;text-align:center">Rank</th>
                                <th>Staff Member</th>
                                <th>Role</th>
                                <th>Branch</th>
                                <th>Sales</th>
                                <th>Items Sold</th>
                                <th>Avg Sale</th>
                                <th>Returns</th>
                                <th style="text-align:right">Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $avatarColors = ['#03A737','#3D7BE7','#02182F','#d97706','#dc2626','#0891b2','#7c3aed','#028a2e'];
                                $roleColors = [
                                    'admin'   => 'badge-purple',
                                    'manager' => 'badge-blue',
                                    'cashier' => 'badge-teal',
                                    'staff'   => 'badge-amber',
                                ];
                            @endphp
                            @foreach($cashiers as $rank => $cashier)
                                @php
                                    $initials = strtoupper(substr($cashier->name, 0, 2));
                                    $avatarBg = $avatarColors[$cashier->id % count($avatarColors)];
                                    $roleName = $cashier->roles->first()?->name ?? 'staff';
                                    $roleBadge = $roleColors[$roleName] ?? 'badge-blue';
                                @endphp
                                <tr class="{{ $rank === 0 ? 'top-row-highlight' : '' }}">
                                    <td class="rank-cell">
                                        @if($rank === 0)
                                            <span class="rank-medal">🥇</span>
                                        @elseif($rank === 1)
                                            <span class="rank-medal">🥈</span>
                                        @elseif($rank === 2)
                                            <span class="rank-medal">🥉</span>
                                        @else
                                            {{ $rank + 1 }}
                                        @endif
                                    </td>
                                    <td>
                                        <div class="staff-avatar-cell">
                                            <div class="staff-avatar" style="background:{{ $avatarBg }}">
                                                {{ $initials }}
                                            </div>
                                            <div>
                                                <div class="fw6" style="color:#02182F">{{ $cashier->name }}</div>
                                                <div style="font-size:11px;color:#9ca3af">{{ $cashier->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $roleBadge }}">{{ ucfirst($roleName) }}</span>
                                    </td>
                                    <td style="font-size:12px;color:#6b7280">{{ $cashier->branch->name ?? '—' }}</td>
                                    <td class="mono fw6" style="color:#02182F">{{ $cashier->total_sales }}</td>
                                    <td class="mono" style="font-size:12px;color:#6b7280">{{ $cashier->total_items }}</td>
                                    <td class="mono" style="font-size:12px;color:#6b7280">KES {{ number_format($cashier->avg_sale, 2) }}</td>
                                    <td>
                                        <span class="mono fw6 {{ $cashier->total_returns > 0 ? 'text-warning' : '' }}" style="font-size:12px;color:{{ $cashier->total_returns > 0 ? '#d97706' : '#9ca3af' }}">
                                            {{ $cashier->total_returns }}
                                        </span>
                                    </td>
                                    <td class="mono fw7 text-positive" style="text-align:right;font-size:14px">
                                        KES {{ number_format($cashier->total_revenue, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">No sales data for this period.</div>
            @endif
        </div>

        {{-- Top Products + Peak Hours --}}
        <div class="two-col">

            {{-- Top 10 Products --}}
            <div class="panel">
                <div class="panel-header">
                    <span class="panel-title">Top 10 Products by Revenue</span>
                </div>
                @if($topProducts->count())
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Revenue</th>
                                <th style="text-align:right">Profit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topProducts as $product)
                                <tr>
                                    <td class="fw6" style="color:#02182F">{{ $product->product_name }}</td>
                                    <td class="mono" style="font-size:12px;color:#6b7280">{{ $product->total_qty }}</td>
                                    <td class="mono fw6 text-brand">KES {{ number_format($product->total_revenue, 2) }}</td>
                                    <td class="mono fw6 text-positive" style="text-align:right">
                                        KES {{ number_format($product->total_profit, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state-sm">No sales data yet.</div>
                @endif
            </div>

            {{-- Peak Sales Hours --}}
            <div class="panel panel-padded">
                <div style="font-family:'Outfit',sans-serif;font-size:14px;font-weight:700;color:#02182F;margin-bottom:16px">
                    Peak Sales Hours
                </div>
                @if($hourly->count())
                    @php $maxRevenue = $hourly->max('revenue'); @endphp
                    @foreach($hourly as $hour)
                        @php
                            $pct   = $maxRevenue > 0 ? round(($hour->revenue / $maxRevenue) * 100) : 0;
                            $label = \Carbon\Carbon::createFromTime($hour->hour)->format('h:00 A');
                        @endphp
                        <div class="peak-row">
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:4px">
                                <span style="font-size:12px;color:#374151;font-weight:500;font-family:'Outfit',sans-serif;width:56px">
                                    {{ $label }}
                                </span>
                                <span style="font-size:11px;color:#9ca3af;font-family:'JetBrains Mono',monospace">
                                    {{ $hour->transactions }} sales
                                </span>
                                <span style="font-size:12px;color:#02182F;font-weight:600;font-family:'JetBrains Mono',monospace">
                                    KES {{ number_format($hour->revenue, 2) }}
                                </span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width:{{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state-sm">No sales data for this period.</div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>