<x-dashboard-layout title="{{ $package->title }}">

  {{-- Back + Actions bar --}}
  <div class="d-flex align-items-center justify-content-between mb-4">
    <a href="{{ route('packages.index') }}" class="btn btn-sm btn-outline-secondary">
      <i class="bi bi-arrow-left me-1"></i> Back to Packages
    </a>
    <div class="d-flex gap-2">
      @can('update', $package)
        <a href="{{ route('packages.edit', $package) }}" class="btn btn-sm btn-outline-secondary">
          <i class="bi bi-pencil me-1"></i> Edit
        </a>
      @endcan
      @can('delete', $package)
        <form method="POST" action="{{ route('packages.destroy', $package) }}"
              onsubmit="return confirm('Delete this package?')">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-outline-danger">
            <i class="bi bi-trash me-1"></i> Delete
          </button>
        </form>
      @endcan
    </div>
  </div>

  <div class="row g-4">

    {{-- LEFT: main content --}}
    <div class="col-12 col-xl-8">

      {{-- Cover image --}}
      @if($package->cover_image)
        <div class="rounded-3 overflow-hidden mb-4 shadow-sm" style="max-height:340px">
          <img src="{{ asset('storage/'.$package->cover_image) }}"
               class="w-100" style="max-height:340px;object-fit:cover;display:block">
        </div>
      @endif

      {{-- Title + badges --}}
      <div class="mb-3">
        <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
          @php
            $statusBadge = match($package->status) {
              'active'   => 'success',
              'draft'    => 'secondary',
              'inactive' => 'danger',
              default    => 'secondary',
            };
          @endphp
          <span class="badge text-bg-{{ $statusBadge }}">{{ ucfirst($package->status) }}</span>
          @foreach($package->categories as $cat)
            <span class="badge rounded-pill" style="background:#F0FDFA;color:#0F766E;border:1px solid #99F6E4">
              <i class="bi bi-tag me-1"></i>{{ $cat->name }}
            </span>
          @endforeach
        </div>
        <h3 class="fw-bold mb-0">{{ $package->title }}</h3>
      </div>

      {{-- Stat cards --}}
      @php
        $reviews = $package->reviews;
        $avgRating = $reviews->count() ? round($reviews->avg('rating'), 1) : null;
      @endphp
      <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3 py-3">
              <div class="rounded-3 p-2 flex-shrink-0" style="background:#F0FDFA">
                <i class="bi bi-cash-coin fs-5" style="color:#0F766E"></i>
              </div>
              <div>
                <div class="fw-bold fs-6 lh-1" style="color:#0F766E">₱{{ number_format($package->price, 0) }}</div>
                <div class="text-muted" style="font-size:.72rem">Per Person</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3 py-3">
              <div class="rounded-3 p-2 flex-shrink-0" style="background:#EFF6FF">
                <i class="bi bi-clock-fill fs-5" style="color:#1D4ED8"></i>
              </div>
              <div>
                <div class="fw-bold fs-6 lh-1">{{ $package->duration_days }}D / {{ $package->duration_days - 1 }}N</div>
                <div class="text-muted" style="font-size:.72rem">Duration</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3 py-3">
              <div class="rounded-3 p-2 flex-shrink-0" style="background:#FFF7ED">
                <i class="bi bi-people-fill fs-5" style="color:#C2410C"></i>
              </div>
              <div>
                @if($package->available_slots === 0)
                  <div class="fw-bold fs-6 lh-1 text-danger">Full</div>
                @elseif($package->available_slots <= 5)
                  <div class="fw-bold fs-6 lh-1 text-warning">{{ $package->available_slots }}</div>
                @else
                  <div class="fw-bold fs-6 lh-1 text-success">{{ $package->available_slots }}</div>
                @endif
                <div class="text-muted" style="font-size:.72rem">Slots Left</div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center gap-3 py-3">
              <div class="rounded-3 p-2 flex-shrink-0" style="background:#FFFBEB">
                <i class="bi bi-star-fill fs-5" style="color:#D97706"></i>
              </div>
              <div>
                <div class="fw-bold fs-6 lh-1" style="color:#D97706">{{ $avgRating ?? '—' }}</div>
                <div class="text-muted" style="font-size:.72rem">Rating ({{ $reviews->count() }})</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Description --}}
      <div class="card border-0 shadow-sm mb-4" style="border-left:4px solid #0F766E !important">
        <div class="card-header bg-white border-0 pt-3 pb-0">
          <h6 class="fw-bold mb-0" style="color:#0F766E"><i class="bi bi-file-text me-2"></i>Overview</h6>
        </div>
        <div class="card-body" style="font-size:.9rem;line-height:1.7;color:#475569">
          {{ $package->description ?? 'No description provided.' }}
        </div>
      </div>

      {{-- Itinerary --}}
      @if($package->itineraries->count())
        <div class="card border-0 shadow-sm mb-4" style="border-left:4px solid #3B82F6 !important">
          <div class="card-header bg-white border-0 pt-3 pb-0">
            <h6 class="fw-bold mb-0" style="color:#1D4ED8"><i class="bi bi-map me-2"></i>Day-by-Day Itinerary</h6>
          </div>
          <div class="card-body">
            <div class="d-flex flex-column gap-0">
              @foreach($package->itineraries as $day)
                <div class="d-flex gap-3">
                  <div class="flex-shrink-0 d-flex flex-column align-items-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white"
                         style="width:38px;height:38px;min-height:38px;background:linear-gradient(135deg,#0F766E,#14B8A6);font-size:.75rem;box-shadow:0 2px 6px rgba(15,118,110,.3)">
                      D{{ $day->day_number }}
                    </div>
                    @if(!$loop->last)
                      <div style="width:2px;flex:1;min-height:28px;background:linear-gradient(#14B8A6,#E2E8F0);margin-top:2px"></div>
                    @endif
                  </div>
                  <div class="pb-4 flex-grow-1 {{ !$loop->last ? '' : '' }}">
                    <div class="fw-semibold mb-1">{{ $day->title }}</div>
                    @if($day->location)
                      <div class="mb-1" style="font-size:.78rem;color:#64748B">
                        <i class="bi bi-geo-alt-fill me-1 text-danger"></i>{{ $day->location }}
                      </div>
                    @endif
                    @if($day->description)
                      <div class="text-muted mb-2" style="font-size:.83rem">{{ $day->description }}</div>
                    @endif
                    <div class="d-flex gap-1 flex-wrap">
                      @if($day->meals_included && count($day->meals_included))
                        @foreach($day->meals_included as $meal)
                          <span class="badge" style="background:#FFFBEB;color:#92400E;border:1px solid #FDE68A;font-size:.7rem">
                            <i class="bi bi-egg-fried me-1"></i>{{ ucfirst($meal) }}
                          </span>
                        @endforeach
                      @endif
                      @if($day->accommodation)
                        <span class="badge" style="background:#EFF6FF;color:#1E40AF;border:1px solid #BFDBFE;font-size:.7rem">
                          <i class="bi bi-building me-1"></i>{{ $day->accommodation }}
                        </span>
                      @endif
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      @endif

      {{-- Reviews --}}
      <div class="card border-0 shadow-sm" style="border-left:4px solid #F59E0B !important">
        <div class="card-header bg-white border-0 pt-3 pb-0 d-flex align-items-center justify-content-between">
          <h6 class="fw-bold mb-0" style="color:#B45309"><i class="bi bi-star-fill me-2" style="color:#F59E0B"></i>Customer Reviews</h6>
          @if($reviews->count())
            <span class="badge" style="background:#FFFBEB;color:#92400E;border:1px solid #FDE68A">
              {{ $reviews->count() }} review{{ $reviews->count() !== 1 ? 's' : '' }}
            </span>
          @endif
        </div>
        <div class="card-body">
          @forelse($reviews->take(5) as $review)
            <div class="{{ !$loop->last ? 'border-bottom pb-3 mb-3' : '' }}">
              <div class="d-flex align-items-center gap-2 mb-1">
                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white flex-shrink-0"
                     style="width:32px;height:32px;font-size:.65rem;background:linear-gradient(135deg,#0F766E,#14B8A6)">
                  {{ strtoupper(substr($review->user->name ?? '?', 0, 2)) }}
                </div>
                <div>
                  <div class="fw-semibold" style="font-size:.85rem">{{ $review->user->name ?? 'Anonymous' }}</div>
                  <div class="d-flex gap-1 align-items-center">
                    @for($s = 1; $s <= 5; $s++)
                      <i class="bi bi-star{{ $s <= $review->rating ? '-fill' : '' }}"
                         style="font-size:.65rem;color:{{ $s <= $review->rating ? '#F59E0B' : '#CBD5E1' }}"></i>
                    @endfor
                    <span class="ms-1 fw-semibold" style="font-size:.72rem;color:#92400E">{{ $review->rating }}/5</span>
                  </div>
                </div>
                <span class="ms-auto text-muted" style="font-size:.72rem">{{ $review->created_at->format('M d, Y') }}</span>
              </div>
              @if($review->body ?? $review->comment ?? null)
                <div style="font-size:.83rem;color:#64748B;padding-left:40px">
                  "{{ $review->body ?? $review->comment }}"
                </div>
              @endif
            </div>
          @empty
            <div class="text-center py-4" style="color:#94A3B8">
              <i class="bi bi-chat-square-text d-block fs-3 mb-1"></i>
              <div style="font-size:.85rem">No reviews yet for this package.</div>
            </div>
          @endforelse
        </div>
      </div>

    </div>

    {{-- RIGHT: booking form --}}
    <div class="col-12 col-xl-4">
      <div class="card border-0 shadow sticky-top" style="top:80px">
        {{-- Colored header --}}
        <div class="card-header border-0 py-3" style="background:linear-gradient(135deg,#0F766E,#0D9488)">
          <h6 class="fw-bold mb-0 text-white">
            <i class="bi bi-calendar2-plus me-2"></i>Book This Package
          </h6>
          <div class="text-white opacity-75" style="font-size:.78rem">
            ₱{{ number_format($package->price, 0) }} per person · {{ $package->duration_days }} days
          </div>
        </div>
        <div class="card-body">
          @auth
            @role('customer')
              @if($package->status === 'active' && $package->available_slots > 0)
                <form method="POST" action="{{ route('bookings.store') }}">
                  @csrf
                  <input type="hidden" name="package_id" value="{{ $package->id }}">

                  <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:.85rem">Travel Date</label>
                    <input type="date" name="travel_date" required
                           min="{{ date('Y-m-d') }}"
                           class="form-control form-control-sm @error('travel_date') is-invalid @enderror"
                           value="{{ old('travel_date') }}">
                    @error('travel_date')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="mb-3">
                    <label class="form-label fw-semibold" style="font-size:.85rem">
                      Slots
                      <span class="text-muted fw-normal">(max {{ $package->available_slots }})</span>
                    </label>
                    <input type="number" name="slots_booked" required
                           min="1" max="{{ $package->available_slots }}"
                           value="{{ old('slots_booked', 1) }}"
                           class="form-control form-control-sm @error('slots_booked') is-invalid @enderror">
                    @error('slots_booked')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>

                  <div class="mb-4">
                    <label class="form-label fw-semibold" style="font-size:.85rem">
                      Special Requests <span class="text-muted fw-normal">(optional)</span>
                    </label>
                    <textarea name="special_requests" rows="3"
                              class="form-control form-control-sm"
                              placeholder="Dietary needs, accessibility requirements...">{{ old('special_requests') }}</textarea>
                  </div>

                  <div class="rounded-3 p-3 mb-3" style="background:#F0FDFA;border:1px solid #99F6E4">
                    <div class="d-flex justify-content-between align-items-center" style="font-size:.85rem">
                      <span class="text-muted">Price per slot</span>
                      <span class="fw-bold" style="color:#0F766E">₱{{ number_format($package->price, 0) }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-1" style="font-size:.75rem;color:#94A3B8">
                      <span>Total calculated at checkout</span>
                    </div>
                  </div>

                  <button type="submit" class="btn w-100 fw-semibold text-white"
                          style="background:linear-gradient(135deg,#0F766E,#0D9488);border:none">
                    <i class="bi bi-calendar2-check me-1"></i> Confirm Booking
                  </button>
                </form>
              @elseif($package->available_slots === 0)
                <div class="text-center py-4">
                  <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                       style="width:56px;height:56px;background:#FEF2F2">
                    <i class="bi bi-x-circle-fill text-danger fs-4"></i>
                  </div>
                  <div class="fw-semibold">Fully Booked</div>
                  <div class="text-muted" style="font-size:.82rem">No slots available at this time.</div>
                </div>
              @else
                <div class="text-center py-4">
                  <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                       style="width:56px;height:56px;background:#F8FAFC">
                    <i class="bi bi-pause-circle-fill text-secondary fs-4"></i>
                  </div>
                  <div class="fw-semibold">Not Available</div>
                  <div class="text-muted" style="font-size:.82rem">This package is not accepting bookings.</div>
                </div>
              @endif
            @else
              <div class="text-center py-4">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                     style="width:56px;height:56px;background:#F0FDFA">
                  <i class="bi bi-info-circle-fill fs-4" style="color:#0F766E"></i>
                </div>
                <div class="fw-semibold mb-1">Customers only</div>
                <div class="text-muted" style="font-size:.82rem">Booking is available for customer accounts.</div>
              </div>
            @endrole
          @else
            <div class="text-center py-4">
              <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                   style="width:56px;height:56px;background:#F0FDFA">
                <i class="bi bi-lock-fill fs-4" style="color:#0F766E"></i>
              </div>
              <div class="fw-semibold mb-1">Sign in to book</div>
              <div class="text-muted mb-3" style="font-size:.82rem">You need an account to reserve this package.</div>
              <a href="{{ route('login') }}" class="btn fw-semibold text-white w-100"
                 style="background:linear-gradient(135deg,#0F766E,#0D9488);border:none">
                <i class="bi bi-box-arrow-in-right me-1"></i> Log In
              </a>
            </div>
          @endauth
        </div>
      </div>
    </div>

  </div>

</x-dashboard-layout>
