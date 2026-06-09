<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('zreports.index') }}" class="text-gray-400 hover:text-gray-600">← Back</a>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Close Shift — Z-Report</h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Sales Summary --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-xs text-gray-500 uppercase font-semibold tracking-wide">Total Sales</p>
                    <p class="text-2xl font-bold text-gray-800 mt-2 font-mono">KES {{ number_format($summary['total_sales'], 2) }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $summary['total_transactions'] }} transactions</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-xs text-gray-500 uppercase font-semibold tracking-wide">Cash Sales</p>
                    <p class="text-2xl font-bold text-green-600 mt-2 font-mono">KES {{ number_format($summary['total_cash_sales'], 2) }}</p>
                    <p class="text-xs text-gray-400 mt-1">Cash payments received</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-xs text-gray-500 uppercase font-semibold tracking-wide">M-Pesa Sales</p>
                    <p class="text-2xl font-bold text-blue-600 mt-2 font-mono">KES {{ number_format($summary['total_mobile_sales'], 2) }}</p>
                    <p class="text-xs text-gray-400 mt-1">Mobile money received</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-xs text-gray-500 uppercase font-semibold tracking-wide">Card Sales</p>
                    <p class="text-2xl font-bold text-purple-600 mt-2 font-mono">KES {{ number_format($summary['total_card_sales'], 2) }}</p>
                    <p class="text-xs text-gray-400 mt-1">Card payments received</p>
                </div>
            </div>

            {{-- Deductions Summary --}}
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-red-50 border border-red-100 rounded-xl p-5">
                    <p class="text-xs text-red-500 uppercase font-semibold tracking-wide">Total Refunds</p>
                    <p class="text-xl font-bold text-red-600 mt-2 font-mono">KES {{ number_format($summary['total_refunds'], 2) }}</p>
                    <p class="text-xs text-red-400 mt-1">KES {{ number_format($summary['total_cash_refunds'], 2) }} in cash</p>
                </div>
                <div class="bg-orange-50 border border-orange-100 rounded-xl p-5">
                    <p class="text-xs text-orange-500 uppercase font-semibold tracking-wide">Total Expenses</p>
                    <p class="text-xl font-bold text-orange-600 mt-2 font-mono">KES {{ number_format($summary['total_expenses'], 2) }}</p>
                    <p class="text-xs text-orange-400 mt-1">KES {{ number_format($summary['total_cash_expenses'], 2) }} paid in cash</p>
                </div>
                <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-5">
                    <p class="text-xs text-indigo-500 uppercase font-semibold tracking-wide">Split Payments</p>
                    <p class="text-xl font-bold text-indigo-600 mt-2 font-mono">KES {{ number_format($summary['total_split_sales'], 2) }}</p>
                    <p class="text-xs text-indigo-400 mt-1">Mixed payment sales</p>
                </div>
            </div>

            {{-- Cashier Breakdown --}}
            @if($summary['sales_by_cashier']->count() > 1)
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-700">Sales by Cashier</h3>
                </div>
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cashier</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transactions</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($summary['sales_by_cashier'] as $cashier)
                            <tr>
                                <td class="px-6 py-3 font-medium text-gray-800">{{ $cashier['name'] }}</td>
                                <td class="px-6 py-3 text-gray-600">{{ $cashier['transactions'] }}</td>
                                <td class="px-6 py-3 font-mono font-semibold text-gray-800">KES {{ number_format($cashier['total'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            {{-- Close Shift Form --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-1">Close the Shift</h3>
                <p class="text-xs text-gray-400 mb-6">Enter the opening float and count the actual cash in the drawer to calculate variance.</p>

                <form method="POST" action="{{ route('zreports.store') }}">
                    @csrf
                    <input type="hidden" name="report_date" value="{{ $date }}" />

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">
                                Opening Cash Float (KES) *
                            </label>
                            <input type="number" name="opening_float" step="0.01" min="0"
                                value="{{ old('opening_float', '0.00') }}"
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 text-lg font-bold font-mono focus:outline-none focus:ring-2 focus:ring-indigo-300"
                                placeholder="0.00" required />
                            <p class="text-xs text-gray-400 mt-1">Cash that was in the drawer at start of shift</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">
                                Actual Cash in Drawer (KES) *
                            </label>
                            <input type="number" name="actual_cash" step="0.01" min="0" id="actual-cash"
                                value="{{ old('actual_cash', '0.00') }}"
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 text-lg font-bold font-mono focus:outline-none focus:ring-2 focus:ring-indigo-300"
                                placeholder="0.00" required oninput="calcVariance()" />
                            <p class="text-xs text-gray-400 mt-1">Count the physical cash now and enter the total</p>
                        </div>
                    </div>

                    {{-- Live variance preview --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-4" id="variance-preview">
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Expected Cash</p>
                                <p class="text-lg font-bold font-mono text-gray-800" id="expected-display">
                                    KES {{ number_format($summary['total_cash_sales'] - $summary['total_cash_refunds'] - $summary['total_cash_expenses'], 2) }}
                                </p>
                                <p class="text-xs text-gray-400">Float + Cash Sales − Refunds − Expenses</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Actual Cash</p>
                                <p class="text-lg font-bold font-mono text-gray-800" id="actual-display">KES 0.00</p>
                                <p class="text-xs text-gray-400">What you physically counted</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Variance</p>
                                <p class="text-lg font-bold font-mono" id="variance-display" style="color:#374151">KES 0.00</p>
                                <p class="text-xs" id="variance-label" style="color:#9ca3af">Enter amounts above</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">
                            Notes (optional)
                        </label>
                        <textarea name="notes" rows="2"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                            placeholder="Any notes about today's shift, discrepancies, or handover instructions...">{{ old('notes') }}</textarea>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit"
                            onclick="return confirm('Are you sure you want to close this shift? This cannot be undone.')"
                            class="bg-indigo-600 text-white px-8 py-3 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition">
                            Close Shift & Generate Z-Report
                        </button>
                        <a href="{{ route('zreports.index') }}"
                           class="px-4 py-3 text-sm text-gray-600 border border-gray-300 rounded-xl hover:bg-gray-50">
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
        el.style.color    = '#16a34a';
        label.textContent = 'Drawer is balanced';
        label.style.color = '#16a34a';
    } else if (variance > 0) {
        el.style.color    = '#2563eb';
        label.textContent = 'Drawer is OVER by KES ' + variance.toFixed(2);
        label.style.color = '#2563eb';
    } else {
        el.style.color    = '#dc2626';
        label.textContent = 'Drawer is SHORT by KES ' + Math.abs(variance).toFixed(2);
        label.style.color = '#dc2626';
    }
}

document.querySelector('[name="opening_float"]').addEventListener('input', calcVariance);
</script>
</x-app-layout>