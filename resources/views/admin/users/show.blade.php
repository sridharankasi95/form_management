@extends('admin.layouts.app')

@section('title', 'User Detail')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-6">

        <!-- Back Button -->
        <a href="{{ route('admin.users.index') }}"
           class="btn btn-secondary mb-3">
            ← Back to Users
        </a>

        <!-- User Detail Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">👤 User Details</h5>
            </div>
            <div class="card-body">

                <table class="table table-borderless">
                    <tr>
                        <th width="150">Name</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td>
                            <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'success' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Registered</th>
                        <td>{{ $user->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                </table>

            </div>
            <div class="card-footer bg-white">
                <!-- Delete from detail page too -->
                <form method="POST"
                      action="{{ route('admin.users.destroy', $user) }}"
                      onsubmit="return confirm('Delete this user?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">
                        🗑 Delete User
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection