<x-dashboard-layout title="Categories">
    <div class="flex items-center gap-4 mb-6">
        <h1 class="text-xl font-bold text-gray-900">Categories</h1>
        <a href="{{ route('categories.create') }}" class="bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-teal-700">+ New Category</a>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-teal-50 text-teal-700 border border-teal-200 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    <th class="px-6 py-3 text-left">Name</th>
                    <th class="px-6 py-3 text-left">Slug</th>
                    <th class="px-6 py-3 text-left">Packages</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($categories as $category)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $category->name }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $category->slug }}</td>
                    <td class="px-6 py-4">{{ $category->packages_count }}</td>
                    <td class="px-6 py-4 flex items-center gap-3">
                        <a href="{{ route('categories.edit', $category) }}" class="text-teal-600 hover:underline text-xs">Edit</a>
                        <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Delete this category?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:underline text-xs">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-10 text-center text-gray-400 text-xs">No categories found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">{{ $categories->links() }}</div>
    </div>
</x-dashboard-layout>