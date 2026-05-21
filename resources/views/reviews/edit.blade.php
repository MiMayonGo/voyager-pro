<x-dashboard-layout title="Edit Review">

  <div class="row justify-content-center">
    <div class="col-lg-6">

      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
          <h5 class="fw-bold mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Your Review</h5>
          <small class="text-muted">{{ $review->package->title ?? 'Package' }}</small>
        </div>
        <div class="card-body">

          <form method="POST" action="{{ route('reviews.update', $review) }}">
            @csrf @method('PUT')

            {{-- Rating --}}
            <div class="mb-4">
              <label class="form-label fw-semibold">Rating</label>
              <div class="d-flex gap-2" id="starRating">
                @for($i = 1; $i <= 5; $i++)
                  <button type="button" class="btn btn-sm p-0 border-0 bg-transparent star-btn"
                          data-value="{{ $i }}"
                          style="font-size:1.6rem;line-height:1;color:{{ $i <= old('rating', $review->rating) ? '#F59E0B' : '#CBD5E1' }}">
                    <i class="bi bi-star-fill"></i>
                  </button>
                @endfor
              </div>
              <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating', $review->rating) }}">
              @error('rating') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            {{-- Comment --}}
            <div class="mb-4">
              <label for="comment" class="form-label fw-semibold">Comment <span class="text-muted">(optional)</span></label>
              <textarea name="comment" id="comment" rows="4"
                        class="form-control @error('comment') is-invalid @enderror"
                        placeholder="Share your experience…">{{ old('comment', $review->comment) }}</textarea>
              @error('comment') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="d-flex justify-content-between">
              <a href="{{ route('reviews.my') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back
              </a>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg me-1"></i> Update Review
              </button>
            </div>
          </form>

        </div>
      </div>

    </div>
  </div>

  @push('scripts')
  <script>
    document.querySelectorAll('.star-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const val = parseInt(this.dataset.value);
        document.getElementById('ratingInput').value = val;
        document.querySelectorAll('.star-btn').forEach((b, i) => {
          b.style.color = i < val ? '#F59E0B' : '#CBD5E1';
        });
      });
    });
  </script>
  @endpush

</x-dashboard-layout>
