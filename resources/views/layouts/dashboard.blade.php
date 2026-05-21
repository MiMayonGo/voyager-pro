<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $title ?? 'Dashboard' }} — VoyagePro</title>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

  <style>
    :root {
      --sidebar-width: 250px;
      --topbar-height: 60px;
      --brand-teal: #0F766E;
      --brand-teal-light: #14B8A6;
      --sidebar-bg: #0F172A;
      --sidebar-text: rgba(255,255,255,0.65);
      --sidebar-active-bg: rgba(20,184,166,0.15);
      --sidebar-active-text: #5EEAD4;
    }
    * { box-sizing: border-box; }
    body { font-family: 'Inter', sans-serif; background: #F1F5F9; margin: 0; }

    /* ── Sidebar ── */
    #sidebar {
      position: fixed; top: 0; left: 0; bottom: 0;
      width: var(--sidebar-width);
      background: var(--sidebar-bg);
      display: flex; flex-direction: column;
      z-index: 1040;
      transition: transform 0.25s ease;
    }
    .sidebar-brand {
      height: var(--topbar-height);
      display: flex; align-items: center;
      padding: 0 1.25rem;
      border-bottom: 1px solid rgba(255,255,255,0.06);
      text-decoration: none;
    }
    .sidebar-brand-text {
      font-size: 1.2rem; font-weight: 700; color: #fff; letter-spacing: -0.3px;
    }
    .sidebar-brand-text span { color: var(--brand-teal-light); }
    .sidebar-role {
      font-size: 0.62rem; font-weight: 600;
      letter-spacing: 0.1em; text-transform: uppercase;
      color: rgba(255,255,255,0.3);
      padding: 0.75rem 1.25rem 0.25rem;
    }
    .sidebar-nav { 
      flex: 1;
      overflow-y: auto; 
      padding: 0.25rem 0.75rem; 
    }
    .sidebar-nav a {
      display: flex; align-items: center; gap: 0.65rem;
      padding: 0.55rem 0.75rem;
      border-radius: 8px;
      font-size: 0.85rem; font-weight: 500;
      color: var(--sidebar-text);
      text-decoration: none;
      transition: background 0.15s, color 0.15s;
      margin-bottom: 2px;
    }
    .sidebar-nav a:hover { background: rgba(255,255,255,0.06); color: #fff; }
    .sidebar-nav a.active {
      background: var(--sidebar-active-bg);
      color: var(--sidebar-active-text);
    }
    .sidebar-nav a i { font-size: 1rem; width: 1.1rem; text-align: center; }
    .sidebar-section {
      font-size: 0.62rem; font-weight: 700;
      letter-spacing: 0.1em; text-transform: uppercase;
      color: rgba(255,255,255,0.25);
      padding: 0.9rem 0.75rem 0.3rem;
    }
    .sidebar-footer {
      padding: 0.75rem;
      border-top: 1px solid rgba(255,255,255,0.06);
    }
    .sidebar-user {
      display: flex; align-items: center; gap: 0.6rem;
      padding: 0.5rem 0.5rem;
      border-radius: 8px;
      margin-bottom: 0.25rem;
    }
    .sidebar-avatar {
      width: 32px; height: 32px; border-radius: 50%;
      background: var(--brand-teal);
      display: flex; align-items: center; justify-content: center;
      font-size: 0.75rem; font-weight: 700; color: #fff; flex-shrink: 0;
    }
    .sidebar-user-name { font-size: 0.82rem; font-weight: 600; color: #fff; }
    .sidebar-user-email { font-size: 0.7rem; color: rgba(255,255,255,0.4); }
    .sidebar-logout {
      display: flex; align-items: center; gap: 0.5rem;
      width: 100%; padding: 0.45rem 0.75rem;
      border: none; background: transparent; border-radius: 7px;
      font-size: 0.8rem; color: rgba(255,255,255,0.4);
      cursor: pointer; transition: background 0.15s, color 0.15s;
    }
    .sidebar-logout:hover { background: rgba(239,68,68,0.12); color: #FCA5A5; }

    /* ── Topbar ── */
    #topbar {
      position: fixed; top: 0;
      left: var(--sidebar-width); right: 0;
      height: var(--topbar-height);
      background: #fff;
      border-bottom: 1px solid #E2E8F0;
      display: flex; align-items: center; justify-content: space-between;
      padding: 0 1.5rem;
      z-index: 1030;
    }
    .topbar-title { font-size: 1rem; font-weight: 700; color: #1E293B; }
    .topbar-date  { font-size: 0.75rem; color: #94A3B8; }
    .topbar-back  {
      font-size: 0.8rem; color: var(--brand-teal);
      text-decoration: none; font-weight: 500;
      display: flex; align-items: center; gap: 0.3rem;
    }
    .topbar-back:hover { color: #0D6560; }

    /* ── Page content ── */
    #page-content {
      margin-left: var(--sidebar-width);
      padding-top: var(--topbar-height);
      min-height: 100vh;
    }
    .page-body { padding: 1.75rem; }

    /* ── Responsive ── */
    @media (max-width: 991.98px) {
      #sidebar { transform: translateX(-100%); }
      #sidebar.show { transform: translateX(0); }
      #topbar { left: 0; }
      #page-content { margin-left: 0; }
    }
  </style>
</head>
<body>

<!-- ── SIDEBAR ── -->
<aside id="sidebar">
  <a href="{{ url('/') }}" class="sidebar-brand">
    <span class="sidebar-brand-text">Voyage<span>Pro</span></span>
  </a>

  @auth
    <div class="sidebar-role">
      {{ ucfirst(str_replace('_', ' ', auth()->user()->getRoleNames()->first() ?? 'User')) }}
    </div>
  @endauth

  <nav class="sidebar-nav">

    @role('super_admin')
      <div class="sidebar-section">Admin</div>
      <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> Dashboard
      </a>
      <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="bi bi-people-fill"></i> Users
      </a>
      <a href="{{ route('packages.index') }}" class="{{ request()->routeIs('packages.*') ? 'active' : '' }}">
        <i class="bi bi-map-fill"></i> Packages
      </a>
      <a href="{{ route('bookings.index') }}" class="{{ request()->routeIs('bookings.*') ? 'active' : '' }}">
        <i class="bi bi-calendar2-check-fill"></i> Bookings
      </a>
      <a href="{{ route('reviews.index') }}" class="{{ request()->routeIs('reviews.*') ? 'active' : '' }}">
        <i class="bi bi-star-half"></i> Reviews
      </a>
      <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
        <i class="bi bi-tags-fill"></i> Categories
      </a>
      <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
        <i class="bi bi-graph-up-arrow"></i> Reports
      </a>
    @endrole

    @role('tour_manager')
      <div class="sidebar-section">Management</div>
      <a href="{{ route('manager.dashboard') }}" class="{{ request()->routeIs('manager.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> Dashboard
      </a>
      <a href="{{ route('packages.index') }}" class="{{ request()->routeIs('packages.*') ? 'active' : '' }}">
        <i class="bi bi-map-fill"></i> Packages
      </a>
      <a href="{{ route('bookings.index') }}" class="{{ request()->routeIs('bookings.*') ? 'active' : '' }}">
        <i class="bi bi-calendar2-check-fill"></i> Bookings
      </a>
      <a href="{{ route('reviews.index') }}" class="{{ request()->routeIs('reviews.*') ? 'active' : '' }}">
        <i class="bi bi-star-half"></i> Reviews
      </a>
      <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
        <i class="bi bi-tags-fill"></i> Categories
      </a>
    @endrole

    @role('customer')
      <div class="sidebar-section">My Account</div>
      <a href="{{ route('customer.dashboard') }}" class="{{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
        <i class="bi bi-house-fill"></i> Dashboard
      </a>
      <a href="{{ route('packages.index') }}" class="{{ request()->routeIs('packages.index') ? 'active' : '' }}">
        <i class="bi bi-compass-fill"></i> Browse Packages
      </a>
      <a href="{{ route('bookings.index') }}" class="{{ request()->routeIs('bookings.*') ? 'active' : '' }}">
        <i class="bi bi-bag-check-fill"></i> My Bookings
      </a>
      <a href="{{ route('reviews.my') }}" class="{{ request()->routeIs('reviews.my') ? 'active' : '' }}">
        <i class="bi bi-star-half"></i> My Reviews
      </a>
    @endrole

  </nav>

  <div class="sidebar-footer">
    @auth
      <div class="sidebar-user">
        <div class="sidebar-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
        <div class="overflow-hidden">
          <div class="sidebar-user-name text-truncate">{{ Auth::user()->name }}</div>
          <div class="sidebar-user-email text-truncate">{{ Auth::user()->email }}</div>
        </div>
      </div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="sidebar-logout">
          <i class="bi bi-box-arrow-left"></i> Log Out
        </button>
      </form>
    @endauth
  </div>
</aside>

<!-- ── TOPBAR ── -->
<div id="topbar">
  <div class="d-flex align-items-center gap-3">
    <button class="btn btn-sm d-lg-none p-1 border-0" id="sidebarToggle">
      <i class="bi bi-list fs-5"></i>
    </button>
    <div>
      <div class="topbar-title">{{ $title ?? 'Dashboard' }}</div>
      <div class="topbar-date">{{ now()->format('l, F j, Y') }}</div>
    </div>
  </div>
  <a href="{{ url('/') }}" class="topbar-back">
    <i class="bi bi-arrow-left"></i> Back to site
  </a>
</div>

<!-- ── SIDEBAR OVERLAY (mobile) ── -->
<div id="sidebarOverlay"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.4);z-index:1035;"
     onclick="closeSidebar()"></div>

<!-- ── PAGE CONTENT ── -->
<div id="page-content">
  <div class="page-body">
    {{ $slot }}
  </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const sidebar  = document.getElementById('sidebar');
  const overlay  = document.getElementById('sidebarOverlay');
  document.getElementById('sidebarToggle')?.addEventListener('click', () => {
    sidebar.classList.toggle('show');
    overlay.style.display = sidebar.classList.contains('show') ? 'block' : 'none';
  });
  function closeSidebar() {
    sidebar.classList.remove('show');
    overlay.style.display = 'none';
  }
</script>
@stack('scripts')
</body>
</html>