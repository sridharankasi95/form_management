@extends('admin.layouts.app')

@section('title', 'Forms Management')

@section('content')

<div class="row mb-4 align-items-center">
    <div class="col">
        <h6 class="text-muted mb-0">
            Total Forms: <strong>{{ $forms->total() }}</strong>
        </h6>
    </div>
    <div class="col text-end">
        <a href="{{ route('admin.forms.create') }}"
           class="btn btn-primary">
            ➕ Create New Form
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Fields</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($forms as $form)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td><strong>{{ $form->title }}</strong></td>
                    <td>
                        <span class="badge bg-info text-dark">
                            {{ $form->fields_count }} fields
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-{{ $form->status === 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($form->status) }}
                        </span>
                    </td>
                    <td>{{ $form->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.forms.show', $form) }}"
                           class="btn btn-sm btn-info text-white">
                            👁 View
                        </a>
                        <a href="{{ route('admin.forms.edit', $form) }}"
                           class="btn btn-sm btn-warning">
                            ✏️ Edit
                        </a>
                        <form method="POST"
                              action="{{ route('admin.forms.destroy', $form) }}"
                              style="display:inline"
                              onsubmit="return confirm('Delete this form?')">
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
                        No forms yet.
                        <a href="{{ route('admin.forms.create') }}">Create one!</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($forms->hasPages())
<div class="mt-4 d-flex justify-content-center">
    {{ $forms->links() }}
</div>
@endif

@endsection