@extends('layouts.admin')

@section('page-title', 'Edit Country')

@section('admin-content')

<!-- Header -->
<div style="margin-bottom: 25px;">
    <h2 style="margin: 0; color: #2e59a7;">
        <i class="bi bi-globe"></i> Edit Country
    </h2>
</div>

<div class="table-container">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.countries.update', $country) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">Country Name <span style="color: red;">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $country->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="code" class="form-label">Country Code <span style="color: red;">*</span></label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $country->code) }}" maxlength="2" placeholder="e.g., IN" required>
                    <small class="form-text text-muted">2-character country code (e.g., IN for India)</small>
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="phone_code" class="form-label">Phone Code</label>
                    <input type="text" class="form-control @error('phone_code') is-invalid @enderror" id="phone_code" name="phone_code" value="{{ old('phone_code', $country->phone_code) }}" placeholder="e.g., +91">
                    @error('phone_code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $country->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Active
                        </label>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Update Country
                    </button>
                    <a href="{{ route('admin.countries.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
