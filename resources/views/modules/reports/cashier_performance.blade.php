<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Cashier Performance</h2>
            <div class="flex gap-2">
                <a href="{{ route('reports.profit-loss') }}"
                   class="border border-gray-300 text-gray-600 px-3 py-2 rounded-lg text-sm hover:bg-gray-50">
                    P&L Report
                </a>
                <a href="{{ route('reports.stock-valuation') }}"
                   class="border border-gray-300 text-gray-600 px-3 py-2 rounded-lg text-sm hover:bg-gray-50">
                    Stock Valuation
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Date Filter --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
                <form method="GET" action="{{ route('reports.cashier-performance') }}"
                      class="flex items-end gap-4 flex-wrap">
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">From</label>
                        <input type="date" name="from" value="{{ $from }}"
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm" />
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 block mb-1">To</label>
                        <input type="date" name="to" value="{{ $to }}"
                            class="border border-gray-300 rounded-lg px-3 py-2 text-sm" />
                    </div>
                    <button type="submit"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
                        Apply
                    </button>
                    <a href="{{ route('reports.cashier-performance') }}"
                        class="px-4 py-2 text-sm text-gray-500 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Reset
                    </a>
                </form>
            </div>

            {{-- Cashier Leaderboard --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-700">Staff Performance</h3>
                </div>
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Staff Member</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Branch</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sales</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items Sold</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Avg Sale</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Returns</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($cashiers as $rank => $cashier)
                            <tr class="hover:bg-gray-50 {{ $rank === 0 ? 'bg-yellow-50' : '' }}">
                                <td class="px-6 py-4">
                                    @if($rank === 0)
                                        <span class="text-lg">🥇</span>
                                    @elseif($rank === 1)
                                        <span class="text-lg">🥈</span>
                                    @elseif($rank === 2)
                                        <span class="text-lg">🥉</span>
                                    @else
                                        <span class="text-gray-400 font-medium">{{ $rank + 1 }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-semibold text-xs">
                                            {{ strtoupper(substr($cashier->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $cashier->name }}</p>
                                            <p class="text-xs text-gray-400">{{ $cashier->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                                        {{ ucfirst($cashier->roles->first()?->name ?? '—') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-500 text-xs">{{ $cashier->branch->name ?? '—' }}</td>
                                <td class="px-6 py-4 font-semibold text-gray-800">{{ $cashier->total_sales }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $cashier->total_items }}</td>
                                <td class="px-6 py-4 text-gray-600">KES {{ number_format($cashier->avg_sale, 2) }}</td>
                                <td class="px-6 py-4">
                                    <span class="{{ $cashier->total_returns > 0 ? 'text-red-500 font-semibold' : 'text-gray-400' }}">
                                        {{ $cashier->total_returns }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-bold text-green-600">
                                    KES {{ number_format($cashier->total_revenue, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-gray-400">
                                    No sales data for this period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Top Products --}}
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-700">Top 10 Products by Revenue</h3>
                    </div>
                    <table class="min-w-full divide-y divide-gray-100 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Revenue</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Profit</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($topProducts as $product)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-medium text-gray-800">{{ $product->product_name }}</td>
                                    <td class="px-6 py-4 text-gray-500">{{ $product->total_qty }}</td>
                                    <td class="px-6 py-4 text-indigo-600 font-medium">
                                        KES {{ number_format($product->total_revenue, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-green-600 font-medium">
                                        KES {{ number_format($product->total_profit, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-400">
                                        No sales data yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Peak Hours --}}
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Peak Sales Hours</h3>
                    @forelse($hourly as $hour)
                        @php
                            $maxRevenue = $hourly->max('revenue');
                            $pct        = $maxRevenue > 0 ? round(($hour->revenue / $maxRevenue) * 100) : 0;
                            $label      = \Carbon\Carbon::createFromTime($hour->hour)->format('h:00 A');
                        @endphp
                        <div class="mb-2">
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-gray-600 w-20">{{ $label }}</span>
                                <span class="text-gray-500">{{ $hour->transactions }} sales</span>
                                <span class="font-medium text-gray-800">KES {{ number_format($hour->revenue, 2) }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-indigo-500 h-2 rounded-full" style="width:{{ $pct }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 text-center py-4">No sales data for this period.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>