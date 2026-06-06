<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Inventory</p>
                <h1 style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:700;color:#02182F;line-height:1.2">
                    Products
                </h1>
            </div>
            <div style="display:flex;align-items:center;gap:10px">
                <a href="{{ route('products.import') }}"
                   style="background:transparent;color:#6b7280;padding:10px 18px;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;font-family:'Outfit',sans-serif;border:1.5px solid #e4e7ef;transition:all .15s"
                   onmouseover="this.style.borderColor='#03A737';this.style.color='#03A737';this.style.background='#e6f7eb'"
                   onmouseout="this.style.borderColor='#e4e7ef';this.style.color='#6b7280';this.style.background='transparent'">
                    Import CSV
                </a>
                <a href="{{ route('products.create') }}"
                   style="background:#03A737;color:#FFFEFE;padding:10px 22px;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;font-family:'Outfit',sans-serif;letter-spacing:.01em;box-shadow:0 2px 8px rgba(3,167,55,.25);transition:all .15s"
                   onmouseover="this.style.background='#028a2e';this.style.boxShadow='0 4px 12px rgba(3,167,55,.35)'"
                   onmouseout="this.style.background='#03A737';this.style.boxShadow='0 2px 8px rgba(3,167,55,.25)'">
                    + Add Product
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
        
        .panel {
            background: #FFFEFE;
            border: 1px solid #e4e7ef;
            border-radius: 14px;
            overflow: hidden;
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
        
        .product-name-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .product-icon-sm {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .product-icon-sm svg {
            width: 18px;
            height: 18px;
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
        .badge-green  { background: #e6f7eb; color: #028a2e; }
        .badge-gray   { background: #f3f4f6; color: #6b7280; }
        .badge-red    { background: #fee2e2; color: #b91c1c; }
        .badge-yellow { background: #fef9c3; color: #a16207; }
        
        .mono { font-family: 'JetBrains Mono', monospace; font-size: 13px; }
        .text-brand { color: #03A737; }
        .fw6 { font-weight: 600; }
        .fw7 { font-weight: 700; }
        
        .action-link {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s;
        }
        .action-link.edit { color: #03A737; }
        .action-link.edit:hover { color: #028a2e; text-decoration: underline; }
        .action-link.delete { color: #dc2626; background: none; border: none; cursor: pointer; font-family: 'Outfit', sans-serif; font-size: 12px; font-weight: 600; }
        .action-link.delete:hover { color: #b91c1c; text-decoration: underline; }
        
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
        
        .pagination-wrap {
            padding: 14px 20px;
            border-top: 1px solid #f1f3f8;
        }
        
        .stock-indicator {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .stock-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }
        .stock-dot.green { background: #03A737; }
        .stock-dot.yellow { background: #eab308; }
        .stock-dot.red { background: #ef4444; }
        .stock-dot.gray { background: #d1d5db; }
    </style>

    <div class="dash-wrap" style="display:flex;flex-direction:column;gap:20px;padding:0 0 20px">

        {{-- Success Message --}}
        @if(session('success'))
            <div style="background:#e6f7eb;border:1px solid #b8e6c4;border-radius:10px;padding:14px 18px;font-family:'Outfit',sans-serif;font-size:13px;color:#028a2e;font-weight:500;display:flex;align-items:center;gap:10px">
                <svg width="16" height="16" fill="none" stroke="#03A737" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Products Table --}}
        <div class="panel">
            @if($products->count())
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Cost</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th style="width:100px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $iconColors = [
                                '#e6f7eb' => '#03A737',
                                '#edf3fd' => '#3D7BE7',
                                '#f0f2f5' => '#02182F',
                                '#fff7ed' => '#d97706',
                                '#fffbeb' => '#a16207',
                                '#fef2f2' => '#dc2626',
                            ];
                            $bgColors = array_keys($iconColors);
                        @endphp
                        @foreach($products as $product)
                            @php
                                $bgColor = $bgColors[$product->id % count($bgColors)];
                                $iconColor = $iconColors[$bgColor];
                            @endphp
                            <tr>
                                <td>
                                    <div class="product-name-cell">
                                        <div class="product-icon-sm" style="background:{{ $bgColor }}">
                                            <svg fill="none" stroke="{{ $iconColor }}" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="fw6" style="color:#02182F">{{ $product->name }}</div>
                                            @if($product->barcode)
                                                <div class="mono" style="font-size:11px;color:#9ca3af">{{ $product->barcode }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="mono" style="font-size:12px;color:#6b7280">{{ $product->sku ?? '—' }}</td>
                                <td style="font-size:12px;color:#6b7280">{{ $product->category->name ?? '—' }}</td>
                                <td class="mono fw6 text-brand">KES {{ number_format($product->price, 2) }}</td>
                                <td class="mono" style="font-size:12px;color:#6b7280">
                                    {{ $product->cost_price ? 'KES ' . number_format($product->cost_price, 2) : '—' }}
                                </td>
                                <td>
                                    @if($product->track_stock)
                                        @php
                                            $stockClass = 'green';
                                            if ($product->stock_qty <= 0) $stockClass = 'red';
                                            elseif ($product->isLowStock()) $stockClass = 'yellow';
                                        @endphp
                                        <div class="stock-indicator">
                                            <span class="stock-dot {{ $stockClass }}"></span>
                                            <span class="fw6" style="color:{{ $stockClass === 'red' ? '#dc2626' : ($stockClass === 'yellow' ? '#a16207' : '#02182F') }}">
                                                {{ $product->stock_qty }}
                                            </span>
                                            @if($product->isLowStock() && $product->stock_qty > 0)
                                                <span style="font-size:10px;color:#a16207;font-weight:500">Low</span>
                                            @endif
                                        </div>
                                    @else
                                        <span style="font-size:11px;color:#9ca3af">Not tracked</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $product->status === 'active' ? 'badge-green' : 'badge-gray' }}">
                                        {{ ucfirst($product->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display:flex;align-items:center;gap:12px">
                                        <a href="{{ route('products.edit', $product) }}" class="action-link edit">Edit</a>
                                        <form method="POST" action="{{ route('products.destroy', $product) }}"
                                              onsubmit="return confirm('Delete this product?')" style="display:inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-link delete">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                @if($products->hasPages())
                    <div class="pagination-wrap">
                        {{ $products->links() }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <p style="font-family:'Outfit',sans-serif;font-size:14px;color:#9ca3af;margin-bottom:6px">No products yet</p>
                    <a href="{{ route('products.create') }}" 
                       style="font-family:'Outfit',sans-serif;font-size:13px;font-weight:600;color:#03A737;text-decoration:none">
                        Add your first product →
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>