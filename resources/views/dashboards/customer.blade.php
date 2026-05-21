<x-dashboard-layout title="My Dashboard">

  {{-- ── STAT CARDS ── --}}
  <div class="row g-3 mb-4">

    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="rounded-3 p-3 flex-shrink-0" style="background:#FFFBEB">
            <i class="bi bi-hourglass-split fs-4" style="color:#B45309"></i>
          </div>
          <div>
            <div class="text-muted small fw-semibold text-uppercase" style="font-size:.7rem;letter-spacing:.06em">Pending Bookings</div>
            <div class="fw-bold fs-3 lh-1 mt-1 {{ $pendingCount > 0 ? 'text-warning' : '' }}">{{ $pendingCount }}</div>
            <div class="text-muted" style="font-size:.72rem">Awaiting confirmation</div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="rounded-3 p-3 flex-shrink-0" style="background:#F0FDF4">
            <i class="bi bi-check2-circle fs-4" style="color:#15803D"></i>
          </div>
          <div>
            <div class="text-muted small fw-semibold text-uppercase" style="font-size:.7rem;letter-spacing:.06em">Confirmed / Completed</div>
            <div class="fw-bold fs-3 lh-1 mt-1" style="color:#15803D">{{ $confirmedCompletedCount }}</div>
            <div class="text-muted" style="font-size:.72rem">Successfully processed</div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="rounded-3 p-3 flex-shrink-0" style="background:#FEF2F2">
            <i class="bi bi-x-circle fs-4" style="color:#DC2626"></i>
          </div>
          <div>
            <div class="text-muted small fw-semibold text-uppercase" style="font-size:.7rem;letter-spacing:.06em">Cancelled Bookings</div>
            <div class="fw-bold fs-3 lh-1 mt-1 text-danger">{{ $cancelledCount }}</div>
            <div class="text-muted" style="font-size:.72rem">Tours you cancelled</div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="rounded-3 p-3 flex-shrink-0" style="background:#EFF6FF">
            <i class="bi bi-send-fill fs-4" style="color:#1D4ED8"></i>
          </div>
          <div>
            <div class="text-muted small fw-semibold text-uppercase" style="font-size:.7rem;letter-spacing:.06em">Upcoming Tours</div>
            <div class="fw-bold fs-3 lh-1 mt-1" style="color:#1D4ED8">{{ $upcomingCount }}</div>
            <div class="text-muted" style="font-size:.72rem">
              @if($nextTravelDate)
                Next: {{ $nextTravelDate->format('M d, Y') }}
              @else
                No upcoming trips
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  {{-- ── QUICK ACTIONS ── --}}
  <div class="row g-2 mb-4">
    <div class="col-auto">
      <a href="{{ route('bookings.index') }}" class="btn btn-sm text-white" style="background:#0F766E">
        <i class="bi bi-suitcase-lg me-1"></i> My Bookings
      </a>
    </div>
    <div class="col-auto">
      <a href="{{ route('packages.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-compass me-1"></i> Browse Packages
      </a>
    </div>
  </div>

  {{-- ── RECENT BOOKINGS TABLE ── --}}
  <div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 pt-3 pb-0 d-flex align-items-center justify-content-between">
      <div>
        <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2" style="color:#0F766E"></i>Recent Bookings</h6>
        <small class="text-muted">Your latest reservations</small>
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
              <th class="ps-3">Package</th>
              <th>Travel Date</th>
              <th>Slots</th>
              <th>Total</th>
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @forelse($recentBookings as $booking)
              <tr>
                <td class="ps-3">
                  <div class="fw-semibold">{{ Str::limit($booking->package?->title ?? '—', 36) }}</div>
                </td>
                <td class="text-muted">
                  <i class="bi bi-calendar3 me-1"></i>{{ $booking->travel_date?->format('M d, Y') ?? '—' }}
                </td>
                <td class="text-center">{{ $booking->slots_booked }}</td>
                <td class="fw-semibold" style="color:#0F766E">₱{{ number_format($booking->total_price, 0) }}</td>
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
                  <a href="{{ route('packages.index') }}" class="d-block mt-2 btn btn-sm text-white" style="background:#0F766E;width:fit-content;margin:auto">
                    <i class="bi bi-map me-1"></i> Browse Packages
                  </a>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

</x-dashboard-layout>
