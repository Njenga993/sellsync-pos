<x-app-layout>
    <x-slot name="header">
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
                    Close Shift — Z-Report
                </h1>
                <p style="font-size:12px;color:#9ca3af;margin-top:2px;font-family:'Outfit',sans-serif">{{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}</p>
            </div>
        </div>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600&display=swap');
        body { font-family: 'Outfit', sans-serif; }
    </style>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Sales Summary --}}
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px">
                <div style="background:#FFFEFE;border:1px solid #e4e7ef;border-radius:14px;padding:20px">
                    <p style="font-size:10px;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;margin:0 0 8px;font-family:'Outfit',sans-serif">Total Sales</p>
                    <p style="font-family:'JetBrains Mono',monospace;font-size:20px;font-weight:700;color:#02182F;margin:0 0 4px">KES {{ number_format($summary['total_sales'], 2) }}</p>
                    <p style="font-size:11px;color:#9ca3af;margin:0;font-family:'Outfit',sans-serif">{{ $summary['total_transactions'] }} transactions</p>
                </div>
                <div style="background:#e6f7eb;border:1px solid #b8e6c4;border-radius:14px;padding:20px">
                    <p style="font-size:10px;font-weight:600;color:#028a2e;text-transform:uppercase;letter-spacing:0.06em;margin:0 0 8px;font-family:'Outfit',sans-serif">Cash Sales</p>
                    <p style="font-family:'JetBrains Mono',monospace;font-size:20px;font-weight:700;color:#02182F;margin:0 0 4px">KES {{ number_format($summary['total_cash_sales'], 2) }}</p>
                    <p style="font-size:11px;color:#03A737;margin:0;font-family:'Outfit',sans-serif">Cash payments received</p>
                </div>
                <div style="background:#edf3fd;border:1px solid #c4d9fb;border-radius:14px;padding:20px">
                    <p style="font-size:10px;font-weight:600;color:#2b5fc4;text-transform:uppercase;letter-spacing:0.06em;margin:0 0 8px;font-family:'Outfit',sans-serif">M-Pesa Sales</p>
                    <p style="font-family:'JetBrains Mono',monospace;font-size:20px;font-weight:700;color:#02182F;margin:0 0 4px">KES {{ number_format($summary['total_mobile_sales'], 2) }}</p>
                    <p style="font-size:11px;color:#3D7BE7;margin:0;font-family:'Outfit',sans-serif">Mobile money received</p>
                </div>
                <div style="background:#f0f2f5;border:1px solid #d4d8e0;border-radius:14px;padding:20px">
                    <p style="font-size:10px;font-weight:600;color:#4b5563;text-transform:uppercase;letter-spacing:0.06em;margin:0 0 8px;font-family:'Outfit',sans-serif">Card Sales</p>
                    <p style="font-family:'JetBrains Mono',monospace;font-size:20px;font-weight:700;color:#02182F;margin:0 0 4px">KES {{ number_format($summary['total_card_sales'], 2) }}</p>
                    <p style="font-size:11px;color:#6b7280;margin:0;font-family:'Outfit',sans-serif">Card payments received</p>
                </div>
            </div>

            {{-- Deductions Summary --}}
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:24px">
                <div style="background:#fef2f2;border:1px solid #fecdd3;border-radius:14px;padding:20px">
                    <p style="font-size:10px;font-weight:600;color:#be123c;text-transform:uppercase;letter-spacing:0.06em;margin:0 0 8px;font-family:'Outfit',sans-serif">Total Refunds</p>
                    <p style="font-family:'JetBrains Mono',monospace;font-size:18px;font-weight:700;color:#dc2626;margin:0 0 4px">KES {{ number_format($summary['total_refunds'], 2) }}</p>
                    <p style="font-size:11px;color:#e11d48;margin:0;font-family:'Outfit',sans-serif">KES {{ number_format($summary['total_cash_refunds'], 2) }} in cash</p>
                </div>
                <div style="background:#fff7ed;border:1px solid #fed7aa;border-radius:14px;padding:20px">
                    <p style="font-size:10px;font-weight:600;color:#c2410c;text-transform:uppercase;letter-spacing:0.06em;margin:0 0 8px;font-family:'Outfit',sans-serif">Total Expenses</p>
                    <p style="font-family:'JetBrains Mono',monospace;font-size:18px;font-weight:700;color:#ea580c;margin:0 0 4px">KES {{ number_format($summary['total_expenses'], 2) }}</p>
                    <p style="font-size:11px;color:#f97316;margin:0;font-family:'Outfit',sans-serif">KES {{ number_format($summary['total_cash_expenses'], 2) }} paid in cash</p>
                </div>
                <div style="background:#f0f2f5;border:1px solid #d4d8e0;border-radius:14px;padding:20px">
                    <p style="font-size:10px;font-weight:600;color:#4b5563;text-transform:uppercase;letter-spacing:0.06em;margin:0 0 8px;font-family:'Outfit',sans-serif">Split Payments</p>
                    <p style="font-family:'JetBrains Mono',monospace;font-size:18px;font-weight:700;color:#02182F;margin:0 0 4px">KES {{ number_format($summary['total_split_sales'], 2) }}</p>
                    <p style="font-size:11px;color:#6b7280;margin:0;font-family:'Outfit',sans-serif">Mixed payment sales</p>
                </div>
            </div>

            {{-- Cashier Breakdown --}}
            @if($summary['sales_by_cashier']->count() > 1)
            <div style="background:#FFFEFE;border:1px solid #e4e7ef;border-radius:14px;overflow:hidden;margin-bottom:24px">
                <div style="padding:16px 24px;border-bottom:1px solid #f1f3f8">
                    <h3 style="font-family:'Outfit',sans-serif;font-size:14px;font-weight:700;color:#02182F;margin:0">Sales by Cashier</h3>
                </div>
                <table style="width:100%;border-collapse:collapse;font-family:'Outfit',sans-serif">
                    <thead>
                        <tr style="background:#fafbff">
                            <th style="text-align:left;font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;padding:12px 24px;border-bottom:1px solid #f1f3f8">Cashier</th>
                            <th style="text-align:left;font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;padding:12px 24px;border-bottom:1px solid #f1f3f8">Transactions</th>
                            <th style="text-align:left;font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;padding:12px 24px;border-bottom:1px solid #f1f3f8">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($summary['sales_by_cashier'] as $cashier)
                            <tr style="border-bottom:1px solid #f8f9fb">
                                <td style="padding:14px 24px;font-size:13px;font-weight:600;color:#02182F">{{ $cashier['name'] }}</td>
                                <td style="padding:14px 24px;font-size:13px;color:#6b7280">{{ $cashier['transactions'] }}</td>
                                <td style="padding:14px 24px;font-family:'JetBrains Mono',monospace;font-weight:700;color:#02182F">KES {{ number_format($cashier['total'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            {{-- Close Shift Form --}}
            <div style="background:#FFFEFE;border:1px solid #e4e7ef;border-radius:14px;padding:24px">
                <h3 style="font-family:'Outfit',sans-serif;font-size:14px;font-weight:700;color:#02182F;margin:0 0 4px">Close the Shift</h3>
                <p style="font-size:11px;color:#9ca3af;margin:0 0 24px;font-family:'Outfit',sans-serif">Enter the opening float and count the actual cash in the drawer to calculate variance.</p>

                <form method="POST" action="{{ route('zreports.store') }}">
                    @csrf
                    <input type="hidden" name="report_date" value="{{ $date }}" />

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
                        <div>
                            <label style="font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px;display:block;font-family:'Outfit',sans-serif">
                                Opening Cash Float (KES) *
                            </label>
                            <input type="number" name="opening_float" step="0.01" min="0"
                                value="{{ old('opening_float', '0.00') }}"
                                style="width:100%;padding:10px 14px;border:1.5px solid #e4e7ef;border-radius:10px;font-family:'JetBrains Mono',monospace;font-size:16px;font-weight:700;color:#02182F;background:#fafbff;outline:none"
                                onfocus="this.style.borderColor='#03A737';this.style.boxShadow='0 0 0 3px rgba(3,167,55,0.08)'"
                                onblur="this.style.borderColor='#e4e7ef';this.style.boxShadow='none'"
                                placeholder="0.00" required />
                            <p style="font-size:10px;color:#9ca3af;margin-top:4px;font-family:'Outfit',sans-serif">Cash that was in the drawer at start of shift</p>
                        </div>
                        <div>
                            <label style="font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px;display:block;font-family:'Outfit',sans-serif">
                                Actual Cash in Drawer (KES) *
                            </label>
                            <input type="number" name="actual_cash" step="0.01" min="0" id="actual-cash"
                                value="{{ old('actual_cash', '0.00') }}"
                                style="width:100%;padding:10px 14px;border:1.5px solid #e4e7ef;border-radius:10px;font-family:'JetBrains Mono',monospace;font-size:16px;font-weight:700;color:#02182F;background:#fafbff;outline:none"
                                onfocus="this.style.borderColor='#03A737';this.style.boxShadow='0 0 0 3px rgba(3,167,55,0.08)'"
                                onblur="this.style.borderColor='#e4e7ef';this.style.boxShadow='none'"
                                placeholder="0.00" required oninput="calcVariance()" />
                            <p style="font-size:10px;color:#9ca3af;margin-top:4px;font-family:'Outfit',sans-serif">Count the physical cash now and enter the total</p>
                        </div>
                    </div>

                    {{-- Live variance preview --}}
                    <div style="background:#fafbff;border:1px solid #e4e7ef;border-radius:10px;padding:16px;margin-bottom:16px" id="variance-preview">
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;text-align:center">
                            <div>
                                <p style="font-size:10px;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;margin:0 0 4px;font-family:'Outfit',sans-serif">Expected Cash</p>
                                <p style="font-family:'JetBrains Mono',monospace;font-size:18px;font-weight:700;color:#02182F;margin:0" id="expected-display">
                                    KES {{ number_format($summary['total_cash_sales'] - $summary['total_cash_refunds'] - $summary['total_cash_expenses'], 2) }}
                                </p>
                                <p style="font-size:10px;color:#9ca3af;margin:2px 0 0;font-family:'Outfit',sans-serif">Float + Cash Sales − Refunds − Expenses</p>
                            </div>
                            <div>
                                <p style="font-size:10px;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;margin:0 0 4px;font-family:'Outfit',sans-serif">Actual Cash</p>
                                <p style="font-family:'JetBrains Mono',monospace;font-size:18px;font-weight:700;color:#02182F;margin:0" id="actual-display">KES 0.00</p>
                                <p style="font-size:10px;color:#9ca3af;margin:2px 0 0;font-family:'Outfit',sans-serif">What you physically counted</p>
                            </div>
                            <div>
                                <p style="font-size:10px;font-weight:600;color:#9ca3af;text-transform:uppercase;letter-spacing:0.06em;margin:0 0 4px;font-family:'Outfit',sans-serif">Variance</p>
                                <p style="font-family:'JetBrains Mono',monospace;font-size:18px;font-weight:700;color:#374151;margin:0" id="variance-display">KES 0.00</p>
                                <p style="font-size:10px;color:#9ca3af;margin:2px 0 0;font-family:'Outfit',sans-serif" id="variance-label">Enter amounts above</p>
                            </div>
                        </div>
                    </div>

                    <div style="margin-bottom:24px">
                        <label style="font-size:11px;font-weight:600;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;margin-bottom:8px;display:block;font-family:'Outfit',sans-serif">
                            Notes (optional)
                        </label>
                        <textarea name="notes" rows="2"
                            style="width:100%;padding:10px 14px;border:1.5px solid #e4e7ef;border-radius:10px;font-family:'Outfit',sans-serif;font-size:13px;color:#02182F;background:#fafbff;outline:none;resize:vertical;min-height:60px"
                            onfocus="this.style.borderColor='#03A737';this.style.boxShadow='0 0 0 3px rgba(3,167,55,0.08)'"
                            onblur="this.style.borderColor='#e4e7ef';this.style.boxShadow='none'"
                            placeholder="Any notes about today's shift, discrepancies, or handover instructions...">{{ old('notes') }}</textarea>
                    </div>

                    <div style="display:flex;gap:12px">
                        <button type="submit"
                            onclick="return confirm('Are you sure you want to close this shift? This cannot be undone.')"
                            style="background:#03A737;color:#FFFEFE;padding:11px 32px;border-radius:10px;font-size:13px;font-weight:600;font-family:'Outfit',sans-serif;border:none;cursor:pointer;box-shadow:0 2px 8px rgba(3,167,55,0.25);transition:all .15s"
                            onmouseover="this.style.background='#028a2e';this.style.boxShadow='0 4px 12px rgba(3,167,55,0.35)'"
                            onmouseout="this.style.background='#03A737';this.style.boxShadow='0 2px 8px rgba(3,167,55,0.25)'">
                            Close Shift & Generate Z-Report
                        </button>
                        <a href="{{ route('zreports.index') }}"
                           style="padding:11px 24px;font-size:13px;font-weight:600;color:#6b7280;border:1.5px solid #e4e7ef;border-radius:10px;text-decoration:none;font-family:'Outfit',sans-serif;transition:all .15s"
                           onmouseover="this.style.borderColor='#03A737';this.style.color='#03A737';this.style.background='#e6f7eb'"
                           onmouseout="this.style.borderColor='#e4e7ef';this.style.color='#6b7280';this.style.background='transparent'">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
const cashSales    = {{ $summary['total_cash_sales'] }};
const cashRefunds  = {{ $summary['total_cash_refunds'] }};
const cashExpenses = {{ $summary['total_cash_expenses'] }};

function calcVariance() {
    const openingFloat = parseFloat(document.querySelector('[name="opening_float"]').value) || 0;
    const actualCash   = parseFloat(document.getElementById('actual-cash').value) || 0;
    const expected     = openingFloat + cashSales - cashRefunds - cashExpenses;
    const variance     = actualCash - expected;

    document.getElementById('expected-display').textContent = 'KES ' + expected.toFixed(2);
    document.getElementById('actual-display').textContent   = 'KES ' + actualCash.toFixed(2);
    document.getElementById('variance-display').textContent = (variance >= 0 ? '+' : '') + 'KES ' + variance.toFixed(2);

    const el    = document.getElementById('variance-display');
    const label = document.getElementById('variance-label');

    if (Math.abs(variance) < 0.01) {
        el.style.color    = '#03A737';
        label.textContent = 'Drawer is balanced';
        label.style.color = '#03A737';
    } else if (variance > 0) {
        el.style.color    = '#3D7BE7';
        label.textContent = 'Drawer is OVER by KES ' + variance.toFixed(2);
        label.style.color = '#3D7BE7';
    } else {
        el.style.color    = '#dc2626';
        label.textContent = 'Drawer is SHORT by KES ' + Math.abs(variance).toFixed(2);
        label.style.color = '#dc2626';
    }
}

document.querySelector('[name="opening_float"]').addEventListener('input', calcVariance);
</script>
</x-app-layout>