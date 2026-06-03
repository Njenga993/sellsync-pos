<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Inventory</p>
                <h1 style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:700;color:#111827;line-height:1.2">
                    Suppliers
                </h1>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('purchase-orders.index') }}"
                   style="background:transparent;color:#6b7280;padding:10px 18px;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;font-family:'Outfit',sans-serif;border:1.5px solid #e4e7ef;transition:all .15s">
                    Purchase Orders
                </a>
                <a href="{{ route('suppliers.create') }}"
                   style="background:#1a56db;color:white;padding:10px 22px;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;font-family:'Outfit',sans-serif;letter-spacing:.01em;box-shadow:0 2px 8px rgba(26,86,219,.25);transition:all .15s">
                    + Add Supplier
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
        
        .dash-wrap { display: flex; flex-direction: column; gap: 20px; }
        
        .panel {
            background: #ffffff;
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
        
        .supplier-name-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .supplier-avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 700;
            color: white;
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
        .badge-green  { background: #dcfce7; color: #15803d; }
        .badge-gray   { background: #f3f4f6; color: #6b7280; }
        .badge-blue   { background: #dbeafe; color: #1d4ed8; }
        
        .count-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 28px;
            height: 24px;
            padding: 0 10px;
            border-radius: 99px;
            background: #eff4ff;
            color: #1a56db;
            font-size: 11px;
            font-weight: 700;
            font-family: 'JetBrains Mono', monospace;
        }
        
        .mono { font-family: 'JetBrains Mono', monospace; }
        .fw6 { font-weight: 600; }
        .fw7 { font-weight: 700; }
        .text-brand { color: #1a56db; }
        
        .action-link {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s;
        }
        .action-link.edit { color: #1a56db; }
        .action-link.edit:hover { color: #1e40af; text-decoration: underline; }
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

        {{-- Suppliers Table --}}
        <div class="panel">
            @if($suppliers->count())
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Supplier</th>
                            <th>Contact</th>
                            <th>Phone</th>
                            <th>City</th>
                            <th>Orders</th>
                            <th>Status</th>
                            <th style="width:100px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $avatarColors = ['#1a56db','#0d9488','#7c3aed','#d97706','#dc2626','#0891b2','#4f46e5','#16a34a'];
                        @endphp
                        @foreach($suppliers as $supplier)
                            @php
                                $initials = strtoupper(substr($supplier->name, 0, 2));
                                $avatarBg = $avatarColors[$supplier->id % count($avatarColors)];
                            @endphp
                            <tr>
                                <td>
                                    <div class="supplier-name-cell">
                                        <div class="supplier-avatar" style="background:{{ $avatarBg }}">
                                            {{ $initials }}
                                        </div>
                                        <div>
                                            <div class="fw6" style="color:#111827">{{ $supplier->name }}</div>
                                            @if($supplier->email)
                                                <div style="font-size:11px;color:#9ca3af">{{ $supplier->email }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td style="font-size:12px;color:#6b7280">{{ $supplier->contact_person ?? '—' }}</td>
                                <td class="mono" style="font-size:12px;color:#6b7280">{{ $supplier->phone ?? '—' }}</td>
                                <td style="font-size:12px;color:#6b7280">{{ $supplier->city ?? '—' }}</td>
                                <td>
                                    <span class="count-chip">{{ $supplier->purchase_orders_count }} POs</span>
                                </td>
                                <td>
                                    <span class="badge {{ $supplier->status === 'active' ? 'badge-green' : 'badge-gray' }}">
                                        {{ ucfirst($supplier->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div style="display:flex;align-items:center;gap:12px">
                                        <a href="{{ route('suppliers.edit', $supplier) }}" class="action-link edit">Edit</a>
                                        <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}"
                                              onsubmit="return confirm('Delete this supplier?')" style="display:inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-link delete">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($suppliers->hasPages())
                    <div style="padding:14px 20px;border-top:1px solid #f1f3f8">
                        {{ $suppliers->links() }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <p style="font-family:'Outfit',sans-serif;font-size:14px;color:#9ca3af;margin-bottom:6px">No suppliers yet</p>
                    <a href="{{ route('suppliers.create') }}" 
                       style="font-family:'Outfit',sans-serif;font-size:13px;font-weight:600;color:#1a56db;text-decoration:none">
                        Add your first supplier →
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>