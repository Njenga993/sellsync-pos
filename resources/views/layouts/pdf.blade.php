<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Report' }} — {{ auth()->user()->tenant->name ?? 'SellSync-POS' }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Outfit', sans-serif; font-size: 10px; color: #111827; padding: 0; }
        
        /* ── Page Container ── */
        .page { padding: 25px 30px; }
        
        /* ── Header ── */
        .header { 
            border-bottom: 3px solid #1a56db; 
            padding-bottom: 18px; 
            margin-bottom: 22px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .header-left { flex: 1; }
        .business-name { 
            font-size: 20px; font-weight: 700; color: #111827; 
            letter-spacing: -0.01em; line-height: 1.2;
        }
        .business-tagline {
            font-family: 'JetBrains Mono', monospace;
            font-size: 8px; color: #9ca3af; 
            text-transform: uppercase; letter-spacing: 0.08em;
            margin-top: 1px;
        }
        .business-details { 
            font-size: 9px; color: #6b7280; margin-top: 8px; 
            line-height: 1.6;
        }
        .business-details span {
            display: inline-block; margin-right: 16px;
        }
        .business-details .detail-icon {
            color: #1a56db; font-weight: 700; margin-right: 2px;
        }
        
        .header-right { text-align: right; flex-shrink: 0; }
        .report-badge {
            display: inline-block;
            background: #eff4ff; color: #1a56db;
            font-size: 8px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.08em;
            padding: 4px 10px; border-radius: 99px;
            margin-bottom: 8px;
        }
        .report-title { 
            font-size: 16px; font-weight: 700; color: #111827; 
        }
        .report-period { 
            font-size: 9px; color: #9ca3af; margin-top: 4px;
            font-family: 'JetBrains Mono', monospace;
        }
        
        /* ── Section Headings ── */
        .section-title {
            font-size: 11px; font-weight: 700; color: #111827;
            margin: 20px 0 10px; padding-bottom: 8px;
            border-bottom: 1px solid #e4e7ef;
            display: flex; align-items: center; gap: 8px;
        }
        .section-title .section-icon {
            width: 20px; height: 20px; border-radius: 5px;
            background: #eff4ff; color: #1a56db;
            display: flex; align-items: center; justify-content: center;
            font-size: 9px; font-weight: 700;
            font-family: 'JetBrains Mono', monospace;
        }
        
        /* ── Summary Cards ── */
        .summary-grid {
            display: flex; gap: 12px; flex-wrap: wrap; margin: 14px 0;
        }
        .summary-card {
            flex: 1; min-width: 100px;
            background: #ffffff; border: 1px solid #e4e7ef;
            border-radius: 10px; padding: 14px 16px;
        }
        .summary-card.highlight { border-color: #c7d7fb; background: #eff4ff; }
        .summary-card.green { border-color: #bbf7d0; background: #f0fdf4; }
        .summary-card.red { border-color: #fecdd3; background: #fff1f2; }
        .summary-card-label {
            font-size: 7px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.08em; margin-bottom: 6px;
        }
        .summary-card.highlight .summary-card-label { color: #3b5fd6; }
        .summary-card.green .summary-card-label { color: #15803d; }
        .summary-card.red .summary-card-label { color: #be123c; }
        .summary-card-value {
            font-family: 'JetBrains Mono', monospace;
            font-size: 15px; font-weight: 700; line-height: 1;
        }
        .summary-card.highlight .summary-card-value { color: #1a3fad; }
        .summary-card.green .summary-card-value { color: #14532d; }
        .summary-card.red .summary-card-value { color: #881337; }
        .summary-card-sub {
            font-size: 8px; color: #9ca3af; margin-top: 4px;
        }
        
        /* ── Tables ── */
        table { 
            width: 100%; border-collapse: collapse; 
            margin: 8px 0 16px; font-size: 9px;
        }
        thead th { 
            background: #fafbff; text-align: left; 
            padding: 10px 14px; font-weight: 700; color: #6b7280; 
            text-transform: uppercase; letter-spacing: 0.06em; 
            border-bottom: 2px solid #e4e7ef; font-size: 7px;
        }
        tbody td { 
            padding: 9px 14px; border-bottom: 1px solid #f8f9fb; 
            color: #374151;
        }
        tbody tr:nth-child(even) td { background: #fcfcfd; }
        tbody tr:last-child td { border-bottom: 1px solid #e4e7ef; }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        .font-bold { font-weight: 700; }
        .text-brand { color: #1a56db; }
        .text-green { color: #16a34a; }
        .text-red { color: #dc2626; }
        .text-sm { font-size: 8px; }
        
        /* ── P&L Rows ── */
        .pl-table { max-width: 480px; }
        .pl-table td { padding: 10px 14px; border-bottom: 1px solid #f1f3f8; }
        .pl-table .pl-label { font-weight: 600; font-size: 10px; }
        .pl-table .pl-total { 
            font-size: 13px; font-weight: 700; 
            padding: 12px 14px; 
        }
        .pl-table .pl-total-green { background: #f0fdf4; }
        .pl-table .pl-total-red { background: #fff1f2; }
        
        /* ── Badges ── */
        .badge {
            display: inline-block; padding: 2px 8px; border-radius: 99px;
            font-size: 7px; font-weight: 700; letter-spacing: 0.03em;
        }
        .badge-green { background: #dcfce7; color: #15803d; }
        .badge-blue { background: #dbeafe; color: #1d4ed8; }
        .badge-yellow { background: #fef9c3; color: #a16207; }
        .badge-red { background: #fee2e2; color: #b91c1c; }
        
        /* ── Color Dot ── */
        .color-dot {
            display: inline-block; width: 8px; height: 8px;
            border-radius: 50%; margin-right: 6px; flex-shrink: 0;
        }
        
        /* ── Footer ── */
        .footer {
            border-top: 2px solid #e4e7ef; 
            padding-top: 14px; margin-top: 30px;
            display: flex; justify-content: space-between; align-items: center;
            font-size: 8px; color: #9ca3af;
        }
        .footer-left { text-align: left; }
        .footer-right { text-align: right; font-family: 'JetBrains Mono', monospace; }
        .footer-brand { font-weight: 700; color: #1a56db; }
        
        /* ── Page Breaks ── */
        .page-break { page-break-before: always; }
        
        /* ── Print Settings ── */
        @page { 
            margin: 15px; 
            @top-center {
                content: element(pageHeader);
            }
            @bottom-center {
                content: "Page " counter(page) " of " counter(pages);
                font-size: 8px; color: #9ca3af; font-family: 'Outfit', sans-serif;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        {{-- Header --}}
        <div class="header">
            <div class="header-left">
                <div class="business-name">{{ auth()->user()->tenant->name ?? 'SellSync-POS' }}</div>
                <div class="business-tagline">Powered by SellSync-POS</div>
                @php $settings = \App\Models\BusinessSetting::getForTenant(auth()->user()->tenant_id); @endphp
                <div class="business-details">
                    @if($settings->phone)
                        <span><span class="detail-icon">T</span> {{ $settings->phone }}</span>
                    @endif
                    @if($settings->email)
                        <span><span class="detail-icon">@</span> {{ $settings->email }}</span>
                    @endif
                    @if($settings->address)
                        <span><span class="detail-icon">⌂</span> {{ $settings->address }}</span>
                    @endif
                </div>
            </div>
            <div class="header-right">
                <div class="report-badge">{{ $type ?? 'REPORT' }}</div>
                <div class="report-title">{{ $title ?? 'Report' }}</div>
                <div class="report-period">{{ $period ?? '' }}</div>
            </div>
        </div>

        {{-- Content --}}
        @yield('content')

        {{-- Footer --}}
        <div class="footer">
            <div class="footer-left">
                Generated by <strong>{{ auth()->user()->name }}</strong> on {{ now()->format('d M Y, h:i A') }}
            </div>
            <div class="footer-right">
                <span class="footer-brand">SellSync-POS</span>
            </div>
        </div>
    </div>
</body>
</html>