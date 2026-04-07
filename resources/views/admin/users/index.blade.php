@extends('admin.layouts.app')

@section('title', 'Users Management')

@section('content')

<!-- Stats + Search Row -->
<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h6 class="text-muted mb-0">
            Total Users: <strong>{{ $users->total() }}</strong>
        </h6>
    </div>
    <div class="col-md-6">
        <form method="GET" action="{{ route('admin.users.index') }}">
            <div class="input-group">
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Search by name or email..."
                    value="{{ request('search') }}"
                >
                <button class="btn btn-primary" type="submit">🔍 Search</button>
                @if(request('search'))
                    <a href="{{ route('admin.users.index') }}"
                       class="btn btn-secondary">✕ Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                    <td>
                        <strong>{{ $user->name }}</strong>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'success' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td>{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        <!-- View Button -->
                        <a href="{{ route('admin.users.show', $user) }}"
                           class="btn btn-sm btn-info text-white">
                            👁 View
                        </a>

                        <!-- Delete Button -->
                        <form method="POST"
                              action="{{ route('admin.users.destroy', $user) }}"
                              style="display:inline"
                              onsubmit="return confirm('Delete this user?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">
                                🗑 Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($users->hasPages())
<div class="mt-4 d-flex justify-content-center">
    {{ $users->appends(request()->query())->links() }}
</div>
@endif

@endsection