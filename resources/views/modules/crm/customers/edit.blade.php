<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('customers.index') }}" class="text-gray-400 hover:text-gray-600">← Back</a>
            <h2 class="text-xl font-semibold text-gray-800">Edit Customer</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <form method="POST" action="{{ route('customers.update', $customer) }}">
                    @csrf @method('PUT')

                    <div class="mb-4">
                        <x-input-label for="name" value="Full Name *" />
                        <x-text-input id="name" name="name" type="text"
                            class="block mt-1 w-full"
                            :value="old('name', $customer->name)" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="phone" value="Phone Number" />
                            <x-text-input id="phone" name="phone" type="text"
                                class="block mt-1 w-full"
                                :value="old('phone', $customer->phone)" />
                        </div>
                        <div>
                            <x-input-label for="email" value="Email Address" />
                            <x-text-input id="email" name="email" type="email"
                                class="block mt-1 w-full"
                                :value="old('email', $customer->email)" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="city" value="City / Town" />
                            <x-text-input id="city" name="city" type="text"
                                class="block mt-1 w-full"
                                :value="old('city', $customer->city)" />
                        </div>
                        <div>
                            <x-input-label for="credit_limit" value="Credit Limit (KES)" />
                            <x-text-input id="credit_limit" name="credit_limit" type="number"
                                step="0.01" class="block mt-1 w-full"
                                :value="old('credit_limit', $customer->credit_limit)" />
                        </div>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="address" value="Address (optional)" />
                        <textarea id="address" name="address" rows="2"
                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('address', $customer->address) }}</textarea>
                    </div>

                    <div class="mb-6">
                        <x-input-label for="status" value="Status" />
                        <select id="status" name="status"
                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                            <option value="active"   {{ old('status', $customer->status) === 'active'   ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $customer->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="flex gap-3">
                        <x-primary-button>Update Customer</x-primary-button>
                        <a href="{{ route('customers.index') }}"
                           class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>