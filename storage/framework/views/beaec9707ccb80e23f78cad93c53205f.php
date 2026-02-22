<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('admin-content'); ?>

<!-- Dashboard Cards -->
<div class="dashboard-cards">
    <div class="dashboard-card">
        <div class="card-icon blue">
            <i class="bi bi-people-fill"></i>
        </div>
        <div class="card-content">
            <h3><?php echo e($totalUsers); ?></h3>
            <p>Total Users</p>
        </div>
    </div>

    <div class="dashboard-card">
        <div class="card-icon green">
            <i class="bi bi-shield-check"></i>
        </div>
        <div class="card-content">
            <h3><?php echo e($adminCount); ?></h3>
            <p>Admin Users</p>
        </div>
    </div>

    <div class="dashboard-card">
        <div class="card-icon orange">
            <i class="bi bi-person-fill"></i>
        </div>
        <div class="card-content">
            <h3><?php echo e($userCount); ?></h3>
            <p>Regular Users</p>
        </div>
    </div>

    <div class="dashboard-card">
        <div class="card-icon purple">
            <i class="bi bi-graph-up"></i>
        </div>
        <div class="card-content">
            <h3><?php echo e($newUsersThisWeek); ?></h3>
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
        <a href="<?php echo e(route('admin.activity-logs.index')); ?>" class="btn btn-primary btn-sm">
            View All <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    <hr>

    <?php if($recentActivityLogs->count() > 0): ?>
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
                    <?php $__currentLoopData = $recentActivityLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <strong><?php echo e($log->user->name ?? 'Unknown'); ?></strong>
                                <br>
                                <small class="text-muted"><?php echo e($log->user->email ?? ''); ?></small>
                            </td>
                            <td>
                                <span class="badge bg-primary"><?php echo e(ucfirst(str_replace('_', ' ', $log->action))); ?></span>
                            </td>
                            <td><?php echo e($log->description); ?></td>
                            <td>
                                <small class="text-muted"><?php echo e($log->created_at->diffForHumans()); ?></small>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div style="padding: 40px; text-align: center; color: #858796;">
            <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
            <p>No activity recorded yet.</p>
        </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('userGrowthChart').getContext('2d');
        const data = <?php echo json_encode($userGrowthData, 15, 512) ?>;

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
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\tech\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>