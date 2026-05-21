<x-dashboard-layout title="New Package">

  <div class="d-flex align-items-center justify-content-between mb-4">
    <div>
      <h5 class="fw-bold mb-0"><i class="bi bi-plus-circle me-2" style="color:#0F766E"></i>New Package</h5>
      <small class="text-muted">Fill in the details to create a tour package</small>
    </div>
    <a href="{{ route('packages.index') }}" class="btn btn-sm btn-outline-secondary">
      <i class="bi bi-arrow-left me-1"></i> Back
    </a>
  </div>

  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-start gap-2 mb-4" role="alert">
      <i class="bi bi-exclamation-triangle-fill mt-1 flex-shrink-0"></i>
      <div>
        <div class="fw-semibold mb-1">Please fix the following errors:</div>
        <ul class="mb-0 ps-3" style="font-size:.85rem">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <form method="POST" action="{{ route('packages.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="row g-4">

      {{-- LEFT: Core details --}}
      <div class="col-12 col-xl-8">

        <div class="card border-0 shadow-sm mb-4" style="border-left:4px solid #0F766E !important">
          <div class="card-header bg-white border-0 pt-3 pb-0">
            <h6 class="fw-bold mb-0" style="color:#0F766E"><i class="bi bi-info-circle me-2"></i>Basic Information</h6>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label fw-semibold" style="font-size:.85rem">Title <span class="text-danger">*</span></label>
              <input type="text" name="title" required
                     class="form-control @error('title') is-invalid @enderror"
                     value="{{ old('title') }}"
                     placeholder="e.g. Palawan Island Hopping">
              @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div>
              <label class="form-label fw-semibold" style="font-size:.85rem">Description</label>
              <textarea name="description" rows="5"
                        class="form-control @error('description') is-invalid @enderror"
                        placeholder="Describe the tour experience, highlights, inclusions...">{{ old('description') }}</textarea>
              @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>
        </div>

        <div class="card border-0 shadow-sm mb-4" style="border-left:4px solid #1D4ED8 !important">
          <div class="card-header bg-white border-0 pt-3 pb-0">
            <h6 class="fw-bold mb-0" style="color:#1D4ED8"><i class="bi bi-sliders me-2"></i>Pricing & Logistics</h6>
          </div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-sm-6">
                <label class="form-label fw-semibold" style="font-size:.85rem">Price (₱) <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0">₱</span>
                  <input type="number" name="price" required min="0" step="0.01"
                         class="form-control border-start-0 @error('price') is-invalid @enderror"
                         value="{{ old('price') }}" placeholder="0.00">
                  @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>
              <div class="col-sm-6">
                <label class="form-label fw-semibold" style="font-size:.85rem">Duration (days) <span class="text-danger">*</span></label>
                <div class="input-group">
                  <input type="number" name="duration_days" required min="1"
                         class="form-control @error('duration_days') is-invalid @enderror"
                         value="{{ old('duration_days') }}" placeholder="1">
                  <span class="input-group-text bg-light">days</span>
                  @error('duration_days')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>
              <div class="col-sm-6">
                <label class="form-label fw-semibold" style="font-size:.85rem">Available Slots <span class="text-danger">*</span></label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-end-0"><i class="bi bi-people"></i></span>
                  <input type="number" name="available_slots" required min="0"
                         class="form-control border-start-0 @error('available_slots') is-invalid @enderror"
                         value="{{ old('available_slots', 0) }}">
                  @error('available_slots')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>
              <div class="col-sm-6">
                <label class="form-label fw-semibold" style="font-size:.85rem">Status</label>
                <select name="status" class="form-select @error('status') is-invalid @enderror">
                  <option value="draft"     {{ old('status') === 'draft'    ? 'selected' : '' }}>Draft</option>
                  <option value="active"    {{ old('status') === 'active'   ? 'selected' : '' }}>Active</option>
                  <option value="inactive"  {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>
          </div>
        </div>

        <div class="card border-0 shadow-sm" style="border-left:4px solid #D97706 !important">
          <div class="card-header bg-white border-0 pt-3 pb-0">
            <h6 class="fw-bold mb-0" style="color:#B45309"><i class="bi bi-tags me-2"></i>Categories</h6>
          </div>
          <div class="card-body">
            @if($categories->isEmpty())
              <div class="text-muted" style="font-size:.85rem">
                No categories yet. <a href="{{ route('categories.create') }}">Create one →</a>
              </div>
            @else
              <div class="d-flex flex-wrap gap-2">
                @foreach($categories as $category)
                  @php $checked = in_array($category->id, old('categories', [])); @endphp
                  <div>
                    <input type="checkbox" class="btn-check" name="categories[]"
                           value="{{ $category->id }}" id="cat_{{ $category->id }}"
                           {{ $checked ? 'checked' : '' }}>
                    <label class="btn btn-sm {{ $checked ? 'btn-success' : 'btn-outline-secondary' }}"
                           for="cat_{{ $category->id }}" style="font-size:.82rem">
                      <i class="bi bi-tag me-1"></i>{{ $category->name }}
                    </label>
                  </div>
                @endforeach
              </div>
            @endif
          </div>
        </div>

      </div>

      {{-- RIGHT: Cover image + submit --}}
      <div class="col-12 col-xl-4">

        <div class="card border-0 shadow-sm mb-4">
          <div class="card-header bg-white border-0 pt-3 pb-0">
            <h6 class="fw-bold mb-0"><i class="bi bi-image me-2 text-muted"></i>Cover Image</h6>
          </div>
          <div class="card-body">
            <div class="rounded-3 d-flex align-items-center justify-content-center mb-3"
                 style="height:160px;background:#F8FAFC;border:2px dashed #CBD5E1" id="imgPreviewWrap">
              <div class="text-center text-muted" id="imgPlaceholder">
                <i class="bi bi-cloud-upload fs-2 d-block mb-1"></i>
                <span style="font-size:.78rem">Preview will appear here</span>
              </div>
            </div>
            <input type="file" name="cover_image" accept="image/*"
                   class="form-control form-control-sm @error('cover_image') is-invalid @enderror"
                   id="coverImageInput">
            @error('cover_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>

        <div class="card border-0 shadow-sm">
          <div class="card-body d-flex flex-column gap-2">
            <button type="submit" class="btn fw-semibold text-white"
                    style="background:linear-gradient(135deg,#0F766E,#0D9488);border:none">
              <i class="bi bi-plus-lg me-1"></i> Create Package
            </button>
            <a href="{{ route('packages.index') }}" class="btn btn-outline-secondary">
              <i class="bi bi-x me-1"></i> Cancel
            </a>
          </div>
        </div>

      </div>
    </div>
  </form>

  @push('scripts')
  <script>
    document.getElementById('coverImageInput').addEventListener('change', function () {
      const file = this.files[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = e => {
        const wrap = document.getElementById('imgPreviewWrap');
        wrap.style.height = 'auto';
        wrap.style.border = 'none';
        wrap.style.padding = '0';
        wrap.innerHTML = `<img src="${e.target.result}" class="w-100 rounded-3" style="object-fit:cover;max-height:200px;display:block">`;
      };
      reader.readAsDataURL(file);
    });
  </script>
  @endpush

</x-dashboard-layout>