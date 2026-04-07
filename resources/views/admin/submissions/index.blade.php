@extends('admin.layouts.app')

@section('title', 'Submissions')

@section('content')

{{-- Filter by Form --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.submissions.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Filter by Form</label>
                    <select name="form_id" class="form-select">
                        <option value="">-- All Forms --</option>
                        @foreach($forms as $form)
                            <option value="{{ $form->id }}"
                                {{ request('form_id') == $form->id ? 'selected' : '' }}>
                                {{ $form->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary">
                        🔍 Filter
                    </button>
                    @if(request('form_id'))
                        <a href="{{ route('admin.submissions.index') }}"
                           class="btn btn-secondary">
                            ✕ Clear
                        </a>
                    @endif
                </div>
                <div class="col text-end">
                    <span class="text-muted">
                        Total: <strong>{{ $submissions->total() }}</strong>
                    </span>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Submissions Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Form</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Submitted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($submissions as $submission)
                <tr>
                    <td>{{ $loop->iteration + ($submissions->currentPage() - 1) * 10 }}</td>
                    <td>
                        <span class="badge bg-primary">
                            {{ $submission->form->title ?? 'N/A' }}
                        </span>
                    </td>
                    <td>{{ $submission->data['Name'] ?? '—' }}</td>
                    <td>{{ $submission->data['Email'] ?? '—' }}</td>
                    <td>{{ $submission->created_at->format('d M Y, h:i A') }}</td>
                    <td>
                        <a href="{{ route('admin.submissions.show', $submission) }}"
                           class="btn btn-sm btn-info text-white">
                            👁 View
                        </a>
                        <form method="POST"
                              action="{{ route('admin.submissions.destroy', $submission) }}"
                              style="display:inline"
                              onsubmit="return confirm('Delete this submission?')">
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
                        No submissions found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($submissions->hasPages())
<div class="mt-4 d-flex justify-content-center">
    {{ $submissions->appends(request()->query())->links() }}
</div>
@endif

@endsection