@extends('layouts.admin')

@section('page-title', 'Patients Management')

@section('admin-content')

<!-- Header with Add Button -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 10px;">
    <h2 style="margin: 0; color: #2e59a7;">
        <i class="bi bi-people-fill"></i> Patients Management List
    </h2>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <a href="{{ route('admin.patients.import-form') }}" class="btn btn-success">
            <i class="bi bi-upload"></i> Bulk Import
        </a>
        <a href="{{ route('admin.patients.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus-fill"></i> Add New Patient
        </a>
    </div>
</div>

<!-- Patients Table Container -->
<div class="table-container">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <h3 style="margin: 0;">Patients List</h3>
    </div>

    <!-- Search and Filter -->
    <div class="search-filter">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Search by name, mobile, or serial number..." onkeyup="searchRecords()">
        </div>
    </div>

    <!-- Records Count -->
    <div class="mb-3">
        <p class="text-muted">
            Showing <strong id="recordsCount">{{ $patients->count() }}</strong> records
        </p>
    </div>

    @if ($patients->count() > 0)
        <div class="table-responsive">
            <table class="records-table">
                <thead>
                    <tr>
                        <th onclick="sortTable('serial')" style="cursor: pointer;">
                            Serial No. <i class="bi bi-arrow-down-up"></i>
                        </th>
                        <th onclick="sortTable('name')" style="cursor: pointer;">
                            Patient Name <i class="bi bi-arrow-down-up"></i>
                        </th>
                        <th>Age/Sex</th>
                        <th>Mobile</th>
                        <th>Village/Taluka</th>
                        <th onclick="sortTable('date')" style="cursor: pointer;">
                            Date <i class="bi bi-arrow-down-up"></i>
                        </th>
                        <th>Campaign Type</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($patients as $patient)
                        <tr>
                            <td><strong>{{ $patient->serial_number }}</strong></td>
                            <td>{{ $patient->patient_name }}</td>
                            <td>{{ $patient->age }} / {{ $patient->sex }}</td>
                            <td><small>{{ $patient->mobile }}</small></td>
                            <td>
                                <small>
                                    {{ $patient->village }}
                                    @if($patient->taluka)
                                        <br>{{ $patient->taluka->name }}
                                    @endif
                                </small>
                            </td>
                            <td><small class="text-muted">{{ $patient->date->format('M d, Y') }}</small></td>
                            <td>
                                @if($patient->campaignType)
                                    <span class="badge bg-info text-dark">{{ $patient->campaignType->name }}</span>
                                @else
                                    <span class="text-muted"><small>--</small></span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <div class="btn-group">
                                    <a href="{{ route('admin.patients.show', $patient) }}" class="btn-edit" title="View">
                                        <i class="bi bi-eye-fill"></i> View
                                    </a>
                                    <a href="{{ route('admin.patients.edit', $patient) }}" class="btn-edit" title="Edit">
                                        <i class="bi bi-pencil-fill"></i> Edit
                                    </a>
                                    <button class="btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $patient->id }}" title="Delete">
                                        <i class="bi bi-trash-fill"></i> Delete
                                    </button>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $patient->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0">
                                                <div class="modal-header bg-danger text-white border-0">
                                                    <h5 class="modal-title">
                                                        <i class="bi bi-exclamation-triangle"></i> Confirm Delete
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="mb-0">
                                                        Are you sure you want to delete <strong>{{ $patient->patient_name }}</strong> ({{ $patient->serial_number }})?
                                                        This action cannot be undone.
                                                    </p>
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <form method="POST" action="{{ route('admin.patients.destroy', $patient) }}" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="padding: 40px; text-align: center; color: #858796;">
            <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
            <p>No patients found.</p>
        </div>
    @endif

    <!-- Pagination -->
    @if ($patients->hasPages())
        <div class="pagination-container mt-3">
            {{ $patients->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

@endsection
