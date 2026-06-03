<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Customers</h2>
            <a href="{{ route('customers.create') }}"
               class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
                + Add Customer
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">City</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Loyalty Points</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Spent</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($customers as $customer)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-800">{{ $customer->name }}</p>
                                    @if($customer->email)
                                        <p class="text-xs text-gray-400">{{ $customer->email }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $customer->phone ?? '—' }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $customer->city ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-yellow-50 text-yellow-700 rounded-full text-xs font-medium">
                                        {{ number_format($customer->loyalty_points) }} pts
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-700 font-medium">
                                    KES {{ number_format($customer->total_spent, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        {{ $customer->status === 'active'
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-gray-100 text-gray-500' }}">
                                        {{ ucfirst($customer->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 flex gap-3">
                                    <a href="{{ route('customers.show', $customer) }}"
                                       class="text-gray-500 hover:underline text-xs">View</a>
                                    <a href="{{ route('customers.edit', $customer) }}"
                                       class="text-indigo-600 hover:underline text-xs">Edit</a>
                                    <form method="POST" action="{{ route('customers.destroy', $customer) }}"
                                          onsubmit="return confirm('Delete this customer?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="text-red-500 hover:underline text-xs">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                    No customers yet.
                                    <a href="{{ route('customers.create') }}"
                                       class="text-indigo-600 hover:underline ml-1">Add your first customer</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($customers->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $customers->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>