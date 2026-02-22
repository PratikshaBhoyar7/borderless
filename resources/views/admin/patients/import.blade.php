@extends('layouts.admin')

@section('page-title', 'Import Patient Data')

@section('admin-content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 fw-bold">Import Patient Data</h1>
            <p class="text-muted">Bulk upload patient records using an Excel file</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('admin.patients.download-template') }}" class="btn btn-success">
                <i class="bi bi-download"></i> Download Sample Template
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5 class="alert-heading">Please fix the following errors:</h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($message = session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <h5 class="alert-heading">Import Successful!</h5>
            <p class="mb-2">{{ $message }}</p>
            <a href="{{ route('admin.patients.index') }}" class="btn btn-sm btn-primary">View All Patients</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

        @if (session('failureCount') > 0)
            <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                <h5 class="alert-heading">Failed Records ({{ session('failureCount') }})</h5>
                <div class="table-responsive">
                    <table class="table table-sm table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Patient Name</th>
                                <th>Date</th>
                                <th>Aadhar</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (session('duplicates', []) as $duplicate)
                                <tr>
                                    <td>{{ $duplicate['patient_name'] }}</td>
                                    <td>{{ $duplicate['date'] }}</td>
                                    <td>{{ $duplicate['aadhar'] }}</td>
                                    <td>
                                        <small class="text-danger">{{ $duplicate['reason'] }}</small>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    @endif

    @if ($message = session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($message = session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4">Upload Excel File</h5>

                    <form action="{{ route('admin.patients.import') }}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf

                        <div class="mb-4">
                            <label for="file" class="form-label fw-bold">Select Excel File</label>
                            <div class="input-group">
                                <input type="file"
                                       class="form-control @error('file') is-invalid @enderror"
                                       id="file"
                                       name="file"
                                       accept=".xlsx,.xls,.csv"
                                       required>
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-upload"></i> Upload & Import
                                </button>
                            </div>
                            <small class="form-text text-muted d-block mt-2">
                                Supported formats: Excel (.xlsx, .xls) or CSV
                                <br>Maximum file size: 10 MB
                            </small>
                            @error('file')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm mt-4">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3">Import Guidelines</h5>

                    <div class="accordion" id="guidelinesAccordion">
                        <div class="accordion-item">
                            <h6 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#guidelineFormat">
                                    <i class="bi bi-file-spreadsheet"></i> File Format
                                </button>
                            </h6>
                            <div id="guidelineFormat" class="accordion-collapse collapse show" data-bs-parent="#guidelinesAccordion">
                                <div class="accordion-body">
                                    <ul class="mb-0">
                                        <li>Download the sample template first to understand the required format</li>
                                        <li>Use Excel (.xlsx) or CSV format</li>
                                        <li>Ensure headers match the template exactly</li>
                                        <li>Date format must be: <strong>DD/MM/YYYY</strong> (e.g., 15/12/2025)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h6 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#guidelineRequired">
                                    <i class="bi bi-asterisk"></i> Required Fields
                                </button>
                            </h6>
                            <div id="guidelineRequired" class="accordion-collapse collapse" data-bs-parent="#guidelinesAccordion">
                                <div class="accordion-body">
                                    <p class="fw-bold mb-2">The following fields are mandatory:</p>
                                    <ul class="mb-0">
                                        <li><strong>Patient Name</strong> - Full name of patient</li>
                                        <li><strong>Age</strong> - Between 0-150</li>
                                        <li><strong>Sex</strong> - Male, Female, or Other</li>
                                        <li><strong>Date</strong> - In DD/MM/YYYY format</li>
                                        <li><strong>Taluka ID</strong> - Must exist in system</li>
                                        <li><strong>Mobile</strong> - 10 digit number</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h6 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#guidetlineDuplicates">
                                    <i class="bi bi-exclamation-triangle"></i> Duplicate Detection
                                </button>
                            </h6>
                            <div id="guidetlineDuplicates" class="accordion-collapse collapse" data-bs-parent="#guidelinesAccordion">
                                <div class="accordion-body">
                                    <p class="mb-2">Records are considered <strong>duplicates</strong> if they have:</p>
                                    <ul class="mb-3">
                                        <li><strong>Same Patient Name</strong> AND</li>
                                        <li><strong>Same Date</strong> AND</li>
                                        <li><strong>Same Aadhar</strong> (if provided)</li>
                                    </ul>
                                    <p class="text-warning mb-0">
                                        <i class="bi bi-info-circle"></i>
                                        Duplicate records will NOT be imported. You'll see a list of skipped records after import.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h6 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#guidelineValidation">
                                    <i class="bi bi-check-circle"></i> Validation Rules
                                </button>
                            </h6>
                            <div id="guidelineValidation" class="accordion-collapse collapse" data-bs-parent="#guidelinesAccordion">
                                <div class="accordion-body">
                                    <table class="table table-sm table-striped">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Field</th>
                                                <th>Rule</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Mobile</td>
                                                <td>Exactly 10 digits (0-9)</td>
                                            </tr>
                                            <tr>
                                                <td>Aadhar</td>
                                                <td>Exactly 12 digits (optional)</td>
                                            </tr>
                                            <tr>
                                                <td>Age</td>
                                                <td>0 to 150</td>
                                            </tr>
                                            <tr>
                                                <td>Height/Weight</td>
                                                <td>Numeric values</td>
                                            </tr>
                                            <tr>
                                                <td>Lab Tests</td>
                                                <td>Comma-separated values (e.g., "Blood Test, Urine Test")</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h6 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#guidelineReferenceIds">
                                    <i class="bi bi-key"></i> Reference IDs
                                </button>
                            </h6>
                            <div id="guidelineReferenceIds" class="accordion-collapse collapse" data-bs-parent="#guidelinesAccordion">
                                <div class="accordion-body">
                                    <p class="mb-3">You need valid Taluka IDs from the system:</p>
                                    <p class="fw-bold mb-2">Find Taluka IDs:</p>
                                    <p>Go to <strong>Location Management → Talukas</strong> to find the IDs for your locations</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm bg-light">
                <div class="card-body p-4">
                    <h6 class="card-title fw-bold mb-3">
                        <i class="bi bi-lightbulb"></i> Quick Tips
                    </h6>
                    <ul class="small">
                        <li class="mb-2">
                            <strong>Always download the template first</strong> - It shows you the exact format needed
                        </li>
                        <li class="mb-2">
                            <strong>Use DD/MM/YYYY date format</strong> - Example: 25/12/2025 for December 25, 2025
                        </li>
                        <li class="mb-2">
                            <strong>Check Taluka ID</strong> - Must exist in your system
                        </li>
                        <li class="mb-2">
                            <strong>Mobile must be 10 digits</strong> - No spaces or special characters
                        </li>
                        <li class="mb-2">
                            <strong>Aadhar must be 12 digits</strong> - Optional but if provided, must be valid
                        </li>
                        <li class="mb-2">
                            <strong>Lab tests as comma-separated</strong> - Example: "Blood Test, Urine Test, ECG"
                        </li>
                        <li>
                            <strong>Duplicates are skipped</strong> - Same name + date + aadhar = duplicate
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm mt-4">
                <div class="card-body p-4">
                    <h6 class="card-title fw-bold mb-3">
                        <i class="bi bi-info-circle"></i> Help
                    </h6>
                    <p class="small mb-3">Need help with the import process?</p>
                    <a href="{{ route('admin.patients.index') }}" class="btn btn-sm btn-outline-primary w-100">
                        <i class="bi bi-list"></i> View Patient List
                    </a>
                    <a href="{{ route('admin.patients.create') }}" class="btn btn-sm btn-outline-secondary w-100 mt-2">
                        <i class="bi bi-plus-circle"></i> Add Single Patient
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .accordion-button {
        font-weight: 500;
        border-bottom: 1px solid #dee2e6 !important;
    }

    .accordion-button:not(.collapsed) {
        background-color: #f8f9fa;
        color: #0c63e4;
    }

    .accordion-item {
        border: none;
        border-bottom: 1px solid #dee2e6;
    }

    .accordion-item:last-child {
        border-bottom: 1px solid #dee2e6;
    }
</style>
@endsection
