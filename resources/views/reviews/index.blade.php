<x-dashboard-layout title="Reviews">

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
      <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
      <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
      <div>
        <h5 class="fw-bold mb-0"><i class="bi bi-star-fill me-2 text-warning"></i>Reviews</h5>
        <small class="text-muted">Customer feedback — read only</small>
      </div>
      <div class="d-flex align-items-center gap-3">
        <span class="text-muted" style="font-size:.82rem">
          <i class="bi bi-bar-chart-fill me-1"></i>
          @php
            $total = $reviews->total();
            $avg   = $total > 0 ? round(\App\Models\Review::avg('rating'), 1) : '—';
          @endphp
          {{ $total }} review{{ $total !== 1 ? 's' : '' }} · Avg {{ $avg }} / 5
        </span>
      </div>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.875rem">
          <thead class="table-light">
            <tr>
              <th class="ps-4">Customer</th>
              <th>Package</th>
              <th>Rating</th>
              <th>Comment</th>
              <th>Submitted</th>
              <th class="text-end pe-4"></th>
            </tr>
          </thead>
          <tbody>
            @forelse($reviews as $review)
              <tr>
                <td class="ps-4">
                  <div class="fw-semibold">{{ $review->user->name ?? '—' }}</div>
                </td>
                <td>
                  <span class="text-muted" style="font-size:.82rem">{{ Str::limit($review->package->title ?? '—', 32) }}</span>
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
                  <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2"
                          title="View full review"
                          data-bs-toggle="modal"
                          data-bs-target="#reviewModal"
                          data-review-user-name="{{ $review->user->name ?? 'Anonymous' }}"
                          data-review-user-initials="{{ strtoupper(substr($review->user->name ?? 'A', 0, 2)) }}"
                          data-review-rating="{{ $review->rating }}"
                          data-review-comment="{{ $review->comment ?? 'No comment provided.' }}"
                          data-review-package="{{ $review->package->title ?? '—' }}"
                          data-review-date="{{ $review->created_at->format('F d, Y \a\t g:i A') }}">
                    <i class="bi bi-eye"></i>
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-5">
                  <i class="bi bi-chat-square-text fs-3 d-block mb-2"></i> No reviews found.
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

{{-- ── REVIEW DETAIL MODAL (Floating Island) ── --}}
<div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm" style="max-width:440px">
    <div class="modal-content border-0 shadow-lg" style="border-radius:16px;overflow:hidden">

      {{-- Header with user info --}}
      <div class="modal-header border-0 pb-0" style="background:linear-gradient(135deg,#0f172a 0%,#1e293b 100%)">
        <div class="d-flex align-items-center gap-3 w-100 py-2">
          <div id="modalUserAvatar"
               class="d-flex align-items-center justify-content-center rounded-circle text-white fw-bold flex-shrink-0"
               style="width:48px;height:48px;font-size:1rem;background:#0f766e">
          </div>
          <div class="min-width-0">
            <div id="modalUserName" class="fw-bold text-white" style="font-size:.95rem"></div>
            <div id="modalUserPackage" class="text-white-50" style="font-size:.75rem"></div>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      {{-- Body with stars and comment --}}
      <div class="modal-body pt-3">
        {{-- Stars --}}
        <div class="d-flex align-items-center gap-1 mb-3" id="modalStars"></div>

        {{-- Comment --}}
        <div class="bg-light rounded-3 p-3" style="font-size:.875rem;line-height:1.7">
          <p id="modalComment" class="mb-0 text-dark" style="white-space:pre-wrap"></p>
        </div>

        {{-- Date --}}
        <div class="mt-3 d-flex align-items-center gap-2 text-muted" style="font-size:.75rem">
          <i class="bi bi-clock"></i>
          <span id="modalDate"></span>
        </div>
      </div>

      {{-- Footer --}}
      <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-sm px-4" style="background:#0f172a;color:#fff;border-radius:8px" data-bs-dismiss="modal">
          <i class="bi bi-x-lg me-1" style="font-size:.65rem"></i> Close
        </button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const modal = document.getElementById('reviewModal');

  modal.addEventListener('show.bs.modal', function (event) {
    const btn = event.relatedTarget;

    // User info
    const userName   = btn.getAttribute('data-review-user-name');
    const initials   = btn.getAttribute('data-review-user-initials');
    const packageName = btn.getAttribute('data-review-package');

    document.getElementById('modalUserName').textContent      = userName;
    document.getElementById('modalUserAvatar').textContent     = initials;
    document.getElementById('modalUserPackage').textContent    = packageName;

    // Rating stars
    const rating = parseInt(btn.getAttribute('data-review-rating'), 10);
    const starsContainer = document.getElementById('modalStars');
    starsContainer.innerHTML = '';
    for (let s = 1; s <= 5; s++) {
      const star = document.createElement('i');
      star.className = s <= rating ? 'bi bi-star-fill' : 'bi bi-star';
      star.style.cssText = 'font-size:.9rem;color:' + (s <= rating ? '#F59E0B' : '#CBD5E1');
      starsContainer.appendChild(star);
    }
    // Rating number badge
    const badge = document.createElement('span');
    badge.className = 'ms-2 badge rounded-pill';
    badge.style.cssText = 'background:#F59E0B;color:#fff;font-size:.7rem';
    badge.textContent = rating + ' / 5';
    starsContainer.appendChild(badge);

    // Comment
    document.getElementById('modalComment').textContent = btn.getAttribute('data-review-comment');

    // Date
    document.getElementById('modalDate').textContent = 'Submitted ' + btn.getAttribute('data-review-date');
  });
});
</script>
@endpush

</x-dashboard-layout>
