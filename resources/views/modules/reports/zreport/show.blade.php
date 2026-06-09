<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('zreports.index') }}" 
                   style="display:flex;align-items:center;gap:6px;color:#9ca3af;font-size:13px;text-decoration:none;padding:6px 10px;border-radius:8px;border:1px solid #e4e7ef;font-family:'Outfit',sans-serif;transition:all .15s"
                   onmouseover="this.style.borderColor='#03A737';this.style.color='#03A737';this.style.background='#e6f7eb'"
                   onmouseout="this.style.borderColor='#e4e7ef';this.style.color='#9ca3af';this.style.background='transparent'">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back
                </a>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Finance</p>
                    <h1 style="font-family:'Outfit',sans-serif;font-size:22px;font-weight:700;color:#02182F;line-height:1.2">
                        Z-Report — {{ $zreport->report_date->format('d F Y') }}
                    </h1>
                    <p style="font-size:12px;color:#9ca3af;margin-top:2px;font-family:'Outfit',sans-serif">
                        Closed by {{ $zreport->user->name ?? '—' }}
                        at {{ $zreport->closed_at->format('h:i A') }}
                        · {{ $zreport->branch->name ?? '—' }}
                    </p>
                </div>
            </div>
            <button onclick="window.print()"
                style="background:transparent;color:#6b7280;padding:10px 16px;border-radius:10px;font-size:13px;font-weight:600;font-family:'Outfit',sans-serif;border:1.5px solid #e4e7ef;cursor:pointer;display:flex;align-items:center;gap:8px;transition:all .15s"
                onmouseover="this.style.borderColor='#03A737';this.style.color='#03A737';this.style.background='#e6f7eb'"
                onmouseout="this.style.borderColor='#e4e7ef';this.style.color='#6b7280';this.style.background='transparent'">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print Report
            </button>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
        body { font-family: 'Outfit', sans-serif; }
    </style>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div style="background:#e6f7eb;border:1px solid #b8e6c4;border-radius:10px;padding:14px 18px;font-family:'Outfit',sans-serif;font-size:13px;color:#028a2e;font-weight:500;display:flex;align-items:center;gap:10px;margin-bottom:16px">
                    <svg width="16" height="16" fill="none" stroke="#03A737" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Variance Alert --}}
            @if($zreport->cash_variance != 0)
                <div style="padding:16px 20px;border-radius:14px;border:1px solid;margin-bottom:24px;display:flex;align-items:center;gap:12px;font-family:'Outfit',sans-serif;
                    {{ $zreport->cash_variance > 0 ? 'background:#edf3fd;border-color:#c4d9fb' : 'background:#fef2f2;border-color:#fecdd3' }}">
                    <svg width="20" height="20" fill="none" stroke="{{ $zreport->cash_variance > 0 ? '#3D7BE7' : '#dc2626' }}" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <p style="font-size:13px;font-weight:600;margin:0;{{ $zreport->cash_variance > 0 ? 'color:#2b5fc4' : 'color:#b91c1c' }}">
                            Cash drawer is {{ $zreport->cash_variance > 0 ? 'OVER' : 'SHORT' }}
                            by KES {{ number_format(abs($zreport->cash_variance), 2) }}
                        </p>
                        <p style="font-size:11px;margin:2px 0 0;{{ $zreport->cash_variance > 0 ? 'color:#3D7BE7' : 'color:#e11d48' }}">
                            Expected KES {{ number_format($zreport->expected_cash, 2) }}
                            · Counted KES {{ number_format($zreport->actual_cash, 2) }}
                        </p>
                    </div>
                </div>
            @else
                <div style="padding:16px 20px;background:#e6f7eb;border:1px solid #b8e6c4;border-radius:14px;margin-bottom:24px;display:flex;align-items:center;gap:12px;font-family:'Outfit',sans-serif">
                    <svg width="20" height="20" fill="none" stroke="#03A737" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p style="font-size:13px;font-weight:600;color:#028a2e;margin:0">Cash drawer is balanced — no variance</p>
                </div>
            @endif

            {{-- Main Report Grid --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px">

                {{-- Sales Breakdown --}}
                <div style="background:#FFFEFE;border:1px solid #e4e7ef;border-radius:14px;overflow:hidden">
                    <div style="padding:14px 24px;border-bottom:1px solid #f1f3f8;background:#fafbff">
                        <h3 style="font-family:'Outfit',sans-serif;font-size:14px;font-weight:700;color:#02182F;margin:0">Sales Breakdown</h3>
                    </div>
                    <div style="padding:0">
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 24px;border-bottom:1px solid #f8f9fb">
                            <span style="font-size:13px;color:#6b7280;font-family:'Outfit',sans-serif">Cash Sales</span>
                            <span style="font-family:'JetBrains Mono',monospace;font-weight:600;color:#02182F">KES {{ number_format($zreport->total_cash_sales, 2) }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 24px;border-bottom:1px solid #f8f9fb">
                            <span style="font-size:13px;color:#6b7280;font-family:'Outfit',sans-serif">M-Pesa Sales</span>
                            <span style="font-family:'JetBrains Mono',monospace;font-weight:600;color:#02182F">KES {{ number_format($zreport->total_mobile_sales, 2) }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 24px;border-bottom:1px solid #f8f9fb">
                            <span style="font-size:13px;color:#6b7280;font-family:'Outfit',sans-serif">Card Sales</span>
                            <span style="font-family:'JetBrains Mono',monospace;font-weight:600;color:#02182F">KES {{ number_format($zreport->total_card_sales, 2) }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 24px;border-bottom:1px solid #f8f9fb">
                            <span style="font-size:13px;color:#6b7280;font-family:'Outfit',sans-serif">Split Payments</span>
                            <span style="font-family:'JetBrains Mono',monospace;font-weight:600;color:#02182F">KES {{ number_format($zreport->total_split_sales, 2) }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 24px;background:#fafbff">
                            <span style="font-size:13px;font-weight:700;color:#02182F;font-family:'Outfit',sans-serif">Total Sales</span>
                            <span style="font-family:'JetBrains Mono',monospace;font-weight:700;color:#03A737;font-size:15px">KES {{ number_format($zreport->total_sales, 2) }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 24px">
                            <span style="font-size:12px;color:#9ca3af;font-family:'Outfit',sans-serif">Total Transactions</span>
                            <span style="font-weight:600;color:#02182F;font-family:'Outfit',sans-serif">{{ $zreport->total_transactions }}</span>
                        </div>
                    </div>
                </div>

                {{-- Cash Reconciliation --}}
                <div style="background:#FFFEFE;border:1px solid #e4e7ef;border-radius:14px;overflow:hidden">
                    <div style="padding:14px 24px;border-bottom:1px solid #f1f3f8;background:#fafbff">
                        <h3 style="font-family:'Outfit',sans-serif;font-size:14px;font-weight:700;color:#02182F;margin:0">Cash Reconciliation</h3>
                    </div>
                    <div style="padding:0">
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 24px;border-bottom:1px solid #f8f9fb">
                            <span style="font-size:13px;color:#6b7280;font-family:'Outfit',sans-serif">Opening Float</span>
                            <span style="font-family:'JetBrains Mono',monospace;font-weight:600;color:#02182F">KES {{ number_format($zreport->opening_float, 2) }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 24px;border-bottom:1px solid #f8f9fb">
                            <span style="font-size:13px;color:#6b7280;font-family:'Outfit',sans-serif">+ Cash Sales</span>
                            <span style="font-family:'JetBrains Mono',monospace;font-weight:600;color:#03A737">+ KES {{ number_format($zreport->total_cash_sales, 2) }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 24px;border-bottom:1px solid #f8f9fb">
                            <span style="font-size:13px;color:#6b7280;font-family:'Outfit',sans-serif">− Cash Refunds</span>
                            <span style="font-family:'JetBrains Mono',monospace;font-weight:600;color:#dc2626">− KES {{ number_format($zreport->total_cash_refunds, 2) }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 24px;border-bottom:1px solid #f8f9fb">
                            <span style="font-size:13px;color:#6b7280;font-family:'Outfit',sans-serif">− Cash Expenses</span>
                            <span style="font-family:'JetBrains Mono',monospace;font-weight:600;color:#dc2626">− KES {{ number_format($zreport->total_cash_expenses, 2) }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 24px;background:#fafbff;border-bottom:1px solid #f1f3f8">
                            <span style="font-size:13px;font-weight:700;color:#02182F;font-family:'Outfit',sans-serif">Expected in Drawer</span>
                            <span style="font-family:'JetBrains Mono',monospace;font-weight:700;color:#02182F">KES {{ number_format($zreport->expected_cash, 2) }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 24px;border-bottom:1px solid #f8f9fb">
                            <span style="font-size:13px;color:#6b7280;font-family:'Outfit',sans-serif">Actual Cash Counted</span>
                            <span style="font-family:'JetBrains Mono',monospace;font-weight:600;color:#02182F">KES {{ number_format($zreport->actual_cash, 2) }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 24px;background:#fafbff">
                            <span style="font-size:13px;font-weight:700;color:#02182F;font-family:'Outfit',sans-serif">Variance</span>
                            <span style="font-family:'JetBrains Mono',monospace;font-weight:700;font-size:15px;color:{{ $zreport->cash_variance >= 0 ? '#03A737' : '#dc2626' }}">
                                {{ $zreport->cash_variance >= 0 ? '+' : '' }}KES {{ number_format($zreport->cash_variance, 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Deductions --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px">
                <div style="background:#FFFEFE;border:1px solid #e4e7ef;border-radius:14px;overflow:hidden">
                    <div style="padding:14px 24px;border-bottom:1px solid #f1f3f8">
                        <h3 style="font-family:'Outfit',sans-serif;font-size:14px;font-weight:700;color:#02182F;margin:0">Refunds Issued</h3>
                    </div>
                    <div style="padding:0">
                        <div style="display:flex;justify-content:space-between;padding:12px 24px;border-bottom:1px solid #f8f9fb">
                            <span style="font-size:13px;color:#6b7280;font-family:'Outfit',sans-serif">Total Refunds</span>
                            <span style="font-family:'JetBrains Mono',monospace;font-weight:600;color:#dc2626">KES {{ number_format($zreport->total_refunds, 2) }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;padding:12px 24px">
                            <span style="font-size:13px;color:#6b7280;font-family:'Outfit',sans-serif">Cash Refunds</span>
                            <span style="font-family:'JetBrains Mono',monospace;font-weight:600;color:#02182F">KES {{ number_format($zreport->total_cash_refunds, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div style="background:#FFFEFE;border:1px solid #e4e7ef;border-radius:14px;overflow:hidden">
                    <div style="padding:14px 24px;border-bottom:1px solid #f1f3f8">
                        <h3 style="font-family:'Outfit',sans-serif;font-size:14px;font-weight:700;color:#02182F;margin:0">Expenses Paid Out</h3>
                    </div>
                    <div style="padding:0">
                        <div style="display:flex;justify-content:space-between;padding:12px 24px;border-bottom:1px solid #f8f9fb">
                            <span style="font-size:13px;color:#6b7280;font-family:'Outfit',sans-serif">Total Expenses</span>
                            <span style="font-family:'JetBrains Mono',monospace;font-weight:600;color:#ea580c">KES {{ number_format($zreport->total_expenses, 2) }}</span>
                        </div>
                        <div style="display:flex;justify-content:space-between;padding:12px 24px">
                            <span style="font-size:13px;color:#6b7280;font-family:'Outfit',sans-serif">Cash Expenses</span>
                            <span style="font-family:'JetBrains Mono',monospace;font-weight:600;color:#02182F">KES {{ number_format($zreport->total_cash_expenses, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            @if($zreport->notes)
            <div style="background:#FFFEFE;border:1px solid #e4e7ef;border-radius:14px;padding:24px;margin-bottom:24px">
                <h3 style="font-family:'Outfit',sans-serif;font-size:14px;font-weight:700;color:#02182F;margin:0 0 8px">Shift Notes</h3>
                <p style="font-size:13px;color:#6b7280;margin:0;font-family:'Outfit',sans-serif">{{ $zreport->notes }}</p>
            </div>
            @endif

            {{-- Transactions List --}}
            <div style="background:#FFFEFE;border:1px solid #e4e7ef;border-radius:14px;overflow:hidden;margin-bottom:24px">
                <div style="padding:14px 24px;border-bottom:1px solid #f1f3f8;display:flex;justify-content:space-between;align-items:center">
                    <h3 style="font-family:'Outfit',sans-serif;font-size:14px;font-weight:700;color:#02182F;margin:0">All Transactions</h3>
                    <span style="font-size:11px;color:#9ca3af;font-family:'Outfit',sans-serif">{{ $sales->count() }} transactions</span>
                </div>
                <table style="width:100%;border-collapse:collapse;font-family:'Outfit',sans-serif">
                    <thead>
                        <tr style="background:#fafbff">
                            <th style="text-align:left;font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;padding:12px 24px;border-bottom:1px solid #f1f3f8">Time</th>
                            <th style="text-align:left;font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;padding:12px 24px;border-bottom:1px solid #f1f3f8">Invoice</th>
                            <th style="text-align:left;font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;padding:12px 24px;border-bottom:1px solid #f1f3f8">Cashier</th>
                            <th style="text-align:left;font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;padding:12px 24px;border-bottom:1px solid #f1f3f8">Method</th>
                            <th style="text-align:right;font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;padding:12px 24px;border-bottom:1px solid #f1f3f8">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $sale)
                            <tr style="border-bottom:1px solid #f8f9fb">
                                <td style="padding:14px 24px;font-size:13px;color:#6b7280">{{ $sale->created_at->format('h:i A') }}</td>
                                <td style="padding:14px 24px;font-family:'JetBrains Mono',monospace;font-size:12px;font-weight:600;color:#03A737">{{ $sale->invoice_no }}</td>
                                <td style="padding:14px 24px;font-size:13px;color:#6b7280">{{ $sale->user->name ?? '—' }}</td>
                                <td style="padding:14px 24px">
                                    <span style="display:inline-flex;align-items:center;padding:3px 10px;border-radius:99px;font-size:11px;font-weight:600;font-family:'Outfit',sans-serif;
                                        {{ $sale->payment_method === 'cash'   ? 'background:#e6f7eb;color:#028a2e'  : '' }}
                                        {{ $sale->payment_method === 'mobile' ? 'background:#edf3fd;color:#2b5fc4'    : '' }}
                                        {{ $sale->payment_method === 'card'   ? 'background:#f0f2f5;color:#4b5563': '' }}
                                        {{ str_contains($sale->payment_method, '+') ? 'background:#fff7ed;color:#c2410c' : '' }}">
                                        {{ ucfirst($sale->payment_method) }}
                                    </span>
                                </td>
                                <td style="padding:14px 24px;text-align:right;font-family:'JetBrains Mono',monospace;font-weight:700;color:#02182F">
                                    KES {{ number_format($sale->total, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding:32px 24px;text-align:center;font-size:13px;color:#9ca3af;font-family:'Outfit',sans-serif">No transactions recorded for this day.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr style="background:#fafbff">
                            <td colspan="4" style="padding:14px 24px;text-align:right;font-size:13px;font-weight:700;color:#02182F;font-family:'Outfit',sans-serif">Total</td>
                            <td style="padding:14px 24px;text-align:right;font-family:'JetBrains Mono',monospace;font-weight:700;color:#03A737;font-size:15px">
                                KES {{ number_format($sales->sum('total'), 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Expenses List --}}
            @if($expenses->count() > 0)
            <div style="background:#FFFEFE;border:1px solid #e4e7ef;border-radius:14px;overflow:hidden">
                <div style="padding:14px 24px;border-bottom:1px solid #f1f3f8">
                    <h3 style="font-family:'Outfit',sans-serif;font-size:14px;font-weight:700;color:#02182F;margin:0">Expenses Recorded</h3>
                </div>
                <table style="width:100%;border-collapse:collapse;font-family:'Outfit',sans-serif">
                    <thead>
                        <tr style="background:#fafbff">
                            <th style="text-align:left;font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;padding:12px 24px;border-bottom:1px solid #f1f3f8">Expense</th>
                            <th style="text-align:left;font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;padding:12px 24px;border-bottom:1px solid #f1f3f8">Category</th>
                            <th style="text-align:left;font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;padding:12px 24px;border-bottom:1px solid #f1f3f8">Method</th>
                            <th style="text-align:right;font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;padding:12px 24px;border-bottom:1px solid #f1f3f8">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expenses as $expense)
                            <tr style="border-bottom:1px solid #f8f9fb">
                                <td style="padding:14px 24px;font-size:13px;font-weight:600;color:#02182F">{{ $expense->title }}</td>
                                <td style="padding:14px 24px;font-size:13px;color:#6b7280">{{ $expense->category->name ?? '—' }}</td>
                                <td style="padding:14px 24px;font-size:13px;color:#6b7280;text-transform:capitalize">{{ $expense->payment_method }}</td>
                                <td style="padding:14px 24px;text-align:right;font-family:'JetBrains Mono',monospace;font-weight:700;color:#ea580c">
                                    KES {{ number_format($expense->amount, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

        </div>
    </div>

    <style>
        @media print {
            nav, header, .no-print, button { display: none !important; }
            body { background: white; }
            .max-w-4xl { max-width: 100%; }
        }
    </style>
</x-app-layout>