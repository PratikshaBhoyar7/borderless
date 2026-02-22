@extends('layouts.admin')

@section('title', 'Manage Role: ' . ucfirst($role->name))

@section('page-title', 'Manage Role: ' . ucfirst($role->name))

@section('admin-content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-left-primary shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="m-0">
                                <i class="bi bi-shield-check"></i>
                                {{ ucfirst($role->name) }} Role
                            </h2>
                            <small class="text-muted">{{ $role->description ?? 'No description' }}</small>
                        </div>
                        <a href="{{ route('admin.role-permissions.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.role-permissions.update', $role) }}">
        @csrf
        @method('PUT')

        <!-- Role Information Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="m-0"><i class="bi bi-info-circle"></i> Role Information & Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3 fw-bold">Role Details</h6>
                                <div class="mb-2">
                                    <strong>Name:</strong> {{ $role->name }}
                                </div>
                                <div class="mb-2">
                                    <strong>Description:</strong> {{ $role->description ?? 'N/A' }}
                                </div>
                                <div class="mb-2">
                                    <strong>Users with this role:</strong>
                                    <span class="badge bg-info">{{ $role->users()->count() }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-3 fw-bold">Permission Statistics</h6>
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <strong>Current Permissions:</strong>
                                        <span class="badge bg-primary">{{ count($rolePermissions) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <strong>Total Available:</strong>
                                        <span class="badge bg-secondary">{{ \App\Models\Permission::count() }}</span>
                                    </div>
                                    @php
                                    $coverage = \App\Models\Permission::count() > 0 ? (count($rolePermissions) / \App\Models\Permission::count() * 100) : 0;
                                    @endphp
                                    <div>
                                        <strong>Permission Coverage:</strong>
                                        <div class="progress mt-2" style="height: 25px;">
                                            <div class="progress-bar {{ $coverage == 100 ? 'bg-success' : ($coverage >= 50 ? 'bg-info' : 'bg-warning') }}"
                                                 role="progressbar"
                                                 style="width: {{ $coverage }}%"
                                                 aria-valuenow="{{ $coverage }}"
                                                 aria-valuemin="0"
                                                 aria-valuemax="100">
                                                <strong>{{ number_format($coverage, 1) }}%</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permissions by Module -->
        <div class="row mb-4">
            @foreach($permissions as $module => $modulePerms)
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-folder"></i>
                            {{ ucfirst(str_replace('_', ' ', $module)) }}
                            <span class="badge bg-light text-dark float-end">
                                {{ count($modulePerms->filter(function($p) use ($rolePermissions) { return in_array($p->id, $rolePermissions); })) }}/{{ $modulePerms->count() }}
                            </span>
                        </h5>
                    </div>
                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                        <div class="permissions-grid">
                            @foreach($modulePerms as $permission)
                            <div class="form-check mb-3 p-2" style="background-color: #f8f9fa; border-radius: 4px;">
                                <input
                                    class="form-check-input permission-input"
                                    type="checkbox"
                                    name="permissions[]"
                                    value="{{ $permission->id }}"
                                    id="perm_{{ $permission->id }}"
                                    {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}
                                    {{ $permission->is_active ? '' : 'disabled' }}
                                >
                                <label class="form-check-label ms-2" for="perm_{{ $permission->id }}">
                                    <strong>{{ $permission->display_name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $permission->name }}</small>
                                    @if(!$permission->is_active)
                                    <br>
                                    <small class="text-danger"><i class="bi bi-x-circle"></i> Inactive</small>
                                    @endif
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Action Buttons -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> Save Permissions
                            </button>
                            <form method="POST" action="{{ route('admin.role-permissions.reset', $role) }}" style="display: inline;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-warning" onclick="return confirm('Reset this role to default permissions?')">
                                    <i class="bi bi-arrow-counterclockwise"></i> Reset to Default
                                </button>
                            </form>
                            <a href="{{ route('admin.role-permissions.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .permissions-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 10px;
    }

    .form-check {
        transition: all 0.2s ease;
    }

    .form-check:hover {
        background-color: #e9ecef !important;
        border-left: 3px solid #4e73df;
    }

    .form-check input:checked ~ label {
        color: #4e73df;
        font-weight: 600;
    }

    .form-check input:checked {
        background-color: #4e73df;
        border-color: #4e73df;
    }

    .card {
        border: none;
    }

    .card-header {
        border-bottom: 1px solid #e3e6f0;
    }

    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }
</style>
@endsection
