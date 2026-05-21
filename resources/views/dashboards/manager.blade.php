<x-dashboard-layout title="Manager Dashboard">

  {{-- ── STAT CARDS ── --}}
  <div class="row g-3 mb-4">

    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="rounded-3 p-3 flex-shrink-0" style="background:#F0FDFA">
            <i class="bi bi-map-fill fs-4" style="color:#0F766E"></i>
          </div>
          <div>
            <div class="text-muted small fw-semibold text-uppercase" style="font-size:.7rem;letter-spacing:.06em">Active Packages</div>
            <div class="fw-bold fs-3 lh-1 mt-1">{{ $activePackages }}</div>
            <div class="text-muted" style="font-size:.72rem">{{ $totalPackages }} total</div>
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
            <div class="fw-bold fs-3 lh-1 mt-1">{{ $totalBookings }}</div>
            <div class="text-muted" style="font-size:.72rem">
              @if($bookingsToday > 0)
                <span class="text-success">+{{ $bookingsToday }} today</span>
              @else
                None today
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="rounded-3 p-3 flex-shrink-0" style="background:#FFF7ED">
            <i class="bi bi-hourglass-split fs-4" style="color:#C2410C"></i>
          </div>
          <div>
            <div class="text-muted small fw-semibold text-uppercase" style="font-size:.7rem;letter-spacing:.06em">Pending Bookings</div>
            <div class="fw-bold fs-3 lh-1 mt-1 {{ $pendingBookings > 0 ? 'text-warning' : '' }}">{{ $pendingBookings }}</div>
            <div class="text-muted" style="font-size:.72rem">Awaiting confirmation</div>
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
            <div class="text-muted small fw-semibold text-uppercase" style="font-size:.7rem;letter-spacing:.06em">Revenue (Month)</div>
            <div class="fw-bold fs-3 lh-1 mt-1">₱{{ number_format($revenueMonth, 0) }}</div>
            <div class="text-muted" style="font-size:.72rem">{{ now()->format('F Y') }}</div>
          </div>
        </div>
      </div>
    </div>

  </div>

  {{-- ── CHARTS + TOP PACKAGES ── --}}
  <div class="row g-3 mb-4">

    <div class="col-12 col-xl-7">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white border-0 pt-3 pb-0 d-flex align-items-center justify-content-between">
          <div>
            <h6 class="fw-bold mb-0">Revenue Trend</h6>
            <small class="text-muted">Last 6 months</small>
          </div>
          <i class="bi bi-graph-up-arrow text-success fs-5"></i>
        </div>
        <div class="card-body">
          <canvas id="revenueChart" height="110"></canvas>
        </div>
      </div>
    </div>

    <div class="col-12 col-xl-5">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white border-0 pt-3 pb-0 d-flex align-items-center justify-content-between">
          <div>
            <h6 class="fw-bold mb-0">Top Packages</h6>
            <small class="text-muted">By booking count</small>
          </div>
          <i class="bi bi-trophy-fill text-warning fs-5"></i>
        </div>
        <div class="card-body">
          @forelse($topPackages as $i => $pkg)
            <div class="d-flex align-items-center gap-3 {{ !$loop->last ? 'mb-3' : '' }}">
              <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white flex-shrink-0"
                   style="width:28px;height:28px;font-size:.72rem;background:{{ ['#0F766E','#14B8A6','#5EEAD4','#99F6E4','#CCFBF1'][$i] ?? '#E2E8F0' }}">
                {{ $i + 1 }}
              </div>
              <div class="flex-grow-1 min-width-0">
                <div class="fw-semibold text-truncate" style="font-size:.83rem">{{ $pkg->title }}</div>
                <div class="progress mt-1" style="height:4px">
                  @php $max = $topPackages->first()->bookings_count ?: 1; @endphp
                  <div class="progress-bar" role="progressbar"
                       style="width:{{ ($pkg->bookings_count / $max) * 100 }}%;background:#0F766E"></div>
                </div>
              </div>
              <span class="badge text-bg-light border fw-semibold" style="font-size:.75rem">
                {{ $pkg->bookings_count }}
              </span>
            </div>
          @empty
            <div class="text-muted text-center py-3" style="font-size:.83rem">No packages yet.</div>
          @endforelse
        </div>
      </div>
    </div>

  </div>

  {{-- ── QUICK ACTIONS ── --}}
  <div class="row g-2 mb-4">
    <div class="col-auto">
      <a href="{{ route('packages.create') }}" class="btn btn-sm text-white" style="background:#0F766E">
        <i class="bi bi-plus-lg me-1"></i> New Package
      </a>
    </div>
    <div class="col-auto">
      <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-calendar2-check me-1"></i> All Bookings
        @if($pendingBookings > 0)
          <span class="badge text-bg-warning text-dark ms-1">{{ $pendingBookings }}</span>
        @endif
      </a>
    </div>
    <div class="col-auto">
      <a href="{{ route('reviews.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-star me-1"></i> Reviews
        @if($pendingReviews > 0)
          <span class="badge text-bg-danger ms-1">{{ $pendingReviews }}</span>
        @endif
      </a>
    </div>
    <div class="col-auto">
      <a href="{{ route('packages.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-map me-1"></i> All Packages
      </a>
    </div>
  </div>

  {{-- ── RECENT BOOKINGS ── --}}
  <div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 pt-3 pb-0 d-flex align-items-center justify-content-between">
      <div>
        <h6 class="fw-bold mb-0">Recent Bookings</h6>
        <small class="text-muted">Latest 8 entries</small>
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
              <th>Total</th>
              <th>Status</th>
              <th class="text-end pe-3">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentBookings as $booking)
              <tr>
                <td class="ps-3">
                  <div class="fw-semibold" style="font-size:.83rem">{{ $booking->user->name ?? '—' }}</div>
                </td>
                <td style="max-width:160px">
                  <span class="text-truncate d-inline-block" style="max-width:150px;font-size:.83rem">
                    {{ $booking->package->title ?? '—' }}
                  </span>
                </td>
                <td class="text-muted" style="font-size:.8rem">
                  <i class="bi bi-calendar3 me-1"></i>{{ $booking->travel_date->format('M d, Y') }}
                </td>
                <td class="fw-semibold" style="font-size:.83rem">₱{{ number_format($booking->total_price, 0) }}</td>
                <td>
                  @php
                    $badge = match($booking->status) {
                      'confirmed' => 'success',
                      'pending'   => 'warning',
                      'cancelled' => 'danger',
                      'completed' => 'primary',
                      default     => 'secondary',
                    };
                  @endphp
                  <span class="badge text-bg-{{ $badge }}{{ $booking->status === 'pending' ? ' text-dark' : '' }}">
                    {{ ucfirst($booking->status) }}
                  </span>
                </td>
                <td class="text-end pe-3">
                  <div class="d-flex align-items-center justify-content-end gap-1">
                    <a href="{{ route('bookings.show', $booking) }}"
                       class="btn btn-sm btn-outline-secondary py-0 px-2" title="View">
                      <i class="bi bi-eye"></i>
                    </a>
                    @if($booking->status === 'pending')
                      <form method="POST" action="{{ route('bookings.confirm', $booking) }}">
                        @csrf @method('PATCH')
                        <button class="btn btn-sm btn-outline-success py-0 px-2" title="Confirm">
                          <i class="bi bi-check-lg"></i>
                        </button>
                      </form>
                    @endif
                    @if($booking->status === 'confirmed')
                      <form method="POST" action="{{ route('bookings.complete', $booking) }}">
                        @csrf @method('PATCH')
                        <button class="btn btn-sm btn-outline-primary py-0 px-2" title="Mark Complete">
                          <i class="bi bi-flag-fill"></i>
                        </button>
                      </form>
                    @endif
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-5">
                  <i class="bi bi-calendar2-x fs-3 d-block mb-2"></i> No bookings yet.
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
    new Chart(document.getElementById('revenueChart'), {
      type: 'line',
      data: {
        labels: @json($revenueLabels),
        datasets: [{
          label: 'Revenue (₱)',
          data: @json($revenueData),
          borderColor: '#0F766E',
          backgroundColor: 'rgba(15,118,110,0.08)',
          borderWidth: 2.5,
          pointBackgroundColor: '#0F766E',
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
            ticks: { font: { size: 11 }, callback: v => '₱' + v.toLocaleString() },
            grid: { color: '#F1F5F9' }
          },
          x: { ticks: { font: { size: 11 } }, grid: { display: false } }
        }
      }
    });
  </script>
  @endpush

</x-dashboard-layout>
