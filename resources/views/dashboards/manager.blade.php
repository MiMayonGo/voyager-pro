<x-dashboard-layout title="Manager Dashboard">

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">My Packages</p>
            <p class="text-3xl font-bold text-gray-900">—</p>
            <p class="text-xs text-teal-600 mt-1">Active packages</p>
        </div>

        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Bookings Today</p>
            <p class="text-3xl font-bold text-gray-900">—</p>
            <p class="text-xs text-sky-500 mt-1">New bookings</p>
        </div>

        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Pending Reviews</p>
            <p class="text-3xl font-bold text-gray-900">—</p>
            <p class="text-xs text-purple-500 mt-1">Awaiting approval</p>
        </div>

        <div class="bg-white rounded-xl p-5 border border-gray-100 shadow-sm">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-1">Revenue</p>
            <p class="text-3xl font-bold text-gray-900">—</p>
            <p class="text-xs text-green-500 mt-1">This month</p>
        </div>

    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <a href="{{ route('packages.create') }}"
           class="flex items-center gap-3 bg-teal-600 hover:bg-teal-700 text-white rounded-xl px-5 py-4 font-semibold text-sm transition">
            <span class="text-lg">＋</span> New Package
        </a>
        <a href="{{ route('bookings.index') }}"
           class="flex items-center gap-3 bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 rounded-xl px-5 py-4 font-semibold text-sm transition">
            <span class="text-lg">📋</span> View Bookings
        </a>
        <a href="{{ route('reviews.index') }}"
           class="flex items-center gap-3 bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 rounded-xl px-5 py-4 font-semibold text-sm transition">
            <span class="text-lg">⭐</span> Moderate Reviews
        </a>
    </div>

    {{-- Recent Bookings --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800 text-sm">Recent Bookings</h2>
            <a href="{{ route('bookings.index') }}" class="text-xs text-teal-600 hover:underline">View all →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        <th class="px-6 py-3 text-left">Customer</th>
                        <th class="px-6 py-3 text-left">Package</th>
                        <th class="px-6 py-3 text-left">Travel Date</th>
                        <th class="px-6 py-3 text-left">Amount</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-400 text-xs">
                            No bookings yet. They'll appear here once customers start booking.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</x-dashboard-layout>
