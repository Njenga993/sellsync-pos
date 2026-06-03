<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('returns.index') }}" 
               style="display:flex;align-items:center;gap:6px;color:#9ca3af;font-size:13px;text-decoration:none;padding:6px 10px;border-radius:8px;border:1px solid #e4e7ef;font-family:'Outfit',sans-serif;transition:all .15s"
               onmouseover="this.style.borderColor='#1a56db';this.style.color='#1a56db';this.style.background='#eff4ff'"
               onmouseout="this.style.borderColor='#e4e7ef';this.style.color='#9ca3af';this.style.background='transparent'">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
            </a>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Finance</p>
                <div style="display:flex;align-items:center;gap:12px">
                    <h1 style="font-family:'JetBrains Mono',monospace;font-size:18px;font-weight:700;color:#111827;line-height:1.2">
                        {{ $return->return_number }}
                    </h1>
                    @php
                        $statusBadge = match($return->status) {
                            'completed'  => 'badge-green',
                            'pending'    => 'badge-yellow',
                            'processing' => 'badge-blue',
                            'rejected'   => 'badge-red',
                            default      => 'badge-gray',
                        };
                    @endphp
                    <span class="badge {{ $statusBadge }}">
                        {{ ucfirst($return->status) }}
                    </span>
                </div>
            </div>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
        
        .dash-wrap { display: flex; flex-direction: column; gap: 16px; }
        
        .panel {
            background: #ffffff;
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
            color: #111827;
        }
        .info-block-value a {
            color: #1a56db;
            text-decoration: none;
        }
        .info-block-value a:hover { text-decoration: underline; }
        
        .refund-amount {
            font-family: 'JetBrains Mono', monospace;
            font-size: 20px;
            font-weight: 700;
            color: #dc2626;
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
        .badge-blue   { background: #dbeafe; color: #1d4ed8; }
        .badge-yellow { background: #fef9c3; color: #a16207; }
        .badge-red    { background: #fee2e2; color: #b91c1c; }
        .badge-gray   { background: #f3f4f6; color: #6b7280; }
        
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
        
        .mono { font-family: 'JetBrains Mono', monospace; }
        .fw6 { font-weight: 600; }
        .fw7 { font-weight: 700; }
        .text-refund { color: #dc2626; }
        
        .panel-header {
            padding: 18px 22px;
            border-bottom: 1px solid #f1f3f8;
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            font-weight: 700;
            color: #111827;
        }
    </style>

    <div class="dash-wrap" style="padding:0 0 20px">

        {{-- Flash Message --}}
        @if(session('success'))
            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:14px 18px;font-family:'Outfit',sans-serif;font-size:13px;color:#15803d;font-weight:500;display:flex;align-items:center;gap:10px">
                <svg width="16" height="16" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Return Summary --}}
        <div class="panel panel-padded">
            <div class="info-grid">
                <div>
                    <div class="info-block-label">Original Invoice</div>
                    <div class="info-block-value">
                        <a href="{{ route('pos.receipt', $return->sale) }}" target="_blank">
                            {{ $return->sale->invoice_no }}
                        </a>
                    </div>
                </div>
                <div>
                    <div class="info-block-label">Customer</div>
                    <div class="info-block-value">{{ $return->sale->customer->name ?? 'Walk-in' }}</div>
                </div>
                <div>
                    <div class="info-block-label">Processed By</div>
                    <div class="info-block-value">{{ $return->user->name }}</div>
                </div>
                <div>
                    <div class="info-block-label">Date</div>
                    <div class="info-block-value">{{ $return->created_at->format('d M Y, h:i A') }}</div>
                </div>
                <div>
                    <div class="info-block-label">Reason</div>
                    <div class="info-block-value">{{ $return->reason }}</div>
                </div>
                <div>
                    <div class="info-block-label">Refund Method</div>
                    <div class="info-block-value" style="text-transform:capitalize">{{ $return->refund_method }}</div>
                </div>
                <div>
                    <div class="info-block-label">Total Refund</div>
                    <div class="refund-amount">KES {{ number_format($return->total_refund, 2) }}</div>
                </div>
                @if($return->notes)
                    <div>
                        <div class="info-block-label">Notes</div>
                        <div class="info-block-value" style="font-size:13px;font-weight:400;color:#6b7280">{{ $return->notes }}</div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Returned Items --}}
        <div class="panel">
            <div class="panel-header">Returned Items</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Subtotal</th>
                        <th>Restocked</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($return->items as $item)
                        <tr>
                            <td class="fw6" style="color:#111827">{{ $item->product_name }}</td>
                            <td style="font-size:12px;color:#6b7280">{{ $item->qty }}</td>
                            <td class="mono" style="font-size:12px;color:#6b7280">KES {{ number_format($item->unit_price, 2) }}</td>
                            <td class="mono fw6" style="color:#111827">KES {{ number_format($item->subtotal, 2) }}</td>
                            <td>
                                @if($item->restock)
                                    <span class="badge badge-green">Restocked</span>
                                @else
                                    <span class="badge badge-gray">Not restocked</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background:#fafbff">
                        <td colspan="3" style="text-align:right;font-family:'Outfit',sans-serif;font-size:13px;font-weight:700;color:#111827">
                            Total Refund
                        </td>
                        <td class="mono fw7 text-refund" style="font-size:16px">
                            KES {{ number_format($return->total_refund, 2) }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>
</x-app-layout>