@extends('layouts.admin')

@section('page-title', 'Patient Details - ' . $patient->serial_number)

@section('admin-content')

<!-- Header -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
    <h2 style="margin: 0; color: #2e59a7;">
        <i class="bi bi-person-fill"></i> Patient Details
    </h2>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('admin.patients.edit', $patient) }}" class="btn btn-primary">
            <i class="bi bi-pencil-fill"></i> Edit
        </a>
        <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="table-container">
    <!-- Basic Information Card -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-person-vcard"></i> Basic Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label style="font-weight: 600; color: #666;">Serial Number</label>
                    <p style="font-size: 1.1em; color: #333;">{{ $patient->serial_number }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <label style="font-weight: 600; color: #666;">Patient Name</label>
                    <p style="font-size: 1.1em; color: #333;">{{ $patient->patient_name }}</p>
                </div>
                <div class="col-md-2 mb-3">
                    <label style="font-weight: 600; color: #666;">Age</label>
                    <p style="font-size: 1.1em; color: #333;">{{ $patient->age }} years</p>
                </div>
                <div class="col-md-2 mb-3">
                    <label style="font-weight: 600; color: #666;">Sex</label>
                    <p style="font-size: 1.1em; color: #333;">{{ $patient->sex }}</p>
                </div>
                <div class="col-md-2 mb-3">
                    <label style="font-weight: 600; color: #666;">Date</label>
                    <p style="font-size: 1.1em; color: #333;">{{ $patient->date->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Campaign Type Card -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-collection"></i> Campaign Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Campaign Type</label>
                    @if($patient->campaignType)
                        <p style="font-size: 1.1em; color: #333;">{{ $patient->campaignType->name }}</p>
                    @else
                        <p style="color: #999;"><em>No campaign assigned</em></p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Location Details Card -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-map"></i> Location Details</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Village</label>
                    <p>{{ $patient->village }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Taluka</label>
                    <p>{{ $patient->taluka?->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">District</label>
                    <p>{{ $patient->district?->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">State</label>
                    <p>{{ $patient->state?->name ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Country</label>
                    <p>{{ $patient->country?->name ?? 'N/A' }}</p>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Mobile Number</label>
                    <p>{{ $patient->mobile }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Aadhar Number</label>
                    <p>{{ $patient->aadhar ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Vital Signs Card -->
    <div class="card mb-4">
        <div class="card-header bg-warning text-white">
            <h5 class="mb-0"><i class="bi bi-heart-pulse"></i> Vital Signs</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label style="font-weight: 600; color: #666;">Height</label>
                    <p>{{ $patient->height ? $patient->height . ' cm' : 'N/A' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <label style="font-weight: 600; color: #666;">Weight</label>
                    <p>{{ $patient->weight ? $patient->weight . ' kg' : 'N/A' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <label style="font-weight: 600; color: #666;">Blood Pressure</label>
                    <p>{{ $patient->bp ?? 'N/A' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <label style="font-weight: 600; color: #666;">Hemoglobin</label>
                    <p>{{ $patient->hb ? $patient->hb . ' g/dL' : 'N/A' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <label style="font-weight: 600; color: #666;">RBS</label>
                    <p>{{ $patient->rbs ? $patient->rbs . ' mg/dL' : 'N/A' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <label style="font-weight: 600; color: #666;">BSL</label>
                    <p>{{ $patient->bsl ? $patient->bsl . ' mg/dL' : 'N/A' }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <label style="font-weight: 600; color: #666;">BMI</label>
                    <p>{{ $patient->bmi ? number_format($patient->bmi, 2) : 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Clinical Information Card -->
    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0"><i class="bi bi-stethoscope"></i> Clinical Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Chief Complaints</label>
                    <p>{{ $patient->complaints }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Known Conditions</label>
                    <p>{{ $patient->known_conditions ?? 'N/A' }}</p>
                </div>
                <div class="col-12 mb-3">
                    <label style="font-weight: 600; color: #666;">Diagnosis</label>
                    <p>{{ $patient->diagnosis }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Treatment Card -->
    <div class="card mb-4">
        <div class="card-header" style="background-color: #6f42c1; color: white;">
            <h5 class="mb-0"><i class="bi bi-pill"></i> Treatment</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Treatment</label>
                    <p>{{ $patient->treatment }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Dosage</label>
                    <p>{{ $patient->dosage }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lab Tests & Referral Card -->
    <div class="card mb-4">
        <div class="card-header" style="background-color: #20c997; color: white;">
            <h5 class="mb-0"><i class="bi bi-flask"></i> Lab Tests & Referral</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Lab Tests</label>
                    @if($patient->lab_tests)
                        <p>
                            @foreach($patient->lab_tests as $test)
                                <span class="badge bg-info">{{ $test }}</span>
                            @endforeach
                        </p>
                    @else
                        <p>N/A</p>
                    @endif
                </div>
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Sample Collected</label>
                    <p>{{ $patient->sample_collected ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Referral Type</label>
                    <p>{{ $patient->referral_type ?? 'N/A' }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Referral Details</label>
                    <p>{{ $patient->referral_details ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes Card -->
    @if($patient->notes)
    <div class="card mb-4">
        <div class="card-header" style="background-color: #fd7e14; color: white;">
            <h5 class="mb-0"><i class="bi bi-sticky"></i> Additional Notes</h5>
        </div>
        <div class="card-body">
            <p>{{ $patient->notes }}</p>
        </div>
    </div>
    @endif

    <!-- Metadata Card -->
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="bi bi-info-circle"></i> Record Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Created By</label>
                    <p>{{ $patient->createdBy->name }} ({{ $patient->createdBy->email }})</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Created At</label>
                    <p>{{ $patient->created_at->format('M d, Y H:i:s') }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Last Updated</label>
                    <p>{{ $patient->updated_at->format('M d, Y H:i:s') }}</p>
                </div>
                @if($patient->deleted_at)
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Deleted At</label>
                    <p>{{ $patient->deleted_at->format('M d, Y H:i:s') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-4 d-flex gap-2">
        <a href="{{ route('admin.patients.edit', $patient) }}" class="btn btn-primary">
            <i class="bi bi-pencil-fill"></i> Edit Patient
        </a>
        <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>
</div>

@endsection
