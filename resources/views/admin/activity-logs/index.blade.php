@extends('layouts.admin')

@section('page-title', 'Activity Logs')

@section('admin-content')

<!-- Header -->
<div style="margin-bottom: 25px;">
    <h2 style="margin: 0; color: #2e59a7;">
        <i class="bi bi-clock-history"></i> Activity Logs
    </h2>
</div>

<!-- Activity Logs Table Container -->
<div class="table-container">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <h3 style="margin: 0;">System Activity</h3>
    </div>

    <!-- Search and Filter -->
    <div class="search-filter">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Search by user, action, or description..."
                onkeyup="searchRecords()">
        </div>
        <div class="filter-group">
            <select class="form-select" id="userFilter" onchange="applyFilters()">
                <option value="">All Users</option>
                @foreach ($users as $user)
                    <option value="{{ $user->name }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Records Count -->
    <div class="mb-3">
        <p class="text-muted">
            Showing <strong id="recordsCount">{{ $activityLogs->count() }}</strong> records
        </p>
    </div>

    @if ($activityLogs->count() > 0)
        <div class="table-responsive">
            <table class="records-table">
                <thead>
                    <tr>
                        <th onclick="sortTable('user')" style="cursor: pointer;">
                            User <i class="bi bi-arrow-down-up"></i>
                        </th>
                        <th onclick="sortTable('action')" style="cursor: pointer;">
                            Action <i class="bi bi-arrow-down-up"></i>
                        </th>
                        <th>Description</th>
                        <th>IP Address</th>
                        <th onclick="sortTable('date')" style="cursor: pointer;">
                            Date & Time <i class="bi bi-arrow-down-up"></i>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($activityLogs as $log)
                        <tr>
                            <td>
                                <strong>{{ $log->user->name ?? 'Unknown' }}</strong>
                                <br>
                                <small class="text-muted">{{ $log->user->email ?? '' }}</small>
                            </td>
                            <td>
                                @php
                                    $actionColors = [
                                        'login' => 'success',
                                        'logout' => 'info',
                                        'registered' => 'success',
                                        'user_created' => 'primary',
                                        'user_updated' => 'primary',
                                        'user_deleted' => 'danger',
                                    ];
                                    $color = $actionColors[$log->action] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }}">
                                    {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                </span>
                            </td>
                            <td>{{ $log->description ?? '--' }}</td>
                            <td>
                                <small class="text-muted">{{ $log->ip_address ?? '--' }}</small>
                            </td>
                            <td>
                                <small class="text-muted" title="{{ $log->created_at->format('M d, Y H:i:s') }}">
                                    {{ $log->created_at->diffForHumans() }}
                                </small>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="padding: 40px; text-align: center; color: #858796;">
            <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
            <p>No activity logs found.</p>
        </div>
    @endif

    <!-- Pagination -->
    @if ($activityLogs->hasPages())
        <div class="pagination-container mt-3">
            {{ $activityLogs->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

@endsection
