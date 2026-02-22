<?php $__env->startSection('page-title', 'Villages Management'); ?>

<?php $__env->startSection('admin-content'); ?>

<!-- Header -->
<div style="margin-bottom: 25px;">
    <h2 style="margin: 0; color: #2e59a7;">
        <i class="bi bi-houses"></i>
        <?php if($taluka): ?>
            Villages of <?php echo e($taluka->name); ?>

        <?php else: ?>
            All Villages
        <?php endif; ?>
    </h2>
</div>

<!-- Villages Table Container -->
<div class="table-container">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <h3 style="margin: 0;">Villages</h3>
        <?php if($taluka): ?>
            <a href="<?php echo e(route('admin.talukas.villages.create', $taluka)); ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add Village
            </a>
        <?php else: ?>
            <a href="<?php echo e(route('admin.villages.create')); ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add Village
            </a>
        <?php endif; ?>
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
            Showing <strong id="recordsCount"><?php echo e($villages->count()); ?></strong> records
        </p>
    </div>

    <?php if($villages->count() > 0): ?>
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
                    <?php $__currentLoopData = $villages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $village): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($village->name); ?></td>
                            <td><?php echo e($village->taluka->name ?? 'N/A'); ?></td>
                            <td style="text-align: center;">
                                <?php if($village->is_active): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <div class="btn-group">
                                    <?php if($taluka): ?>
                                        <a href="<?php echo e(route('admin.talukas.villages.edit', [$taluka, $village])); ?>" class="btn-edit" title="Edit">
                                            <i class="bi bi-pencil-fill"></i> Edit
                                        </a>
                                        <button class="btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo e($village->id); ?>" title="Delete">
                                            <i class="bi bi-trash-fill"></i> Delete
                                        </button>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal<?php echo e($village->id); ?>" tabindex="-1">
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
                                                            Are you sure you want to delete <strong><?php echo e($village->name); ?></strong>?
                                                            This action cannot be undone.
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <form method="POST" action="<?php echo e(route('admin.talukas.villages.destroy', [$taluka, $village])); ?>" style="display: inline;">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <a href="<?php echo e(route('admin.talukas.villages.edit', [$village->taluka, $village])); ?>" class="btn-edit" title="Edit">
                                            <i class="bi bi-pencil-fill"></i> Edit
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div style="padding: 40px; text-align: center; color: #858796;">
            <i class="bi bi-inbox" style="font-size: 2rem; display: block; margin-bottom: 10px;"></i>
            <p>No villages found.</p>
        </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\tech\borderless\borderless\resources\views/admin/villages/index.blade.php ENDPATH**/ ?>