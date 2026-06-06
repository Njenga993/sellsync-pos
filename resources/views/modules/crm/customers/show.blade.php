<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('customers.index') }}" 
               style="display:flex;align-items:center;gap:6px;color:#9ca3af;font-size:13px;text-decoration:none;padding:6px 10px;border-radius:8px;border:1px solid #e4e7ef;font-family:'Outfit',sans-serif;transition:all .15s"
               onmouseover="this.style.borderColor='#03A737';this.style.color='#03A737';this.style.background='#e6f7eb'"
               onmouseout="this.style.borderColor='#e4e7ef';this.style.color='#9ca3af';this.style.background='transparent'">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
            </a>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">People</p>
                <h1 style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:700;color:#02182F;line-height:1.2">
                    {{ $customer->name }}
                </h1>
            </div>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
        
        .dash-wrap { display: flex; flex-direction: column; gap: 16px; }
        
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
        }
        @media (max-width: 768px) { .stat-grid { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 480px) { .stat-grid { grid-template-columns: 1fr; } }
        
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
            color: #02182F;
        }
        
        .card-blue   { background: #edf3fd; border-color: #c4d9fb; }
        .card-blue   .stat-card-label  { color: #2b5fc4; }
        
        .card-yellow { background: #fffbeb; border-color: #fde68a; }
        .card-yellow .stat-card-label  { color: #a16207; }
        
        .card-dark   { background: #f0f2f5; border-color: #d4d8e0; }
        .card-dark   .stat-card-label  { color: #4b5563; }
        
        .panel {
            background: #FFFEFE;
            border: 1px solid #e4e7ef;
            border-radius: 14px;
            overflow: hidden;
        }
        .panel-padded { padding: 24px; }
        
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
            color: #02182F;
        }
        
        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        @media (max-width: 480px) { .detail-grid { grid-template-columns: 1fr; } }
        
        .detail-block-label {
            font-family: 'Outfit', sans-serif;
            font-size: 10px;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 4px;
        }
        .detail-block-value {
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            font-weight: 600;
            color: #02182F;
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
        
        .mono { font-family: 'JetBrains Mono', monospace; }
        .fw6 { font-weight: 600; }
        .fw7 { font-weight: 700; }
        .text-brand { color: #03A737; }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            color: #9ca3af;
        }
    </style>

    <div class="dash-wrap" style="padding:0 0 20px">

        {{-- Stats --}}
        <div class="stat-grid">
            <div class="stat-card card-blue">
                <div class="stat-card-label">Total Spent</div>
                <div class="stat-card-value">KES {{ number_format($customer->total_spent, 2) }}</div>
            </div>
            <div class="stat-card card-yellow">
                <div class="stat-card-label">Loyalty Points</div>
                <div class="stat-card-value">{{ number_format($customer->loyalty_points) }} pts</div>
            </div>
            <div class="stat-card card-dark">
                <div class="stat-card-label">Credit Limit</div>
                <div class="stat-card-value">KES {{ number_format($customer->credit_limit, 2) }}</div>
            </div>
        </div>

        {{-- Customer Details --}}
        <div class="panel panel-padded">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
                <span style="font-family:'Outfit',sans-serif;font-size:14px;font-weight:700;color:#02182F">Customer Details</span>
                <a href="{{ route('customers.edit', $customer) }}" 
                   style="font-family:'Outfit',sans-serif;font-size:12px;font-weight:600;color:#03A737;text-decoration:none">Edit</a>
            </div>
            <div class="detail-grid">
                <div>
                    <div class="detail-block-label">Phone</div>
                    <div class="detail-block-value">{{ $customer->phone ?? '—' }}</div>
                </div>
                <div>
                    <div class="detail-block-label">Email</div>
                    <div class="detail-block-value">{{ $customer->email ?? '—' }}</div>
                </div>
                <div>
                    <div class="detail-block-label">City</div>
                    <div class="detail-block-value">{{ $customer->city ?? '—' }}</div>
                </div>
                <div>
                    <div class="detail-block-label">Address</div>
                    <div class="detail-block-value">{{ $customer->address ?? '—' }}</div>
                </div>
            </div>
        </div>

        {{-- Recent Purchases --}}
        <div class="panel">
            <div class="panel-header">
                <span class="panel-title">Recent Purchases</span>
            </div>
            @if($recentSales->count())
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentSales as $sale)
                            <tr>
                                <td class="mono text-brand fw6" style="font-size:12px">{{ $sale->invoice_no }}</td>
                                <td style="font-size:12px;color:#6b7280">{{ $sale->created_at->format('d M Y, h:i A') }}</td>
                                <td class="mono fw6" style="color:#02182F">KES {{ number_format($sale->total, 2) }}</td>
                                <td style="font-size:12px;color:#6b7280">{{ ucfirst($sale->payment_method) }}</td>
                                <td>
                                    <span class="badge {{ $sale->status === 'completed' ? 'badge-green' : ($sale->status === 'pending' ? 'badge-yellow' : 'badge-blue') }}">
                                        {{ ucfirst($sale->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">No purchases yet.</div>
            @endif
        </div>

    </div>
</x-app-layout>