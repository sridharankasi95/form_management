@extends('admin.layouts.app')

@section('title', 'Create Form')

@section('content')

<a href="{{ route('admin.forms.index') }}" class="btn btn-secondary mb-3">
    ← Back
</a>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0">➕ Create New Form</h5>
    </div>
    <div class="card-body">

        <form method="POST" action="{{ route('admin.forms.store') }}">
            @csrf

            {{-- Form Title --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Form Title *</label>
                <input type="text"
                       name="title"
                       class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title') }}"
                       placeholder="Enter form title">
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Status --}}
            <div class="mb-4">
                <label class="form-label fw-bold">Status *</label>
                <select name="status" class="form-select">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            {{-- Default Fields (Read Only) --}}
            <div class="mb-4">
                <label class="form-label fw-bold">
                    Default Fields
                    <span class="badge bg-secondary">Auto Added</span>
                </label>
                <div class="p-3 bg-light rounded">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input type="text" class="form-control"
                                   value="Name (Text, Required)"
                                   disabled>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control"
                                   value="Email (Email, Required)"
                                   disabled>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control"
                                   value="Phone (Text, Optional)"
                                   disabled>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Dynamic Fields --}}
            <div class="mb-4">
                <label class="form-label fw-bold">
                    Dynamic Fields
                    <button type="button"
                            class="btn btn-sm btn-success ms-2"
                            onclick="addField()">
                        ➕ Add Field
                    </button>
                </label>

                <div id="fields-container">
                    {{-- JS will add rows here --}}
                </div>
            </div>

            <button type="submit" class="btn btn-primary px-4">
                💾 Save Form
            </button>
        </form>

    </div>
</div>

{{-- Field Row Template (hidden) --}}
<template id="field-template">
    <div class="field-row card mb-2">
        <div class="card-body py-2">
            <div class="row g-2 align-items-center">

                {{-- Label --}}
                <div class="col-md-3">
                    <input type="text"
                           name="fields[__INDEX__][label]"
                           class="form-control"
                           placeholder="Field Label">
                </div>

                {{-- Type --}}
                <div class="col-md-2">
                    <select name="fields[__INDEX__][type]"
                            class="form-select field-type-select"
                            onchange="toggleOptions(this)">
                        <option value="text">Text</option>
                        <option value="number">Number</option>
                        <option value="email">Email</option>
                        <option value="date">Date</option>
                        <option value="dropdown">Dropdown</option>
                        <option value="checkbox">Checkbox</option>
                    </select>
                </div>

                {{-- Required --}}
                <div class="col-md-2">
                    <div class="form-check mt-2">
                        <input type="checkbox"
                               name="fields[__INDEX__][required]"
                               class="form-check-input"
                               value="1">
                        <label class="form-check-label">Required</label>
                    </div>
                </div>

                {{-- Options (for dropdown) --}}
                <div class="col-md-3 options-box" style="display:none">
                    <input type="text"
                           name="fields[__INDEX__][options]"
                           class="form-control"
                           placeholder="opt1, opt2, opt3">
                </div>

                {{-- Remove Button --}}
                <div class="col-md-2">
                    <button type="button"
                            class="btn btn-sm btn-outline-danger"
                            onclick="removeField(this)">
                        ✕ Remove
                    </button>
                </div>

            </div>
        </div>
    </div>
</template>

@push('scripts')
<script>
    let fieldIndex = 0;

    function addField() {
        const template = document.getElementById('field-template');
        const container = document.getElementById('fields-container');

        // Clone template
        const clone = template.content.cloneNode(true);

        // Replace __INDEX__ with actual index
        clone.querySelectorAll('[name]').forEach(el => {
            el.name = el.name.replace('__INDEX__', fieldIndex);
        });

        container.appendChild(clone);
        fieldIndex++;
    }

    function removeField(btn) {
        btn.closest('.field-row').remove();
    }

    function toggleOptions(select) {
        const row = select.closest('.field-row');
        const optionsBox = row.querySelector('.options-box');

        if (select.value === 'dropdown') {
            optionsBox.style.display = 'block';
        } else {
            optionsBox.style.display = 'none';
        }
    }
</script>
@endpush

@endsection