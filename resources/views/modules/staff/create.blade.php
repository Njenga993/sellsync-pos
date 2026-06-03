<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('staff.index') }}" class="text-gray-400 hover:text-gray-600">← Back</a>
            <h2 class="text-xl font-semibold text-gray-800">Add Staff Member</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <form method="POST" action="{{ route('staff.store') }}">
                    @csrf

                    <div class="mb-4">
                        <x-input-label for="name" value="Full Name *" />
                        <x-text-input id="name" name="name" type="text"
                            class="block mt-1 w-full" :value="old('name')"
                            placeholder="e.g. John Kamau" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="email" value="Email Address *" />
                            <x-text-input id="email" name="email" type="email"
                                class="block mt-1 w-full" :value="old('email')" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="phone" value="Phone Number" />
                            <x-text-input id="phone" name="phone" type="text"
                                class="block mt-1 w-full" :value="old('phone')"
                                placeholder="e.g. 0712 345 678" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="role" value="Role *" />
                            <select id="role" name="role"
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="">-- Select role --</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ old('role') === $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="branch_id" value="Branch *" />
                            <select id="branch_id" name="branch_id"
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="">-- Select branch --</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('branch_id')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <x-input-label for="password" value="Password *" />
                            <x-text-input id="password" name="password" type="password"
                                class="block mt-1 w-full" required />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="password_confirmation" value="Confirm Password *" />
                            <x-text-input id="password_confirmation" name="password_confirmation"
                                type="password" class="block mt-1 w-full" required />
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <x-primary-button>Add Staff Member</x-primary-button>
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