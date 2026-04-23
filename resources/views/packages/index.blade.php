<x-dashboard-layout title="Packages">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-gray-900">Tour Packages</h1>
        @can('create', \App\Models\Package::class)
        <a href="{{ route('packages.create') }}" class="bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-teal-700">+ New Package</a>
        @endcan
    </div>

    @if(session('success'))
        <div class="mb-4 bg-teal-50 text-teal-700 border border-teal-200 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    <th class="px-6 py-3 text-left">Title</th>
                    <th class="px-6 py-3 text-left">Price</th>
                    <th class="px-6 py-3 text-left">Duration</th>
                    <th class="px-6 py-3 text-left">Slots</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($packages as $package)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900">
                        <a href="{{ route('packages.show', $package) }}" class="hover:text-teal-600">{{ $package->title }}</a>
                    </td>
                    <td class="px-6 py-4">${{ number_format($package->price, 2) }}</td>
                    <td class="px-6 py-4">{{ $package->duration_days }} days</td>
                    <td class="px-6 py-4">{{ $package->available_slots }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            {{ $package->status === 'active' ? 'bg-green-100 text-green-700' :
                               ($package->status === 'draft' ? 'bg-gray-100 text-gray-600' : 'bg-red-100 text-red-600') }}">
                            {{ ucfirst($package->status) }}
                        </span>
                    </td>
                                        <td class="px-6 py-4 flex items-center gap-3">
                        <a href="{{ route('packages.show', $package) }}" class="text-teal-600 hover:underline text-xs font-medium">View</a>
                        @can('update', $package)
                        <a href="{{ route('packages.edit', $package) }}" class="text-gray-600 hover:underline text-xs">Edit</a>
                        @endcan
                        @can('delete', $package)
                        <form method="POST" action="{{ route('packages.destroy', $package) }}" onsubmit="return confirm('Delete this package?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:underline text-xs">Delete</button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400 text-xs">No packages found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">{{ $packages->links() }}</div>
    </div>
</x-dashboard-layout>