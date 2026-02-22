@extends('layouts.admin')

@section('page-title', 'Villages Management')

@section('admin-content')

<!-- Header -->
<div style="margin-bottom: 25px;">
    <h2 style="margin: 0; color: #2e59a7;">
        <i class="bi bi-houses"></i>
        @if($taluka)
            Villages of {{ $taluka->name }}
        @else
            All Villages
        @endif
    </h2>
</div>

<!-- Villages Table Container -->
<div class="table-container">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <h3 style="margin: 0;">Villages</h3>
        @if($taluka)
            <a href="{{ route('admin.talukas.villages.create', $taluka) }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add Village
            </a>
        @else
            <a href="{{ route('admin.villages.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add Village
            </a>
        @endif
    </div>

    <!-- Search and Filter -->
    <div class="search-filter">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Search by village name..." onkeyup="searchRecords()">
        </div>
    </div>

    <!-- Records Count -->
    <div class="mb-3">
        <p class="text-muted">
            Showing <strong id="recordsCount">{{ $villages->count() }}</strong> records
        </p>
    </div>

    @if ($villages->count() > 0)
        <div class="table-responsive">
            <table class="records-table">
                <thead>
                    <tr>
                        <th onclick="sortTable('name')" style="cursor: pointer;">
                            Village Name <i class="bi bi-arrow-down-up"></i>
                        </th>
                        <th>Taluka</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($villages as $village)
                        <tr>
                            <td>{{ $village->name }}</td>
                            <td>{{ $village->taluka->name ?? 'N/A' }}</td>
                            <td style="text-align: center;">
                                @if($village->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <div class="btn-group">
                                    @if($taluka)
                                        <a href="{{ route('admin.talukas.villages.edit', [$taluka, $village]) }}" class="btn-edit" title="Edit">
                                            <i class="bi bi-pencil-fill"></i> Edit
                                        </a>
                                        <button class="btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $village->id }}" title="Delete">
                                            <i class="bi bi-trash-fill"></i> Delete
                                        </button>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal{{ $village->id }}" tabindex="-1">
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
                                                            Are you sure you want to delete <strong>{{ $village->name }}</strong>?
                                                            This action cannot be undone.
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <form method="POST" action="{{ route('admin.talukas.villages.destroy', [$taluka, $village]) }}" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <a href="{{ route('admin.talukas.villages.edit', [$village->taluka, $village]) }}" class="btn-edit" title="Edit">
                                            <i class="bi bi-pencil-fill"></i> Edit
                                        </a>
                                    @endif
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
            <p>No villages found.</p>
        </div>
    @endif
</div>

@endsection
