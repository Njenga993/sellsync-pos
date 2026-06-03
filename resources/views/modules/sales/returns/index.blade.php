<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Returns & Refunds</h2>
            <a href="{{ route('returns.create') }}"
               class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
                + Process Return
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

            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Return #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Refund Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Refund</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($returns as $return)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-mono text-xs text-indigo-600 font-semibold">
                                    {{ $return->return_number }}
                                </td>
                                <td class="px-6 py-4 font-mono text-xs text-gray-600">
                                    {{ $return->sale->invoice_no ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    {{ $return->created_at->format('d M Y') }}
                                    <span class="block text-xs text-gray-400">
                                        {{ $return->created_at->format('h:i A') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $return->items_count }} item(s)</td>
                                <td class="px-6 py-4 text-gray-500 capitalize">{{ $return->refund_method }}</td>
                                <td class="px-6 py-4 font-semibold text-red-500">
                                    KES {{ number_format($return->total_refund, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $return->status_color }}">
                                        {{ ucfirst($return->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('returns.show', $return) }}"
                                       class="text-indigo-600 hover:underline text-xs">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                    No returns processed yet.
                                    <a href="{{ route('returns.create') }}"
                                       class="text-indigo-600 hover:underline ml-1">Process a return</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($returns->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">{{ $returns->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>