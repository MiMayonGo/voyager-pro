<x-dashboard-layout title="Admin Dashboard">

  {{-- ── PRIMARY STAT CARDS ── --}}
  <div class="row g-3 mb-4">

    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="rounded-3 p-3 flex-shrink-0" style="background:#F0FDFA">
            <i class="bi bi-people-fill fs-4" style="color:#0F766E"></i>
          </div>
          <div>
            <div class="text-muted small fw-semibold text-uppercase" style="font-size:.7rem;letter-spacing:.06em">Total Users</div>
            <div class="fw-bold fs-3 lh-1 mt-1">{{ number_format($totalUsers) }}</div>
            <div class="text-muted" style="font-size:.72rem">
              <i class="bi bi-person-plus-fill text-success"></i>
              +{{ $newUsersMonth }} this month
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="rounded-3 p-3 flex-shrink-0" style="background:#FFF7ED">
            <i class="bi bi-map-fill fs-4" style="color:#C2410C"></i>
          </div>
          <div>
            <div class="text-muted small fw-semibold text-uppercase" style="font-size:.7rem;letter-spacing:.06em">Active Packages</div>
            <div class="fw-bold fs-3 lh-1 mt-1">{{ number_format($activePackages) }}</div>
            <div class="text-muted" style="font-size:.72rem">
              <i class="bi bi-check-circle-fill text-warning"></i>
              Published &amp; visible
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="rounded-3 p-3 flex-shrink-0" style="background:#EFF6FF">
            <i class="bi bi-calendar2-check-fill fs-4" style="color:#1D4ED8"></i>
          </div>
          <div>
            <div class="text-muted small fw-semibold text-uppercase" style="font-size:.7rem;letter-spacing:.06em">Total Bookings</div>
            <div class="fw-bold fs-3 lh-1 mt-1">{{ number_format($totalBookings) }}</div>
            <div class="text-muted" style="font-size:.72rem">
              <i class="bi bi-hourglass-split text-warning"></i>
              {{ $pendingBookings }} pending
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="rounded-3 p-3 flex-shrink-0" style="background:#F0FDF4">
            <i class="bi bi-cash-coin fs-4" style="color:#15803D"></i>
          </div>
          <div>
            <div class="text-muted small fw-semibold text-uppercase" style="font-size:.7rem;letter-spacing:.06em">Total Revenue</div>
            <div class="fw-bold fs-3 lh-1 mt-1">₱{{ number_format($totalRevenue, 0) }}</div>
            <div class="text-muted" style="font-size:.72rem">
              <i class="bi bi-credit-card-fill text-success"></i>
              Paid payments
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  {{-- ── SECONDARY STAT CARDS ── --}}
  <div class="row g-3 mb-4">

    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm text-center py-3">
        <div class="fw-bold fs-4 text-warning">{{ $pendingBookings }}</div>
        <div class="text-muted small"><i class="bi bi-hourglass-split me-1"></i>Pending</div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm text-center py-3">
        <div class="fw-bold fs-4 text-primary">{{ $confirmedBookings }}</div>
        <div class="text-muted small"><i class="bi bi-check2-circle me-1"></i>Confirmed</div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm text-center py-3">
        <div class="fw-bold fs-4 text-info">{{ $bookingsToday }}</div>
        <div class="text-muted small"><i class="bi bi-calendar-day me-1"></i>Bookings Today</div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm text-center py-3">
        <div class="fw-bold fs-4 text-success">₱{{ number_format($revenueToday, 0) }}</div>
        <div class="text-muted small"><i class="bi bi-cash-stack me-1"></i>Revenue Today</div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm text-center py-3">
        <div class="fw-bold fs-4 text-success">{{ $newUsersMonth }}</div>
        <div class="text-muted small"><i class="bi bi-person-plus me-1"></i>New Users (month)</div>
      </div>
    </div>

  </div>

  {{-- ── CHARTS ROW 1: Revenue + Doughnut ── --}}
  <div class="row g-3 mb-4">

    <div class="col-12 col-xl-8">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white border-0 pt-3 pb-0 d-flex align-items-center justify-content-between">
          <div>
            <h6 class="fw-bold mb-0">Revenue Overview</h6>
            <small class="text-muted">Paid payments — last 6 months</small>
          </div>
          <i class="bi bi-graph-up-arrow text-success fs-5"></i>
        </div>
        <div class="card-body">
          <canvas id="revenueChart" height="100"></canvas>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white border-0 pt-3 pb-0">
          <h6 class="fw-bold mb-0">Bookings by Status</h6>
          <small class="text-muted">All time</small>
        </div>
        <div class="card-body d-flex align-items-center justify-content-center">
          <canvas id="statusChart" style="max-height:240px"></canvas>
        </div>
      </div>
    </div>

  </div>

  {{-- ── CHARTS ROW 2: Daily bookings + Top packages ── --}}
  <div class="row g-3 mb-4">

    <div class="col-12 col-xl-7">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white border-0 pt-3 pb-0 d-flex align-items-center justify-content-between">
          <div>
            <h6 class="fw-bold mb-0">Daily Bookings</h6>
            <small class="text-muted">Last 30 days</small>
          </div>
          <i class="bi bi-bar-chart-fill text-primary fs-5"></i>
        </div>
        <div class="card-body">
          <canvas id="dailyBookingsChart" height="120"></canvas>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-5">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white border-0 pt-3 pb-0 d-flex align-items-center justify-content-between">
          <div>
            <h6 class="fw-bold mb-0">Top 5 Packages</h6>
            <small class="text-muted">By booking count</small>
          </div>
          <i class="bi bi-trophy-fill text-warning fs-5"></i>
        </div>
        <div class="card-body">
          <canvas id="topPackagesChart" height="160"></canvas>
        </div>
      </div>
    </div>

  </div>

  {{-- ── QUICK ACTIONS ── --}}
  <div class="row g-2 mb-4">
    <div class="col-auto">
      <a href="{{ route('admin.users.index') }}" class="btn btn-sm" style="background:#0F766E;color:#fff">
        <i class="bi bi-people me-1"></i> Manage Users
      </a>
    </div>
    <div class="col-auto">
      <a href="{{ route('packages.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-map me-1"></i> All Packages
      </a>
    </div>
    <div class="col-auto">
      <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-calendar2-check me-1"></i> All Bookings
      </a>
    </div>
    <div class="col-auto">
      <a href="{{ route('reviews.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-star me-1"></i> Reviews
      </a>
    </div>
  </div>

  {{-- ── RECENT BOOKINGS TABLE ── --}}
  <div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 pt-3 pb-0 d-flex align-items-center justify-content-between">
      <div>
        <h6 class="fw-bold mb-0">Recent Bookings</h6>
        <small class="text-muted">Latest 10 entries</small>
      </div>
      <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-outline-secondary">
        View all <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.85rem">
          <thead class="table-light">
            <tr>
              <th class="ps-3">Customer</th>
              <th>Package</th>
              <th>Travel Date</th>
              <th>Amount</th>
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentBookings as $booking)
              <tr>
                <td class="ps-3">
                  <div class="fw-semibold">{{ $booking->user?->name ?? '—' }}</div>
                  <div class="text-muted" style="font-size:.75rem">{{ $booking->user?->email }}</div>
                </td>
                <td>{{ Str::limit($booking->package?->title ?? '—', 36) }}</td>
                <td>{{ $booking->travel_date?->format('M d, Y') ?? '—' }}</td>
                <td>₱{{ number_format($booking->total_price, 0) }}</td>
                <td>
                  @php
                    $badge = match($booking->status) {
                      'confirmed'  => 'success',
                      'pending'    => 'warning',
                      'cancelled'  => 'danger',
                      'completed'  => 'primary',
                      default      => 'secondary',
                    };
                  @endphp
                  <span class="badge text-bg-{{ $badge }}">{{ ucfirst($booking->status) }}</span>
                </td>
                <td>
                  <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-outline-secondary py-0 px-2">
                    <i class="bi bi-eye"></i>
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-4">
                  <i class="bi bi-inbox fs-4 d-block mb-1"></i> No bookings yet.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
  <script>
    const teal   = '#0F766E';
    const tealL  = '#5EEAD4';
    const blue   = '#3B82F6';
    const amber  = '#F59E0B';
    const red    = '#EF4444';
    const green  = '#22C55E';
    const purple = '#A855F7';
    const slate  = '#94A3B8';

    // ── Revenue line chart ──────────────────────────────────────────────
    new Chart(document.getElementById('revenueChart'), {
      type: 'line',
      data: {
        labels: @json($revenueLabels),
        datasets: [{
          label: 'Revenue (₱)',
          data: @json($revenueData),
          borderColor: teal,
          backgroundColor: 'rgba(15,118,110,0.08)',
          borderWidth: 2.5,
          pointBackgroundColor: teal,
          pointRadius: 4,
          tension: 0.4,
          fill: true,
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
          y: {
            beginAtZero: true,
            ticks: { callback: v => '₱' + v.toLocaleString(), font: { size: 11 } },
            grid: { color: '#F1F5F9' }
          },
          x: { ticks: { font: { size: 11 } }, grid: { display: false } }
        }
      }
    });

    // ── Status doughnut ─────────────────────────────────────────────────
    const statusRaw = @json($bookingsByStatus);
    const statusOrder  = ['pending','confirmed','completed','cancelled'];
    const statusColors = { pending:'#F59E0B', confirmed:'#3B82F6', completed:'#22C55E', cancelled:'#EF4444' };
    new Chart(document.getElementById('statusChart'), {
      type: 'doughnut',
      data: {
        labels: statusOrder.map(s => s.charAt(0).toUpperCase() + s.slice(1)),
        datasets: [{
          data: statusOrder.map(s => statusRaw[s] ?? 0),
          backgroundColor: statusOrder.map(s => statusColors[s]),
          borderWidth: 0,
          hoverOffset: 6,
        }]
      },
      options: {
        cutout: '68%',
        plugins: {
          legend: { position: 'bottom', labels: { font: { size: 12 }, boxWidth: 12, padding: 12 } }
        }
      }
    });

    // Daily bookings bar
    new Chart(document.getElementById('dailyBookingsChart'), {
      type: 'bar',
      data: {
        labels: @json($dailyData),
        datasets: [{
          label: 'Bookings',
          data: @json($dailyData),
          backgroundColor: 'rgba(59,130,246,0.7)',
          borderRadius: 4,
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
          y: {
            beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 } },
            grid: { color: '#F1F5F9' }
          },
          x: {
            ticks: {
              font: { size: 9 },
              maxTicksLimit: 10,
              maxRotation: 0,
            },
            grid: { display: false }
          }
        }
      }
    });

    // ── Top packages horizontal bar ─────────────────────────────────────
    const pkgData   = @json($topPackages->pluck('bookings_count'));
    const pkgLabels = @json($topPackages->pluck('title'));
    new Chart(document.getElementById('topPackagesChart'), {
      type: 'bar',
      data: {
        labels: pkgLabels,
        datasets: [{
          label: 'Bookings',
          data: pkgData,
          backgroundColor: ['#0F766E','#14B8A6','#5EEAD4','#99F6E4','#CCFBF1'],
          borderRadius: 4,
        }]
      },
      options: {
        indexAxis: 'y',
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
          x: {
            beginAtZero: true,
            ticks: { stepSize: 1, font: { size: 11 } },
            grid: { color: '#F1F5F9' }
          },
          y: { ticks: { font: { size: 11 } }, grid: { display: false } }
        }
      }
    });
  </script>
  @endpush

</x-dashboard-layout>