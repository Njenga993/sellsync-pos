<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Finance</p>
                <h1 style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:700;color:#02182F;line-height:1.2">
                    Z-Reports
                </h1>
                <p class="text-sm text-gray-500 mt-1">Daily end-of-day shift summaries</p>
            </div>
            <a href="{{ route('zreports.create') }}"
               style="background:#03A737;color:#FFFEFE;padding:10px 22px;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;font-family:'Outfit',sans-serif;letter-spacing:.01em;box-shadow:0 2px 8px rgba(3,167,55,.25);transition:all .15s"
               onmouseover="this.style.background='#028a2e';this.style.boxShadow='0 4px 12px rgba(3,167,55,.35)'"
               onmouseout="this.style.background='#03A737';this.style.boxShadow='0 2px 8px rgba(3,167,55,.25)'">
                Close Today's Shift
            </a>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
        body { font-family: 'Outfit', sans-serif; }
    </style>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div style="background:#e6f7eb;border:1px solid #b8e6c4;border-radius:10px;padding:14px 18px;font-family:'Outfit',sans-serif;font-size:13px;color:#028a2e;font-weight:500;display:flex;align-items:center;gap:10px;margin-bottom:16px">
                    <svg width="16" height="16" fill="none" stroke="#03A737" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Today's status --}}
            @if($todayReport)
                <div style="background:#e6f7eb;border:1px solid #b8e6c4;border-radius:14px;padding:16px 20px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;font-family:'Outfit',sans-serif">
                    <div style="display:flex;align-items:center;gap:12px">
                        <div style="width:32px;height:32px;background:#ccf4d6;border-radius:50%;display:flex;align-items:center;justify-content:center">
                            <svg width="16" height="16" fill="none" stroke="#03A737" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p style="font-size:13px;font-weight:600;color:#028a2e;margin:0">Today's shift is closed</p>
                            <p style="font-size:11px;color:#03A737;margin:2px 0 0">Closed at {{ $todayReport->closed_at->format('h:i A') }} by {{ $todayReport->user->name }}</p>
                        </div>
                    </div>
                    <a href="{{ route('zreports.show', $todayReport) }}"
                       style="font-size:13px;font-weight:600;color:#028a2e;text-decoration:none">
                        View Report →
                    </a>
                </div>
            @else
                <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:14px;padding:16px 20px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;font-family:'Outfit',sans-serif">
                    <div style="display:flex;align-items:center;gap:12px">
                        <div style="width:32px;height:32px;background:#fef3c7;border-radius:50%;display:flex;align-items:center;justify-content:center">
                            <svg width="16" height="16" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p style="font-size:13px;font-weight:600;color:#a16207;margin:0">Today's shift is still open</p>
                            <p style="font-size:11px;color:#ca8a04;margin:2px 0 0">{{ now()->format('l, d F Y') }} — Close the shift before end of day</p>
                        </div>
                    </div>
                    <a href="{{ route('zreports.create') }}"
                       style="font-size:13px;font-weight:600;color:#a16207;text-decoration:none">
                        Close Shift Now →
                    </a>
                </div>
            @endif

            {{-- Reports List --}}
            <div style="background:#FFFEFE;border:1px solid #e4e7ef;border-radius:14px;overflow:hidden">
                <div style="padding:16px 24px;border-bottom:1px solid #f1f3f8;display:flex;justify-content:space-between;align-items:center">
                    <h3 style="font-family:'Outfit',sans-serif;font-size:14px;font-weight:700;color:#02182F;margin:0">All Z-Reports</h3>
                    <span style="font-size:11px;color:#9ca3af;font-family:'Outfit',sans-serif">{{ $reports->total() }} reports</span>
                </div>
                <table style="width:100%;border-collapse:collapse;font-family:'Outfit',sans-serif">
                    <thead>
                        <tr style="background:#fafbff">
                            <th style="text-align:left;font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;padding:12px 20px;border-bottom:1px solid #f1f3f8">Date</th>
                            <th style="text-align:left;font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;padding:12px 20px;border-bottom:1px solid #f1f3f8">Branch</th>
                            <th style="text-align:left;font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;padding:12px 20px;border-bottom:1px solid #f1f3f8">Closed By</th>
                            <th style="text-align:left;font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;padding:12px 20px;border-bottom:1px solid #f1f3f8">Transactions</th>
                            <th style="text-align:left;font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;padding:12px 20px;border-bottom:1px solid #f1f3f8">Total Sales</th>
                            <th style="text-align:left;font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;padding:12px 20px;border-bottom:1px solid #f1f3f8">Variance</th>
                            <th style="text-align:left;font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;padding:12px 20px;border-bottom:1px solid #f1f3f8">Status</th>
                            <th style="text-align:left;font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;padding:12px 20px;border-bottom:1px solid #f1f3f8"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                            <tr style="border-bottom:1px solid #f8f9fb">
                                <td style="padding:14px 20px;font-size:13px;color:#374151">
                                    <p style="font-weight:600;color:#02182F;margin:0">{{ $report->report_date->format('d M Y') }}</p>
                                    <p style="font-size:11px;color:#9ca3af;margin:2px 0 0">{{ $report->report_date->format('l') }}</p>
                                </td>
                                <td style="padding:14px 20px;font-size:13px;color:#6b7280">{{ $report->branch->name ?? '—' }}</td>
                                <td style="padding:14px 20px;font-size:13px;color:#6b7280">{{ $report->user->name ?? '—' }}</td>
                                <td style="padding:14px 20px;font-size:13px;color:#6b7280">{{ $report->total_transactions }}</td>
                                <td style="padding:14px 20px;font-size:13px;font-weight:700;color:#02182F;font-family:'JetBrains Mono',monospace">
                                    KES {{ number_format($report->total_sales, 2) }}
                                </td>
                                <td style="padding:14px 20px;font-family:'JetBrains Mono',monospace;font-weight:700;color:{{ $report->cash_variance >= 0 ? '#03A737' : '#dc2626' }}">
                                    {{ $report->cash_variance >= 0 ? '+' : '' }}{{ number_format($report->cash_variance, 2) }}
                                </td>
                                <td style="padding:14px 20px">
                                    <span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:99px;font-size:11px;font-weight:600;font-family:'Outfit',sans-serif;
                                        {{ $report->status === 'closed' ? 'background:#e6f7eb;color:#028a2e' : 'background:#fef9c3;color:#a16207' }}">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                </td>
                                <td style="padding:14px 20px">
                                    <a href="{{ route('zreports.show', $report) }}"
                                       style="font-family:'Outfit',sans-serif;font-size:12px;font-weight:600;color:#03A737;text-decoration:none">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="padding:48px 20px;text-align:center;font-size:13px;color:#9ca3af;font-family:'Outfit',sans-serif">
                                    No Z-Reports yet.
                                    <a href="{{ route('zreports.create') }}"
                                       style="color:#03A737;font-weight:600;text-decoration:none;margin-left:4px">Close today's shift</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($reports->hasPages())
                    <div style="padding:14px 24px;border-top:1px solid #f1f3f8">{{ $reports->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>