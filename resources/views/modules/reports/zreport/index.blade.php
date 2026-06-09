<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Z-Reports</h2>
                <p class="text-sm text-gray-500 mt-1">Daily end-of-day shift summaries</p>
            </div>
            <a href="{{ route('zreports.create') }}"
               class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-indigo-700 transition">
                Close Today's Shift
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Today's status --}}
            @if($todayReport)
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg width="16" height="16" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-green-800">Today's shift is closed</p>
                            <p class="text-xs text-green-600">Closed at {{ $todayReport->closed_at->format('h:i A') }} by {{ $todayReport->user->name }}</p>
                        </div>
                    </div>
                    <a href="{{ route('zreports.show', $todayReport) }}"
                       class="text-sm font-semibold text-green-700 hover:underline">
                        View Report →
                    </a>
                </div>
            @else
                <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-xl flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg width="16" height="16" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-yellow-800">Today's shift is still open</p>
                            <p class="text-xs text-yellow-600">{{ now()->format('l, d F Y') }} — Close the shift before end of day</p>
                        </div>
                    </div>
                    <a href="{{ route('zreports.create') }}"
                       class="text-sm font-semibold text-yellow-700 hover:underline">
                        Close Shift Now →
                    </a>
                </div>
            @endif

            {{-- Reports List --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-sm font-semibold text-gray-700">All Z-Reports</h3>
                    <span class="text-xs text-gray-400">{{ $reports->total() }} reports</span>
                </div>
                <table class="min-w-full divide-y divide-gray-100 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Branch</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Closed By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transactions</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Sales</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Variance</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($reports as $report)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-800">{{ $report->report_date->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-400">{{ $report->report_date->format('l') }}</p>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $report->branch->name ?? '—' }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $report->user->name ?? '—' }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $report->total_transactions }}</td>
                                <td class="px-6 py-4 font-semibold text-gray-800 font-mono">
                                    KES {{ number_format($report->total_sales, 2) }}
                                </td>
                                <td class="px-6 py-4 font-mono font-semibold {{ $report->variance_color }}">
                                    {{ $report->cash_variance >= 0 ? '+' : '' }}{{ number_format($report->cash_variance, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                                        {{ $report->status === 'closed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('zreports.show', $report) }}"
                                       class="text-indigo-600 hover:underline text-xs font-medium">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                    No Z-Reports yet.
                                    <a href="{{ route('zreports.create') }}"
                                       class="text-indigo-600 hover:underline ml-1">Close today's shift</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($reports->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">{{ $reports->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>