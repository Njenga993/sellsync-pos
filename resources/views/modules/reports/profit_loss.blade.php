<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Profit & Loss Report</h2>
            <div class="flex gap-2">
                <a href="{{ route('reports.stock-valuation') }}"
                   class="border border-gray-300 text-gray-600 px-3 py-2 rounded-lg text-sm hover:bg-gray-50">
                    Stock Valuation
                </a>
                <a href="{{ route('reports.cashier-performance') }}"
                   class="border border-gray-300 text-gray-600 px-3 py-2 rounded-lg text-sm hover:bg-gray-50">
                    Cashier Performance
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Date Filter --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
                <form method="GET" action="{{ route('reports.profit-loss') }}"
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
                    <a href="{{ route('reports.profit-loss') }}"
                        class="px-4 py-2 text-sm text-gray-500 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Reset
                    </a>
                </form>
            </div>

            {{-- P&L Summary --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-sm font-semibold text-gray-700">
                        Profit & Loss Statement
                        <span class="text-xs font-normal text-gray-400 ml-2">
                            {{ \Carbon\Carbon::parse($from)->format('d M Y') }} —
                            {{ \Carbon\Carbon::parse($to)->format('d M Y') }}
                        </span>
                    </h3>
                </div>
                <div class="divide-y divide-gray-100">
                    <div class="px-6 py-4 flex justify-between items-center">
                        <div>
                            <p class="font-medium text-gray-800">Gross Revenue</p>
                            <p class="text-xs text-gray-400 mt-0.5">Total sales collected</p>
                        </div>
                        <p class="text-lg font-bold text-green-600">KES {{ number_format($revenue, 2) }}</p>
                    </div>
                    <div class="px-6 py-4 flex justify-between items-center bg-red-50">
                        <div>
                            <p class="font-medium text-gray-800">Cost of Goods Sold (COGS)</p>
                            <p class="text-xs text-gray-400 mt-0.5">Cost price × qty sold</p>
                        </div>
                        <p class="text-lg font-bold text-red-500">− KES {{ number_format($cogs, 2) }}</p>
                    </div>
                    <div class="px-6 py-4 flex justify-between items-center bg-red-50">
                        <div>
                            <p class="font-medium text-gray-800">Refunds Issued</p>
                            <p class="text-xs text-gray-400 mt-0.5">Total returned amount</p>
                        </div>
                        <p class="text-lg font-bold text-red-500">− KES {{ number_format($refunds, 2) }}</p>
                    </div>
                    <div class="px-6 py-4 flex justify-between items-center bg-blue-50">
                        <div>
                            <p class="font-semibold text-gray-800">Gross Profit</p>
                            <p class="text-xs text-gray-400 mt-0.5">Revenue − COGS − Refunds</p>
                        </div>
                        <p class="text-xl font-bold {{ $grossProfit >= 0 ? 'text-blue-600' : 'text-red-500' }}">
                            KES {{ number_format($grossProfit, 2) }}
                        </p>
                    </div>
                    <div class="px-6 py-4 flex justify-between items-center bg-red-50">
                        <div>
                            <p class="font-medium text-gray-800">Operating Expenses</p>
                            <p class="text-xs text-gray-400 mt-0.5">Rent, utilities, salaries, etc.</p>
                        </div>
                        <p class="text-lg font-bold text-red-500">− KES {{ number_format($expenses, 2) }}</p>
                    </div>
                    <div class="px-6 py-4 flex justify-between items-center
                        {{ $netProfit >= 0 ? 'bg-green-50' : 'bg-red-50' }}">
                        <div>
                            <p class="font-bold text-gray-800 text-base">Net Profit</p>
                            <p class="text-xs text-gray-400 mt-0.5">Gross profit − expenses</p>
                        </div>
                        <p class="text-2xl font-bold {{ $netProfit >= 0 ? 'text-green-600' : 'text-red-500' }}">
                            KES {{ number_format($netProfit, 2) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Monthly Breakdown --}}
                @if($monthlySales->count() > 0)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-700">Monthly Revenue</h3>
                    </div>
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Month</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transactions</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($monthlySales as $month)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 text-gray-700 font-medium">
                                        {{ \Carbon\Carbon::parse($month->month . '-01')->format('F Y') }}
                                    </td>
                                    <td class="px-6 py-3 text-gray-500">{{ $month->transactions }}</td>
                                    <td class="px-6 py-3 font-semibold text-green-600">
                                        KES {{ number_format($month->revenue, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                {{-- Expenses by Category --}}
                @if($expensesByCategory->count() > 0)
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Expenses by Category</h3>
                    @foreach($expensesByCategory as $cat)
                        @php
                            $pct   = $expenses > 0 ? round(($cat->total / $expenses) * 100) : 0;
                            $color = $cat->category->color ?? '#6366f1';
                        @endphp
                        <div class="mb-3">
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600">{{ $cat->category->name ?? 'Uncategorised' }}</span>
                                <span class="font-medium">
                                    KES {{ number_format($cat->total, 2) }}
                                    <span class="text-xs text-gray-400">({{ $pct }}%)</span>
                                </span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="h-2 rounded-full" style="width:{{ $pct }}%;background:{{ $color }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>