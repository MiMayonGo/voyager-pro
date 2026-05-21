<x-dashboard-layout title="Manage Users">

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
      <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
      <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
      <i class="bi bi-exclamation-triangle-fill"></i> {{ $errors->first() }}
      <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex align-items-center justify-content-between py-3">
      <div>
        <h5 class="fw-bold mb-0"><i class="bi bi-people-fill me-2" style="color:#0F766E"></i>Manage Users</h5>
        <small class="text-muted">All registered accounts</small>
      </div>
      <a href="{{ route('admin.users.create') }}" class="btn btn-sm text-white" style="background:#0F766E">
        <i class="bi bi-person-plus me-1"></i> Add User
      </a>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size:.875rem">
          <thead class="table-light">
            <tr>
              <th class="ps-4">User</th>
              <th>Role</th>
              <th>Status</th>
              <th>Joined</th>
              <th class="text-end pe-4">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($users as $user)
              <tr>
                <td class="ps-4">
                  <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white flex-shrink-0"
                         style="width:36px;height:36px;font-size:.75rem;background:#0F766E">
                      {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div>
                      <div class="fw-semibold">{{ $user->name }}</div>
                      <div class="text-muted" style="font-size:.75rem">{{ $user->email }}</div>
                    </div>
                  </div>
                </td>
                <td>
                  @php
                    $role = $user->getRoleNames()->first() ?? 'no role';
                    $roleColor = match($role) {
                      'super_admin'  => 'danger',
                      'tour_manager' => 'warning',
                      default        => 'secondary',
                    };
                  @endphp
                  <span class="badge text-bg-{{ $roleColor }}">{{ ucfirst(str_replace('_',' ',$role)) }}</span>
                </td>
                <td>
                  @if($user->is_active)
                    <span class="badge text-bg-success"><i class="bi bi-circle-fill me-1" style="font-size:.5rem"></i>Active</span>
                  @else
                    <span class="badge text-bg-danger"><i class="bi bi-circle-fill me-1" style="font-size:.5rem"></i>Inactive</span>
                  @endif
                </td>
                <td class="text-muted" style="font-size:.8rem">{{ $user->created_at->format('M d, Y') }}</td>
                <td class="text-end pe-4">
                  @if($user->id !== auth()->id())
                    <div class="d-flex align-items-center justify-content-end gap-2">
                      <a href="{{ route('admin.users.edit', $user) }}"
                         class="btn btn-sm btn-outline-secondary py-1 px-2">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                        @csrf @method('PATCH')
                        <button class="btn btn-sm py-1 px-2 {{ $user->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                          <i class="bi {{ $user->is_active ? 'bi-person-dash' : 'bi-person-check' }}"></i>
                        </button>
                      </form>
                    </div>
                  @else
                    <span class="text-muted" style="font-size:.75rem">You</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center text-muted py-5">
                  <i class="bi bi-people fs-3 d-block mb-2"></i> No users found.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    @if($users->hasPages())
      <div class="card-footer bg-white border-0 pt-0">
        {{ $users->links() }}
      </div>
    @endif
  </div>

</x-dashboard-layout>