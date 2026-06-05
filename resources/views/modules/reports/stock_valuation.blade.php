<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Finance</p>
                <h1 style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:700;color:#111827;line-height:1.2">
                    Stock Valuation Report
                </h1>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('reports.profit-loss') }}"
                   style="background:transparent;color:#6b7280;padding:9px 16px;border-radius:10px;font-size:12px;font-weight:600;text-decoration:none;font-family:'Outfit',sans-serif;border:1.5px solid #e4e7ef;transition:all .15s">
                    P&L Report
                </a>
                <a href="{{ route('reports.cashier-performance') }}"
                   style="background:transparent;color:#6b7280;padding:9px 16px;border-radius:10px;font-size:12px;font-weight:600;text-decoration:none;font-family:'Outfit',sans-serif;border:1.5px solid #e4e7ef;transition:all .15s">
                    Cashier Performance
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
        
        .dash-wrap { display: flex; flex-direction: column; gap: 20px; }
        
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
        
        .card-gray   { background: #f9fafb; border-color: #e5e7eb; }
        .card-gray   .stat-card-label  { color: #6b7280; }
        .card-gray   .stat-card-value  { color: #111827; }
        .card-gray   .stat-card-sub    { color: #9ca3af; }
        
        .card-blue   { background: #eff4ff; border-color: #c7d7fb; }
        .card-blue   .stat-card-label  { color: #3b5fd6; }
        .card-blue   .stat-card-value  { color: #1a3fad; }
        .card-blue   .stat-card-sub    { color: #6b85d6; }
        
        .card-green  { background: #f0fdf4; border-color: #bbf7d0; }
        .card-green  .stat-card-label  { color: #15803d; }
        .card-green  .stat-card-value  { color: #14532d; }
        .card-green  .stat-card-sub    { color: #22c55e; }
        
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
        .badge-yellow { background: #fef9c3; color: #a16207; }
        .badge-red    { background: #fee2e2; color: #b91c1c; }
        
        .margin-badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 600;
            font-family: 'JetBrains Mono', monospace;
        }
        .margin-high   { background: #dcfce7; color: #15803d; }
        .margin-medium { background: #fef9c3; color: #a16207; }
        .margin-low    { background: #fee2e2; color: #b91c1c; }
        
        .stock-dot {
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            margin-right: 6px;
        }
        .stock-dot.green  { background: #22c55e; }
        .stock-dot.yellow { background: #eab308; }
        .stock-dot.red    { background: #ef4444; }
        
        .mono { font-family: 'JetBrains Mono', monospace; }
        .fw6 { font-weight: 600; }
        .fw7 { font-weight: 700; }
        .text-brand { color: #1a56db; }
        .text-positive { color: #16a34a; }
        .text-danger { color: #dc2626; }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            color: #9ca3af;
        }
    </style>

    <div class="dash-wrap" style="padding:0 0 20px">

        {{-- Summary Cards --}}
        <div class="stat-grid">
            <div class="stat-card card-gray">
                <div class="stat-card-label">Cost Value</div>
                <div class="stat-card-value">KES {{ number_format($summary['total_cost_value'], 2) }}</div>
                <div class="stat-card-sub">What you paid</div>
            </div>
            <div class="stat-card card-blue">
                <div class="stat-card-label">Retail Value</div>
                <div class="stat-card-value">KES {{ number_format($summary['total_sell_value'], 2) }}</div>
                <div class="stat-card-sub">If all sold at selling price</div>
            </div>
            <div class="stat-card card-green">
                <div class="stat-card-label">Potential Profit</div>
                <div class="stat-card-value">KES {{ number_format($summary['total_potential'], 2) }}</div>
                <div class="stat-card-sub">Retail − cost value</div>
            </div>
            <div class="stat-card card-red">
                <div class="stat-card-label">Stock Alerts</div>
                <div class="stat-card-value">{{ $summary['out_of_stock'] }} / {{ $summary['low_stock'] }}</div>
                <div class="stat-card-sub">Out of stock / Low stock</div>
            </div>
            <a href="{{ route('reports.stock-valuation.export', request()->query()) }}" 
   style="background:transparent;color:#6b7280;padding:8px 16px;border-radius:10px;font-size:12px;font-weight:600;text-decoration:none;font-family:'Outfit',sans-serif;border:1.5px solid #e4e7ef;transition:all .15s;display:inline-flex;align-items:center;gap:6px">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
    </svg>
    Export PDF
</a>
        </div>

        {{-- Valuation by Category --}}
        @if($byCategory->count() > 0)
            <div class="panel">
                <div class="panel-header">
                    <span class="panel-title">Valuation by Category</span>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Products</th>
                            <th>Cost Value</th>
                            <th>Retail Value</th>
                            <th>Margin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($byCategory as $catName => $data)
                            @php
                                $margin = $data['sell_value'] > 0
                                    ? round((($data['sell_value'] - $data['cost_value']) / $data['sell_value']) * 100, 1)
                                    : 0;
                                $marginBadge = $margin >= 30 ? 'margin-high' : ($margin >= 10 ? 'margin-medium' : 'margin-low');
                            @endphp
                            <tr>
                                <td class="fw6" style="color:#111827">{{ $catName ?? 'Uncategorised' }}</td>
                                <td class="mono" style="font-size:12px;color:#6b7280">{{ $data['count'] }}</td>
                                <td class="mono" style="font-size:12px;color:#6b7280">KES {{ number_format($data['cost_value'], 2) }}</td>
                                <td class="mono fw6 text-brand">KES {{ number_format($data['sell_value'], 2) }}</td>
                                <td>
                                    <span class="margin-badge {{ $marginBadge }}">{{ $margin }}%</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- All Products --}}
        <div class="panel">
            <div class="panel-header">
                <span class="panel-title">All Products</span>
                <span class="panel-meta">{{ $products->count() }} tracked products</span>
            </div>
            @if($products->count())
                <div style="overflow-x:auto">
                    <table class="data-table" style="min-width:1000px">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Stock</th>
                                <th>Cost Price</th>
                                <th>Sell Price</th>
                                <th>Cost Value</th>
                                <th>Retail Value</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                @php
                                    $stockStatus = 'green';
                                    $stockBadge = 'badge-green';
                                    $stockLabel = 'In Stock';
                                    if ($product->stock_qty <= 0) {
                                        $stockStatus = 'red';
                                        $stockBadge = 'badge-red';
                                        $stockLabel = 'Out of Stock';
                                    } elseif ($product->isLowStock()) {
                                        $stockStatus = 'yellow';
                                        $stockBadge = 'badge-yellow';
                                        $stockLabel = 'Low Stock';
                                    }
                                @endphp
                                <tr>
                                    <td>
                                        <div class="fw6" style="color:#111827">{{ $product->name }}</div>
                                        @if($product->sku)
                                            <div class="mono" style="font-size:11px;color:#9ca3af">{{ $product->sku }}</div>
                                        @endif
                                    </td>
                                    <td style="font-size:12px;color:#6b7280">{{ $product->category->name ?? '—' }}</td>
                                    <td>
                                        <span class="mono fw7" style="font-size:14px;color:{{ $stockStatus === 'red' ? '#dc2626' : ($stockStatus === 'yellow' ? '#d97706' : '#111827') }}">
                                            {{ $product->stock_qty }}
                                        </span>
                                    </td>
                                    <td class="mono" style="font-size:12px;color:#6b7280">KES {{ number_format($product->cost_price, 2) }}</td>
                                    <td class="mono" style="font-size:12px;color:#374151">KES {{ number_format($product->price, 2) }}</td>
                                    <td class="mono" style="font-size:12px;color:#6b7280">KES {{ number_format($product->cost_value, 2) }}</td>
                                    <td class="mono fw6 text-brand">KES {{ number_format($product->sell_value, 2) }}</td>
                                    <td>
                                        <span class="badge {{ $stockBadge }}">
                                            <span class="stock-dot {{ $stockStatus }}"></span>
                                            {{ $stockLabel }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">No tracked products found.</div>
            @endif
        </div>

    </div>
</x-app-layout>