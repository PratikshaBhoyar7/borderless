<?php $__env->startSection('page-title', 'Activity Logs'); ?>

<?php $__env->startSection('admin-content'); ?>

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
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($user->name); ?>"><?php echo e($user->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </div>

    <!-- Records Count -->
    <div class="mb-3">
        <p class="text-muted">
            Showing <strong id="recordsCount"><?php echo e($activityLogs->count()); ?></strong> records
        </p>
    </div>

    <?php if($activityLogs->count() > 0): ?>
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
                    <?php $__currentLoopData = $activityLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <strong><?php echo e($log->user->name ?? 'Unknown'); ?></strong>
                                <br>
                                <small class="text-muted"><?php echo e($log->user->email ?? ''); ?></small>
                            </td>
                            <td>
                                <?php
                                    $actionColors = [
                                        'login' => 'success',
                                        'logout' => 'info',
                                        'registered' => 'success',
                                        'user_created' => 'primary',
                                        'user_updated' => 'primary',
                                        'user_deleted' => 'danger',
                                    ];
                                    $color = $actionColors[$log->action] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?php echo e($color); ?>">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $log->action))); ?>

                                </span>
                            </td>
                            <td><?php echo e($log->description ?? '--'); ?></td>
                            <td>
                                <small class="text-muted"><?php echo e($log->ip_address ?? '--'); ?></small>
                            </td>
                            <td>
                                <small class="text-muted" title="<?php echo e($log->created_at->format('M d, Y H:i:s')); ?>">
                                    <?php echo e($log->created_at->diffForHumans()); ?>

                                </small>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div style="padding: 40px; text-align: center; color: #858796;">
            <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
            <p>No activity logs found.</p>
        </div>
    <?php endif; ?>

    <!-- Pagination -->
    <?php if($activityLogs->hasPages()): ?>
        <div class="pagination-container mt-3">
            <?php echo e($activityLogs->links('pagination::bootstrap-5')); ?>

        </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u756840116/domains/pink-spider-206461.hostingersite.com/public_html/resources/views/admin/activity-logs/index.blade.php ENDPATH**/ ?>