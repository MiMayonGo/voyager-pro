<x-dashboard-layout title="New Package">
    <div class="max-w-2xl">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-bold text-gray-900">New Package</h1>
            <a href="{{ route('packages.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Back</a>
        </div>

        <form method="POST" action="{{ route('packages.store') }}" enctype="multipart/form-data"
              class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="4"
                          class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price ($)</label>
                    <input type="number" name="price" value="{{ old('price') }}" min="0" step="0.01" required
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Duration (days)</label>
                    <input type="number" name="duration_days" value="{{ old('duration_days') }}" min="1" required
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @error('duration_days')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Available Slots</label>
                    <input type="number" name="available_slots" value="{{ old('available_slots', 0) }}" min="0" required
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Categories</label>
                <div class="flex flex-wrap gap-3">
                    @foreach($categories as $category)
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                               {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                        {{ $category->name }}
                    </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cover Image</label>
                <input type="file" name="cover_image" accept="image/*"
                       class="w-full text-sm text-gray-500 border border-gray-200 rounded-lg px-3 py-2">
            </div>

            <div class="pt-2">
                <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-teal-700">
                    Create Package
                </button>
            </div>
        </form>
    </div>
</x-dashboard-layout>