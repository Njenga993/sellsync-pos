<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Inventory</p>
                <h1 style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:700;color:#02182F;line-height:1.2">
                    Purchase Orders
                </h1>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('suppliers.index') }}"
                   style="background:transparent;color:#6b7280;padding:10px 18px;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;font-family:'Outfit',sans-serif;border:1.5px solid #e4e7ef;transition:all .15s"
                   onmouseover="this.style.borderColor='#03A737';this.style.color='#03A737';this.style.background='#e6f7eb'"
                   onmouseout="this.style.borderColor='#e4e7ef';this.style.color='#6b7280';this.style.background='transparent'">
                    Suppliers
                </a>
                <a href="{{ route('purchase-orders.create') }}"
                   style="background:#03A737;color:#FFFEFE;padding:10px 22px;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;font-family:'Outfit',sans-serif;letter-spacing:.01em;box-shadow:0 2px 8px rgba(3,167,55,.25);transition:all .15s"
                   onmouseover="this.style.background='#028a2e';this.style.boxShadow='0 4px 12px rgba(3,167,55,.35)'"
                   onmouseout="this.style.background='#03A737';this.style.boxShadow='0 2px 8px rgba(3,167,55,.25)'">
                    + New PO
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
        
        .dash-wrap { display: flex; flex-direction: column; gap: 20px; }
        
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
        .text-brand { color: #03A737; }
        
        .action-link {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s;
        }
        .action-link.view { color: #03A737; }
        .action-link.view:hover { color: #028a2e; text-decoration: underline; }
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
    </style>

    <div class="dash-wrap" style="padding:0 0 20px">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div style="background:#e6f7eb;border:1px solid #b8e6c4;border-radius:10px;padding:14px 18px;font-family:'Outfit',sans-serif;font-size:13px;color:#028a2e;font-weight:500;display:flex;align-items:center;gap:10px">
                <svg width="16" height="16" fill="none" stroke="#03A737" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Purchase Orders Table --}}
        <div class="panel">
            @if($orders->count())
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>PO Number</th>
                            <th>Supplier</th>
                            <th>Order Date</th>
                            <th>Expected</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th style="width:100px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            @php
                                $statusBadge = match($order->status) {
                                    'draft'     => 'badge-gray',
                                    'ordered'   => 'badge-blue',
                                    'partial'   => 'badge-yellow',
                                    'received'  => 'badge-green',
                                    'cancelled' => 'badge-red',
                                    default     => 'badge-gray',
                                };
                            @endphp
                            <tr>
                                <td>
                                    <span class="mono fw6 text-brand" style="font-size:12px">{{ $order->po_number }}</span>
                                </td>
                                <td>
                                    <div class="fw6" style="color:#02182F">{{ $order->supplier->name }}</div>
                                    <div style="font-size:11px;color:#9ca3af">{{ $order->user->name }}</div>
                                </td>
                                <td style="font-size:12px;color:#6b7280">
                                    {{ $order->order_date->format('d M Y') }}
                                </td>
                                <td style="font-size:12px;color:#6b7280">
                                    {{ $order->expected_date?->format('d M Y') ?? '—' }}
                                </td>
                                <td style="font-size:12px;color:#6b7280">{{ $order->items_count }} items</td>
                                <td class="mono fw6" style="color:#02182F">KES {{ number_format($order->total, 2) }}</td>
                                <td>
                                    <span class="badge {{ $statusBadge }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display:flex;align-items:center;gap:12px">
                                        <a href="{{ route('purchase-orders.show', $order) }}" class="action-link view">View</a>
                                        @if($order->status === 'draft')
                                            <form method="POST" action="{{ route('purchase-orders.destroy', $order) }}"
                                                  onsubmit="return confirm('Delete this PO?')" style="display:inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="action-link delete">Delete</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($orders->hasPages())
                    <div style="padding:14px 20px;border-top:1px solid #f1f3f8">
                        {{ $orders->links() }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p style="font-family:'Outfit',sans-serif;font-size:14px;color:#9ca3af;margin-bottom:6px">No purchase orders yet</p>
                    <a href="{{ route('purchase-orders.create') }}" 
                       style="font-family:'Outfit',sans-serif;font-size:13px;font-weight:600;color:#03A737;text-decoration:none">
                        Create your first PO →
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>