<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('zreports.index') }}" class="text-gray-400 hover:text-gray-600">← Back</a>
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">
                        Z-Report — {{ $zreport->report_date->format('d F Y') }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-0.5">
                        Closed by {{ $zreport->user->name ?? '—' }}
                        at {{ $zreport->closed_at->format('h:i A') }}
                        · {{ $zreport->branch->name ?? '—' }}
                    </p>
                </div>
            </div>
            <button onclick="window.print()"
                class="border border-gray-300 text-gray-600 px-4 py-2 rounded-xl text-sm font-medium hover:bg-gray-50 flex items-center gap-2">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print Report
            </button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Variance Alert --}}
            @if($zreport->cash_variance != 0)
                <div class="mb-6 p-4 rounded-xl border flex items-center gap-3
                    {{ $zreport->cash_variance > 0
                        ? 'bg-blue-50 border-blue-200'
                        : 'bg-red-50 border-red-200' }}">
                    <svg width="20" height="20" fill="none" stroke="{{ $zreport->cash_variance > 0 ? '#2563eb' : '#dc2626' }}" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold {{ $zreport->cash_variance > 0 ? 'text-blue-800' : 'text-red-800' }}">
                            Cash drawer is {{ $zreport->cash_variance > 0 ? 'OVER' : 'SHORT' }}
                            by KES {{ number_format(abs($zreport->cash_variance), 2) }}
                        </p>
                        <p class="text-xs {{ $zreport->cash_variance > 0 ? 'text-blue-600' : 'text-red-600' }}">
                            Expected KES {{ number_format($zreport->expected_cash, 2) }}
                            · Counted KES {{ number_format($zreport->actual_cash, 2) }}
                        </p>
                    </div>
                </div>
            @else
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center gap-3">
                    <svg width="20" height="20" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-semibold text-green-800">Cash drawer is balanced — no variance</p>
                </div>
            @endif

            {{-- Main Report Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

                {{-- Sales Breakdown --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-sm font-semibold text-gray-700">Sales Breakdown</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        <div class="px-6 py-3 flex justify-between items-center">
                            <span class="text-sm text-gray-600">Cash Sales</span>
                            <span class="font-mono font-semibold text-gray-800">KES {{ number_format($zreport->total_cash_sales, 2) }}</span>
                        </div>
                        <div class="px-6 py-3 flex justify-between items-center">
                            <span class="text-sm text-gray-600">M-Pesa Sales</span>
                            <span class="font-mono font-semibold text-gray-800">KES {{ number_format($zreport->total_mobile_sales, 2) }}</span>
                        </div>
                        <div class="px-6 py-3 flex justify-between items-center">
                            <span class="text-sm text-gray-600">Card Sales</span>
                            <span class="font-mono font-semibold text-gray-800">KES {{ number_format($zreport->total_card_sales, 2) }}</span>
                        </div>
                        <div class="px-6 py-3 flex justify-between items-center">
                            <span class="text-sm text-gray-600">Split Payments</span>
                            <span class="font-mono font-semibold text-gray-800">KES {{ number_format($zreport->total_split_sales, 2) }}</span>
                        </div>
                        <div class="px-6 py-3 flex justify-between items-center bg-gray-50">
                            <span class="text-sm font-bold text-gray-800">Total Sales</span>
                            <span class="font-mono font-bold text-indigo-600 text-base">KES {{ number_format($zreport->total_sales, 2) }}</span>
                        </div>
                        <div class="px-6 py-3 flex justify-between items-center">
                            <span class="text-sm text-gray-500">Total Transactions</span>
                            <span class="font-semibold text-gray-700">{{ $zreport->total_transactions }}</span>
                        </div>
                    </div>
                </div>

                {{-- Cash Reconciliation --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                        <h3 class="text-sm font-semibold text-gray-700">Cash Reconciliation</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        <div class="px-6 py-3 flex justify-between items-center">
                            <span class="text-sm text-gray-600">Opening Float</span>
                            <span class="font-mono font-semibold text-gray-800">KES {{ number_format($zreport->opening_float, 2) }}</span>
                        </div>
                        <div class="px-6 py-3 flex justify-between items-center">
                            <span class="text-sm text-gray-600">+ Cash Sales</span>
                            <span class="font-mono font-semibold text-green-600">+ KES {{ number_format($zreport->total_cash_sales, 2) }}</span>
                        </div>
                        <div class="px-6 py-3 flex justify-between items-center">
                            <span class="text-sm text-gray-600">− Cash Refunds</span>
                            <span class="font-mono font-semibold text-red-500">− KES {{ number_format($zreport->total_cash_refunds, 2) }}</span>
                        </div>
                        <div class="px-6 py-3 flex justify-between items-center">
                            <span class="text-sm text-gray-600">− Cash Expenses</span>
                            <span class="font-mono font-semibold text-red-500">− KES {{ number_format($zreport->total_cash_expenses, 2) }}</span>
                        </div>
                        <div class="px-6 py-3 flex justify-between items-center bg-gray-50">
                            <span class="text-sm font-bold text-gray-800">Expected in Drawer</span>
                            <span class="font-mono font-bold text-gray-800">KES {{ number_format($zreport->expected_cash, 2) }}</span>
                        </div>
                        <div class="px-6 py-3 flex justify-between items-center">
                            <span class="text-sm text-gray-600">Actual Cash Counted</span>
                            <span class="font-mono font-semibold text-gray-800">KES {{ number_format($zreport->actual_cash, 2) }}</span>
                        </div>
                        <div class="px-6 py-3 flex justify-between items-center bg-gray-50">
                            <span class="text-sm font-bold text-gray-800">Variance</span>
                            <span class="font-mono font-bold text-base {{ $zreport->variance_color }}">
                                {{ $zreport->cash_variance >= 0 ? '+' : '' }}KES {{ number_format($zreport->cash_variance, 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Deductions --}}
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-700">Refunds Issued</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        <div class="px-6 py-3 flex justify-between">
                            <span class="text-sm text-gray-600">Total Refunds</span>
                            <span class="font-mono font-semibold text-red-500">KES {{ number_format($zreport->total_refunds, 2) }}</span>
                        </div>
                        <div class="px-6 py-3 flex justify-between">
                            <span class="text-sm text-gray-600">Cash Refunds</span>
                            <span class="font-mono font-semibold text-gray-700">KES {{ number_format($zreport->total_cash_refunds, 2) }}</span>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-700">Expenses Paid Out</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        <div class="px-6 py-3 flex justify-between">
                            <span class="text-sm text-gray-600">Total Expenses</span>
                            <span class="font-mono font-semibold text-orange-500">KES {{ number_format($zreport->total_expenses, 2) }}</span>
                        </div>
                        <div class="px-6 py-3 flex justify-between">
                            <span class="text-sm text-gray-600">Cash Expenses</span>
                            <span class="font-mono font-semibold text-gray-700">KES {{ number_format($zreport->total_cash_expenses, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Notes --}}
            @if($zreport->notes)
            <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Shift Notes</h3>
                <p class="text-sm text-gray-600">{{ $zreport->notes }}</p>
            </div>
            @endif

            {{-- Transactions List --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-sm font-semibold text-gray-700">All Transactions</h3>
                    <span class="text-xs text-gray-400">{{ $sales->count() }} transactions</span>
                </div>
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cashier</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($sales as $sale)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 text-gray-500">{{ $sale->created_at->format('h:i A') }}</td>
                                <td class="px-6 py-3 font-mono text-xs text-indigo-600 font-semibold">{{ $sale->invoice_no }}</td>
                                <td class="px-6 py-3 text-gray-600">{{ $sale->user->name ?? '—' }}</td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $sale->payment_method === 'cash'   ? 'bg-green-100 text-green-700'  : '' }}
                                        {{ $sale->payment_method === 'mobile' ? 'bg-blue-100 text-blue-700'    : '' }}
                                        {{ $sale->payment_method === 'card'   ? 'bg-purple-100 text-purple-700': '' }}
                                        {{ str_contains($sale->payment_method, '+') ? 'bg-orange-100 text-orange-700' : '' }}">
                                        {{ ucfirst($sale->payment_method) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-right font-mono font-semibold text-gray-800">
                                    KES {{ number_format($sale->total, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-400">No transactions recorded for this day.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="4" class="px-6 py-3 text-right text-sm font-bold text-gray-700">Total</td>
                            <td class="px-6 py-3 text-right font-mono font-bold text-indigo-600">
                                KES {{ number_format($sales->sum('total'), 2) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Expenses List --}}
            @if($expenses->count() > 0)
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-700">Expenses Recorded</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Expense</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($expenses as $expense)
                            <tr>
                                <td class="px-6 py-3 font-medium text-gray-800">{{ $expense->title }}</td>
                                <td class="px-6 py-3 text-gray-500">{{ $expense->category->name ?? '—' }}</td>
                                <td class="px-6 py-3 text-gray-500 capitalize">{{ $expense->payment_method }}</td>
                                <td class="px-6 py-3 text-right font-mono font-semibold text-orange-600">
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