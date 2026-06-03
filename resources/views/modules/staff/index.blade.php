<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Staff Members</h2>
            <a href="{{ route('staff.create') }}"
               class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
                + Add Staff
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
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Branch</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($staff as $member)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-semibold text-xs">
                                            {{ strtoupper(substr($member->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $member->name }}</p>
                                            @if($member->phone)
                                                <p class="text-xs text-gray-400">{{ $member->phone }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $member->email }}</td>
                                <td class="px-6 py-4">
                                    @foreach($member->roles as $role)
                                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    {{ $member->branch->name ?? '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                        {{ $member->status === 'active'
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-gray-100 text-gray-500' }}">
                                        {{ ucfirst($member->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 flex gap-3 items-center">
                                    <a href="{{ route('staff.edit', $member) }}"
                                       class="text-indigo-600 hover:underline text-xs">Edit</a>
                                    @if($member->id !== auth()->id())
                                        <form method="POST" action="{{ route('staff.destroy', $member) }}"
                                              onsubmit="return confirm('Remove this staff member?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-500 hover:underline text-xs">Remove</button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-300">You</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                    No staff members yet.
                                    <a href="{{ route('staff.create') }}"
                                       class="text-indigo-600 hover:underline ml-1">Add your first staff member</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($staff->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $staff->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>