<x-dashboard-layout title="New Category">
    <div class="max-w-lg">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-bold text-gray-900">New Category</h1>
            <a href="{{ route('categories.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Back</a>
        </div>
        <form method="POST" action="{{ route('categories.store') }}"
              class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="pt-2">
                <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-teal-700">Create Category</button>
            </div>
        </form>
    </div>
</x-dashboard-layout>