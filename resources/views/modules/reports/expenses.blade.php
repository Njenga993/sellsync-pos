<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Expense Tracker</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Date Filter --}}
            <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
                <form method="GET" action="{{ route('expenses.index') }}"
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
                    <a href="{{ route('expenses.index') }}"
                        class="px-4 py-2 text-sm text-gray-500 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Reset
                    </a>
                </form>
            </div>

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-xs text-gray-500 uppercase">Total Expenses</p>
                    <p class="text-2xl font-bold text-red-500 mt-1">
                        KES {{ number_format($summary->total_expenses ?? 0, 2) }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">{{ $summary->total_count ?? 0 }} records</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-xs text-gray-500 uppercase">Total Revenue</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">
                        KES {{ number_format($totalRevenue, 2) }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">Same period</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <p class="text-xs text-gray-500 uppercase">Net Profit</p>
                    @php $net = $totalRevenue - ($summary->total_expenses ?? 0); @endphp
                    <p class="text-2xl font-bold {{ $net >= 0 ? 'text-indigo-600' : 'text-red-500' }} mt-1">
                        KES {{ number_format($net, 2) }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">Revenue minus expenses</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- LEFT: Forms --}}
                <div class="space-y-4">

                    {{-- Add Expense Form --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h3 class="text-sm font-semibold text-gray-700 mb-4">Record Expense</h3>
                        <form method="POST" action="{{ route('expenses.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="text-xs text-gray-500 block mb-1">Title *</label>
                                <input type="text" name="title" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                                    placeholder="e.g. Electricity bill" />
                            </div>

                            <div class="mb-3">
                                <label class="text-xs text-gray-500 block mb-1">Amount (KES) *</label>
                                <input type="number" name="amount" step="0.01" min="0" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                                    placeholder="0.00" />
                            </div>

                            <div class="mb-3">
                                <label class="text-xs text-gray-500 block mb-1">Date *</label>
                                <input type="date" name="expense_date" required
                                    value="{{ now()->toDateString() }}"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                            </div>

                            <div class="mb-3">
                                <label class="text-xs text-gray-500 block mb-1">Category</label>
                                <select name="expense_category_id"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                    <option value="">-- No category --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="text-xs text-gray-500 block mb-1">Payment Method</label>
                                <select name="payment_method"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                    <option value="cash">Cash</option>
                                    <option value="card">Card</option>
                                    <option value="mobile">M-Pesa</option>
                                    <option value="bank">Bank Transfer</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="text-xs text-gray-500 block mb-1">Reference (optional)</label>
                                <input type="text" name="reference"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                                    placeholder="Receipt or invoice number" />
                            </div>

                            <div class="mb-4">
                                <label class="text-xs text-gray-500 block mb-1">Notes (optional)</label>
                                <textarea name="notes" rows="2"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                                    placeholder="Any additional details"></textarea>
                            </div>

                            <button type="submit"
                                class="w-full bg-indigo-600 text-white py-2.5 rounded-lg text-sm font-semibold hover:bg-indigo-700">
                                Save Expense
                            </button>
                        </form>
                    </div>

                    {{-- Add Category Form --}}
                    <div class="bg-white rounded-xl border border-gray-200 p-6">
                        <h3 class="text-sm font-semibold text-gray-700 mb-4">Add Category</h3>
                        <form method="POST" action="{{ route('expense-categories.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="text-xs text-gray-500 block mb-1">Category Name *</label>
                                <input type="text" name="name" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                                    placeholder="e.g. Utilities, Rent, Salaries" />
                            </div>

                            <div class="mb-4">
                                <label class="text-xs text-gray-500 block mb-1">Colour</label>
                                <div class="flex items-center gap-3">
                                    <input type="color" name="color" value="#6366f1"
                                        class="w-10 h-10 rounded border border-gray-300 cursor-pointer" />
                                    <span class="text-xs text-gray-500">Pick a colour</span>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full bg-gray-700 text-white py-2.5 rounded-lg text-sm font-semibold hover:bg-gray-800">
                                Save Category
                            </button>
                        </form>
                    </div>

                    {{-- By Category Breakdown --}}
                    @if($byCategory->count() > 0)
                    <div class="bg-white rounded-xl border border-gray-200 p-5">
                        <h3 class="text-sm font-semibold text-gray-700 mb-4">By Category</h3>
                        @foreach($byCategory as $cat)
                            @php
                                $total = $summary->total_expenses ?? 1;
                                $pct   = $total > 0 ? round(($cat->total / $total) * 100) : 0;
                                $color = $cat->category->color ?? '#6366f1';
                            @endphp
                            <div class="mb-3">
                                <div class="flex justify-between text-sm mb-1">
                                    <span class="text-gray-600">{{ $cat->category->name ?? 'Uncategorised' }}</span>
                                    <span class="font-medium">KES {{ number_format($cat->total, 2) }}</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2">
                                    <div class="h-2 rounded-full" style="width:{{ $pct }}%;background:{{ $color }}"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- RIGHT: Expense List --}}
                <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-sm font-semibold text-gray-700">All Expenses</h3>
                        <span class="text-xs text-gray-400">{{ $expenses->total() }} records</span>
                    </div>
                    <table class="min-w-full divide-y divide-gray-100 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($expenses as $expense)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-gray-500">
                                        {{ $expense->expense_date->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-gray-800">{{ $expense->title }}</p>
                                        @if($expense->notes)
                                            <p class="text-xs text-gray-400">{{ $expense->notes }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($expense->category)
                                            <span class="px-2 py-1 rounded-full text-xs font-medium text-white"
                                                style="background:{{ $expense->category->color }}">
                                                {{ $expense->category->name }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-xs">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 text-xs capitalize">
                                        {{ $expense->payment_method }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-red-500">
                                        KES {{ number_format($expense->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <form method="POST" action="{{ route('expenses.destroy', $expense) }}"
                                              onsubmit="return confirm('Delete this expense?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="text-red-400 hover:text-red-600 text-xs">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                        No expenses recorded yet. Use the form on the left to add one.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @if($expenses->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100">
                            {{ $expenses->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>