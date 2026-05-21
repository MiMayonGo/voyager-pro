<x-dashboard-layout title="Bookings">

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
      <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
      <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @role('customer')

    {{-- ── CUSTOMER TABLE VIEW ── --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
      <div>
        <h5 class="fw-bold mb-0"><i class="bi bi-suitcase-lg-fill me-2" style="color:#0F766E"></i>My Bookings</h5>
        <small class="text-muted">Your travel reservations</small>
      </div>
    </div>

    <form method="GET" action="{{ route('bookings.index') }}" class="mb-4">
      <div class="input-group" style="max-width:420px">
        <span class="input-group-text bg-white border-end-0">
          <i class="bi bi-search text-muted"></i>
        </span>
        <input type="text" name="search" value="{{ $search ?? '' }}"
               class="form-control border-start-0 ps-0"
               placeholder="Search by package name…">
        @if(!empty($search))
          <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-x"></i>
          </a>
        @else
          <button class="btn btn-outline-secondary" type="submit">Search</button>
        @endif
      </div>
    </form>

    <div class="card border-0 shadow-sm">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" style="font-size:.875rem">
            <thead class="table-light">
              <tr>
                <th class="ps-4">Package</th>
                <th>Travel Date</th>
                <th>Slots</th>
                <th>Total</th>
                <th>Status</th>
                <th class="text-end pe-4">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($bookings as $booking)
                @php
                  $badge = match($booking->status) {
                    'confirmed' => 'success',
                    'pending'   => 'warning',
                    'cancelled' => 'danger',
                    'completed' => 'primary',
                    default     => 'secondary',
                  };
                @endphp
                <tr>
                  <td class="ps-4 fw-semibold">{{ $booking->package->title ?? 'Package Unavailable' }}</td>
                  <td class="text-muted">
                    <i class="bi bi-calendar3 me-1"></i>{{ $booking->travel_date->format('M d, Y') }}
                  </td>
                  <td class="text-center">{{ $booking->slots_booked }}</td>
                  <td class="fw-semibold" style="color:#0F766E">₱{{ number_format($booking->total_price, 0) }}</td>
                  <td>
                    <span class="badge text-bg-{{ $badge }}{{ $booking->status === 'pending' ? ' text-dark' : '' }}">
                      {{ ucfirst($booking->status) }}
                    </span>
                  </td>
                  <td class="text-end pe-4">
                    <div class="d-flex align-items-center justify-content-end gap-2">
                      <a href="{{ route('bookings.show', $booking) }}"
                         class="btn btn-sm btn-outline-secondary py-1 px-2" title="View">
                        <i class="bi bi-eye"></i>
                      </a>
                      @if(in_array($booking->status, ['pending', 'confirmed']))
                        <form method="POST" action="{{ route('bookings.cancel', $booking) }}"
                              onsubmit="return confirm('Cancel this booking?')">
                          @csrf @method('PATCH')
                          <button class="btn btn-sm btn-outline-danger py-1 px-2" title="Cancel">
                            <i class="bi bi-x-lg"></i>
                          </button>
                        </form>
                      @endif
                      @if($booking->status === 'completed')
                        @php
                          $alreadyReviewed = \App\Models\Review::where('user_id', auth()->id())
                            ->where('package_id', $booking->package_id)
                            ->exists();
                        @endphp
                        @if(!$alreadyReviewed)
                          <button class="btn btn-sm btn-outline-warning py-1 px-2"
                                  title="Write a review"
                                  data-bs-toggle="modal"
                                  data-bs-target="#reviewModal-{{ $booking->id }}">
                            <i class="bi bi-star"></i>
                          </button>
                        @else
                          <span class="text-muted" style="font-size:.72rem" title="Already reviewed">
                            <i class="bi bi-check2 text-success"></i>
                          </span>
                        @endif
                      @endif
                    </div>
                  </td>
                </tr>

                {{-- ── REVIEW MODAL ── --}}
                @if($booking->status === 'completed')
                  <div class="modal fade" id="reviewModal-{{ $booking->id }}" tabindex="-1"
                       aria-labelledby="reviewModalLabel-{{ $booking->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content border-0 shadow">
                        <form method="POST" action="{{ route('reviews.store') }}">
                          @csrf
                          <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                          <div class="modal-header border-0 pb-0">
                            <div>
                              <h5 class="fw-bold mb-0">
                                <i class="bi bi-star-fill text-warning me-2"></i>Review Package
                              </h5>
                              <small class="text-muted">{{ $booking->package->title ?? 'Package' }}</small>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>

                          <div class="modal-body">
                            {{-- Star rating --}}
                            <div class="mb-3">
                              <label class="form-label fw-semibold">Rating</label>
                              <div class="d-flex gap-2" id="starGroup-{{ $booking->id }}">
                                @for($s = 1; $s <= 5; $s++)
                                  <label class="star-label" style="cursor:pointer">
                                    <input type="radio" name="rating" value="{{ $s }}"
                                           class="d-none star-radio" required>
                                    <i class="bi bi-star fs-3 star-icon" style="color:#CBD5E1;transition:color .15s"></i>
                                  </label>
                                @endfor
                              </div>
                              @error('rating')
                                <div class="text-danger" style="font-size:.8rem">{{ $message }}</div>
                              @enderror
                            </div>

                            {{-- Comment --}}
                            <div class="mb-2">
                              <label for="comment-{{ $booking->id }}" class="form-label fw-semibold">
                                Comment <span class="text-muted fw-normal">(optional)</span>
                              </label>
                              <textarea name="comment" id="comment-{{ $booking->id }}" rows="3"
                                        class="form-control @error('comment') is-invalid @enderror"
                                        placeholder="Share your experience…">{{ old('comment') }}</textarea>
                              @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                              @enderror
                            </div>
                          </div>

                          <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn text-white" style="background:#0F766E">
                              <i class="bi bi-send me-1"></i> Submit Review
                            </button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                @endif

              @empty
                <tr>
                  <td colspan="6" class="text-center text-muted py-5">
                    <i class="bi bi-calendar2-x fs-3 d-block mb-2"></i>
                    @if(!empty($search))
                      No bookings match "{{ $search }}".
                      <a href="{{ route('bookings.index') }}" class="d-block mt-2 btn btn-sm btn-outline-secondary" style="width:fit-content;margin:auto">Clear search</a>
                    @else
                      No bookings yet.
                      <a href="{{ route('packages.index') }}" class="d-block mt-2 btn btn-sm text-white" style="background:#0F766E;width:fit-content;margin:auto">
                        <i class="bi bi-map me-1"></i> Browse Packages
                      </a>
                    @endif
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      @if($bookings->hasPages())
        <div class="card-footer bg-white border-0">{{ $bookings->links() }}</div>
      @endif
    </div>

    {{-- ── STAR RATING SCRIPT ── --}}
    @push('scripts')
    <script>
      document.querySelectorAll('[id^="starGroup-"]').forEach(group => {
        const radios = group.querySelectorAll('.star-radio');
        const icons  = group.querySelectorAll('.star-icon');

        function updateStars(selectedValue) {
          icons.forEach((icon, idx) => {
            if (idx < selectedValue) {
              icon.className = 'bi bi-star-fill fs-3 star-icon';
              icon.style.color = '#F59E0B';
            } else {
              icon.className = 'bi bi-star fs-3 star-icon';
              icon.style.color = '#CBD5E1';
            }
          });
        }

        radios.forEach(radio => {
          radio.addEventListener('change', () => {
            if (radio.checked) updateStars(parseInt(radio.value));
          });
        });

        // Hover effect
        icons.forEach((icon, idx) => {
          icon.addEventListener('mouseenter', () => {
            const checked = group.querySelector('.star-radio:checked');
            if (!checked) updateStars(idx + 1);
          });
          icon.addEventListener('mouseleave', () => {
            const checked = group.querySelector('.star-radio:checked');
            updateStars(checked ? parseInt(checked.value) : 0);
          });
        });
      });
    </script>
    @endpush

  @else

    {{-- ── MANAGER / ADMIN TABLE VIEW ── --}}
    <form method="GET" action="{{ route('bookings.index') }}" class="mb-3">
      <div class="input-group" style="max-width:420px">
        <span class="input-group-text bg-white border-end-0">
          <i class="bi bi-search text-muted"></i>
        </span>
        <input type="text" name="search" value="{{ $search ?? '' }}"
               class="form-control border-start-0 ps-0"
               placeholder="Search customer or package…">
        @if(!empty($search))
          <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-x"></i>
          </a>
        @else
          <button class="btn btn-outline-secondary" type="submit">Search</button>
        @endif
      </div>
    </form>

    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
        <div>
          <h5 class="fw-bold mb-0"><i class="bi bi-calendar2-check-fill me-2" style="color:#1D4ED8"></i>Bookings</h5>
          <small class="text-muted">All travel reservations</small>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" style="font-size:.875rem">
            <thead class="table-light">
              <tr>
                <th class="ps-4">Customer</th>
                <th>Package</th>
                <th>Travel Date</th>
                <th>Slots</th>
                <th>Total</th>
                <th>Status</th>
                <th class="text-end pe-4">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($bookings as $booking)
                <tr>
                  <td class="ps-4">
                    <div class="fw-semibold">{{ $booking->user->name ?? '—' }}</div>
                  </td>
                  <td>{{ Str::limit($booking->package->title ?? '—', 34) }}</td>
                  <td class="text-muted">
                    <i class="bi bi-calendar3 me-1"></i>{{ $booking->travel_date->format('M d, Y') }}
                  </td>
                  <td class="text-center">{{ $booking->slots_booked }}</td>
                  <td class="fw-semibold">₱{{ number_format($booking->total_price, 0) }}</td>
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
                  <td class="text-end pe-4">
                    <div class="d-flex align-items-center justify-content-end gap-2">
                      <a href="{{ route('bookings.show', $booking) }}"
                         class="btn btn-sm btn-outline-secondary py-1 px-2" title="View">
                        <i class="bi bi-eye"></i>
                      </a>
                      @if($booking->status === 'pending')
                        <form method="POST" action="{{ route('bookings.confirm', $booking) }}">
                          @csrf @method('PATCH')
                          <button class="btn btn-sm btn-outline-success py-1 px-2" title="Confirm">
                            <i class="bi bi-check-lg"></i>
                          </button>
                        </form>
                      @endif
                      @if($booking->status === 'confirmed')
                        <form method="POST" action="{{ route('bookings.complete', $booking) }}">
                          @csrf @method('PATCH')
                          <button class="btn btn-sm btn-outline-primary py-1 px-2" title="Mark Complete">
                            <i class="bi bi-flag-fill"></i>
                          </button>
                        </form>
                      @endif
                      @if(in_array($booking->status, ['pending', 'confirmed']))
                        <form method="POST" action="{{ route('bookings.cancel', $booking) }}"
                              onsubmit="return confirm('Cancel this booking?')">
                          @csrf @method('PATCH')
                          <button class="btn btn-sm btn-outline-danger py-1 px-2" title="Cancel">
                            <i class="bi bi-x-lg"></i>
                          </button>
                        </form>
                      @endif
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center text-muted py-5">
                    <i class="bi bi-calendar2-x fs-3 d-block mb-2"></i>
                    {{ !empty($search) ? 'No bookings match "'.$search.'".' : 'No bookings found.' }}
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      @if($bookings->hasPages())
        <div class="card-footer bg-white border-0">{{ $bookings->links() }}</div>
      @endif
    </div>

  @endrole

</x-dashboard-layout>