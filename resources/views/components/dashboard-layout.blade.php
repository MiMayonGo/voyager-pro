@props(['title' => 'Dashboard'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} — {{ config('app.name', 'VoyagePro') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    @stack('styles')
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f1f5f9; }

        /* ── SIDEBAR ─────────────────────────────────────────── */
        .sidebar {
            width: 255px; min-height: 100vh;
            background: #0f172a;
            position: fixed; top: 0; left: 0; z-index: 300;
            display: flex; flex-direction: column;
            overflow-y: auto; transition: transform .28s ease;
        }
        .sidebar-brand {
            padding: 1.2rem 1.4rem;
            border-bottom: 1px solid rgba(255,255,255,.07);
            flex-shrink: 0;
        }
        .sidebar-brand a { font-size: 1.25rem; font-weight: 700; color: #fff; text-decoration: none; }
        .sidebar-brand span { color: #14b8a6; }
        .sidebar-role {
            font-size: .62rem; letter-spacing: .1em; text-transform: uppercase;
            color: rgba(255,255,255,.28); margin-top: 3px;
        }
        .sidebar-nav { flex: 1; padding: .6rem .65rem; }
        .nav-section-label {
            font-size: .6rem; font-weight: 700; letter-spacing: .12em;
            text-transform: uppercase; color: rgba(255,255,255,.22);
            padding: .8rem .75rem .3rem;
        }
        .sidebar-link {
            display: flex; align-items: center; gap: .65rem;
            padding: .58rem .75rem; border-radius: 8px;
            color: rgba(255,255,255,.48); text-decoration: none;
            font-size: .835rem; font-weight: 500;
            transition: all .15s ease; margin-bottom: 2px;
        }
        .sidebar-link i {
             font-size: .9rem; 
             width: 1rem; 
             text-align: center; 
             flex-shrink: 0; 
            }
            
        .sidebar-link:hover { background: rgba(255,255,255,.07); color: #fff; }
        .sidebar-link.active { background: rgba(20,184,166,.16); color: #5eead4; }
        .sidebar-footer {
            flex-shrink: 0; padding: .7rem;
            border-top: 1px solid rgba(255,255,255,.07);
        }
        .user-info { display: flex; align-items: center; gap: .6rem; padding: .5rem .5rem .6rem; }
        .user-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: #0f766e; display: flex; align-items: center;
            justify-content: center; font-size: .72rem; font-weight: 700;
            color: #fff; flex-shrink: 0;
        }
        .user-name  { font-size: .78rem; font-weight: 600; color: #fff; line-height: 1.25; }
        .user-email { font-size: .67rem; color: rgba(255,255,255,.32); line-height: 1.25; }
        .btn-logout {
            width: 100%; text-align: left; background: none; border: none;
            color: rgba(255,255,255,.32); font-size: .78rem;
            font-family: 'Poppins', sans-serif;
            padding: .42rem .75rem; border-radius: 7px; cursor: pointer; transition: all .15s;
        }
        .btn-logout:hover { background: rgba(239,68,68,.1); color: #f87171; }

        /* ── MAIN ────────────────────────────────────────────── */
        .main-wrap { margin-left: 255px; min-height: 100vh; display: flex; flex-direction: column; }
        .topbar {
            background: #fff; border-bottom: 1px solid #e2e8f0;
            padding: .85rem 1.75rem;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
        }
        .topbar-title { font-size: .95rem; font-weight: 700; color: #1e293b; margin: 0; line-height: 1.25; }
        .topbar-date  { font-size: .7rem; color: #94a3b8; margin: 0; }
        .page-content { flex: 1; padding: 1.6rem 1.75rem; }

        /* ── SIDEBAR OVERLAY (mobile) ────────────────────────── */
        .sb-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.5); z-index: 299;
        }
        @media (max-width: 991.98px) {
            .sidebar      { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .sb-overlay.open { display: block; }
            .main-wrap    { margin-left: 0; }
            .page-content { padding: 1.1rem; }
        }
    </style>
</head>
<body>

<div class="sb-overlay" id="sbOverlay"></div>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <a href="{{ url('/') }}">Voyage<span>Pro</span></a>
        <div class="sidebar-role">
            @auth{{ ucfirst(str_replace('_', ' ', auth()->user()->getRoleNames()->first() ?? 'User')) }}@endauth
        </div>
    </div>

    <nav class="sidebar-nav">

        @role('super_admin')
        <div class="nav-section-label">Admin</div>
        <a href="{{ route('admin.dashboard') }}"  class="sidebar-link {{ request()->routeIs('admin.dashboard')  ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*')    ? 'active' : '' }}"><i class="bi bi-people-fill"></i> Users</a>
        <a href="{{ route('packages.index') }}"   class="sidebar-link {{ request()->routeIs('packages.*')       ? 'active' : '' }}"><i class="bi bi-map-fill"></i> Packages</a>
        <a href="{{ route('bookings.index') }}"   class="sidebar-link {{ request()->routeIs('bookings.*')       ? 'active' : '' }}"><i class="bi bi-calendar2-check-fill"></i> Bookings</a>
        <a href="{{ route('reviews.index') }}"    class="sidebar-link {{ request()->routeIs('reviews.*')        ? 'active' : '' }}"><i class="bi bi-star-fill"></i> Reviews</a>
        <a href="{{ route('categories.index') }}" class="sidebar-link {{ request()->routeIs('categories.*')     ? 'active' : '' }}"><i class="bi bi-tag-fill"></i> Categories</a>
        <a href="{{ route('reports.index') }}"    class="sidebar-link {{ request()->routeIs('reports.*')        ? 'active' : '' }}"><i class="bi bi-graph-up-arrow"></i> Reports</a>
        @endrole

        @role('tour_manager')
        <div class="nav-section-label">Management</div>
        <a href="{{ route('manager.dashboard') }}" class="sidebar-link {{ request()->routeIs('manager.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="{{ route('packages.index') }}"    class="sidebar-link {{ request()->routeIs('packages.*')        ? 'active' : '' }}"><i class="bi bi-map-fill"></i> Packages</a>
        <a href="{{ route('bookings.index') }}"    class="sidebar-link {{ request()->routeIs('bookings.*')        ? 'active' : '' }}"><i class="bi bi-calendar2-check-fill"></i> Bookings</a>
        <a href="{{ route('reviews.index') }}"     class="sidebar-link {{ request()->routeIs('reviews.*')         ? 'active' : '' }}"><i class="bi bi-star-fill"></i> Reviews</a>
        <a href="{{ route('categories.index') }}"  class="sidebar-link {{ request()->routeIs('categories.*')      ? 'active' : '' }}"><i class="bi bi-tag-fill"></i> Categories</a>
        @endrole

        @role('customer')
        <div class="nav-section-label">My Account</div>
        <a href="{{ route('customer.dashboard') }}" class="sidebar-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}"><i class="bi bi-house-fill"></i> Dashboard</a>
        <a href="{{ route('packages.index') }}"     class="sidebar-link {{ request()->routeIs('packages.*')         ? 'active' : '' }}"><i class="bi bi-map-fill"></i> Browse Packages</a>
        <a href="{{ route('bookings.index') }}"     class="sidebar-link {{ request()->routeIs('bookings.*')         ? 'active' : '' }}"><i class="bi bi-calendar2-check-fill"></i> My Bookings</a>
        <a href="{{ route('reviews.my') }}"         class="sidebar-link {{ request()->routeIs('reviews.my')        ? 'active' : '' }}"><i class="bi bi-star-fill"></i> My Reviews</a>
        @endrole

    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
            <div style="min-width:0">
                <div class="user-name text-truncate">{{ Auth::user()->name }}</div>
                <div class="user-email text-truncate">{{ Auth::user()->email }}</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="bi bi-box-arrow-left me-2"></i>Log Out
            </button>
        </form>
    </div>
</aside>

<div class="main-wrap">
    <header class="topbar">
        <div>
            <p class="topbar-title">{{ $title }}</p>
            <p class="topbar-date">{{ now()->format('l, F j, Y') }}</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <a href="{{ url('/') }}" class="text-decoration-none d-none d-sm-flex align-items-center gap-1"
               style="color:#0f766e;font-size:.8rem">
                <i class="bi bi-globe2"></i> Back to site
            </a>
            <button id="sbToggle" class="d-lg-none btn btn-light btn-sm border-0 px-2">
                <i class="bi bi-list fs-5"></i>
            </button>
        </div>
    </header>

    <main class="page-content">
        {{ $slot }}
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
<script>
    const sidebar  = document.getElementById('sidebar');
    const overlay  = document.getElementById('sbOverlay');
    const toggle   = document.getElementById('sbToggle');
    const open  = () => { sidebar.classList.add('open');  overlay.classList.add('open');  };
    const close = () => { sidebar.classList.remove('open'); overlay.classList.remove('open'); };
    toggle?.addEventListener('click', () => sidebar.classList.contains('open') ? close() : open());
    overlay.addEventListener('click', close);
</script>
</body>
</html>