<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-semibold text-gray-800">Create your POS account</h1>
        <p class="text-sm text-gray-500 mt-1">Set up your business in under 2 minutes</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Business Information --}}
        <div class="mb-4">
            <x-input-label for="business_name" :value="__('Business Name')" />
            <x-text-input id="business_name" class="block mt-1 w-full" type="text"
                name="business_name" :value="old('business_name')" required autofocus
                placeholder="e.g. Kamau Supermarket" />
            <x-input-error :messages="$errors->get('business_name')" class="mt-2" />
        </div>

        <div class="mb-4">
            <x-input-label for="business_type" :value="__('Business Type')" />
            <select id="business_type" name="business_type"
                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500 text-sm">
                <option value="">-- Select business type --</option>
                <option value="retail"      {{ old('business_type') == 'retail'      ? 'selected' : '' }}>Retail Shop</option>
                <option value="restaurant"  {{ old('business_type') == 'restaurant'  ? 'selected' : '' }}>Restaurant / Cafe</option>
                <option value="salon"       {{ old('business_type') == 'salon'       ? 'selected' : '' }}>Salon / Spa</option>
                <option value="pharmacy"    {{ old('business_type') == 'pharmacy'    ? 'selected' : '' }}>Pharmacy</option>
                <option value="wholesale"   {{ old('business_type') == 'wholesale'   ? 'selected' : '' }}>Wholesale</option>
                <option value="other"       {{ old('business_type') == 'other'       ? 'selected' : '' }}>Other</option>
            </select>
            <x-input-error :messages="$errors->get('business_type')" class="mt-2" />
        </div>

        {{-- Owner Information --}}
        <div class="mb-4">
            <x-input-label for="name" :value="__('Your Full Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text"
                name="name" :value="old('name')" required
                placeholder="Owner / Manager name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mb-4">
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email"
                name="email" :value="old('email')" required
                placeholder="you@yourbusiness.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mb-4">
            <x-input-label for="phone" :value="__('Phone Number')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="text"
                name="phone" :value="old('phone')" required
                placeholder="e.g. 0712 345 678" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <div class="mb-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password"
                name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mb-6">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <x-primary-button class="w-full justify-center py-3">
            {{ __('Create My POS Account') }}
        </x-primary-button>

        <p class="text-center text-sm text-gray-500 mt-4">
            Already have an account?
            <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Sign in</a>
        </p>
    </form>
</x-guest-layout>