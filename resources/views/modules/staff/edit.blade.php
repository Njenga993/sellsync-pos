<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('staff.index') }}" class="text-gray-400 hover:text-gray-600">← Back</a>
            <h2 class="text-xl font-semibold text-gray-800">Edit Staff Member</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <form method="POST" action="{{ route('staff.update', $staff) }}">
                    @csrf @method('PUT')

                    <div class="mb-4">
                        <x-input-label for="name" value="Full Name *" />
                        <x-text-input id="name" name="name" type="text"
                            class="block mt-1 w-full"
                            :value="old('name', $staff->name)" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="email" value="Email Address *" />
                            <x-text-input id="email" name="email" type="email"
                                class="block mt-1 w-full"
                                :value="old('email', $staff->email)" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="phone" value="Phone Number" />
                            <x-text-input id="phone" name="phone" type="text"
                                class="block mt-1 w-full"
                                :value="old('phone', $staff->phone)" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="role" value="Role *" />
                            <select id="role" name="role"
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ old('role', $staff->roles->first()?->name) === $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="branch_id" value="Branch *" />
                            <select id="branch_id" name="branch_id"
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ old('branch_id', $staff->branch_id) == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="status" value="Status" />
                            <select id="status" name="status"
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="active"   {{ old('status', $staff->status) === 'active'   ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $staff->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <x-input-label for="password" value="New Password (leave blank to keep)" />
                            <x-text-input id="password" name="password" type="password"
                                class="block mt-1 w-full" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="password_confirmation" value="Confirm Password" />
                            <x-text-input id="password_confirmation" name="password_confirmation"
                                type="password" class="block mt-1 w-full" />
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <x-primary-button>Update Staff Member</x-primary-button>
                        <a href="{{ route('staff.index') }}"
                           class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>