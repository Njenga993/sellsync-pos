<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Finance</p>
                <h1 style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:700;color:#02182F;line-height:1.2">
                    Returns & Refunds
                </h1>
            </div>
            <a href="{{ route('returns.create') }}"
               style="background:#03A737;color:#FFFEFE;padding:10px 22px;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;font-family:'Outfit',sans-serif;letter-spacing:.01em;box-shadow:0 2px 8px rgba(3,167,55,.25);transition:all .15s"
               onmouseover="this.style.background='#028a2e';this.style.boxShadow='0 4px 12px rgba(3,167,55,.35)'"
               onmouseout="this.style.background='#03A737';this.style.boxShadow='0 2px 8px rgba(3,167,55,.25)'">
                + Process Return
            </a>
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
        .text-refund { color: #dc2626; }
        
        .action-link {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s;
        }
        .action-link.view { color: #03A737; }
        .action-link.view:hover { color: #028a2e; text-decoration: underline; }
        
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

        {{-- Flash Message --}}
        @if(session('success'))
            <div style="background:#e6f7eb;border:1px solid #b8e6c4;border-radius:10px;padding:14px 18px;font-family:'Outfit',sans-serif;font-size:13px;color:#028a2e;font-weight:500;display:flex;align-items:center;gap:10px">
                <svg width="16" height="16" fill="none" stroke="#03A737" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Returns Table --}}
        <div class="panel">
            @if($returns->count())
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Return #</th>
                            <th>Invoice</th>
                            <th>Date</th>
                            <th>Items</th>
                            <th>Refund Method</th>
                            <th>Total Refund</th>
                            <th>Status</th>
                            <th style="width:70px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($returns as $return)
                            @php
                                $statusBadge = match($return->status) {
                                    'completed'  => 'badge-green',
                                    'pending'    => 'badge-yellow',
                                    'processing' => 'badge-blue',
                                    'rejected'   => 'badge-red',
                                    default      => 'badge-gray',
                                };
                            @endphp
                            <tr>
                                <td>
                                    <span class="mono fw6 text-brand" style="font-size:12px">{{ $return->return_number }}</span>
                                </td>
                                <td class="mono" style="font-size:12px;color:#6b7280">
                                    {{ $return->sale->invoice_no ?? '—' }}
                                </td>
                                <td>
                                    <div style="font-size:12px;color:#374151">{{ $return->created_at->format('d M Y') }}</div>
                                    <div class="mono" style="font-size:11px;color:#9ca3af">{{ $return->created_at->format('h:i A') }}</div>
                                </td>
                                <td style="font-size:12px;color:#6b7280">{{ $return->items_count }} item(s)</td>
                                <td style="font-size:12px;color:#6b7280;text-transform:capitalize">{{ $return->refund_method }}</td>
                                <td class="mono fw7 text-refund">KES {{ number_format($return->total_refund, 2) }}</td>
                                <td>
                                    <span class="badge {{ $statusBadge }}">
                                        {{ ucfirst($return->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('returns.show', $return) }}" class="action-link view">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($returns->hasPages())
                    <div style="padding:14px 20px;border-top:1px solid #f1f3f8">
                        {{ $returns->links() }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                    </svg>
                    <p style="font-family:'Outfit',sans-serif;font-size:14px;color:#9ca3af;margin-bottom:6px">No returns processed yet</p>
                    <a href="{{ route('returns.create') }}" 
                       style="font-family:'Outfit',sans-serif;font-size:13px;font-weight:600;color:#03A737;text-decoration:none">
                        Process a return →
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>