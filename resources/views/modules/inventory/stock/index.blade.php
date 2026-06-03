<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Inventory</p>
                <h1 style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:700;color:#111827;line-height:1.2">
                    Stock Management
                </h1>
            </div>
            <a href="{{ route('stock.history') }}"
               style="background:transparent;color:#6b7280;padding:10px 20px;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;font-family:'Outfit',sans-serif;border:1.5px solid #e4e7ef;transition:all .15s">
                View History
            </a>
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
            position: relative;
            overflow: hidden;
            border: 1px solid transparent;
        }
        .stat-card-label {
            font-family: 'Outfit', sans-serif;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            margin-bottom: 10px;
        }
        .stat-card-value {
            font-family: 'JetBrains Mono', monospace;
            font-size: 26px;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 6px;
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
        .stat-icon svg { width: 18px; height: 18px; }
        
        .card-blue   { background: #eff4ff; border-color: #c7d7fb; }
        .card-blue   .stat-card-label  { color: #3b5fd6; }
        .card-blue   .stat-card-value  { color: #1a3fad; }
        .card-blue   .stat-card-sub    { color: #6b85d6; }
        .card-blue   .stat-icon        { background: #dbeafe; }
        .card-blue   .stat-icon svg    { color: #1a56db; }
        
        .card-yellow { background: #fffbeb; border-color: #fde68a; }
        .card-yellow .stat-card-label  { color: #a16207; }
        .card-yellow .stat-card-value  { color: #854d0e; }
        .card-yellow .stat-card-sub    { color: #ca8a04; }
        .card-yellow .stat-icon        { background: #fef3c7; }
        .card-yellow .stat-icon svg    { color: #d97706; }
        
        .card-red    { background: #fff1f2; border-color: #fecdd3; }
        .card-red    .stat-card-label  { color: #be123c; }
        .card-red    .stat-card-value  { color: #881337; }
        .card-red    .stat-card-sub    { color: #e11d48; }
        .card-red    .stat-icon        { background: #ffe4e6; }
        .card-red    .stat-icon svg    { color: #e11d48; }
        
        .card-teal   { background: #f0fdfa; border-color: #99f6e4; }
        .card-teal   .stat-card-label  { color: #0f766e; }
        .card-teal   .stat-card-value  { color: #134e4a; }
        .card-teal   .stat-card-sub    { color: #2dd4bf; }
        .card-teal   .stat-icon        { background: #ccfbf1; }
        .card-teal   .stat-icon svg    { color: #0d9488; }
        
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
        
        .two-col { display: grid; grid-template-columns: 360px 1fr; gap: 16px; }
        @media (max-width: 900px) { .two-col { grid-template-columns: 1fr; } }
        
        .form-panel {
            background: #ffffff;
            border: 1px solid #e4e7ef;
            border-radius: 14px;
            padding: 24px;
        }
        
        .section-title {
            font-family: 'Outfit', sans-serif;
            font-size: 10px;
            font-weight: 700;
            color: #c4c9d6;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f1f3f8;
        }
        
        .form-group {
            margin-bottom: 14px;
        }
        
        .form-label {
            font-family: 'Outfit', sans-serif;
            font-size: 11px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 6px;
            display: block;
        }
        
        .form-input {
            width: 100%;
            padding: 9px 12px;
            border: 1.5px solid #e4e7ef;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            color: #111827;
            background: #fafbff;
            transition: all 0.15s ease;
            outline: none;
        }
        .form-input:focus {
            border-color: #1a56db;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.08);
        }
        .form-input::placeholder { color: #c4c9d6; }
        
        .form-select {
            width: 100%;
            padding: 9px 12px;
            border: 1.5px solid #e4e7ef;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            color: #111827;
            background: #fafbff;
            transition: all 0.15s ease;
            outline: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg width='12' height='8' fill='none' stroke='%239ca3af' stroke-width='2' viewBox='0 0 24 24'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
            cursor: pointer;
        }
        .form-select:focus {
            border-color: #1a56db;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.08);
        }
        
        .form-textarea {
            width: 100%;
            padding: 9px 12px;
            border: 1.5px solid #e4e7ef;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            color: #111827;
            background: #fafbff;
            transition: all 0.15s ease;
            outline: none;
            resize: vertical;
            min-height: 60px;
        }
        .form-textarea:focus {
            border-color: #1a56db;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.08);
        }
        
        .btn-primary {
            width: 100%;
            padding: 11px 20px;
            background: #1a56db;
            color: white;
            border: none;
            border-radius: 10px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.01em;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(26, 86, 219, 0.25);
            transition: all 0.15s;
        }
        .btn-primary:hover {
            background: #1e40af;
            box-shadow: 0 4px 12px rgba(26, 86, 219, 0.35);
            transform: translateY(-1px);
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
        
        .stock-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 6px;
        }
        .stock-dot.green  { background: #22c55e; }
        .stock-dot.yellow { background: #eab308; }
        .stock-dot.red    { background: #ef4444; }
        
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
        
        .mono { font-family: 'JetBrains Mono', monospace; }
        .fw6 { font-weight: 600; }
        .fw7 { font-weight: 700; }
        
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

        {{-- Flash Messages --}}
        @if(session('success'))
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:14px 18px;font-family:'Outfit',sans-serif;font-size:13px;color:#15803d;font-weight:500;display:flex;align-items:center;gap:10px">
                <svg width="16" height="16" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="background:#fef2f2;border:1px solid #fecdd3;border-radius:10px;padding:14px 18px;font-family:'Outfit',sans-serif;font-size:13px;color:#b91c1c;font-weight:500;display:flex;align-items:center;gap:10px">
                <svg width="16" height="16" fill="none" stroke="#dc2626" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- Summary Cards --}}
        <div class="stat-grid">
            <div class="stat-card card-blue">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div class="stat-card-label">Tracked Products</div>
                <div class="stat-card-value">{{ $summary['total_products'] }}</div>
                <div class="stat-card-sub">Products with stock tracking</div>
            </div>

            <div class="stat-card card-yellow">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="stat-card-label">Low Stock</div>
                <div class="stat-card-value">{{ $summary['low_stock'] }}</div>
                <div class="stat-card-sub">Need reordering soon</div>
            </div>

            <div class="stat-card card-red">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                </div>
                <div class="stat-card-label">Out of Stock</div>
                <div class="stat-card-value">{{ $summary['out_of_stock'] }}</div>
                <div class="stat-card-sub">Urgent attention needed</div>
            </div>

            <div class="stat-card card-teal">
                <div class="stat-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </div>
                <div class="stat-card-label">Total Movements</div>
                <div class="stat-card-value">{{ $summary['total_movements'] }}</div>
                <div class="stat-card-sub">Stock changes recorded</div>
            </div>
        </div>

        {{-- Two Column Layout --}}
        <div class="two-col">

            {{-- Stock Adjust Form --}}
            <div class="form-panel" style="align-self:start">
                <div class="section-title">Adjust Stock</div>
                <form method="POST" action="{{ route('stock.adjust') }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label" for="product-select">Product *</label>
                        <select name="product_id" id="product-select" required class="form-select">
                            <option value="">Select product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->name }} (Stock: {{ $product->stock_qty }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="type-select">Movement Type *</label>
                        <select name="type" id="type-select" required class="form-select">
                            <option value="stock_in">Stock In (Add stock)</option>
                            <option value="stock_out">Stock Out (Remove stock)</option>
                            <option value="adjustment">Adjustment (Set exact qty)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="qty-input">Quantity *</label>
                        <input type="number" name="qty" id="qty-input" min="1" required
                            class="form-input" placeholder="Enter quantity" />
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="ref-input">Reference (optional)</label>
                        <input type="text" name="reference" id="ref-input"
                            class="form-input" placeholder="e.g. PO-001, Supplier name" />
                    </div>

                    <div class="form-group" style="margin-bottom:18px">
                        <label class="form-label" for="notes-input">Notes (optional)</label>
                        <textarea name="notes" id="notes-input" rows="2" class="form-textarea"
                            placeholder="Reason for adjustment"></textarea>
                    </div>

                    <button type="submit" class="btn-primary">Update Stock</button>
                </form>
            </div>

            {{-- Stock List --}}
            <div class="panel">
                <div class="panel-header">
                    <span class="panel-title">Current Stock Levels</span>
                </div>
                @if($products->count())
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Stock</th>
                                <th>Alert At</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                @php
                                    $stockStatus = 'green';
                                    if ($product->stock_qty <= 0) $stockStatus = 'red';
                                    elseif ($product->isLowStock()) $stockStatus = 'yellow';
                                @endphp
                                <tr>
                                    <td>
                                        <div class="fw6" style="color:#111827">{{ $product->name }}</div>
                                        @if($product->sku)
                                            <div class="mono" style="font-size:11px;color:#9ca3af">{{ $product->sku }}</div>
                                        @endif
                                    </td>
                                    <td style="font-size:12px;color:#6b7280">
                                        {{ $product->category->name ?? '—' }}
                                    </td>
                                    <td>
                                        <span class="mono fw7" style="font-size:15px;color:{{ $stockStatus === 'red' ? '#dc2626' : ($stockStatus === 'yellow' ? '#d97706' : '#111827') }}">
                                            {{ $product->stock_qty }}
                                        </span>
                                    </td>
                                    <td class="mono" style="font-size:12px;color:#6b7280">{{ $product->low_stock_alert }}</td>
                                    <td>
                                        <span class="badge {{ $stockStatus === 'red' ? 'badge-red' : ($stockStatus === 'yellow' ? 'badge-yellow' : 'badge-green') }}">
                                            <span class="stock-dot {{ $stockStatus }}" style="margin-right:4px"></span>
                                            {{ $stockStatus === 'red' ? 'Out of Stock' : ($stockStatus === 'yellow' ? 'Low Stock' : 'In Stock') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($products->hasPages())
                        <div style="padding:14px 20px;border-top:1px solid #f1f3f8">
                            {{ $products->links() }}
                        </div>
                    @endif
                @else
                    <div class="empty-state">
                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <p style="font-family:'Outfit',sans-serif;font-size:14px;color:#9ca3af;margin-bottom:6px">No tracked products found</p>
                        <a href="{{ route('products.create') }}" 
                           style="font-family:'Outfit',sans-serif;font-size:13px;font-weight:600;color:#1a56db;text-decoration:none">
                            Add products with stock tracking →
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>