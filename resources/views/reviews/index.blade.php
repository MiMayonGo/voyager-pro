<x-dashboard-layout title="Reviews">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-gray-900">Reviews</h1>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-teal-50 text-teal-700 border border-teal-200 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    <th class="px-6 py-3 text-left">Customer</th>
                    <th class="px-6 py-3 text-left">Package</th>
                    <th class="px-6 py-3 text-left">Rating</th>
                    <th class="px-6 py-3 text-left">Comment</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($reviews as $review)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $review->user->name ?? '—' }}</td>
                    <td class="px-6 py-4">{{ $review->package->title ?? '—' }}</td>
                    <td class="px-6 py-4">{{ $review->rating }} / 5</td>
                    <td class="px-6 py-4 max-w-xs truncate text-gray-500">{{ $review->comment }}</td>
                    <td class="px-6 py-4">
                        @if($review->is_approved)
                            <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">Approved</span>
                        @else
                            <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full">Pending</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 flex items-center gap-3">
                        @if(!$review->is_approved)
                        <form method="POST" action="{{ route('reviews.approve', $review) }}">
                            @csrf @method('PATCH')
                            <button class="text-teal-600 hover:underline text-xs">Approve</button>
                        </form>
                        @endif
                        <form method="POST" action="{{ route('reviews.destroy', $review) }}" onsubmit="return confirm('Delete this review?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:underline text-xs">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400 text-xs">No reviews found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">{{ $reviews->links() }}</div>
    </div>
</x-dashboard-layout>