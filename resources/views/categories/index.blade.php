<x-dashboard-layout title="Categories">

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
      <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
      <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
      <div>
        <h5 class="fw-bold mb-0"><i class="bi bi-tags-fill me-2" style="color:#0F766E"></i>Categories</h5>
        <small class="text-muted">Tour package categories</small>
      </div>
      <a href="{{ route('categories.create') }}" class="btn btn-sm text-white"
         style="background:#0F766E">
        <i class="bi bi-plus-lg me-1"></i> New Category
      </a>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.875rem">
          <thead class="table-light">
            <tr>
              <th class="ps-4">Name</th>
              <th>Slug</th>
              <th>Packages</th>
              <th class="text-end pe-4">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($categories as $category)
              <tr>
                <td class="ps-4 fw-semibold">{{ $category->name }}</td>
                <td>
                  <code class="text-muted" style="font-size:.78rem;background:#F8FAFC;padding:.15rem .4rem;border-radius:4px">
                    {{ $category->slug }}
                  </code>
                </td>
                <td>
                  <span class="badge rounded-pill text-bg-light border" style="font-size:.78rem">
                    {{ $category->packages_count }} {{ Str::plural('package', $category->packages_count) }}
                  </span>
                </td>
                <td class="text-end pe-4">
                  <div class="d-flex align-items-center justify-content-end gap-2">
                    <a href="{{ route('categories.edit', $category) }}"
                       class="btn btn-sm btn-outline-secondary py-1 px-2">
                      <i class="bi bi-pencil"></i>
                    </a>
                    <form method="POST" action="{{ route('categories.destroy', $category) }}"
                          onsubmit="return confirm('Delete this category?')">
                      @csrf @method('DELETE')
                      <button class="btn btn-sm btn-outline-danger py-1 px-2">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center text-muted py-5">
                  <i class="bi bi-tags fs-3 d-block mb-2"></i> No categories yet.
                  <a href="{{ route('categories.create') }}" class="d-block mt-1" style="font-size:.85rem">Create one →</a>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    @if($categories->hasPages())
      <div class="card-footer bg-white border-0">{{ $categories->links() }}</div>
    @endif
  </div>

</x-dashboard-layout>