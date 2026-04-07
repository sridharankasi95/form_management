@extends('admin.layouts.app')

@section('title', 'Edit Form')

@section('content')

<a href="{{ route('admin.forms.index') }}" class="btn btn-secondary mb-3">
    ← Back
</a>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0">✏️ Edit Form</h5>
    </div>
    <div class="card-body">

        <form method="POST" action="{{ route('admin.forms.update', $form) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-bold">Form Title *</label>
                <input type="text"
                       name="title"
                       class="form-control"
                       value="{{ $form->title }}">
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Status</label>
                <select name="status" class="form-select">
                    <option value="active"
                        {{ $form->status === 'active' ? 'selected' : '' }}>
                        Active
                    </option>
                    <option value="inactive"
                        {{ $form->status === 'inactive' ? 'selected' : '' }}>
                        Inactive
                    </option>
                </select>
            </div>

            {{-- Default fields (read only) --}}
            <div class="mb-4">
                <label class="form-label fw-bold">
                    Default Fields
                    <span class="badge bg-secondary">Auto Added</span>
                </label>
                <div class="p-3 bg-light rounded">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input class="form-control"
                                   value="Name (Text, Required)" disabled>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control"
                                   value="Email (Email, Required)" disabled>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control"
                                   value="Phone (Text, Optional)" disabled>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Existing dynamic fields --}}
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
                    @foreach($form->fields->where('order', '>=', 4) as $index => $field)
                    <div class="field-row card mb-2">
                        <div class="card-body py-2">
                            <div class="row g-2 align-items-center">
                                <div class="col-md-3">
                                    <input type="text"
                                           name="fields[{{ $index }}][label]"
                                           class="form-control"
                                           value="{{ $field->label }}">
                                </div>
                                <div class="col-md-2">
                                    <select name="fields[{{ $index }}][type]"
                                            class="form-select"
                                            onchange="toggleOptions(this)">
                                        @foreach(['text','number','email','date','dropdown','checkbox'] as $type)
                                        <option value="{{ $type }}"
                                            {{ $field->type === $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-check mt-2">
                                        <input type="checkbox"
                                               name="fields[{{ $index }}][required]"
                                               class="form-check-input"
                                               {{ $field->required ? 'checked' : '' }}>
                                        <label class="form-check-label">Required</label>
                                    </div>
                                </div>
                                <div class="col-md-3 options-box"
                                     style="{{ $field->type === 'dropdown' ? '' : 'display:none' }}">
                                    <input type="text"
                                           name="fields[{{ $index }}][options]"
                                           class="form-control"
                                           placeholder="opt1, opt2"
                                           value="{{ $field->options ? implode(', ', $field->options) : '' }}">
                                </div>
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
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn btn-primary px-4">
                💾 Update Form
            </button>
        </form>

    </div>
</div>

<template id="field-template">
    <div class="field-row card mb-2">
        <div class="card-body py-2">
            <div class="row g-2 align-items-center">
                <div class="col-md-3">
                    <input type="text"
                           name="fields[__INDEX__][label]"
                           class="form-control"
                           placeholder="Field Label">
                </div>
                <div class="col-md-2">
                    <select name="fields[__INDEX__][type]"
                            class="form-select"
                            onchange="toggleOptions(this)">
                        <option value="text">Text</option>
                        <option value="number">Number</option>
                        <option value="email">Email</option>
                        <option value="date">Date</option>
                        <option value="dropdown">Dropdown</option>
                        <option value="checkbox">Checkbox</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <div class="form-check mt-2">
                        <input type="checkbox"
                               name="fields[__INDEX__][required]"
                               class="form-check-input">
                        <label class="form-check-label">Required</label>
                    </div>
                </div>
                <div class="col-md-3 options-box" style="display:none">
                    <input type="text"
                           name="fields[__INDEX__][options]"
                           class="form-control"
                           placeholder="opt1, opt2, opt3">
                </div>
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
    let fieldIndex = 1000;

    function addField() {
        const template = document.getElementById('field-template');
        const container = document.getElementById('fields-container');
        const clone = template.content.cloneNode(true);
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
        optionsBox.style.display = select.value === 'dropdown' ? 'block' : 'none';
    }
</script>
@endpush

@endsection