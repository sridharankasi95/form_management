@extends('admin.layouts.app')

@section('title', 'Form Details')

@section('content')

<a href="{{ route('admin.forms.index') }}" class="btn btn-secondary mb-3">
    ← Back
</a>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-dark text-white d-flex justify-content-between">
        <h5 class="mb-0">📋 {{ $form->title }}</h5>
        <span class="badge bg-{{ $form->status === 'active' ? 'success' : 'secondary' }} fs-6">
            {{ ucfirst($form->status) }}
        </span>
    </div>
    <div class="card-body">

        <h6 class="fw-bold mb-3">Fields ({{ $form->fields->count() }} total)</h6>

        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Order</th>
                    <th>Label</th>
                    <th>Type</th>
                    <th>Required</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
                @foreach($form->fields as $field)
                <tr>
                    <td>{{ $field->order }}</td>
                    <td>{{ $field->label }}</td>
                    <td>
                        <span class="badge bg-info text-dark">
                            {{ ucfirst($field->type) }}
                        </span>
                    </td>
                    <td>
                        @if($field->required)
                            <span class="badge bg-danger">Yes</span>
                        @else
                            <span class="badge bg-secondary">No</span>
                        @endif
                    </td>
                    <td>
                        @if($field->options)
                            {{ implode(', ', $field->options) }}
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
    <div class="card-footer bg-white">
        <a href="{{ route('admin.forms.edit', $form) }}"
           class="btn btn-warning">
            ✏️ Edit Form
        </a>
    </div>
</div>

@endsection