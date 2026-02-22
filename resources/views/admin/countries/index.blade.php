@extends('layouts.admin')

@section('page-title', 'Countries Management')

@section('admin-content')

<!-- Header -->
<div style="margin-bottom: 25px;">
    <h2 style="margin: 0; color: #2e59a7;">
        <i class="bi bi-globe"></i> Countries List
    </h2>
</div>

<!-- Countries Table Container -->
<div class="table-container">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <h3 style="margin: 0;">Countries</h3>
        <a href="{{ route('admin.countries.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Country
        </a>
    </div>

    <!-- Search and Filter -->
    <div class="search-filter">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Search by country name or code..." onkeyup="searchRecords()">
        </div>
    </div>

    <!-- Records Count -->
    <div class="mb-3">
        <p class="text-muted">
            Showing <strong id="recordsCount">{{ $countries->count() }}</strong> records
        </p>
    </div>

    @if ($countries->count() > 0)
        <div class="table-responsive">
            <table class="records-table">
                <thead>
                    <tr>
                        <th onclick="sortTable('name')" style="cursor: pointer;">
                            Country Name <i class="bi bi-arrow-down-up"></i>
                        </th>
                        <th onclick="sortTable('code')" style="cursor: pointer;">
                            Code <i class="bi bi-arrow-down-up"></i>
                        </th>
                        <th>Phone Code</th>
                        <th>Status</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($countries as $country)
                        <tr>
                            <td><strong>{{ $country->name }}</strong></td>
                            <td><span class="badge bg-info">{{ $country->code }}</span></td>
                            <td>{{ $country->phone_code ?? '--' }}</td>
                            <td>
                                @if ($country->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <div class="btn-group">
                                    <a href="{{ route('admin.countries.edit', $country) }}" class="btn-edit" title="Edit">
                                        <i class="bi bi-pencil-fill"></i> Edit
                                    </a>
                                    <button class="btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $country->id }}" title="Delete">
                                        <i class="bi bi-trash-fill"></i> Delete
                                    </button>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $country->id }}" tabindex="-1">
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
                                                        Are you sure you want to delete <strong>{{ $country->name }}</strong>?
                                                        This action cannot be undone.
                                                    </p>
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <form method="POST" action="{{ route('admin.countries.destroy', $country) }}" style="display: inline;">
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
            <p>No countries found.</p>
        </div>
    @endif

    <!-- Pagination -->
    @if ($countries->hasPages())
        <div class="pagination-container mt-3">
            {{ $countries->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

@endsection
