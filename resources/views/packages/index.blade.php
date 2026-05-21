<x-dashboard-layout title="Packages">

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
      <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
      <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @role('customer')
    {{-- ── EXPLORE MORE PACKAGES (Original Grid System) ── --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
      <div>
        <h5 class="fw-bold mb-0"><i class="bi bi-compass me-2" style="color:#0F766E"></i>Explore More Packages</h5>
        <small class="text-muted">Find your next adventure to book</small>
      </div>
    </div>

    <form method="GET" action="{{ route('packages.index') }}" class="mb-4">
      <div class="d-flex flex-wrap gap-2">
        <div class="input-group" style="max-width:360px">
          <span class="input-group-text bg-white border-end-0">
            <i class="bi bi-search text-muted"></i>
          </span>
          <input type="text" name="search" value="{{ $search ?? '' }}"
                 class="form-control border-start-0 ps-0"
                 placeholder="Search packages…">
        </div>
        <select name="category" class="form-select" style="max-width:200px">
          <option value="">All Categories</option>
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ ($category ?? '') == $cat->id ? 'selected' : '' }}>
              {{ $cat->name }}
            </option>
          @endforeach
        </select>
        <button type="submit" class="btn text-white" style="background:#0F766E">
          <i class="bi bi-search me-1"></i> Search
        </button>
        @if(!empty($search) || !empty($category))
          <a href="{{ route('packages.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-x me-1"></i> Clear
          </a>
        @endif
      </div>
    </form>

    @if($packages->isEmpty())
      <div class="text-center py-5 text-muted">
        <i class="bi bi-map fs-1 d-block mb-3" style="color:#CBD5E1"></i>
        <div class="fw-semibold mb-1">No packages found</div>
        @if(!empty($search) || !empty($category))
          <a href="{{ route('packages.index') }}" class="btn btn-sm btn-outline-secondary mt-2">Clear filters</a>
        @endif
      </div>
    @else
      <div class="row g-4">
        @foreach($packages as $package)
          <div class="col-12 col-sm-6 col-xl-4">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
              @if($package->cover_image)
                <div style="height:180px;overflow:hidden">
                  <img src="{{ asset('storage/'.$package->cover_image) }}"
                       class="w-100" style="object-fit:cover;height:180px;display:block">
                </div>
              @else
                <div class="d-flex align-items-center justify-content-center"
                     style="height:120px;background:linear-gradient(135deg,#F0FDFA,#CCFBF1)">
                  <i class="bi bi-image fs-1" style="color:#5EEAD4"></i>
                </div>
              @endif
              <div class="card-body d-flex flex-column">
                {{-- Categories --}}
                @if($package->categories->isNotEmpty())
                  <div class="d-flex flex-wrap gap-1 mb-2">
                    @foreach($package->categories->take(3) as $cat)
                      <span class="badge rounded-pill text-bg-light border" style="font-size:.7rem">{{ $cat->name }}</span>
                    @endforeach
                  </div>
                @endif

                <h6 class="fw-bold mb-1">{{ $package->title }}</h6>

                {{-- Star rating --}}
                <div class="d-flex align-items-center gap-1 mb-2" style="font-size:.8rem">
                  @php $avg = round($package->avg_rating ?? 0, 1); @endphp
                  @for($i = 1; $i <= 5; $i++)
                    <i class="bi bi-star{{ $i <= $avg ? '-fill' : ($i - 0.5 <= $avg ? '-half' : '') }}"
                       style="color:#F59E0B;font-size:.75rem"></i>
                  @endfor
                  <span class="text-muted ms-1">{{ $avg > 0 ? $avg : 'No reviews' }}
                    @if($package->review_count > 0)({{ $package->review_count }})@endif
                  </span>
                </div>

                {{-- Meta chips --}}
                <div class="d-flex flex-wrap gap-2 mb-3" style="font-size:.78rem;color:#64748B">
                  <span><i class="bi bi-clock me-1"></i>{{ $package->duration_days }}D / {{ $package->duration_days - 1 }}N</span>
                  @if($package->available_slots > 0)
                    <span><i class="bi bi-people me-1"></i>{{ $package->available_slots }} slots left</span>
                  @else
                    <span class="text-danger fw-semibold"><i class="bi bi-x-circle me-1"></i>Sold out</span>
                  @endif
                </div>

                <div class="d-flex align-items-center justify-content-between mt-auto">
                  <div>
                    <div class="fw-bold fs-5" style="color:#0F766E">₱{{ number_format($package->price, 0) }}</div>
                    <div class="text-muted" style="font-size:.72rem">per person</div>
                  </div>
                  <a href="{{ route('packages.show', $package) }}"
                     class="btn btn-sm text-white px-3" style="background:#0F766E">
                    <i class="bi bi-arrow-right me-1"></i> View
                  </a>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>
      @if($packages->hasPages())
        <div class="mt-4">{{ $packages->links() }}</div>
      @endif
    @endif

  @else

    {{-- ── MANAGER / ADMIN TABLE VIEW ── --}}
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
        <div>
          <h5 class="fw-bold mb-0"><i class="bi bi-map-fill me-2" style="color:#C2410C"></i>Tour Packages</h5>
          <small class="text-muted">All packages in the system</small>
        </div>
        @can('create', \App\Models\Package::class)
          <a href="{{ route('packages.create') }}" class="btn btn-sm text-white" style="background:#0F766E">
            <i class="bi bi-plus-lg me-1"></i> New Package
          </a>
        @endcan
      </div>
      <div class="card-body p-0">
        {{-- Search & Filter Form --}}
        <form method="GET" action="{{ route('packages.index') }}" class="px-3 pt-3 pb-2 border-bottom">
          <div class="d-flex flex-wrap gap-2 align-items-center">
            <div class="input-group" style="max-width:320px">
              <span class="input-group-text bg-white border-end-0">
                <i class="bi bi-search text-muted"></i>
              </span>
              <input type="text" name="search" value="{{ $search ?? '' }}"
                     class="form-control border-start-0 ps-0"
                     placeholder="Search by title, description or price…">
            </div>
            <select name="category" class="form-select" style="max-width:180px">
              <option value="">All Categories</option>
              @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ ($category ?? '') == $cat->id ? 'selected' : '' }}>
                  {{ $cat->name }}
                </option>
              @endforeach
            </select>
            <button type="submit" class="btn btn-sm text-white" style="background:#0F766E">
              <i class="bi bi-search me-1"></i> Search
            </button>
            @if(!empty($search) || !empty($category))
              <a href="{{ route('packages.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-x me-1"></i> Clear
              </a>
            @endif
          </div>
        </form>
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" style="font-size:.875rem">
            <thead class="table-light">
              <tr>
                <th class="ps-4">Title</th>
                <th>Price</th>
                <th>Duration</th>
                <th>Slots</th>
                <th>Status</th>
                <th class="text-end pe-4">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($packages as $package)
                <tr>
                  <td class="ps-4">
                    <a href="{{ route('packages.show', $package) }}"
                       class="fw-semibold text-decoration-none" style="color:#0F766E">
                      {{ $package->title }}
                    </a>
                  </td>
                  <td class="fw-semibold">₱{{ number_format($package->price, 0) }}</td>
                  <td class="text-muted">
                    <i class="bi bi-clock me-1"></i>{{ $package->duration_days }}D / {{ $package->duration_days - 1 }}N
                  </td>
                  <td>
                    @if($package->available_slots <= 5 && $package->available_slots > 0)
                      <span class="badge text-bg-danger">{{ $package->available_slots }} left</span>
                    @elseif($package->available_slots === 0)
                      <span class="badge text-bg-secondary">Sold out</span>
                    @else
                      <span class="text-muted">{{ $package->available_slots }}</span>
                    @endif
                  </td>
                  <td>
                    @php
                      $statusBadge = match($package->status) {
                        'active'   => 'success',
                        'draft'    => 'secondary',
                        'inactive' => 'danger',
                        default    => 'secondary',
                      };
                    @endphp
                    <span class="badge text-bg-{{ $statusBadge }}">{{ ucfirst($package->status) }}</span>
                  </td>
                  <td class="text-end pe-4">
                    <div class="d-flex align-items-center justify-content-end gap-2">
                      <a href="{{ route('packages.show', $package) }}"
                         class="btn btn-sm btn-outline-secondary py-1 px-2" title="View">
                        <i class="bi bi-eye"></i>
                      </a>
                      @can('update', $package)
                        <a href="{{ route('packages.edit', $package) }}"
                           class="btn btn-sm btn-outline-secondary py-1 px-2" title="Edit">
                          <i class="bi bi-pencil"></i>
                        </a>
                      @endcan
                      @can('delete', $package)
                        <form method="POST" action="{{ route('packages.destroy', $package) }}"
                              onsubmit="return confirm('Delete this package?')">
                          @csrf @method('DELETE')
                          <button class="btn btn-sm btn-outline-danger py-1 px-2" title="Delete">
                            <i class="bi bi-trash"></i>
                          </button>
                        </form>
                      @endcan
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center text-muted py-5">
                    <i class="bi bi-map fs-3 d-block mb-2"></i> No packages found.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      @if($packages->hasPages())
        <div class="card-footer bg-white border-0">{{ $packages->links() }}</div>
      @endif
    </div>

  @endrole

</x-dashboard-layout>