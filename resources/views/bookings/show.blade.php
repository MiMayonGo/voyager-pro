<x-dashboard-layout title="Booking Details">
    <div class="max-w-2xl">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-bold text-gray-900">Booking Details</h1>
            <a href="{{ route('bookings.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Back</a>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div><p class="text-xs text-gray-400">Package</p><p class="font-medium text-gray-900">{{ $booking->package->title ?? '—' }}</p></div>
                <div><p class="text-xs text-gray-400">Status</p>
                    <span class="px-2 py-1 rounded-full text-xs font-medium
                        {{ $booking->status === 'confirmed' ? 'bg-green-100 text-green-700' :
                           ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                           ($booking->status === 'cancelled' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600')) }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
                <div><p class="text-xs text-gray-400">Travel Date</p><p class="font-medium text-gray-900">{{ $booking->travel_date->format('M d, Y') }}</p></div>
                <div><p class="text-xs text-gray-400">Slots Booked</p><p class="font-medium text-gray-900">{{ $booking->slots_booked }}</p></div>
                <div><p class="text-xs text-gray-400">Total Price</p><p class="font-bold text-teal-600">${{ number_format($booking->total_price, 2) }}</p></div>
                @hasanyrole('tour_manager|super_admin')
                <div><p class="text-xs text-gray-400">Customer</p><p class="font-medium text-gray-900">{{ $booking->user->name ?? '—' }}</p></div>
                @endhasanyrole
            </div>

            @if($booking->special_requests)
            <div class="border-t border-gray-100 pt-4">
                <p class="text-xs text-gray-400 mb-1">Special Requests</p>
                <p class="text-sm text-gray-700">{{ $booking->special_requests }}</p>
            </div>
            @endif

            @if(in_array($booking->status, ['pending','confirmed']))
            <div class="border-t border-gray-100 pt-4 flex gap-3">
                @hasanyrole('tour_manager|super_admin')
                @if($booking->status === 'pending')
                <form method="POST" action="{{ route('bookings.confirm', $booking) }}">
                    @csrf @method('PATCH')
                    <button class="bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-teal-700">Confirm Booking</button>
                </form>
                @endif
                @if($booking->status === 'confirmed')
                <form method="POST" action="{{ route('bookings.complete', $booking) }}">
                    @csrf @method('PATCH')
                    <button class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700">Mark Completed</button>
                </form>
                @endif
                @endhasanyrole
                <form method="POST" action="{{ route('bookings.cancel', $booking) }}" onsubmit="return confirm('Cancel this booking?')">
                    @csrf @method('PATCH')
                    <button class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-600">Cancel Booking</button>
                </form>
            </div>
            @endif
        </div>
    </div>
</x-dashboard-layout>