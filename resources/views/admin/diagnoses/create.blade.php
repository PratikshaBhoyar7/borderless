@extends('layouts.admin')

@section('page-title', 'Add Diagnosis')

@section('admin-content')

<!-- Header -->
<div style="margin-bottom: 25px;">
    <h2 style="margin: 0; color: #2e59a7;">
        <i class="bi bi-clipboard-pulse"></i> Add Diagnosis
    </h2>
</div>

<div class="table-container">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.diagnoses.store') }}" id="diagnosisForm">
                @csrf

                <!-- Diagnosis Information Section -->
                <h5 class="mb-3" style="color: #2e59a7; border-bottom: 2px solid #e3e6f0; padding-bottom: 10px;">
                    <i class="bi bi-clipboard2-pulse"></i> Diagnosis Details
                </h5>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="title" class="form-label">
                            Diagnosis Title <span style="color: red;">*</span>
                        </label>

                        <input type="text"
                               class="form-control @error('title') is-invalid @enderror"
                               id="title"
                               name="title"
                               value="{{ old('title') }}"
                               placeholder="Enter diagnosis title"
                               required>

                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Create Diagnosis
                    </button>

                    <a href="{{ route('admin.diagnoses.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection