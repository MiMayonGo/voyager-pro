<x-dashboard-layout title="Admin Dashboard">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <p class="text-sm text-gray-500">Total Users</p>
            <p class="text-3xl font-bold text-teal-600 mt-1">{{ \App\Models\User::count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <p class="text-sm text-gray-500">Packages</p>
            <p class="text-3xl font-bold text-teal-600 mt-1">{{ \App\Models\Package::count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <p class="text-sm text-gray-500">Bookings</p>
            <p class="text-3xl font-bold text-teal-600 mt-1">{{ \App\Models\Booking::count() }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <p class="text-sm text-gray-500">Revenue</p>
            <p class="text-3xl font-bold text-teal-600 mt-1">${{ number_format(\App\Models\Payment::where('status','paid')->sum('amount'), 2) }}</p>
        </div>
    </div>

    <div class="flex gap-3 mb-8">
        <a href="{{ route('admin.users.index') }}" class="bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-teal-700">Manage Users</a>
        <a href="{{ route('packages.index') }}" class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50">All Packages</a>
        <a href="{{ route('bookings.index') }}" class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50">All Bookings</a>
    </div>
</x-dashboard-layout>
