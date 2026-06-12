<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Report' }} — {{ auth()->user()->tenant->name ?? 'SellSync-POS' }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Outfit', sans-serif; font-size: 10px; color: #02182F; padding: 0; }
        
        .page { padding: 25px 30px; }
        
        /* ── Header ── */
        .header { 
            border-bottom: 3px solid #03A737; 
            padding-bottom: 18px; 
            margin-bottom: 22px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .header-left { flex: 1; }
        .business-name { 
            font-size: 22px; font-weight: 700; color: #02182F; 
            letter-spacing: -0.01em; line-height: 1.2;
        }
        .business-tagline {
            font-family: 'JetBrains Mono', monospace;
            font-size: 8px; color: #03A737; 
            text-transform: uppercase; letter-spacing: 0.08em;
            margin-top: 1px; font-weight: 600;
        }
        .business-details { 
            font-size: 9px; color: #6b7280; margin-top: 8px; 
            line-height: 1.6;
        }
        .business-details span { display: inline-block; margin-right: 16px; }
        .business-details .detail-icon { color: #03A737; font-weight: 700; margin-right: 2px; }
        
        .header-right { text-align: right; flex-shrink: 0; }
        .report-badge {
            display: inline-block;
            background: #e6f7eb; color: #03A737;
            font-size: 9px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.08em;
            padding: 5px 12px; border-radius: 99px;
            margin-bottom: 8px;
        }
        .report-title { font-size: 18px; font-weight: 700; color: #02182F; }
        .report-period { 
            font-size: 9px; color: #9ca3af; margin-top: 4px;
            font-family: 'JetBrains Mono', monospace;
        }
        
        /* ── Section Cards ── */
        .section-card {
            border: 1px solid #e4e7ef;
            border-radius: 10px;
            margin: 16px 0;
            overflow: hidden;
            background: #FFFEFE;
        }
        .section-card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 18px;
            background: #fafbff;
            border-bottom: 1px solid #f1f3f8;
        }
        .section-card-icon {
            width: 30px; height: 30px;
            border-radius: 8px;
            background: #e6f7eb;
            color: #03A737;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .section-card-title {
            font-family: 'Outfit', sans-serif;
            font-size: 13px; font-weight: 700;
            color: #02182F; flex: 1;
        }
        .section-card-badge {
            font-size: 9px; font-weight: 600;
            color: #9ca3af; background: #f1f3f8;
            padding: 3px 10px; border-radius: 99px;
        }
        
        /* ── Summary Cards ── */
        .summary-grid {
            display: flex; gap: 12px; flex-wrap: wrap; margin: 14px 0;
        }
        .summary-card {
            flex: 1; min-width: 120px;
            background: #FFFEFE; border: 1px solid #e4e7ef;
            border-radius: 10px; padding: 16px 18px;
            display: flex; align-items: flex-start; gap: 12px;
        }
        .summary-card-green  { border-color: #b8e6c4; background: #e6f7eb; }
        .summary-card-blue   { border-color: #c4d9fb; background: #edf3fd; }
        .summary-card-dark   { border-color: #d4d8e0; background: #f0f2f5; }
        .summary-card-red    { border-color: #fecdd3; background: #fef2f2; }
        
        .summary-card-icon {
            width: 36px; height: 36px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .summary-card-green .summary-card-icon { background: #ccf4d6; color: #03A737; }
        .summary-card-blue  .summary-card-icon { background: #dbe8fc; color: #3D7BE7; }
        .summary-card-dark  .summary-card-icon { background: #e4e7ef; color: #02182F; }
        .summary-card-red   .summary-card-icon { background: #ffe4e6; color: #dc2626; }
        
        .summary-card-content { flex: 1; }
        .summary-card-label {
            font-size: 7px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.08em; margin-bottom: 4px;
        }
        .summary-card-green .summary-card-label { color: #028a2e; }
        .summary-card-blue  .summary-card-label { color: #2b5fc4; }
        .summary-card-dark  .summary-card-label { color: #4b5563; }
        .summary-card-red   .summary-card-label { color: #be123c; }
        
        .summary-card-value {
            font-family: 'JetBrains Mono', monospace;
            font-size: 15px; font-weight: 700; line-height: 1;
            color: #02182F; margin-bottom: 2px;
        }
        .summary-card-sub { font-size: 8px; color: #9ca3af; }
        
        /* ── Mini Bar ── */
        .mini-bar {
            height: 5px; background: #f1f3f8;
            border-radius: 99px; overflow: hidden;
        }
        .mini-bar-fill { height: 100%; border-radius: 99px; }
        
        /* ── Rank Badges ── */
        .rank-badge {
            display: inline-flex; align-items: center; justify-content: center;
            width: 20px; height: 20px; border-radius: 50%;
            font-size: 9px; font-weight: 700; font-family: 'Outfit', sans-serif;
        }
        .rank-badge-gold   { background: #fef3c7; color: #b45309; }
        .rank-badge-silver { background: #f1f5f9; color: #64748b; }
        .rank-badge-bronze { background: #fef2f2; color: #b91c1c; }
        
        /* ── Tables ── */
        table { width: 100%; border-collapse: collapse; margin: 0; font-size: 9px; }
        thead th { 
            background: #fafbff; text-align: left; 
            padding: 10px 14px; font-weight: 700; color: #6b7280; 
            text-transform: uppercase; letter-spacing: 0.06em; 
            border-bottom: 2px solid #e4e7ef; font-size: 7px;
        }
        tbody td { 
            padding: 10px 14px; border-bottom: 1px solid #f8f9fb; 
            color: #374151;
        }
        tbody tr:nth-child(even) td { background: #fafbff; }
        tbody tr:last-child td { border-bottom: 1px solid #e4e7ef; }
        
        .text-right  { text-align: right; }
        .text-center { text-align: center; }
        .font-mono   { font-family: 'JetBrains Mono', monospace; }
        .font-bold   { font-weight: 700; }
        .text-brand  { color: #03A737 !important; }
        .text-muted  { color: #9ca3af; }
        .text-sm     { font-size: 8px; }
        
        /* ── Dot Indicator ── */
        .dot-indicator {
            display: inline-block; width: 8px; height: 8px;
            border-radius: 50%; margin-right: 6px; flex-shrink: 0;
            vertical-align: middle;
        }
        
        /* ── Badges ── */
        .badge {
            display: inline-block; padding: 2px 8px; border-radius: 99px;
            font-size: 7px; font-weight: 700; letter-spacing: 0.03em;
            text-transform: capitalize;
        }
        .badge-green  { background: #e6f7eb; color: #028a2e; }
        .badge-blue   { background: #edf3fd; color: #2b5fc4; }
        .badge-yellow { background: #fef9c3; color: #a16207; }
        .badge-gray   { background: #f3f4f6; color: #6b7280; }
        
        /* ── Empty State ── */
        .empty-state { text-align: center; padding: 24px 20px; font-size: 10px; color: #9ca3af; }
        
        /* ── Footer ── */
        .footer {
            border-top: 2px solid #e4e7ef; 
            padding-top: 14px; margin-top: 30px;
            display: flex; justify-content: space-between; align-items: center;
            font-size: 8px; color: #9ca3af;
        }
        .footer-left { text-align: left; }
        .footer-right { text-align: right; font-family: 'JetBrains Mono', monospace; }
        .footer-brand { font-weight: 700; color: #03A737; }
        
        .page-break { page-break-before: always; }
        
        @page { 
            margin: 15px; 
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

        @yield('content')

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