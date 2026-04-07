@extends('admin.layouts.app')

@section('title', 'Import Preview')

@section('content')

{{-- Summary Bar --}}
<div class="row g-3 mb-4">

    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center p-3">
            <div style="font-size:32px">📊</div>
            <h4 class="mt-1">
                {{ count($validRows) + count($invalidRows) }}
            </h4>
            <p class="text-muted mb-0">Total Rows</p>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center p-3
                    border-success border-2">
            <div style="font-size:32px">✅</div>
            <h4 class="mt-1 text-success">
                {{ count($validRows) }}
            </h4>
            <p class="text-muted mb-0">Valid Rows</p>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center p-3
                    border-danger border-2">
            <div style="font-size:32px">❌</div>
            <h4 class="mt-1 text-danger">
                {{ count($invalidRows) }}
            </h4>
            <p class="text-muted mb-0">Invalid Rows</p>
        </div>
    </div>

</div>

{{-- Valid Rows Table --}}
@if(count($validRows) > 0)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-success text-white d-flex
                justify-content-between align-items-center">
        <h6 class="mb-0">
            ✅ Valid Rows ({{ count($validRows) }})
            — These will be imported
        </h6>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Row #</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($validRows as $item)
                <tr>
                    <td>{{ $item['row'] }}</td>
                    <td>{{ $item['data']['name'] }}</td>
                    <td>{{ $item['data']['email'] }}</td>
                    <td>
                        {{-- Hide password --}}
                        <span class="text-muted">
                            {{ str_repeat('*', strlen($item['data']['password'])) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-success">
                            ✅ Ready
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Invalid Rows Table --}}
@if(count($invalidRows) > 0)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-danger text-white">
        <h6 class="mb-0">
            ❌ Invalid Rows ({{ count($invalidRows) }})
            — These will be skipped
        </h6>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Row #</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Errors</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invalidRows as $item)
                <tr class="table-danger">
                    <td>{{ $item['row'] }}</td>
                    <td>{{ $item['data']['name'] ?? '—' }}</td>
                    <td>{{ $item['data']['email'] ?? '—' }}</td>
                    <td>
                        @foreach($item['errors'] as $error)
                            <span class="badge bg-danger mb-1 d-block
                                         text-start">
                                {{ $error }}
                            </span>
                        @endforeach
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Action Buttons --}}
<div class="d-flex gap-3">

    {{-- Confirm Import Button --}}
    @if(count($validRows) > 0)
        <form method="POST" action="{{ route('admin.import.confirm') }}">
            @csrf
            <button type="submit"
                    class="btn btn-success btn-lg"
                    onclick="return confirm(
                        'Import {{ count($validRows) }} users?'
                    )">
                ✅ Confirm Import
                ({{ count($validRows) }} users)
            </button>
        </form>
    @endif

    {{-- Upload Again Button --}}
    <a href="{{ route('admin.import.index') }}"
       class="btn btn-secondary btn-lg">
        ↩️ Upload Different File
    </a>

</div>

@endsection