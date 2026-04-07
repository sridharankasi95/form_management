@extends('admin.layouts.app')

@section('title', 'Submission Detail')

@section('content')

<a href="{{ route('admin.submissions.index') }}"
   class="btn btn-secondary mb-3">
    ← Back
</a>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-dark text-white d-flex justify-content-between">
        <h5 class="mb-0">📥 Submission Detail</h5>
        <span class="badge bg-primary fs-6">
            {{ $submission->form->title ?? 'N/A' }}
        </span>
    </div>
    <div class="card-body">

        <p class="text-muted">
            Submitted on:
            {{ $submission->created_at->format('d M Y, h:i A') }}
        </p>

        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th width="200">Field</th>
                    <th>Answer</th>
                </tr>
            </thead>
            <tbody>
                @foreach($submission->data as $label => $value)
                <tr>
                    <td><strong>{{ $label }}</strong></td>
                    <td>{{ $value ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
    <div class="card-footer bg-white">
        <form method="POST"
              action="{{ route('admin.submissions.destroy', $submission) }}"
              onsubmit="return confirm('Delete this submission?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm">
                🗑 Delete Submission
            </button>
        </form>
    </div>
</div>

@endsection