@extends('layouts.admin')

@section('page-title', 'Edit Complaint')

@section('admin-content')

<!-- Header -->
<div style="margin-bottom: 25px;">
    <h2 style="margin: 0; color: #2e59a7;">
        <i class="bi bi-pencil-square"></i> Edit Complaint
    </h2>
</div>

<div class="table-container">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.complaints.update', $complaint->id) }}" id="complaintForm">
                @csrf
                @method('PUT')

                <!-- Complaint Information Section -->
                <h5 class="mb-3" style="color: #2e59a7; border-bottom: 2px solid #e3e6f0; padding-bottom: 10px;">
                    <i class="bi bi-chat-left-text"></i> Complaint Details
                </h5>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="complaint" class="form-label">
                            Complaint <span style="color: red;">*</span>
                        </label>

                        <textarea class="form-control @error('complaint') is-invalid @enderror"
                                  id="complaint"
                                  name="complaint"
                                  rows="5"
                                  placeholder="Enter complaint details"
                                  required>{{ old('complaint', $complaint->complaint) }}</textarea>

                        @error('complaint')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Update Complaint
                    </button>

                    <a href="{{ route('admin.complaints.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection