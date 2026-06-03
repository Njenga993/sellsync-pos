<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Business Settings & Receipt Customisation</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
                @csrf

                {{-- Logo --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Business Logo</h3>

                    <div class="flex items-start gap-6">
                        <div class="flex-shrink-0">
                            @if($settings->logo_path)
                                <img src="{{ Storage::url($settings->logo_path) }}"
                                     alt="Business Logo"
                                     class="w-24 h-24 object-contain border border-gray-200 rounded-lg p-2" />
                            @else
                                <div class="w-24 h-24 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center">
                                    <span class="text-3xl">🏪</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <label class="text-xs text-gray-500 block mb-1">Upload Logo</label>
                            <input type="file" name="logo" accept="image/png,image/jpeg"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                            <p class="text-xs text-gray-400 mt-1">PNG or JPG, max 2MB. Recommended: 200x200px</p>

                            @if($settings->logo_path)
                                <label class="flex items-center gap-2 mt-3 cursor-pointer">
                                    <input type="checkbox" name="remove_logo" value="1"
                                        class="rounded border-gray-300 text-red-500" />
                                    <span class="text-xs text-red-500">Remove current logo</span>
                                </label>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Business Info --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Business Information</h3>
                    <p class="text-xs text-gray-400 mb-4">This information appears on your receipts.</p>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="phone" value="Phone Number" />
                            <x-text-input id="phone" name="phone" type="text"
                                class="block mt-1 w-full"
                                :value="old('phone', $settings->phone)"
                                placeholder="e.g. 0712 345 678" />
                        </div>
                        <div>
                            <x-input-label for="email" value="Email Address" />
                            <x-text-input id="email" name="email" type="email"
                                class="block mt-1 w-full"
                                :value="old('email', $settings->email)"
                                placeholder="info@yourbusiness.com" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="city" value="City / Town" />
                            <x-text-input id="city" name="city" type="text"
                                class="block mt-1 w-full"
                                :value="old('city', $settings->city)"
                                placeholder="e.g. Nairobi" />
                        </div>
                        <div>
                            <x-input-label for="website" value="Website (optional)" />
                            <x-text-input id="website" name="website" type="text"
                                class="block mt-1 w-full"
                                :value="old('website', $settings->website)"
                                placeholder="www.yourbusiness.com" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="address" value="Physical Address" />
                        <textarea id="address" name="address" rows="2"
                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('address', $settings->address) }}</textarea>
                    </div>
                </div>

                {{-- Tax & Currency --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Tax & Currency</h3>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <x-input-label for="currency_symbol" value="Currency Symbol *" />
                            <x-text-input id="currency_symbol" name="currency_symbol" type="text"
                                class="block mt-1 w-full"
                                :value="old('currency_symbol', $settings->currency_symbol)"
                                placeholder="KES" />
                        </div>
                        <div>
                            <x-input-label for="currency_code" value="Currency Code *" />
                            <x-text-input id="currency_code" name="currency_code" type="text"
                                class="block mt-1 w-full"
                                :value="old('currency_code', $settings->currency_code)"
                                placeholder="KES" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="tax_name" value="Tax Name" />
                            <x-text-input id="tax_name" name="tax_name" type="text"
                                class="block mt-1 w-full"
                                :value="old('tax_name', $settings->tax_name)"
                                placeholder="VAT" />
                        </div>
                        <div>
                            <x-input-label for="tax_number" value="Tax / PIN Number" />
                            <x-text-input id="tax_number" name="tax_number" type="text"
                                class="block mt-1 w-full"
                                :value="old('tax_number', $settings->tax_number)"
                                placeholder="P051234567A" />
                        </div>
                    </div>
                </div>

                {{-- Receipt Customisation --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Receipt Customisation</h3>

                    <div class="mb-4">
                        <x-input-label for="receipt_header" value="Receipt Header Message" />
                        <textarea id="receipt_header" name="receipt_header" rows="2"
                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm"
                            placeholder="e.g. Welcome to Nyakazi Organics!">{{ old('receipt_header', $settings->receipt_header) }}</textarea>
                        <p class="text-xs text-gray-400 mt-1">Shown at the top of every receipt</p>
                    </div>

                    <div class="mb-6">
                        <x-input-label for="receipt_footer" value="Receipt Footer Message" />
                        <textarea id="receipt_footer" name="receipt_footer" rows="2"
                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm"
                            placeholder="e.g. Thank you for your business!">{{ old('receipt_footer', $settings->receipt_footer) }}</textarea>
                        <p class="text-xs text-gray-400 mt-1">Shown at the bottom of every receipt</p>
                    </div>

                    <h4 class="text-xs font-semibold text-gray-600 uppercase mb-3">Show on Receipt</h4>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach([
                            ['receipt_show_logo',    'Business Logo'],
                            ['receipt_show_address', 'Business Address'],
                            ['receipt_show_phone',   'Phone Number'],
                            ['receipt_show_email',   'Email Address'],
                            ['receipt_show_tax',     'Tax / PIN Number'],
                            ['receipt_show_loyalty', 'Loyalty Points'],
                        ] as [$field, $label])
                            <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" name="{{ $field }}" value="1"
                                    {{ old($field, $settings->$field) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm" />
                                <span class="text-sm text-gray-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Receipt Preview --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6 mb-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4">Receipt Preview</h3>
                    <div class="flex justify-center">
                        <div class="border border-dashed border-gray-300 rounded-lg p-4 w-64 font-mono text-xs text-gray-700">
                            @if($settings->receipt_show_logo && $settings->logo_path)
                                <div class="text-center mb-2">
                                    <img src="{{ Storage::url($settings->logo_path) }}"
                                         alt="Logo" class="w-16 h-16 object-contain mx-auto" />
                                </div>
                            @endif
                            <p class="text-center font-bold text-sm">{{ auth()->user()->tenant->name }}</p>
                            @if($settings->receipt_show_address && $settings->address)
                                <p class="text-center">{{ $settings->address }}</p>
                            @endif
                            @if($settings->receipt_show_phone && $settings->phone)
                                <p class="text-center">{{ $settings->phone }}</p>
                            @endif
                            @if($settings->receipt_show_email && $settings->email)
                                <p class="text-center">{{ $settings->email }}</p>
                            @endif
                            @if($settings->receipt_header)
                                <p class="text-center mt-1 italic">{{ $settings->receipt_header }}</p>
                            @endif
                            <div class="border-t border-dashed border-gray-400 my-2"></div>
                            <div class="flex justify-between"><span>Invoice:</span><span>INV-XXXX-000001</span></div>
                            <div class="flex justify-between"><span>Date:</span><span>{{ now()->format('d/m/Y') }}</span></div>
                            <div class="border-t border-dashed border-gray-400 my-2"></div>
                            <div><p>Sample Product x 2</p><div class="flex justify-between"><span></span><span>{{ $settings->currency_symbol }} 200.00</span></div></div>
                            <div class="border-t border-dashed border-gray-400 my-2"></div>
                            <div class="flex justify-between font-bold"><span>TOTAL</span><span>{{ $settings->currency_symbol }} 200.00</span></div>
                            @if($settings->receipt_show_tax && $settings->tax_number)
                                <p class="mt-1">{{ $settings->tax_name }} No: {{ $settings->tax_number }}</p>
                            @endif
                            @if($settings->receipt_show_loyalty)
                                <p class="mt-1">Loyalty Points Earned: 2</p>
                            @endif
                            <div class="border-t border-dashed border-gray-400 my-2"></div>
                            @if($settings->receipt_footer)
                                <p class="text-center italic">{{ $settings->receipt_footer }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <x-primary-button>Save Settings</x-primary-button>
                    <a href="{{ route('dashboard') }}"
                       class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>