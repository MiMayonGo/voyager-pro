<x-dashboard-layout title="Bookings">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-gray-900">Bookings</h1>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-teal-50 text-teal-700 border border-teal-200 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    @hasanyrole('tour_manager|super_admin')
                    <th class="px-6 py-3 text-left">Customer</th>
                    @endhasanyrole
                    <th class="px-6 py-3 text-left">Package</th>
                    <th class="px-6 py-3 text-left">Travel Date</th>
                    <th class="px-6 py-3 text-left">Slots</th>
                    <th class="px-6 py-3 text-left">Total</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($bookings as $booking)
                <tr class="hover:bg-gray-50">
                    @hasanyrole('tour_manager|super_admin')
                    <td class="px-6 py-4">{{ $booking->user->name ?? '—' }}</td>
                    @endhasanyrole
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $booking->package->title ?? '—' }}</td>
                    <td class="px-6 py-4">{{ $booking->travel_date->format('M d, Y') }}</td>
                    <td class="px-6 py-4">{{ $booking->slots_booked }}</td>
                    <td class="px-6 py-4">${{ number_format($booking->total_price, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-700' :
                               ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                               ($booking->status === 'cancelled' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600')) }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 flex items-center gap-3">
                        <a href="{{ route('bookings.show', $booking) }}" class="text-teal-600 hover:underline text-xs">View</a>
                        @if(in_array($booking->status, ['pending','confirmed']))
                        <form method="POST" action="{{ route('bookings.cancel', $booking) }}" onsubmit="return confirm('Cancel this booking?')">
                            @csrf @method('PATCH')
                            <button class="text-red-500 hover:underline text-xs">Cancel</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-10 text-center text-gray-400 text-xs">No bookings found.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">{{ $bookings->links() }}</div>
    </div>
</x-dashboard-layout>