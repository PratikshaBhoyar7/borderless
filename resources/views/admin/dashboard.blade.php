@extends('layouts.admin')

@section('page-title', 'Dashboard')

@section('admin-content')

<!-- Dashboard Cards -->
<div class="dashboard-cards">
    <div class="dashboard-card">
        <div class="card-icon blue">
            <i class="bi bi-people-fill"></i>
        </div>
        <div class="card-content">
            <h3>{{ $totalUsers }}</h3>
            <p>Total Users</p>
        </div>
    </div>

    <div class="dashboard-card">
        <div class="card-icon green">
            <i class="bi bi-shield-check"></i>
        </div>
        <div class="card-content">
            <h3>{{ $adminCount }}</h3>
            <p>Admin Users</p>
        </div>
    </div>

    <div class="dashboard-card">
        <div class="card-icon orange">
            <i class="bi bi-person-fill"></i>
        </div>
        <div class="card-content">
            <h3>{{ $userCount }}</h3>
            <p>Regular Users</p>
        </div>
    </div>

    <div class="dashboard-card">
        <div class="card-icon purple">
            <i class="bi bi-graph-up"></i>
        </div>
        <div class="card-content">
            <h3>{{ $newUsersThisWeek }}</h3>
            <p>New This Week</p>
        </div>
    </div>
</div>

<!-- User Growth Chart -->
<div class="form-container mb-4">
    <h3>User Growth (Last 30 Days)</h3>
    <hr>
    <canvas id="userGrowthChart" height="60"></canvas>
</div>

<!-- Recent Activity -->
<div class="form-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0;">Recent Activity</h3>
        <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-primary btn-sm">
            View All <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    <hr>

    @if ($recentActivityLogs->count() > 0)
        <div class="table-responsive">
            <table class="records-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentActivityLogs as $log)
                        <tr>
                            <td>
                                <strong>{{ $log->user->name ?? 'Unknown' }}</strong>
                                <br>
                                <small class="text-muted">{{ $log->user->email ?? '' }}</small>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $log->action)) }}</span>
                            </td>
                            <td>{{ $log->description }}</td>
                            <td>
                                <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="padding: 40px; text-align: center; color: #858796;">
            <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
            <p>No activity recorded yet.</p>
        </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('userGrowthChart').getContext('2d');
        const data = @json($userGrowthData);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(d => d.date),
                datasets: [{
                    label: 'Total Users',
                    data: data.map(d => d.count),
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#4e73df',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
