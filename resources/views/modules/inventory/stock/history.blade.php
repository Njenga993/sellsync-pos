<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('stock.index') }}" 
               style="display:flex;align-items:center;gap:6px;color:#9ca3af;font-size:13px;text-decoration:none;padding:6px 10px;border-radius:8px;border:1px solid #e4e7ef;font-family:'Outfit',sans-serif;transition:all .15s"
               onmouseover="this.style.borderColor='#1a56db';this.style.color='#1a56db';this.style.background='#eff4ff'"
               onmouseout="this.style.borderColor='#e4e7ef';this.style.color='#9ca3af';this.style.background='transparent'">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
            </a>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Inventory</p>
                <h1 style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:700;color:#111827;line-height:1.2">
                    Stock Movement History
                </h1>
            </div>
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
        
        .filter-select {
            padding: 8px 12px;
            border: 1.5px solid #e4e7ef;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            color: #111827;
            background: #fafbff;
            transition: all 0.15s;
            outline: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' fill='none' stroke='%239ca3af' stroke-width='2' viewBox='0 0 24 24'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            padding-right: 32px;
            cursor: pointer;
            min-width: 160px;
        }
        .filter-select:focus {
            border-color: #1a56db;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.08);
        }
        
        .btn-filter {
            padding: 8px 18px;
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
            padding: 8px 18px;
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
            white-space: nowrap;
        }
        .data-table td {
            padding: 14px 20px;
            font-size: 13px;
            color: #374151;
            border-bottom: 1px solid #f8f9fb;
            white-space: nowrap;
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
        .badge-red    { background: #fee2e2; color: #b91c1c; }
        .badge-blue   { background: #dbeafe; color: #1d4ed8; }
        .badge-yellow { background: #fef9c3; color: #a16207; }
        .badge-purple { background: #f3e8ff; color: #7c3aed; }
        
        .mono { font-family: 'JetBrains Mono', monospace; }
        .fw6 { font-weight: 600; }
        .fw7 { font-weight: 700; }
        .text-brand { color: #1a56db; }
        
        .qty-positive { color: #16a34a; }
        .qty-negative { color: #dc2626; }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }
        .empty-state svg {
            width: 48px;
            height: 48px;
            color: #d1d5db;
            margin-bottom: 16px;
        }
    </style>

    <div class="dash-wrap" style="padding:0 0 20px">

        {{-- Filters --}}
        <div class="filter-bar">
            <form method="GET" action="{{ route('stock.history') }}" style="display:flex;align-items:flex-end;gap:14px;flex-wrap:wrap;width:100%">
                <div class="filter-group">
                    <span class="filter-label">Product</span>
                    <select name="product_id" class="filter-select">
                        <option value="">All Products</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}"
                                {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <span class="filter-label">Movement Type</span>
                    <select name="type" class="filter-select">
                        <option value="">All Types</option>
                        <option value="stock_in"   {{ request('type') === 'stock_in'   ? 'selected' : '' }}>Stock In</option>
                        <option value="stock_out"  {{ request('type') === 'stock_out'  ? 'selected' : '' }}>Stock Out</option>
                        <option value="adjustment" {{ request('type') === 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                        <option value="sale"       {{ request('type') === 'sale'       ? 'selected' : '' }}>Sale</option>
                        <option value="return"     {{ request('type') === 'return'     ? 'selected' : '' }}>Return</option>
                    </select>
                </div>
                <button type="submit" class="btn-filter">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:4px">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filter
                </button>
                <a href="{{ route('stock.history') }}" class="btn-reset">Reset</a>
            </form>
        </div>

        {{-- Movement Table --}}
        <div class="panel">
            <div class="panel-header">
                <span class="panel-title">Stock Movements</span>
                <span class="panel-meta">{{ $movements->total() }} records</span>
            </div>
            @if($movements->count())
                <div style="overflow-x:auto">
                    <table class="data-table" style="min-width:900px">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Product</th>
                                <th>Type</th>
                                <th>Qty</th>
                                <th>Before</th>
                                <th>After</th>
                                <th>Reference</th>
                                <th>By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($movements as $movement)
                                @php
                                    $typeBadge = match($movement->type) {
                                        'stock_in'   => 'badge-green',
                                        'stock_out'  => 'badge-red',
                                        'adjustment' => 'badge-blue',
                                        'sale'       => 'badge-yellow',
                                        'return'     => 'badge-purple',
                                        default      => 'badge-blue',
                                    };
                                @endphp
                                <tr>
                                    <td>
                                        <div style="font-size:12px;color:#374151">{{ $movement->created_at->format('d M Y') }}</div>
                                        <div class="mono" style="font-size:11px;color:#9ca3af">{{ $movement->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        <div class="fw6" style="color:#111827">
                                            {{ $movement->product->name ?? '—' }}
                                        </div>
                                        @if($movement->product->sku ?? false)
                                            <div class="mono" style="font-size:11px;color:#9ca3af">
                                                {{ $movement->product->sku }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $typeBadge }}">
                                            {{ $movement->type_label }}
                                        </span>
                                    </td>
                                    <td class="mono fw7" style="font-size:14px">
                                        <span class="{{ $movement->qty > 0 ? 'qty-positive' : 'qty-negative' }}">
                                            {{ $movement->qty > 0 ? '+' : '' }}{{ $movement->qty }}
                                        </span>
                                    </td>
                                    <td class="mono" style="font-size:12px;color:#6b7280">{{ $movement->before_qty }}</td>
                                    <td class="mono fw6" style="font-size:13px;color:#111827">{{ $movement->after_qty }}</td>
                                    <td>
                                        <div style="font-size:12px;color:#374151">{{ $movement->reference ?? '—' }}</div>
                                        @if($movement->notes)
                                            <div style="font-size:11px;color:#9ca3af;max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" 
                                                 title="{{ $movement->notes }}">
                                                {{ $movement->notes }}
                                            </div>
                                        @endif
                                    </td>
                                    <td style="font-size:12px;color:#6b7280">
                                        {{ $movement->user->name ?? '—' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($movements->hasPages())
                    <div style="padding:14px 20px;border-top:1px solid #f1f3f8">
                        {{ $movements->links() }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    <p style="font-family:'Outfit',sans-serif;font-size:14px;color:#9ca3af;margin-bottom:4px">No stock movements recorded yet</p>
                    <p style="font-family:'Outfit',sans-serif;font-size:12px;color:#d1d5db">Stock adjustments will appear here</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>