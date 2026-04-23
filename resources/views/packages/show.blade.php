<x-dashboard-layout title="{{ $package->title }}">
    <div class="max-w-3xl">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-bold text-gray-900">{{ $package->title }}</h1>
            <a href="{{ route('packages.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Back</a>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            @if($package->cover_image)
                <img src="{{ asset('storage/'.$package->cover_image) }}" class="w-full h-56 object-cover">
            @endif
            <div class="p-6 space-y-4">
                <div class="flex flex-wrap gap-2">
                    @foreach($package->categories as $cat)
                        <span class="bg-teal-50 text-teal-700 text-xs px-2 py-1 rounded-full">{{ $cat->name }}</span>
                    @endforeach
                </div>
                <p class="text-gray-600 text-sm leading-relaxed">{{ $package->description }}</p>
                <div class="grid grid-cols-3 gap-4 border-t border-gray-100 pt-4">
                    <div><p class="text-xs text-gray-400">Price</p><p class="font-bold text-gray-900">${{ number_format($package->price, 2) }}</p></div>
                    <div><p class="text-xs text-gray-400">Duration</p><p class="font-bold text-gray-900">{{ $package->duration_days }} days</p></div>
                    <div><p class="text-xs text-gray-400">Available Slots</p><p class="font-bold text-gray-900">{{ $package->available_slots }}</p></div>
                </div>
                @role('customer')
                @if($package->available_slots > 0 && $package->status === 'active')
                <form method="POST" action="{{ route('bookings.store') }}" class="border-t border-gray-100 pt-4 space-y-3">
                    @csrf
                    <input type="hidden" name="package_id" value="{{ $package->id }}">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Travel Date</label>
                            <input type="date" name="travel_date" min="{{ date('Y-m-d') }}" required
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Slots</label>
                            <input type="number" name="slots_booked" value="1" min="1" max="{{ $package->available_slots }}" required
                                   class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Special Requests</label>
                        <textarea name="special_requests" rows="2"
                                  class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-teal-500"></textarea>
                    </div>
                    <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-teal-700">Book Now</button>
                </form>
                @endif
                @endrole
            </div>
        </div>
    </div>
</x-dashboard-layout>