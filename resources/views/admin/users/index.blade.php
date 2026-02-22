@extends('layouts.admin')

@section('page-title', 'Users Management')

@section('admin-content')

<!-- Header with Add Button -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
    <h2 style="margin: 0; color: #2e59a7;">
        <i class="bi bi-people-fill"></i> Users Management
    </h2>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus-fill"></i> Add New User
    </a>
</div>

<!-- Users Table Container -->
<div class="table-container">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <h3 style="margin: 0;">Users List</h3>
    </div>

    <!-- Search and Filter -->
    <div class="search-filter">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Search by name, email, or role..."
                onkeyup="searchRecords()">
        </div>
    </div>

    <!-- Records Count -->
    <div class="mb-3">
        <p class="text-muted">
            Showing <strong id="recordsCount">{{ $users->count() }}</strong> records
        </p>
    </div>

    <!-- Users Table -->
    @if ($users->count() > 0)
        <div class="table-responsive">
            <table class="records-table">
                <thead>
                    <tr>
                        <th onclick="sortTable('id')" style="cursor: pointer;">
                            ID <i class="bi bi-arrow-down-up"></i>
                        </th>
                        <th onclick="sortTable('name')" style="cursor: pointer;">
                            Name <i class="bi bi-arrow-down-up"></i>
                        </th>
                        <th>Email</th>
                        <th>Role</th>
                        <th onclick="sortTable('date')" style="cursor: pointer;">
                            Joined <i class="bi bi-arrow-down-up"></i>
                        </th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td class="fw-bold">{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>
                                <small>{{ $user->email }}</small>
                            </td>
                            <td>
                                @if ($user->roles->count() > 0)
                                    @foreach ($user->roles as $role)
                                        <span class="badge bg-success">{{ ucfirst($role->name) }}</span>
                                    @endforeach
                                @else
                                    <span class="badge bg-secondary">No Role</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $user->created_at->format('M d, Y') }}</small>
                            </td>
                            <td style="text-align: center;">
                                <div class="btn-group">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn-edit"
                                        title="Edit">
                                        <i class="bi bi-pencil-fill"></i> Edit
                                    </a>
                                    <button class="btn-delete" data-bs-toggle="modal"
                                        data-bs-target="#deleteModal{{ $user->id }}" title="Delete">
                                        <i class="bi bi-trash-fill"></i> Delete
                                    </button>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0">
                                                <div class="modal-header bg-danger text-white border-0">
                                                    <h5 class="modal-title">
                                                        <i class="bi bi-exclamation-triangle"></i> Confirm Delete
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="mb-0">
                                                        Are you sure you want to delete <strong>{{ $user->name }}</strong>?
                                                        This action cannot be undone.
                                                    </p>
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Cancel</button>
                                                    <form method="POST"
                                                        action="{{ route('admin.users.destroy', $user) }}"
                                                        style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">
                                                            Delete
                                                        </button>
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
            <p>No users found.</p>
        </div>
    @endif

    <!-- Pagination -->
    @if ($users->hasPages())
        <div class="pagination-container mt-3">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

@endsection
