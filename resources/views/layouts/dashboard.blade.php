<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }} — VoyagePro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

<div class="flex min-h-screen">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-white border-r border-gray-100 flex flex-col shrink-0 sticky top-0 h-screen overflow-y-auto">

        {{-- Logo --}}
        <div class="px-6 py-5 border-b border-gray-100">
            <a href="{{ url('/') }}" class="text-xl font-bold text-gray-900">
                Voyage<span class="text-teal-600">Pro</span>
            </a>
            <p class="text-xs text-gray-400 mt-0.5 uppercase tracking-widest">
                @auth {{ ucfirst(str_replace('_', ' ', auth()->user()->getRoleNames()->first() ?? 'Dashboard')) }} @endauth
            </p>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-4 py-4 space-y-1">

            @role('tour_manager')
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest px-3 mb-2 mt-2">Management</p>
            <a href="{{ route('manager.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('manager.dashboard') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <span class="w-2 h-2 rounded-full bg-teal-500 shrink-0"></span> Dashboard
            </a>
            <a href="{{ route('packages.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('packages.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <span class="w-2 h-2 rounded-full bg-amber-400 shrink-0"></span> Packages
            </a>
            <a href="{{ route('bookings.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('bookings.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <span class="w-2 h-2 rounded-full bg-sky-400 shrink-0"></span> Bookings
            </a>
            <a href="{{ route('reviews.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('reviews.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <span class="w-2 h-2 rounded-full bg-purple-400 shrink-0"></span> Reviews
            </a>
            <a href="{{ route('categories.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('categories.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <span class="w-2 h-2 rounded-full bg-green-400 shrink-0"></span> Categories
            </a>
            @endrole

            @role('super_admin')
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest px-3 mb-2 mt-2">Admin</p>
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('admin.dashboard') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <span class="w-2 h-2 rounded-full bg-red-400 shrink-0"></span> Dashboard
            </a>
            <a href="{{ route('admin.users.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('admin.users.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <span class="w-2 h-2 rounded-full bg-red-400 shrink-0"></span> Users
            </a>
            <a href="{{ route('packages.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('packages.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <span class="w-2 h-2 rounded-full bg-amber-400 shrink-0"></span> Packages
            </a>
            <a href="{{ route('bookings.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('bookings.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <span class="w-2 h-2 rounded-full bg-sky-400 shrink-0"></span> Bookings
            </a>
            <a href="{{ route('reviews.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('reviews.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <span class="w-2 h-2 rounded-full bg-purple-400 shrink-0"></span> Reviews
            </a>
            <a href="{{ route('categories.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('categories.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <span class="w-2 h-2 rounded-full bg-green-400 shrink-0"></span> Categories
            </a>
            @endrole

            @role('customer')
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest px-3 mb-2 mt-2">My Account</p>
            <a href="{{ route('customer.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('customer.dashboard') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <span class="w-2 h-2 rounded-full bg-green-500 shrink-0"></span> Dashboard
            </a>
            <a href="{{ url('/packages') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition text-gray-600 hover:bg-gray-50 hover:text-gray-900">
                <span class="w-2 h-2 rounded-full bg-teal-400 shrink-0"></span> Browse Packages
            </a>
            <a href="{{ route('bookings.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition
                      {{ request()->routeIs('bookings.*') ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <span class="w-2 h-2 rounded-full bg-sky-400 shrink-0"></span> My Bookings
            </a>
            @endrole

        </nav>

        {{-- User + Logout --}}
        <div class="px-4 py-4 border-t border-gray-100">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-full bg-teal-600 flex items-center justify-center text-white text-xs font-bold shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full text-left text-xs text-gray-500 hover:text-red-500 transition px-3 py-1.5 rounded-lg hover:bg-red-50">
                    → Log Out
                </button>
            </form>
        </div>

    </aside>

    {{-- MAIN --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- Top Bar --}}
        <header class="bg-white border-b border-gray-100 px-8 py-4 flex items-center justify-between shrink-0">
            <div>
                <h1 class="text-lg font-bold text-gray-900">{{ $title ?? 'Dashboard' }}</h1>
                <p class="text-xs text-gray-400">{{ now()->format('l, F j, Y') }}</p>
            </div>
            <a href="{{ url('/') }}" class="text-xs text-teal-600 hover:underline">← Back to site</a>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 p-8">
            {{ $slot }}
        </main>

    </div>
</div>

</body>
</html>
