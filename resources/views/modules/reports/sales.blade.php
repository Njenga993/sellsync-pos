<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Sales Report</h2>
            <a href="{{ route('pos.index') }}"
               class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
                🖥️ Open POS
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Date Filter --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
                <form method="GET" action="{{ route('reports.sales') }}" class="flex items-end gap-4 flex-wrap">
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">From</label>
                        <input type="date" name="from" value="{{ $from }}"
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">To</label>
                        <input type="date" name="to" value="{{ $to }}"
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                    </div>
                    <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
                        Apply Filter
                    </button>
                    <a href="{{ route('reports.sales') }}"
                        class="px-4 py-2 text-sm text-gray-500 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Reset
                    </a>
                </form>
            </div>

            {{-- Summary Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-xs text-gray-500 uppercase">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">
                        KES {{ number_format($summary->total_revenue ?? 0, 2) }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">{{ $summary->total_transactions ?? 0 }} transactions</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-xs text-gray-500 uppercase">Average Sale</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">
                        KES {{ number_format($summary->avg_sale ?? 0, 2) }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">Per transaction</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-xs text-gray-500 uppercase">Total Tax</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">
                        KES {{ number_format($summary->total_tax ?? 0, 2) }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">Collected</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-xs text-gray-500 uppercase">Discounts Given</p>
                    <p class="text-2xl font-bold text-red-500 mt-1">
                        KES {{ number_format($summary->total_discount ?? 0, 2) }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">Total deducted</p>
                </div>
            </div>

            {{-- Payment Breakdown + Top Products --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">

                {{-- Payment Methods --}}
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Payment Methods</h3>
                    @php
                        $totalRev = $summary->total_revenue ?? 1;
                        $methods = [
                            'Cash'   => $summary->cash_total   ?? 0,
                            'Card'   => $summary->card_total   ?? 0,
                            'M-Pesa' => $summary->mobile_total ?? 0,
                        ];
                    @endphp
                    @foreach($methods as $label => $amount)
                        @php $pct = $totalRev > 0 ? round(($amount / $totalRev) * 100) : 0; @endphp
                        <div class="mb-3">
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">{{ $label }}</span>
                                <span class="font-medium text-gray-800">
                                    KES {{ number_format($amount, 2) }}
                                    <span class="text-xs text-gray-400">({{ $pct }}%)</span>
                                </span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Top Products --}}
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Top Selling Products</h3>
                    @forelse($topProducts as $product)
                        <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $product->product_name }}</p>
                                <p class="text-xs text-gray-400">{{ $product->total_qty }} units sold</p>
                            </div>
                            <span class="text-sm font-semibold text-indigo-600">
                                KES {{ number_format($product->total_revenue, 2) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 text-center py-4">No sales data yet.</p>
                    @endforelse
                </div>
            </div>

            {{-- Daily Breakdown --}}
            @if($dailySales->count() > 0)
            <div class="bg-white rounded-xl border border-gray-200 p-5 mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Daily Breakdown</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-xs text-gray-500 uppercase">
                                <th class="text-left py-2 pr-6">Date</th>
                                <th class="text-left py-2 pr-6">Transactions</th>
                                <th class="text-left py-2">Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($dailySales as $day)
                                <tr>
                                    <td class="py-2 pr-6 text-gray-600">
                                        {{ \Carbon\Carbon::parse($day->date)->format('D, d M Y') }}
                                    </td>
                                    <td class="py-2 pr-6 text-gray-600">{{ $day->transactions }}</td>
                                    <td class="py-2 font-semibold text-gray-800">
                                        KES {{ number_format($day->revenue, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- Sales Transaction List --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-700">All Transactions</h3>
                    <span class="text-xs text-gray-400">{{ $sales->total() }} records</span>
                </div>
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cashier</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Receipt</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($sales as $sale)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-mono text-xs text-gray-600">
                                    {{ $sale->invoice_no }}
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    {{ $sale->created_at->format('d M Y') }}
                                    <span class="block text-xs text-gray-400">{{ $sale->created_at->format('h:i A') }}</span>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $sale->customer->name ?? 'Walk-in' }}
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $sale->user->name ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $sale->items->count() }} item(s)
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        {{ $sale->payment_method === 'cash'   ? 'bg-green-100 text-green-700'  : '' }}
                                        {{ $sale->payment_method === 'card'   ? 'bg-blue-100 text-blue-700'    : '' }}
                                        {{ $sale->payment_method === 'mobile' ? 'bg-yellow-100 text-yellow-700': '' }}">
                                        {{ ucfirst($sale->payment_method) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-semibold text-gray-800">
                                    KES {{ number_format($sale->total, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('pos.receipt', $sale) }}"
                                       target="_blank"
                                       class="text-indigo-600 hover:underline text-xs">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                    No sales found for this period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($sales->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $sales->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>