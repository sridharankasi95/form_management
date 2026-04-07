<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $form->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <style>
        body {
            background: #f0f2f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 0;
        }
        .form-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 40px;
            width: 100%;
            max-width: 600px;
        }
        .form-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #2c3e50;
        }
    </style>
</head>
<body>

<div class="form-card">

    {{-- Title --}}
    <div class="form-title">📋 {{ $form->title }}</div>
    <p class="text-muted mb-4">Please fill in all required fields.</p>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix these errors:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ route('forms.submit', $form->id) }}">
        @csrf

        @foreach($form->fields as $field)

            @php
                // "Full Name" → "full_name"
                $key = strtolower(str_replace(' ', '_', $field->label));
            @endphp

            <div class="mb-3">
                <label class="form-label fw-semibold">
                    {{ $field->label }}
                    @if($field->required)
                        <span class="text-danger">*</span>
                    @endif
                </label>

                {{-- TEXT --}}
                @if($field->type === 'text')
                    <input
                        type="text"
                        name="{{ $key }}"
                        class="form-control @error($key) is-invalid @enderror"
                        value="{{ old($key) }}"
                        {{ $field->required ? 'required' : '' }}
                    >

                {{-- EMAIL --}}
                @elseif($field->type === 'email')
                    <input
                        type="email"
                        name="{{ $key }}"
                        class="form-control @error($key) is-invalid @enderror"
                        value="{{ old($key) }}"
                        {{ $field->required ? 'required' : '' }}
                    >

                {{-- NUMBER --}}
                @elseif($field->type === 'number')
                    <input
                        type="number"
                        name="{{ $key }}"
                        class="form-control @error($key) is-invalid @enderror"
                        value="{{ old($key) }}"
                        {{ $field->required ? 'required' : '' }}
                    >

                {{-- DATE --}}
                @elseif($field->type === 'date')
                    <input
                        type="date"
                        name="{{ $key }}"
                        class="form-control @error($key) is-invalid @enderror"
                        value="{{ old($key) }}"
                        {{ $field->required ? 'required' : '' }}
                    >

                {{-- DROPDOWN --}}
                @elseif($field->type === 'dropdown')
                    <select
                        name="{{ $key }}"
                        class="form-select @error($key) is-invalid @enderror"
                        {{ $field->required ? 'required' : '' }}
                    >
                        <option value="">-- Select --</option>
                        @if($field->options)
                            @foreach($field->options as $option)
                                <option value="{{ $option }}"
                                    {{ old($key) === $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        @endif
                    </select>

                {{-- CHECKBOX --}}
                @elseif($field->type === 'checkbox')
                    <div class="form-check">
                        <input
                            type="checkbox"
                            name="{{ $key }}"
                            class="form-check-input @error($key) is-invalid @enderror"
                            value="Yes"
                            {{ old($key) ? 'checked' : '' }}
                        >
                        <label class="form-check-label">
                            Yes
                        </label>
                    </div>

                @endif

                {{-- Inline Error --}}
                @error($key)
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror
            </div>

        @endforeach

        <button type="submit" class="btn btn-primary w-100 mt-2">
            🚀 Submit Form
        </button>

    </form>
</div>

</body>
</html>