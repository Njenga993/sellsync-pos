<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('categories.index') }}" class="text-gray-400 hover:text-gray-600">← Back</a>
            <h2 class="text-xl font-semibold text-gray-800">Edit Category</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <form method="POST" action="{{ route('categories.update', $category) }}">
                    @csrf @method('PUT')

                    <div class="mb-4">
                        <x-input-label for="name" value="Category Name" />
                        <x-text-input id="name" name="name" type="text"
                            class="block mt-1 w-full"
                            :value="old('name', $category->name)" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="parent_id" value="Parent Category (optional)" />
                        <select id="parent_id" name="parent_id"
                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500">
                            <option value="">-- No parent (top level) --</option>
                            @foreach($parents as $parent)
                                <option value="{{ $parent->id }}"
                                    {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                    {{ $parent->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <x-input-label for="description" value="Description (optional)" />
                        <textarea id="description" name="description" rows="3"
                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500">{{ old('description', $category->description) }}</textarea>
                    </div>

                    <div class="mb-6">
                        <x-input-label for="status" value="Status" />
                        <select id="status" name="status"
                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500">
                            <option value="active"   {{ old('status', $category->status) === 'active'   ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $category->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="flex gap-3">
                        <x-primary-button>Update Category</x-primary-button>
                        <a href="{{ route('categories.index') }}"
                           class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>