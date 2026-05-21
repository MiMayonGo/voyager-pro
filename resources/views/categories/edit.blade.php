<x-dashboard-layout title="Edit Category">

  <div class="card border-0 shadow-sm" style="max-width:36rem">
    <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
      <div>
        <h5 class="fw-bold mb-0"><i class="bi bi-tag me-2" style="color:#0F766E"></i>Edit Category</h5>
        <small class="text-muted">Update category details</small>
      </div>
      <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Back
      </a>
    </div>
    <div class="card-body p-4">
      <form method="POST" action="{{ route('categories.update', $category) }}">
        @csrf @method('PUT')

        <div class="mb-3">
          <label for="name" class="form-label fw-semibold">Name</label>
          <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                 class="form-control @error('name') is-invalid @enderror"
                 placeholder="e.g. Adventure, Cultural, Beach">
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="d-flex gap-2 pt-2">
          <button type="submit" class="btn text-white" style="background:#0F766E">
            <i class="bi bi-check-lg me-1"></i> Update Category
          </button>
          <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>

</x-dashboard-layout>
