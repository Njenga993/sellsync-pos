<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('purchase-orders.index') }}" 
                   style="display:flex;align-items:center;gap:6px;color:#9ca3af;font-size:13px;text-decoration:none;padding:6px 10px;border-radius:8px;border:1px solid #e4e7ef;font-family:'Outfit',sans-serif;transition:all .15s"
                   onmouseover="this.style.borderColor='#03A737';this.style.color='#03A737';this.style.background='#e6f7eb'"
                   onmouseout="this.style.borderColor='#e4e7ef';this.style.color='#9ca3af';this.style.background='transparent'">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Purchase Order</p>
                    <div style="display:flex;align-items:center;gap:12px">
                        <h1 style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:700;color:#02182F;line-height:1.2">
                            {{ $purchaseOrder->po_number }}
                        </h1>
                        @php
                            $statusBadge = match($purchaseOrder->status) {
                                'draft'     => 'badge-gray',
                                'ordered'   => 'badge-blue',
                                'partial'   => 'badge-yellow',
                                'received'  => 'badge-green',
                                'cancelled' => 'badge-red',
                                default     => 'badge-gray',
                            };
                        @endphp
                        <span class="badge {{ $statusBadge }}">
                            {{ ucfirst($purchaseOrder->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
        
        .dash-wrap { display: flex; flex-direction: column; gap: 16px; }
        
        .panel {
            background: #FFFEFE;
            border: 1px solid #e4e7ef;
            border-radius: 14px;
            overflow: hidden;
        }
        .panel-padded { padding: 24px; }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        @media (max-width: 768px) { .info-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 480px) { .info-grid { grid-template-columns: 1fr; } }
        
        .info-block-label {
            font-family: 'Outfit', sans-serif;
            font-size: 10px;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 4px;
        }
        .info-block-value {
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            font-weight: 600;
            color: #02182F;
        }
        .info-block-sub {
            font-size: 11px;
            color: #6b7280;
            margin-top: 2px;
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
        .badge-red    { background: #fee2e2; color: #b91c1c; }
        .badge-gray   { background: #f3f4f6; color: #6b7280; }
        
        .mono { font-family: 'JetBrains Mono', monospace; }
        .fw6 { font-weight: 600; }
        .fw7 { font-weight: 700; }
        
        .btn-receive {
            background: #03A737;
            color: #FFFEFE;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            font-family: 'Outfit', sans-serif;
            border: none;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(3, 167, 55, 0.25);
            transition: all 0.15s;
        }
        .btn-receive:hover {
            background: #028a2e;
            box-shadow: 0 4px 12px rgba(3, 167, 55, 0.35);
            transform: translateY(-1px);
        }
        
        .qty-received-full { color: #03A737; font-weight: 700; }
        .qty-received-partial { color: #d97706; }
        
        .receive-input {
            width: 70px;
            padding: 6px 8px;
            border: 1.5px solid #e4e7ef;
            border-radius: 8px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            color: #02182F;
            background: #fafbff;
            text-align: center;
            transition: all 0.15s;
            outline: none;
        }
        .receive-input:focus {
            border-color: #03A737;
            box-shadow: 0 0 0 3px rgba(3, 167, 55, 0.08);
        }
        
        .completed-banner {
            background: #e6f7eb;
            border: 1px solid #b8e6c4;
            border-radius: 14px;
            padding: 18px 22px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            color: #028a2e;
            font-weight: 500;
        }
    </style>

    <div class="dash-wrap" style="padding:0 0 20px">

        {{-- Flash Message --}}
        @if(session('success'))
            <div style="background:#e6f7eb;border:1px solid #b8e6c4;border-radius:10px;padding:14px 18px;font-family:'Outfit',sans-serif;font-size:13px;color:#028a2e;font-weight:500;display:flex;align-items:center;gap:10px">
                <svg width="16" height="16" fill="none" stroke="#03A737" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Completed Banner --}}
        @if($purchaseOrder->status === 'received')
            <div class="completed-banner">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                All items received on {{ $purchaseOrder->received_date?->format('d M Y') }}. Stock has been automatically updated.
            </div>
        @endif

        {{-- PO Info --}}
        <div class="panel panel-padded">
            <div class="info-grid">
                <div>
                    <div class="info-block-label">Supplier</div>
                    <div class="info-block-value">{{ $purchaseOrder->supplier->name }}</div>
                    @if($purchaseOrder->supplier->phone)
                        <div class="info-block-sub">{{ $purchaseOrder->supplier->phone }}</div>
                    @endif
                </div>
                <div>
                    <div class="info-block-label">Order Date</div>
                    <div class="info-block-value">{{ $purchaseOrder->order_date->format('d M Y') }}</div>
                </div>
                <div>
                    <div class="info-block-label">Expected Date</div>
                    <div class="info-block-value">{{ $purchaseOrder->expected_date?->format('d M Y') ?? '—' }}</div>
                </div>
                <div>
                    <div class="info-block-label">Created By</div>
                    <div class="info-block-value">{{ $purchaseOrder->user->name }}</div>
                </div>
            </div>
            @if($purchaseOrder->notes)
                <div style="margin-top:16px;padding-top:16px;border-top:1px solid #f1f3f8">
                    <div class="info-block-label" style="margin-bottom:4px">Notes</div>
                    <p style="font-size:13px;color:#6b7280;font-family:'Outfit',sans-serif">{{ $purchaseOrder->notes }}</p>
                </div>
            @endif
        </div>

        {{-- Items + Receive Form --}}
        <div class="panel">
            <div style="padding:18px 22px;border-bottom:1px solid #f1f3f8">
                <span style="font-family:'Outfit',sans-serif;font-size:14px;font-weight:700;color:#02182F">Order Items</span>
            </div>

            @if(in_array($purchaseOrder->status, ['ordered', 'partial']))
                <form method="POST" action="{{ route('purchase-orders.receive', $purchaseOrder) }}">
                    @csrf
            @endif

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Ordered</th>
                        <th>Received</th>
                        <th>Unit Cost</th>
                        <th>Subtotal</th>
                        @if(in_array($purchaseOrder->status, ['ordered', 'partial']))
                            <th>Receive Now</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchaseOrder->items as $i => $item)
                        @php
                            $remaining = $item->qty_ordered - $item->qty_received;
                            $receivedClass = $item->qty_received >= $item->qty_ordered ? 'qty-received-full' : 'qty-received-partial';
                        @endphp
                        <tr>
                            <td class="fw6" style="color:#02182F">{{ $item->product_name }}</td>
                            <td class="mono" style="font-size:12px;color:#6b7280">{{ $item->qty_ordered }}</td>
                            <td>
                                <span class="mono fw7 {{ $receivedClass }}">{{ $item->qty_received }}</span>
                                @if($remaining > 0 && $item->qty_received > 0)
                                    <span style="font-size:10px;color:#9ca3af;margin-left:4px">
                                        ({{ $remaining }} pending)
                                    </span>
                                @endif
                            </td>
                            <td class="mono" style="font-size:12px;color:#6b7280">KES {{ number_format($item->unit_cost, 2) }}</td>
                            <td class="mono fw6" style="color:#02182F">KES {{ number_format($item->subtotal, 2) }}</td>
                            @if(in_array($purchaseOrder->status, ['ordered', 'partial']))
                                <td>
                                    <input type="hidden" name="items[{{ $i }}][id]" value="{{ $item->id }}" />
                                    <input type="number" name="items[{{ $i }}][qty_received]"
                                        min="0" max="{{ $remaining }}" value="0"
                                        class="receive-input" />
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="padding:16px 22px;border-top:1px solid #f1f3f8;display:flex;justify-content:space-between;align-items:center">
                <div>
                    <span style="font-size:13px;color:#6b7280;font-family:'Outfit',sans-serif">Total: </span>
                    <span class="mono fw7" style="font-size:16px;color:#02182F">KES {{ number_format($purchaseOrder->total, 2) }}</span>
                </div>
                @if(in_array($purchaseOrder->status, ['ordered', 'partial']))
                    <button type="submit" class="btn-receive">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:4px">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Record Receipt
                    </button>
                @endif
            </div>

            @if(in_array($purchaseOrder->status, ['ordered', 'partial']))
                </form>
            @endif
        </div>

    </div>
</x-app-layout>