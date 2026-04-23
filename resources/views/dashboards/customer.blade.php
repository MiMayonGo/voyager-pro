<x-dashboard-layout title="My Dashboard">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <p class="text-sm text-gray-500">My Bookings</p>
            <p class="text-3xl font-bold text-teal-600 mt-1">{{ \App\Models\Booking::where('user_id', auth()->id())->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <p class="text-sm text-gray-500">Pending</p>
            <p class="text-3xl font-bold text-yellow-500 mt-1">{{ \App\Models\Booking::where('user_id', auth()->id())->where('status','pending')->count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <p class="text-sm text-gray-500">Completed</p>
            <p class="text-3xl font-bold text-green-600 mt-1">{{ \App\Models\Booking::where('user_id', auth()->id())->where('status','completed')->count() }}</p>
        </div>
    </div>

    <div class="flex gap-3 mb-8">
        <a href="{{ route('bookings.index') }}" class="bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-teal-700">My Bookings</a>
        <a href="{{ route('packages.index') }}" class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50">Browse Packages</a>
    </div>
</x-dashboard-layout>