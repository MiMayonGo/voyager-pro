<x-dashboard-layout title="Reports — {{ now()->year }}">

  {{-- ── HEADER ── --}}
  <div class="d-flex align-items-center justify-content-between mb-4">
    <div>
      <h5 class="fw-bold mb-0"><i class="bi bi-file-earmark-bar-graph me-2" style="color:#0F766E"></i>Annual Report</h5>
      <small class="text-muted">Static snapshot — {{ now()->year }} fiscal year</small>
    </div>
    <div class="d-flex gap-2">
      <a href="{{ route('reports.export') }}" class="btn btn-sm text-white" style="background:#0F766E">
        <i class="bi bi-download me-1"></i> Export Full Report (CSV)
      </a>
      <button onclick="window.print()" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-printer me-1"></i> Print
      </button>
    </div>
  </div>

  {{-- ── TAB NAV ── --}}
  <ul class="nav nav-tabs border-0 mb-4" id="reportTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active fw-semibold px-3" id="financial-tab" data-bs-toggle="tab"
              data-bs-target="#financial" type="button" role="tab" style="color:#0F766E">
        <i class="bi bi-cash-coin me-1"></i> Financial
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link fw-semibold px-3" id="customers-tab" data-bs-toggle="tab"
              data-bs-target="#customers" type="button" role="tab" style="color:#0F766E">
        <i class="bi bi-people-fill me-1"></i> Customers
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link fw-semibold px-3" id="packages-tab" data-bs-toggle="tab"
              data-bs-target="#packages" type="button" role="tab" style="color:#0F766E">
        <i class="bi bi-map-fill me-1"></i> Packages
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link fw-semibold px-3" id="operations-tab" data-bs-toggle="tab"
              data-bs-target="#operations" type="button" role="tab" style="color:#0F766E">
        <i class="bi bi-gear me-1"></i> Operations
      </button>
    </li>
  </ul>

  <div class="tab-content">

    {{-- ═══════════════════════════════════════════════════════════════════
         TAB 1: FINANCIAL
         ═══════════════════════════════════════════════════════════════════ --}}
    <div class="tab-pane fade show active" id="financial" role="tabpanel">

      {{-- Summary cards --}}
      <div class="row g-3 mb-4">
        <div class="col-auto">
          <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3 py-2 px-3">
              <i class="bi bi-cash-coin fs-4" style="color:#0F766E"></i>
              <div>
                <div class="text-muted small">Total Revenue</div>
                <div class="fw-bold fs-5">₱{{ number_format($salesTotalRevenue, 0) }}</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-auto">
          <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3 py-2 px-3">
              <i class="bi bi-receipt fs-4" style="color:#1D4ED8"></i>
              <div>
                <div class="text-muted small">Total Bookings</div>
                <div class="fw-bold fs-5">{{ number_format($salesTotalBookings) }}</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-auto">
          <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3 py-2 px-3">
              <i class="bi bi-currency-exchange fs-4" style="color:#15803D"></i>
              <div>
                <div class="text-muted small">Avg. Booking Value</div>
                <div class="fw-bold fs-5">₱{{ number_format($salesTotalBookings > 0 ? $salesTotalRevenue / $salesTotalBookings : 0, 0) }}</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-auto">
          <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3 py-2 px-3">
              <i class="bi bi-arrow-return-left fs-4" style="color:#DC2626"></i>
              <div>
                <div class="text-muted small">Refunded Amount</div>
                <div class="fw-bold fs-5">₱{{ number_format($refundedTotalAmount, 0) }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Charts row: Payment Status Pie + Revenue by Package Bar --}}
      <div class="row g-3 mb-4">
        <div class="col-12 col-xl-5">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-3 pb-0">
              <h6 class="fw-bold mb-0"><i class="bi bi-pie-chart me-1" style="color:#0F766E"></i>Payment Status</h6>
              <small class="text-muted">Breakdown of all payments</small>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
              <canvas id="paymentStatusChart" style="max-height:240px"></canvas>
            </div>
          </div>
        </div>
        <div class="col-12 col-xl-7">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-3 pb-0">
              <h6 class="fw-bold mb-0"><i class="bi bi-cash-stack me-1" style="color:#0F766E"></i>Revenue by Package</h6>
              <small class="text-muted">Total revenue generated per package</small>
            </div>
            <div class="card-body">
              <canvas id="finRevenueChart" height="160"></canvas>
            </div>
          </div>
        </div>
      </div>

      {{-- Monthly Trend --}}
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 pt-3 pb-0">
          <h6 class="fw-bold mb-0"><i class="bi bi-bar-chart-line me-1" style="color:#0F766E"></i>Monthly Trend</h6>
          <small class="text-muted">Revenue (bars) vs Bookings (line)</small>
        </div>
        <div class="card-body">
          <canvas id="monthlyChart" height="100"></canvas>
        </div>
      </div>

      {{-- Refunds / Cancellations table --}}
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 pt-3 pb-0">
          <h6 class="fw-bold mb-0"><i class="bi bi-exclamation-triangle me-1" style="color:#DC2626"></i>Refunds & Cancellations</h6>
          <small class="text-muted">{{ now()->year }} summary</small>
        </div>
        <div class="card-body p-0">
          <table class="table align-middle mb-0" style="font-size:.85rem">
            <thead class="table-light">
              <tr>
                <th class="ps-3">Metric</th>
                <th class="text-end pe-3">Value</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="ps-3">Cancelled Bookings</td>
                <td class="text-end pe-3 fw-semibold text-danger">{{ number_format($cancelledBookingsCount) }}</td>
              </tr>
              <tr>
                <td class="ps-3">Refunded Payments</td>
                <td class="text-end pe-3 fw-semibold text-danger">{{ number_format($refundedPaymentsCount) }}</td>
              </tr>
              <tr>
                <td class="ps-3">Total Refunded Amount</td>
                <td class="text-end pe-3 fw-semibold text-danger">₱{{ number_format($refundedTotalAmount, 0) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      {{-- Booking Details table --}}
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pt-3 pb-0 d-flex align-items-center justify-content-between">
          <div>
            <h6 class="fw-bold mb-0">Booking Details</h6>
            <small class="text-muted">{{ $salesTotalBookings }} records</small>
          </div>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:.85rem">
              <thead class="table-light">
                <tr>
                  <th class="ps-3">#</th>
                  <th>Customer</th>
                  <th>Package</th>
                  <th>Amount</th>
                  <th>Payment</th>
                  <th>Status</th>
                  <th class="text-end pe-3">Date</th>
                </tr>
              </thead>
              <tbody>
                @forelse($salesReport as $i => $booking)
                  <tr>
                    <td class="ps-3 text-muted">{{ $i + 1 }}</td>
                    <td>
                      <div class="fw-semibold">{{ $booking->user?->name ?? '—' }}</div>
                      <div class="text-muted" style="font-size:.75rem">{{ $booking->user?->email }}</div>
                    </td>
                    <td>{{ $booking->package?->title ?? '—' }}</td>
                    <td class="fw-semibold">₱{{ number_format($booking->total_price, 0) }}</td>
                    <td>
                      @php
                        $payStatus = $booking->payment?->status ?? 'unpaid';
                        $payBadge  = match($payStatus) {
                          'paid' => 'success', 'pending' => 'warning',
                          'failed' => 'danger', 'refunded' => 'secondary',
                          default => 'secondary',
                        };
                      @endphp
                      <span class="badge text-bg-{{ $payBadge }}">{{ ucfirst($payStatus) }}</span>
                    </td>
                    <td>
                      @php
                        $bBadge = match($booking->status) {
                          'confirmed' => 'success', 'pending' => 'warning',
                          'cancelled' => 'danger', 'completed' => 'primary',
                          default => 'secondary',
                        };
                      @endphp
                      <span class="badge text-bg-{{ $bBadge }}">{{ ucfirst($booking->status) }}</span>
                    </td>
                    <td class="text-end pe-3 text-muted" style="font-size:.8rem">
                      {{ $booking->created_at->format('M d, Y') }}
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                      <i class="bi bi-inbox fs-4 d-block mb-1"></i> No bookings in {{ now()->year }}.
                    </td>
                  </tr>
                @endforelse
              </tbody>
              @if($salesReport->isNotEmpty())
                <tfoot class="table-light fw-semibold">
                  <tr>
                    <td colspan="3" class="ps-3">Total</td>
                    <td>₱{{ number_format($salesTotalRevenue, 0) }}</td>
                    <td colspan="3"></td>
                  </tr>
                </tfoot>
              @endif
            </table>
          </div>
        </div>
      </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════
         TAB 2: CUSTOMERS
         ═══════════════════════════════════════════════════════════════════ --}}
    <div class="tab-pane fade" id="customers" role="tabpanel">

      {{-- Summary cards --}}
      <div class="row g-3 mb-4">
        <div class="col-auto">
          <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3 py-2 px-3">
              <i class="bi bi-people fs-4" style="color:#0F766E"></i>
              <div>
                <div class="text-muted small">Total Users</div>
                <div class="fw-bold fs-5">{{ number_format($userReport->count()) }}</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-auto">
          <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3 py-2 px-3">
              <i class="bi bi-arrow-repeat fs-4" style="color:#1D4ED8"></i>
              <div>
                <div class="text-muted small">Repeat Customers</div>
                <div class="fw-bold fs-5">{{ number_format($repeatCustomers) }}</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-auto">
          <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3 py-2 px-3">
              <i class="bi bi-person-check fs-4" style="color:#15803D"></i>
              <div>
                <div class="text-muted small">First-time Bookers</div>
                <div class="fw-bold fs-5">{{ number_format($firstTimeCustomers) }}</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-auto">
          <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3 py-2 px-3">
              <i class="bi bi-person-x fs-4" style="color:#94A3B8"></i>
              <div>
                <div class="text-muted small">No Bookings Yet</div>
                <div class="fw-bold fs-5">{{ number_format($zeroBookingUsers) }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Charts row: Repeat vs First-time + Registration Trend --}}
      <div class="row g-3 mb-4">
        <div class="col-12 col-xl-5">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-3 pb-0">
              <h6 class="fw-bold mb-0"><i class="bi bi-people me-1" style="color:#0F766E"></i>Customer Type</h6>
              <small class="text-muted">Repeat vs First-time</small>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
              <canvas id="customerTypeChart" style="max-height:240px"></canvas>
            </div>
          </div>
        </div>
        <div class="col-12 col-xl-7">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-3 pb-0">
              <h6 class="fw-bold mb-0"><i class="bi bi-person-plus me-1" style="color:#0F766E"></i>User Registration Trend</h6>
              <small class="text-muted">New users per month</small>
            </div>
            <div class="card-body">
              <canvas id="userRegChart" height="100"></canvas>
            </div>
          </div>
        </div>
      </div>

      {{-- User table --}}
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pt-3 pb-0">
          <h6 class="fw-bold mb-0">User Activity Details</h6>
          <small class="text-muted">All users — {{ now()->year }}</small>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:.85rem">
              <thead class="table-light">
                <tr>
                  <th class="ps-3">#</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th class="text-center">Bookings</th>
                  <th class="text-center">Type</th>
                  <th class="text-end pe-3">Total Spent</th>
                </tr>
              </thead>
              <tbody>
                @forelse($userReport as $i => $user)
                  <tr>
                    <td class="ps-3 text-muted">{{ $i + 1 }}</td>
                    <td class="fw-semibold">{{ $user->name }}</td>
                    <td class="text-muted" style="font-size:.8rem">{{ $user->email }}</td>
                    <td>
                      @php
                        $roleBadge = match($user->role_name) {
                          'super_admin' => 'dark', 'tour_manager' => 'primary', 'customer' => 'info',
                          default => 'secondary',
                        };
                      @endphp
                      <span class="badge text-bg-{{ $roleBadge }}">{{ str_replace('_', ' ', $user->role_name) }}</span>
                    </td>
                    <td class="text-center fw-semibold">{{ $user->bookings_count ?? 0 }}</td>
                    <td class="text-center">
                      @if(($user->bookings_count ?? 0) > 1)
                        <span class="badge text-bg-primary"><i class="bi bi-arrow-repeat me-1"></i>Repeat</span>
                      @elseif(($user->bookings_count ?? 0) === 1)
                        <span class="badge text-bg-success">First-time</span>
                      @else
                        <span class="badge text-bg-secondary">None</span>
                      @endif
                    </td>
                    <td class="text-end pe-3 fw-semibold">₱{{ number_format($user->total_spent ?? 0, 0) }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                      <i class="bi bi-people fs-4 d-block mb-1"></i> No users found.
                    </td>
                  </tr>
                @endforelse
              </tbody>
              @if($userReport->isNotEmpty())
                <tfoot class="table-light fw-semibold">
                  <tr>
                    <td colspan="4" class="ps-3">Total</td>
                    <td class="text-center">{{ $userReport->sum('bookings_count') }}</td>
                    <td></td>
                    <td class="text-end pe-3">₱{{ number_format($userReport->sum('total_spent'), 0) }}</td>
                  </tr>
                </tfoot>
              @endif
            </table>
          </div>
        </div>
      </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════
         TAB 3: PACKAGES
         ═══════════════════════════════════════════════════════════════════ --}}
    <div class="tab-pane fade" id="packages" role="tabpanel">

      {{-- Charts row: Doughnut + Worst performers --}}
      <div class="row g-3 mb-4">
        <div class="col-12 col-xl-5">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-3 pb-0">
              <h6 class="fw-bold mb-0"><i class="bi bi-pie-chart me-1" style="color:#0F766E"></i>Package Popularity</h6>
              <small class="text-muted">Booking share by package</small>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
              <canvas id="packageDoughnut" style="max-height:260px"></canvas>
            </div>
          </div>
        </div>
        <div class="col-12 col-xl-7">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-3 pb-0">
              <h6 class="fw-bold mb-0"><i class="bi bi-emoji-frown me-1" style="color:#DC2626"></i>Worst Performers</h6>
              <small class="text-muted">Packages with lowest bookings</small>
            </div>
            <div class="card-body p-0">
              <table class="table align-middle mb-0" style="font-size:.85rem">
                <thead class="table-light">
                  <tr>
                    <th class="ps-3">Package</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th class="text-center">Bookings</th>
                    <th class="text-center">Reviews</th>
                    <th class="text-center pe-3">Rating</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($worstPerformers as $pkg)
                    <tr>
                      <td class="ps-3 fw-semibold">{{ $pkg->title }}</td>
                      <td>₱{{ number_format($pkg->price, 0) }}</td>
                      <td>
                        @php
                          $sBadge = match($pkg->status) {
                            'active' => 'success', 'draft' => 'secondary', 'inactive' => 'danger',
                            default => 'secondary',
                          };
                        @endphp
                        <span class="badge text-bg-{{ $sBadge }}">{{ ucfirst($pkg->status) }}</span>
                      </td>
                      <td class="text-center fw-semibold text-danger">{{ $pkg->bookings_count ?? 0 }}</td>
                      <td class="text-center">{{ $pkg->review_count ?? 0 }}</td>
                      <td class="text-center pe-3">
                        @if($pkg->avg_rating)
                          <span class="text-warning">{{ number_format($pkg->avg_rating, 1) }}</span>
                        @else
                          <span class="text-muted">—</span>
                        @endif
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="6" class="text-center text-muted py-3">No data</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      {{-- Seasonal Trend Heatmap --}}
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 pt-3 pb-0">
          <h6 class="fw-bold mb-0"><i class="bi bi-calendar3 me-1" style="color:#0F766E"></i>Seasonal Trend</h6>
          <small class="text-muted">Bookings by month × package</small>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0 text-center" style="font-size:.8rem">
              <thead class="table-light">
                <tr>
                  <th class="text-start ps-3">Package</th>
                  @foreach($seasonalMonths as $m)
                    <th style="min-width:50px">{{ $m }}</th>
                  @endforeach
                </tr>
              </thead>
              <tbody>
                @forelse($seasonalData as $row)
                  <tr>
                    <td class="text-start ps-3 fw-semibold">{{ $row['package'] }}</td>
                    @foreach(range(1, 12) as $m)
                      @php
                        $val = $row[$m];
                        $bg = $val === 0 ? '' : ($val <= 2 ? 'bg-success bg-opacity-10' : ($val <= 5 ? 'bg-success bg-opacity-25' : 'bg-success bg-opacity-50'));
                      @endphp
                      <td class="{{ $bg }} fw-semibold">{{ $val }}</td>
                    @endforeach
                  </tr>
                @empty
                  <tr>
                    <td colspan="13" class="text-center text-muted py-3">No booking data available.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      {{-- Package table --}}
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pt-3 pb-0">
          <h6 class="fw-bold mb-0">Package Performance Details</h6>
          <small class="text-muted">All packages — {{ now()->year }}</small>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:.85rem">
              <thead class="table-light">
                <tr>
                  <th class="ps-3">#</th>
                  <th>Package</th>
                  <th>Price</th>
                  <th>Status</th>
                  <th class="text-center">Bookings</th>
                  <th class="text-center">Reviews</th>
                  <th class="text-center">Avg Rating</th>
                  <th class="text-end pe-3">Revenue</th>
                </tr>
              </thead>
              <tbody>
                @forelse($packageReport as $i => $pkg)
                  <tr>
                    <td class="ps-3 text-muted">{{ $i + 1 }}</td>
                    <td>
                      <div class="fw-semibold">{{ $pkg->title }}</div>
                      <div class="text-muted" style="font-size:.75rem">{{ $pkg->duration_days }}D / {{ $pkg->duration_days - 1 }}N</div>
                    </td>
                    <td class="fw-semibold">₱{{ number_format($pkg->price, 0) }}</td>
                    <td>
                      @php
                        $sBadge = match($pkg->status) {
                          'active' => 'success', 'draft' => 'secondary', 'inactive' => 'danger',
                          default => 'secondary',
                        };
                      @endphp
                      <span class="badge text-bg-{{ $sBadge }}">{{ ucfirst($pkg->status) }}</span>
                    </td>
                    <td class="text-center fw-semibold">{{ $pkg->bookings_count ?? 0 }}</td>
                    <td class="text-center">{{ $pkg->review_count ?? 0 }}</td>
                    <td class="text-center">
                      @if($pkg->avg_rating)
                        <span class="text-warning">{{ number_format($pkg->avg_rating, 1) }}</span>
                        <i class="bi bi-star-fill text-warning" style="font-size:.65rem"></i>
                      @else
                        <span class="text-muted">—</span>
                      @endif
                    </td>
                    <td class="text-end pe-3 fw-semibold">₱{{ number_format($pkg->total_revenue ?? 0, 0) }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                      <i class="bi bi-map fs-4 d-block mb-1"></i> No packages found.
                    </td>
                  </tr>
                @endforelse
              </tbody>
              @if($packageReport->isNotEmpty())
                <tfoot class="table-light fw-semibold">
                  <tr>
                    <td colspan="4" class="ps-3">Total</td>
                    <td class="text-center">{{ $packageReport->sum('bookings_count') }}</td>
                    <td class="text-center">{{ $packageReport->sum('review_count') }}</td>
                    <td class="text-center">—</td>
                    <td class="text-end pe-3">₱{{ number_format($packageReport->sum('total_revenue'), 0) }}</td>
                  </tr>
                </tfoot>
              @endif
            </table>
          </div>
        </div>
      </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════
         TAB 4: OPERATIONS
         ═══════════════════════════════════════════════════════════════════ --}}
    <div class="tab-pane fade" id="operations" role="tabpanel">

      {{-- Summary cards --}}
      <div class="row g-3 mb-4">
        <div class="col-auto">
          <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3 py-2 px-3">
              <i class="bi bi-check-circle fs-4" style="color:#15803D"></i>
              <div>
                <div class="text-muted small">Payment Success Rate</div>
                <div class="fw-bold fs-5">{{ $paymentSuccessRate }}%</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-auto">
          <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3 py-2 px-3">
              <i class="bi bi-clock-history fs-4" style="color:#1D4ED8"></i>
              <div>
                <div class="text-muted small">Avg. Payment Time</div>
                <div class="fw-bold fs-5">{{ $avgPaymentHours ? number_format($avgPaymentHours, 0) . ' hrs' : '—' }}</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-auto">
          <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3 py-2 px-3">
              <i class="bi bi-x-circle fs-4" style="color:#DC2626"></i>
              <div>
                <div class="text-muted small">Cancellation Rate</div>
                <div class="fw-bold fs-5">{{ $cancellationRate }}%</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-auto">
          <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3 py-2 px-3">
              <i class="bi bi-slash-circle fs-4" style="color:#DC2626"></i>
              <div>
                <div class="text-muted small">Cancelled Bookings</div>
                <div class="fw-bold fs-5">{{ number_format($cancelledBookingsYear) }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Charts row: Booking Status Doughnut --}}
      <div class="row g-3 mb-4">
        <div class="col-12 col-xl-6">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-3 pb-0">
              <h6 class="fw-bold mb-0"><i class="bi bi-pie-chart me-1" style="color:#0F766E"></i>Booking Status Breakdown</h6>
              <small class="text-muted">All bookings by status</small>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
              <canvas id="opsBookingStatusChart" style="max-height:240px"></canvas>
            </div>
          </div>
        </div>
        <div class="col-12 col-xl-6">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 pt-3 pb-0">
              <h6 class="fw-bold mb-0"><i class="bi bi-info-circle me-1" style="color:#0F766E"></i>Operational Summary</h6>
              <small class="text-muted">Key metrics at a glance</small>
            </div>
            <div class="card-body">
              <table class="table align-middle mb-0" style="font-size:.85rem">
                <tbody>
                  <tr>
                    <td class="fw-semibold ps-0">Total Payments ({{ now()->year }})</td>
                    <td class="text-end fw-semibold">{{ number_format($totalPayments ?? 0) }}</td>
                  </tr>
                  <tr>
                    <td class="fw-semibold ps-0">Successful Payments</td>
                    <td class="text-end fw-semibold text-success">{{ number_format($successfulPayments ?? 0) }}</td>
                  </tr>
                  <tr>
                    <td class="fw-semibold ps-0">Payment Success Rate</td>
                    <td class="text-end fw-semibold">{{ $paymentSuccessRate }}%</td>
                  </tr>
                  <tr>
                    <td class="fw-semibold ps-0">Avg. Booking → Payment Time</td>
                    <td class="text-end fw-semibold">{{ $avgPaymentHours ? number_format($avgPaymentHours, 0) . ' hours' : '—' }}</td>
                  </tr>
                  <tr>
                    <td class="fw-semibold ps-0">Total Bookings ({{ now()->year }})</td>
                    <td class="text-end fw-semibold">{{ number_format($totalBookingsYear ?? 0) }}</td>
                  </tr>
                  <tr>
                    <td class="fw-semibold ps-0">Cancelled Bookings</td>
                    <td class="text-end pe-3 fw-semibold text-danger">{{ number_format($cancelledBookingsYear) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>{{-- /.tab-content --}}

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

    // ── Payment Status Pie ──────────────────────────────────────────────
    new Chart(document.getElementById('paymentStatusChart'), {
      type: 'doughnut',
      data: {
        labels: @json($paymentStatusLabels),
        datasets: [{
          data: @json($paymentStatusCounts),
          backgroundColor: [green, amber, red, slate],
          borderWidth: 0,
          hoverOffset: 6,
        }]
      },
      options: {
        cutout: '62%',
        plugins: {
          legend: { position: 'bottom', labels: { font: { size: 11 }, boxWidth: 12, padding: 10 } }
        }
      }
    });

    // ── Revenue by Package (Financial tab) ──────────────────────────────
    const finPkgColors = ['#0F766E','#14B8A6','#5EEAD4','#99F6E4','#CCFBF1','#A7F3D0','#6EE7B7','#34D399','#10B981','#059669'];
    new Chart(document.getElementById('finRevenueChart'), {
      type: 'bar',
      data: {
        labels: @json($finPkgLabels),
        datasets: [{
          label: 'Revenue (₱)',
          data: @json($finPkgRevenueData),
          backgroundColor: finPkgColors.slice(0, @json($finPkgLabels).length),
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
            ticks: { callback: v => '₱' + v.toLocaleString(), font: { size: 11 } },
            grid: { color: '#F1F5F9' }
          },
          y: { ticks: { font: { size: 11 } }, grid: { display: false } }
        }
      }
    });

    // ── Monthly Revenue + Bookings (mixed chart) ───────────────────────
    new Chart(document.getElementById('monthlyChart'), {
      type: 'bar',
      data: {
        labels: @json($monthlyLabels),
        datasets: [
          {
            label: 'Revenue (₱)',
            data: @json($monthlyRevenueData),
            backgroundColor: 'rgba(15,118,110,0.7)',
            borderRadius: 4,
            order: 2,
          },
          {
            label: 'Bookings',
            data: @json($monthlyBookingData),
            type: 'line',
            borderColor: blue,
            backgroundColor: 'rgba(59,130,246,0.08)',
            borderWidth: 2.5,
            pointBackgroundColor: blue,
            pointRadius: 4,
            tension: 0.4,
            fill: true,
            yAxisID: 'y1',
            order: 1,
          }
        ]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: 'top', labels: { font: { size: 12 }, boxWidth: 12, padding: 12 } }
        },
        scales: {
          y: {
            beginAtZero: true,
            position: 'left',
            ticks: { callback: v => '₱' + v.toLocaleString(), font: { size: 11 } },
            grid: { color: '#F1F5F9' }
          },
          y1: {
            beginAtZero: true,
            position: 'right',
            ticks: { stepSize: 1, font: { size: 11 } },
            grid: { display: false }
          },
          x: { ticks: { font: { size: 11 } }, grid: { display: false } }
        }
      }
    });

    // ── Customer Type Doughnut ──────────────────────────────────────────
    new Chart(document.getElementById('customerTypeChart'), {
      type: 'doughnut',
      data: {
        labels: ['Repeat Customers', 'First-time Bookers', 'No Bookings'],
        datasets: [{
          data: [{{ $repeatCustomers }}, {{ $firstTimeCustomers }}, {{ $zeroBookingUsers }}],
          backgroundColor: [blue, green, slate],
          borderWidth: 0,
          hoverOffset: 6,
        }]
      },
      options: {
        cutout: '62%',
        plugins: {
          legend: { position: 'bottom', labels: { font: { size: 11 }, boxWidth: 12, padding: 10 } }
        }
      }
    });

    // ── User Registration Trend ────────────────────────────────────────
    new Chart(document.getElementById('userRegChart'), {
      type: 'line',
      data: {
        labels: @json($userRegistrationLabels),
        datasets: [{
          label: 'New Users',
          data: @json($userRegistrationData),
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
            ticks: { stepSize: 1, font: { size: 11 } },
            grid: { color: '#F1F5F9' }
          },
          x: { ticks: { font: { size: 11 } }, grid: { display: false } }
        }
      }
    });

    // ── Package Popularity Doughnut ─────────────────────────────────────
    const pkgLabels = @json($packageLabels);
    const pkgBookingData = @json($packageBookingData);
    const pkgColors = ['#0F766E','#14B8A6','#5EEAD4','#99F6E4','#CCFBF1','#A7F3D0','#6EE7B7','#34D399','#10B981','#059669'];
    new Chart(document.getElementById('packageDoughnut'), {
      type: 'doughnut',
      data: {
        labels: pkgLabels,
        datasets: [{
          data: pkgBookingData,
          backgroundColor: pkgColors.slice(0, pkgLabels.length),
          borderWidth: 0,
          hoverOffset: 6,
        }]
      },
      options: {
        cutout: '62%',
        plugins: {
          legend: { position: 'bottom', labels: { font: { size: 11 }, boxWidth: 12, padding: 10 } }
        }
      }
    });

    // ── Operations: Booking Status Doughnut ─────────────────────────────
    new Chart(document.getElementById('opsBookingStatusChart'), {
      type: 'doughnut',
      data: {
        labels: @json($bookingStatusLabels),
        datasets: [{
          data: @json($bookingStatusData),
          backgroundColor: [amber, green, blue, red],
          borderWidth: 0,
          hoverOffset: 6,
        }]
      },
      options: {
        cutout: '62%',
        plugins: {
          legend: { position: 'bottom', labels: { font: { size: 11 }, boxWidth: 12, padding: 10 } }
        }
      }
    });
  </script>
  @endpush

  {{-- Print styles --}}
  @push('styles')
  <style>
    @media print {
      #sidebar, #topbar, .nav-tabs, .btn { display: none !important; }
      #page-content { margin-left: 0 !important; padding-top: 0 !important; }
      .tab-pane { display: block !important; opacity: 1 !important; visibility: visible !important; }
      .tab-pane:not(#financial) { page-break-before: always; }
      .card { box-shadow: none !important; border: 1px solid #ddd !important; }
      body { font-size: 11pt; }
    }
  </style>
  @endpush

</x-dashboard-layout>
