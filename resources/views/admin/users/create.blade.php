<x-dashboard-layout title="Add User">

  <div class="card border-0 shadow-sm" style="max-width:600px">
    <div class="card-header bg-white border-0 pt-3 pb-0">
      <h5 class="fw-bold mb-0"><i class="bi bi-person-plus me-2" style="color:#0F766E"></i>Add New User</h5>
      <small class="text-muted">Create a new account with a specific role</small>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        {{-- Name --}}
        <div class="mb-3">
          <label for="name" class="form-label fw-semibold">Name</label>
          <input type="text" name="name" id="name"
                 class="form-control @error('name') is-invalid @enderror"
                 value="{{ old('name') }}" required>
          @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Email --}}
        <div class="mb-3">
          <label for="email" class="form-label fw-semibold">Email</label>
          <input type="email" name="email" id="email"
                 class="form-control @error('email') is-invalid @enderror"
                 value="{{ old('email') }}" required>
          @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Password --}}
        <div class="mb-3">
          <label for="password" class="form-label fw-semibold">Password</label>
          <input type="password" name="password" id="password"
                 class="form-control @error('password') is-invalid @enderror"
                 autocomplete="new-password" required>
          @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        {{-- Confirm Password --}}
        <div class="mb-3">
          <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
          <input type="password" name="password_confirmation" id="password_confirmation"
                 class="form-control" autocomplete="new-password" required>
        </div>

        {{-- Role --}}
        <div class="mb-4">
          <label for="role" class="form-label fw-semibold">Role</label>
          <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
            <option value="">— Select Role —</option>
            @foreach($roles as $role)
              <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                {{ ucfirst(str_replace('_', ' ', $role->name)) }}
              </option>
            @endforeach
          </select>
          @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="d-flex gap-2">
          <button type="submit" class="btn text-white px-4" style="background:#0F766E">
            <i class="bi bi-check-lg me-1"></i> Create User
          </button>
          <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4">
            Cancel
          </a>
        </div>
      </form>
    </div>
  </div>

</x-dashboard-layout>
