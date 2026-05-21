<x-dashboard-layout title="Booking Details">

  <div class="d-flex align-items-center justify-content-between mb-4">
    <div>
      <h5 class="fw-bold mb-0"><i class="bi bi-calendar2-check-fill me-2" style="color:#1D4ED8"></i>Booking Details</h5>
      <small class="text-muted">Reservation #{{ strtoupper(substr($booking->id, 0, 8)) }}</small>
    </div>
    <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-outline-secondary">
      <i class="bi bi-arrow-left me-1"></i> Back to Bookings
    </a>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
      <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
      <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="row g-4">

    {{-- LEFT: Booking info --}}
    <div class="col-12 col-xl-8">

      {{-- Status banner --}}
      @php
        $bannerBg = match($booking->status) {
          'confirmed' => ['bg' => '#F0FDF4', 'border' => '#86EFAC', 'icon' => 'bi-check-circle-fill', 'color' => '#15803D'],
          'pending'   => ['bg' => '#FFFBEB', 'border' => '#FCD34D', 'icon' => 'bi-hourglass-split',   'color' => '#B45309'],
          'cancelled' => ['bg' => '#FEF2F2', 'border' => '#FCA5A5', 'icon' => 'bi-x-circle-fill',     'color' => '#DC2626'],
          'completed' => ['bg' => '#EFF6FF', 'border' => '#93C5FD', 'icon' => 'bi-flag-fill',          'color' => '#1D4ED8'],
          default     => ['bg' => '#F8FAFC', 'border' => '#CBD5E1', 'icon' => 'bi-circle',             'color' => '#64748B'],
        };
      @endphp
      <div class="rounded-3 d-flex align-items-center gap-3 px-4 py-3 mb-4"
           style="background:{{ $bannerBg['bg'] }};border:1px solid {{ $bannerBg['border'] }}">
        <i class="bi {{ $bannerBg['icon'] }} fs-4" style="color:{{ $bannerBg['color'] }}"></i>
        <div>
          <div class="fw-semibold" style="color:{{ $bannerBg['color'] }}">{{ ucfirst($booking->status) }}</div>
          <div class="text-muted" style="font-size:.78rem">
            @if($booking->status === 'pending') Awaiting confirmation by a manager.
            @elseif($booking->status === 'confirmed') Booking has been confirmed.
            @elseif($booking->status === 'completed') Trip has been completed.
            @elseif($booking->status === 'cancelled') This booking was cancelled.
            @endif
          </div>
        </div>
      </div>

      {{-- Package + trip details --}}
      <div class="card border-0 shadow-sm mb-4" style="border-left:4px solid #0F766E !important">
        <div class="card-header bg-white border-0 pt-3 pb-0">
          <h6 class="fw-bold mb-0" style="color:#0F766E"><i class="bi bi-map me-2"></i>Trip Details</h6>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-sm-6">
              <div class="text-muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em">Package</div>
              <div class="fw-semibold mt-1">{{ $booking->package->title ?? '—' }}</div>
            </div>
            <div class="col-sm-6">
              <div class="text-muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em">Travel Date</div>
              <div class="fw-semibold mt-1">
                <i class="bi bi-calendar3 me-1 text-muted"></i>{{ $booking->travel_date->format('F d, Y') }}
              </div>
            </div>
            <div class="col-sm-6">
              <div class="text-muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em">Slots Booked</div>
              <div class="fw-semibold mt-1">
                <i class="bi bi-people me-1 text-muted"></i>{{ $booking->slots_booked }} {{ Str::plural('person', $booking->slots_booked) }}
              </div>
            </div>
            <div class="col-sm-6">
              <div class="text-muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em">Total Price</div>
              <div class="fw-bold mt-1 fs-5" style="color:#0F766E">₱{{ number_format($booking->total_price, 0) }}</div>
            </div>
            @hasanyrole('tour_manager|super_admin')
              <div class="col-sm-6">
                <div class="text-muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em">Customer</div>
                <div class="fw-semibold mt-1">
                  <i class="bi bi-person me-1 text-muted"></i>{{ $booking->user->name ?? '—' }}
                </div>
              </div>
              <div class="col-sm-6">
                <div class="text-muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em">Email</div>
                <div class="mt-1" style="font-size:.88rem">{{ $booking->user->email ?? '—' }}</div>
              </div>
            @endhasanyrole
            <div class="col-sm-6">
              <div class="text-muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em">Booked On</div>
              <div class="mt-1" style="font-size:.88rem">{{ $booking->created_at->format('M d, Y · h:i A') }}</div>
            </div>
          </div>

          @if($booking->special_requests)
            <div class="mt-4 pt-3 border-top">
              <div class="text-muted mb-1" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em">Special Requests</div>
              <div class="rounded-3 p-3" style="background:#F8FAFC;font-size:.88rem;color:#475569">
                {{ $booking->special_requests }}
              </div>
            </div>
          @endif
        </div>
      </div>

      {{-- Payment info --}}
      @if($booking->payment)
        <div class="card border-0 shadow-sm" style="border-left:4px solid #15803D !important">
          <div class="card-header bg-white border-0 pt-3 pb-0">
            <h6 class="fw-bold mb-0" style="color:#15803D"><i class="bi bi-cash-coin me-2"></i>Payment</h6>
          </div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-sm-4">
                <div class="text-muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em">Amount</div>
                <div class="fw-bold mt-1">₱{{ number_format($booking->payment->amount, 0) }}</div>
              </div>
              <div class="col-sm-4">
                <div class="text-muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em">Method</div>
                <div class="fw-semibold mt-1">{{ ucfirst($booking->payment->method ?? '—') }}</div>
              </div>
              <div class="col-sm-4">
                <div class="text-muted" style="font-size:.72rem;text-transform:uppercase;letter-spacing:.05em">Status</div>
                @php
                  $payBadge = match($booking->payment->status) {
                    'paid'     => 'success',
                    'pending'  => 'warning',
                    'failed'   => 'danger',
                    'refunded' => 'secondary',
                    default    => 'secondary',
                  };
                @endphp
                <span class="badge text-bg-{{ $payBadge }} mt-1">{{ ucfirst($booking->payment->status) }}</span>
              </div>
            </div>
          </div>
        </div>
      @endif

    </div>

    {{-- RIGHT: Actions --}}
    <div class="col-12 col-xl-4">
      <div class="card border-0 shadow sticky-top" style="top:80px">
        <div class="card-header border-0 py-3" style="background:linear-gradient(135deg,#1E3A5F,#1D4ED8)">
          <h6 class="fw-bold mb-0 text-white"><i class="bi bi-gear me-2"></i>Actions</h6>
          <div class="text-white opacity-75" style="font-size:.78rem">Manage this booking</div>
        </div>
        <div class="card-body d-flex flex-column gap-2">

          @hasanyrole('tour_manager|super_admin')

            @if($booking->status === 'pending')
              <form method="POST" action="{{ route('bookings.confirm', $booking) }}">
                @csrf @method('PATCH')
                <button class="btn w-100 fw-semibold text-white"
                        style="background:linear-gradient(135deg,#0F766E,#0D9488);border:none">
                  <i class="bi bi-check-circle me-2"></i> Approve Booking
                </button>
              </form>
            @endif

            @if($booking->status === 'confirmed')
              <form method="POST" action="{{ route('bookings.complete', $booking) }}">
                @csrf @method('PATCH')
                <button class="btn w-100 fw-semibold text-white"
                        style="background:linear-gradient(135deg,#1D4ED8,#3B82F6);border:none">
                  <i class="bi bi-flag-fill me-2"></i> Mark as Completed
                </button>
              </form>
            @endif

          @endhasanyrole

          @if(in_array($booking->status, ['pending', 'confirmed']))
            <form method="POST" action="{{ route('bookings.cancel', $booking) }}"
                  onsubmit="return confirm('Are you sure you want to cancel this booking?')">
              @csrf @method('PATCH')
              <button class="btn w-100 btn-outline-danger fw-semibold">
                <i class="bi bi-x-circle me-2"></i> Cancel Booking
              </button>
            </form>
          @endif

          @if(in_array($booking->status, ['cancelled', 'completed']))
            <div class="text-center py-2 text-muted" style="font-size:.82rem">
              <i class="bi bi-lock me-1"></i> No further actions available.
            </div>
          @endif

          <hr class="my-1">

          <a href="{{ route('packages.show', $booking->package) }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-map me-1"></i> View Package
          </a>
          <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-list-ul me-1"></i> All Bookings
          </a>

        </div>
      </div>
    </div>

  </div>

</x-dashboard-layout>