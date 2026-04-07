@extends('admin.layouts.app')

@section('title', 'Import Users')

@section('content')

<div class="row justify-content-center">
<div class="col-md-8">

    {{-- Instructions Card --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">📤 Import Users via CSV</h5>
        </div>
        <div class="card-body">

            {{-- How it works --}}
            <div class="alert alert-info">
                <strong>📋 How it works:</strong>
                <ol class="mb-0 mt-2">
                    <li>Upload a CSV file with user data</li>
                    <li>System validates each row</li>
                    <li>Preview shows ✅ valid and ❌ invalid rows</li>
                    <li>Click Confirm to import only valid rows</li>
                </ol>
            </div>

            {{-- Required columns --}}
            <div class="alert alert-warning">
                <strong>⚠️ Required CSV Columns:</strong>
                <code>name, email, password</code>
                <br>
                <small>Column headers must be lowercase exactly as shown.</small>
            </div>

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

            {{-- Upload Form --}}
            <form method="POST"
                  action="{{ route('admin.import.preview') }}"
                  enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-bold">
                        Select CSV File *
                    </label>
                    <input
                        type="file"
                        name="csv_file"
                        class="form-control @error('csv_file') is-invalid @enderror"
                        accept=".csv,.txt"
                    >
                    @error('csv_file')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    <small class="text-muted">
                        Max size: 2MB. Format: .csv only
                    </small>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        📊 Upload & Preview
                    </button>
                    <a href="{{ route('admin.import.sample') }}"
                       class="btn btn-outline-secondary">
                        ⬇️ Download Sample CSV
                    </a>
                </div>

            </form>
        </div>
    </div>

    {{-- CSV Format Example --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light">
            <h6 class="mb-0">📄 CSV Format Example</h6>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>name</th>
                        <th>email</th>
                        <th>password</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>John Doe</td>
                        <td>john@example.com</td>
                        <td>password123</td>
                    </tr>
                    <tr>
                        <td>Jane Smith</td>
                        <td>jane@example.com</td>
                        <td>mypassword</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>

@endsection