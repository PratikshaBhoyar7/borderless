@extends('layouts.admin')

@section('page-title', 'Add Known Condition')

@section('admin-content')

<div style="margin-bottom: 25px;">
    <h2 style="margin: 0; color: #2e59a7;">
        <i class="bi bi-plus-circle"></i> Add Known Condition
    </h2>
</div>

<div class="card">
    <div class="card-body">

        <form method="POST" action="{{ route('admin.known-conditions.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">
                    Condition Title <span class="text-danger">*</span>
                </label>

                <input type="text"
                       name="title"
                       class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title') }}"
                       placeholder="Enter condition title"
                       required>

                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i> Save
            </button>

            <a href="{{ route('admin.known-conditions.index') }}"
               class="btn btn-secondary">
                Cancel
            </a>

        </form>

    </div>
</div>

@endsection