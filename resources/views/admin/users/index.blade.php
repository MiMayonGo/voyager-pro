<x-dashboard-layout title="Manage Users">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-gray-900">Manage Users</h1>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-teal-50 text-teal-700 border border-teal-200 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="mb-4 bg-red-50 text-red-700 border border-red-200 px-4 py-3 rounded-lg text-sm">{{ $errors->first() }}</div>
    @endif

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    <th class="px-6 py-3 text-left">Name</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-left">Role</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full">
                            {{ $user->getRoleNames()->first() ?? 'No role' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($user->is_active)
                            <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">Active</span>
                        @else
                            <span class="bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                            @csrf @method('PATCH')
                            <button class="text-xs {{ $user->is_active ? 'text-red-500 hover:underline' : 'text-teal-600 hover:underline' }}">
                                {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-10 text-center text-gray-400 text-xs">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">{{ $users->links() }}</div>
    </div>
</x-dashboard-layout>