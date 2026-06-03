<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Finance</p>
                <h1 style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:700;color:#111827;line-height:1.2">
                    Sales Report
                </h1>
            </div>
            <a href="{{ route('pos.index') }}"
               style="background:#1a56db;color:white;padding:10px 22px;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;font-family:'Outfit',sans-serif;letter-spacing:.01em;box-shadow:0 2px 8px rgba(26,86,219,.25);transition:all .15s">
                Open POS
            </a>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
        
        .dash-wrap { display: flex; flex-direction: column; gap: 20px; }
        
        .filter-bar {
            background: #ffffff;
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
            color: #111827;
            background: #fafbff;
            transition: all 0.15s;
            outline: none;
        }
        .filter-input:focus {
            border-color: #1a56db;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.08);
        }
        
        .btn-filter {
            padding: 9px 18px;
            background: #1a56db;
            color: white;
            border: none;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(26, 86, 219, 0.25);
            transition: all 0.15s;
        }
        .btn-filter:hover {
            background: #1e40af;
            box-shadow: 0 4px 12px rgba(26, 86, 219, 0.35);
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
            border-color: #1a56db;
            color: #1a56db;
            background: #eff4ff;
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
        }
        .stat-card-sub {
            font-family: 'Outfit', sans-serif;
            font-size: 11px;
        }
        
        .card-blue   { background: #eff4ff; border-color: #c7d7fb; }
        .card-blue   .stat-card-label  { color: #3b5fd6; }
        .card-blue   .stat-card-value  { color: #1a3fad; }
        .card-blue   .stat-card-sub    { color: #6b85d6; }
        
        .card-teal   { background: #f0fdfa; border-color: #99f6e4; }
        .card-teal   .stat-card-label  { color: #0f766e; }
        .card-teal   .stat-card-value  { color: #134e4a; }
        .card-teal   .stat-card-sub    { color: #2dd4bf; }
        
        .card-indigo { background: #f5f3ff; border-color: #c4b5fd; }
        .card-indigo .stat-card-label  { color: #5b21b6; }
        .card-indigo .stat-card-value  { color: #3b0764; }
        .card-indigo .stat-card-sub    { color: #7c3aed; }
        
        .card-red    { background: #fff1f2; border-color: #fecdd3; }
        .card-red    .stat-card-label  { color: #be123c; }
        .card-red    .stat-card-value  { color: #881337; }
        .card-red    .stat-card-sub    { color: #e11d48; }
        
        .panel {
            background: #ffffff;
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
            color: #111827;
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
        .fill-green  { background: #16a34a; }
        .fill-blue   { background: #1a56db; }
        .fill-purple { background: #7c3aed; }
        
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
        .badge-green  { background: #dcfce7; color: #15803d; }
        .badge-blue   { background: #dbeafe; color: #1d4ed8; }
        .badge-yellow { background: #fef9c3; color: #a16207; }
        
        .mono { font-family: 'JetBrains Mono', monospace; }
        .fw6 { font-weight: 600; }
        .fw7 { font-weight: 700; }
        .text-brand { color: #1a56db; }
        
        .action-link {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 600;
            color: #1a56db;
            text-decoration: none;
            transition: all 0.15s;
        }
        .action-link:hover { color: #1e40af; text-decoration: underline; }
        
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
    </style>

    <div class="dash-wrap" style="padding:0 0 20px">

        {{-- Date Filter --}}
        <div class="filter-bar">
            <form method="GET" action="{{ route('reports.sales') }}" style="display:flex;align-items:flex-end;gap:14px;flex-wrap:wrap;width:100%">
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
        </div>

        {{-- Summary Cards --}}
        <div class="stat-grid">
            <div class="stat-card card-blue">
                <div class="stat-card-label">Total Revenue</div>
                <div class="stat-card-value">KES {{ number_format($summary->total_revenue ?? 0, 2) }}</div>
                <div class="stat-card-sub">{{ $summary->total_transactions ?? 0 }} transactions</div>
            </div>
            <div class="stat-card card-teal">
                <div class="stat-card-label">Average Sale</div>
                <div class="stat-card-value">KES {{ number_format($summary->avg_sale ?? 0, 2) }}</div>
                <div class="stat-card-sub">Per transaction</div>
            </div>
            <div class="stat-card card-indigo">
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

        {{-- Payment Breakdown + Top Products --}}
        <div class="two-col">

            {{-- Payment Methods --}}
            <div class="panel panel-padded">
                <div class="panel-title" style="margin-bottom:16px">Payment Methods</div>
                @php
                    $totalRev = $summary->total_revenue ?? 1;
                    $methods = [
                        ['label' => 'Cash',   'amount' => $summary->cash_total   ?? 0, 'fill' => 'fill-green'],
                        ['label' => 'Card',   'amount' => $summary->card_total   ?? 0, 'fill' => 'fill-blue'],
                        ['label' => 'M-Pesa', 'amount' => $summary->mobile_total ?? 0, 'fill' => 'fill-purple'],
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

            {{-- Top Products --}}
            <div class="panel panel-padded">
                <div class="panel-title" style="margin-bottom:16px">Top Selling Products</div>
                @forelse($topProducts as $product)
                    <div class="top-product-row">
                        <div>
                            <div style="font-size:13px;font-weight:600;color:#111827;font-family:'Outfit',sans-serif">{{ $product->product_name }}</div>
                            <div style="font-size:11px;color:#9ca3af;margin-top:2px">{{ $product->total_qty }} units sold</div>
                        </div>
                        <span class="mono fw6" style="color:#1a56db">KES {{ number_format($product->total_revenue, 2) }}</span>
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
                                    <td style="font-size:12px;color:#374151">
                                        {{ \Carbon\Carbon::parse($day->date)->format('D, d M Y') }}
                                    </td>
                                    <td class="mono" style="font-size:12px;color:#6b7280">{{ $day->transactions }}</td>
                                    <td class="mono fw6" style="color:#111827">KES {{ number_format($day->revenue, 2) }}</td>
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
                    <table class="data-table" style="min-width:900px">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Date & Time</th>
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
                                    <td style="font-size:12px;color:#6b7280">{{ $sale->customer->name ?? 'Walk-in' }}</td>
                                    <td style="font-size:12px;color:#6b7280">{{ $sale->user->name ?? '—' }}</td>
                                    <td style="font-size:12px;color:#6b7280">{{ $sale->items->count() }} item(s)</td>
                                    <td>
                                        <span class="badge {{ $payBadge }}">{{ ucfirst($sale->payment_method) }}</span>
                                    </td>
                                    <td class="mono fw6" style="color:#111827">KES {{ number_format($sale->total, 2) }}</td>
                                    <td>
                                        <a href="{{ route('pos.receipt', $sale) }}" target="_blank" class="action-link">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($sales->hasPages())
                    <div style="padding:14px 20px;border-top:1px solid #f1f3f8">
                        {{ $sales->links() }}
                    </div>
                @endif
            @else
                <div class="empty-state" style="padding:60px">No sales found for this period.</div>
            @endif
        </div>

    </div>
</x-app-layout>