@extends('layouts.admin')

@section('page-title', 'Roles Management')

@section('admin-content')

<!-- Header -->
<div style="margin-bottom: 25px;">
    <h2 style="margin: 0; color: #2e59a7;">
        <i class="bi bi-shield-check"></i> Roles List
    </h2>
</div>

<!-- Roles Table Container -->
<div class="table-container">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <h3 style="margin: 0;">Available Roles</h3>
    </div>

    <!-- Search and Filter -->
    <div class="search-filter">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Search by role name or description..."
                onkeyup="searchRecords()">
        </div>
    </div>

    <!-- Records Count -->
    <div class="mb-3">
        <p class="text-muted">
            Showing <strong id="recordsCount">{{ $roles->count() }}</strong> records
        </p>
    </div>

    @if ($roles->count() > 0)
        <div class="table-responsive">
            <table class="records-table">
                <thead>
                    <tr>
                        <th onclick="sortTable('name')" style="cursor: pointer;">
                            Role Name <i class="bi bi-arrow-down-up"></i>
                        </th>
                        <th>Description</th>
                        <th onclick="sortTable('users')" style="cursor: pointer;">
                            Users Count <i class="bi bi-arrow-down-up"></i>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>
                                <strong>
                                    <i class="bi bi-shield-fill" style="color: #4e73df;"></i>
                                    {{ ucfirst($role->name) }}
                                </strong>
                            </td>
                            <td>{{ $role->description ?? 'No description' }}</td>
                            <td>
                                <span class="badge bg-info">
                                    <i class="bi bi-person-fill"></i>
                                    {{ $role->users_count ?? 0 }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="padding: 40px; text-align: center; color: #858796;">
            <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
            <p>No roles found.</p>
        </div>
    @endif
</div>

@endsection
