<x-dashboard-layout title="My Reviews">

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
      <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
      <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
      <div>
        <h5 class="fw-bold mb-0"><i class="bi bi-star-fill me-2 text-warning"></i>My Reviews</h5>
        <small class="text-muted">Reviews you have written</small>
      </div>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.875rem">
          <thead class="table-light">
            <tr>
              <th class="ps-4">Package</th>
              <th>Rating</th>
              <th>Comment</th>
              <th>Submitted</th>
              <th class="text-end pe-4">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($reviews as $review)
              <tr>
                <td class="ps-4">
                  <span class="fw-semibold">{{ Str::limit($review->package->title ?? '—', 40) }}</span>
                </td>
                <td>
                  <div class="d-flex align-items-center gap-1">
                    @for($s = 1; $s <= 5; $s++)
                      <i class="bi bi-star{{ $s <= $review->rating ? '-fill' : '' }}"
                         style="font-size:.7rem;color:{{ $s <= $review->rating ? '#F59E0B' : '#CBD5E1' }}"></i>
                    @endfor
                    <span class="ms-1 fw-semibold" style="font-size:.8rem">{{ $review->rating }}</span>
                  </div>
                </td>
                <td style="max-width:220px">
                  <span class="text-muted d-inline-block text-truncate" style="max-width:200px;font-size:.8rem">
                    {{ $review->comment ?? '—' }}
                  </span>
                </td>
                <td>
                  <span class="text-muted" style="font-size:.8rem">{{ $review->created_at->format('M d, Y') }}</span>
                </td>
                <td class="text-end pe-4">
                  <div class="d-flex align-items-center justify-content-end gap-2">
                    <a href="{{ route('reviews.edit', $review) }}"
                       class="btn btn-sm btn-outline-primary py-1 px-2" title="Edit">
                      <i class="bi bi-pencil"></i>
                    </a>
                    <form method="POST" action="{{ route('reviews.destroy', $review) }}"
                          onsubmit="return confirm('Delete this review?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-outline-danger py-1 px-2" title="Delete">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center text-muted py-5">
                  <i class="bi bi-chat-square-text fs-3 d-block mb-2"></i> You haven't written any reviews yet.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    @if($reviews->hasPages())
      <div class="card-footer bg-white border-0">{{ $reviews->links() }}</div>
    @endif
  </div>

</x-dashboard-layout>
