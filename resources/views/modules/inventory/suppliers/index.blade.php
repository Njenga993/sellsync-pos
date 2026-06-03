<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Suppliers</h2>
            <div class="flex gap-2">
                <a href="{{ route('purchase-orders.index') }}"
                   class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50">
                    Purchase Orders
                </a>
                <a href="{{ route('suppliers.create') }}"
                   class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
                    + Add Supplier
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Supplier</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">City</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Orders</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($suppliers as $supplier)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-800">{{ $supplier->name }}</p>
                                    @if($supplier->email)
                                        <p class="text-xs text-gray-400">{{ $supplier->email }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $supplier->contact_person ?? '—' }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $supplier->phone ?? '—' }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $supplier->city ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs font-medium">
                                        {{ $supplier->purchase_orders_count }} POs
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        {{ $supplier->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ ucfirst($supplier->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 flex gap-3">
                                    <a href="{{ route('suppliers.edit', $supplier) }}"
                                       class="text-indigo-600 hover:underline text-xs">Edit</a>
                                    <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}"
                                          onsubmit="return confirm('Delete this supplier?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline text-xs">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                    No suppliers yet.
                                    <a href="{{ route('suppliers.create') }}"
                                       class="text-indigo-600 hover:underline ml-1">Add your first supplier</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($suppliers->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">{{ $suppliers->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>