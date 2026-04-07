@extends('admin.layouts.app')

@section('title', 'Export Submissions')

@section('content')

<div class="row justify-content-center">
<div class="col-md-8">

    {{-- Success / Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- Stats Row --}}
    <div class="row g-3 mb-4">

        {{-- Total Submissions --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-3">
                <div style="font-size:32px">📥</div>
                <h4 class="mt-1">{{ $totalSubmissions }}</h4>
                <p class="text-muted mb-0">Total Submissions</p>
            </div>
        </div>

        {{-- Total Forms --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-3">
                <div style="font-size:32px">📋</div>
                <h4 class="mt-1">{{ $forms->count() }}</h4>
                <p class="text-muted mb-0">Total Forms</p>
            </div>
        </div>

        {{-- Export Info --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-3">
                <div style="font-size:32px">💾</div>
                <h4 class="mt-1">CSV</h4>
                <p class="text-muted mb-0">Export Format</p>
            </div>
        </div>

    </div>

    {{-- Export Form Card --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">💾 Export Submissions to CSV</h5>
        </div>
        <div class="card-body">

            {{-- Info --}}
            <div class="alert alert-info mb-4">
                <strong>ℹ️ Export Info:</strong>
                <ul class="mb-0 mt-1">
                    <li>Select a specific form or export all submissions</li>
                    <li>CSV will include: #, Form Name, Submitted Date,
                        and all field values</li>
                    <li>File downloads automatically</li>
                </ul>
            </div>

            <form method="POST"
                  action="{{ route('admin.export.csv') }}">
                @csrf

                {{-- Form Filter --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">
                        Filter by Form
                        <span class="text-muted fw-normal">
                            (optional)
                        </span>
                    </label>
                    <select name="form_id" class="form-select">
                        <option value="">
                            -- Export All Forms --
                        </option>
                        @foreach($forms as $form)
                            <option value="{{ $form->id }}">
                                {{ $form->title }}
                                ({{ $form->submissions_count }}
                                submissions)
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">
                        Leave blank to export all submissions
                    </small>
                </div>

                {{-- Export Button --}}
                <button type="submit" class="btn btn-success btn-lg w-100">
                    ⬇️ Download CSV Export
                </button>

            </form>

        </div>
    </div>

    {{-- Per Form Summary --}}
    @if($forms->count() > 0)
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-light">
            <h6 class="mb-0">📊 Submissions per Form</h6>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Form Title</th>
                        <th>Status</th>
                        <th>Submissions</th>
                        <th>Quick Export</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($forms as $index => $form)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $form->title }}</strong></td>
                        <td>
                            <span class="badge bg-{{
                                $form->status === 'active'
                                ? 'success' : 'secondary'
                            }}">
                                {{ ucfirst($form->status) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-info text-dark">
                                {{ $form->submissions_count }}
                            </span>
                        </td>
                        <td>
                            {{-- Quick Export per form --}}
                            <form method="POST"
                                  action="{{ route('admin.export.csv') }}"
                                  style="display:inline">
                                @csrf
                                <input type="hidden"
                                       name="form_id"
                                       value="{{ $form->id }}">
                                <button
                                    type="submit"
                                    class="btn btn-sm btn-outline-success"
                                    {{ $form->submissions_count == 0
                                        ? 'disabled' : '' }}>
                                    ⬇️ Export
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
</div>

@endsection