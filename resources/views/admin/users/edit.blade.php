<x-dashboard-layout title="Edit User">
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PATCH')
        <div>
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
        </div>
        <div>
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>
        <div>
            <label>New Password</span></label>
            <input type="password" name="password" autocomplete="new-password">
        </div>
        <div>
            <label>Confirm Password</label>
            <input type="password" name="password_confirmation" autocomplete="new-password">
        </div>
        <button type="submit">Update</button>
    </form>
</x-dashboard-layout>