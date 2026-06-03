<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('products.index') }}" class="text-gray-400 hover:text-gray-600">← Back</a>
            <h2 class="text-xl font-semibold text-gray-800">Add Product</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('products.store') }}">
                @csrf

                <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Basic Information</h3>

                    <div class="mb-4">
                        <x-input-label for="name" value="Product Name *" />
                        <x-text-input id="name" name="name" type="text"
                            class="block mt-1 w-full" :value="old('name')"
                            placeholder="e.g. Coca Cola 500ml" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="category_id" value="Category" />
                            <select id="category_id" name="category_id"
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="">-- No category --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="status" value="Status" />
                            <select id="status" name="status"
                                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="description" value="Description (optional)" />
                        <textarea id="description" name="description" rows="3"
                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Pricing</h3>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <x-input-label for="price" value="Selling Price (KES) *" />
                            <x-text-input id="price" name="price" type="number"
                                step="0.01" class="block mt-1 w-full"
                                :value="old('price', '0.00')" required />
                            <x-input-error :messages="$errors->get('price')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="cost_price" value="Cost Price (KES)" />
                            <x-text-input id="cost_price" name="cost_price" type="number"
                                step="0.01" class="block mt-1 w-full"
                                :value="old('cost_price', '0.00')" />
                        </div>
                        <div>
                            <x-input-label for="tax_rate" value="Tax Rate (%)" />
                            <x-text-input id="tax_rate" name="tax_rate" type="number"
                                step="0.01" class="block mt-1 w-full"
                                :value="old('tax_rate', '0')" />
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Inventory</h3>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="sku" value="SKU (auto-generated if empty)" />
                            <x-text-input id="sku" name="sku" type="text"
                                class="block mt-1 w-full" :value="old('sku')"
                                placeholder="e.g. BEV-001" />
                        </div>
                        <div>
                            <x-input-label for="barcode" value="Barcode" />
                            <x-text-input id="barcode" name="barcode" type="text"
                                class="block mt-1 w-full" :value="old('barcode')"
                                placeholder="Scan or type barcode" />
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div>
                            <x-input-label for="stock_qty" value="Opening Stock" />
                            <x-text-input id="stock_qty" name="stock_qty" type="number"
                                class="block mt-1 w-full" :value="old('stock_qty', '0')" />
                        </div>
                        <div>
                            <x-input-label for="low_stock_alert" value="Low Stock Alert At" />
                            <x-text-input id="low_stock_alert" name="low_stock_alert" type="number"
                                class="block mt-1 w-full" :value="old('low_stock_alert', '5')" />
                        </div>
                        <div class="flex items-end pb-1">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="track_stock" value="1"
                                    {{ old('track_stock', '1') ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm" />
                                <span class="text-sm text-gray-700">Track stock</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <x-primary-button>Save Product</x-primary-button>
                    <a href="{{ route('products.index') }}"
                       class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>