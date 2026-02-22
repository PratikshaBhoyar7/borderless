<?php $__env->startSection('page-title', 'States Management'); ?>

<?php $__env->startSection('admin-content'); ?>

<!-- Header -->
<div style="margin-bottom: 25px;">
    <h2 style="margin: 0; color: #2e59a7;">
        <i class="bi bi-map"></i> States List
    </h2>
</div>

<!-- States Table Container -->
<div class="table-container">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <h3 style="margin: 0;">States</h3>
        <a href="<?php echo e(route('admin.states.create')); ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add State
        </a>
    </div>

    <!-- Search and Filter -->
    <div class="search-filter">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Search by state name or code..." onkeyup="searchRecords()">
        </div>
        <div class="filter-group">
            <select class="form-select" id="countryFilter" onchange="applyFilters()">
                <option value="">All Countries</option>
                <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($country->name); ?>"><?php echo e($country->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </div>

    <!-- Records Count -->
    <div class="mb-3">
        <p class="text-muted">
            Showing <strong id="recordsCount"><?php echo e($states->count()); ?></strong> records
        </p>
    </div>

    <?php if($states->count() > 0): ?>
        <div class="table-responsive">
            <table class="records-table">
                <thead>
                    <tr>
                        <th onclick="sortTable('country')" style="cursor: pointer;">
                            Country <i class="bi bi-arrow-down-up"></i>
                        </th>
                        <th onclick="sortTable('name')" style="cursor: pointer;">
                            State Name <i class="bi bi-arrow-down-up"></i>
                        </th>
                        <th onclick="sortTable('code')" style="cursor: pointer;">
                            Code <i class="bi bi-arrow-down-up"></i>
                        </th>
                        <th>Status</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong><?php echo e($state->country->name); ?></strong></td>
                            <td><?php echo e($state->name); ?></td>
                            <td><span class="badge bg-info"><?php echo e($state->code ?? '--'); ?></span></td>
                            <td>
                                <?php if($state->is_active): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <div class="btn-group">
                                    <a href="<?php echo e(route('admin.states.edit', $state)); ?>" class="btn-edit" title="Edit">
                                        <i class="bi bi-pencil-fill"></i> Edit
                                    </a>
                                    <button class="btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo e($state->id); ?>" title="Delete">
                                        <i class="bi bi-trash-fill"></i> Delete
                                    </button>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal<?php echo e($state->id); ?>" tabindex="-1">
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
                                                        Are you sure you want to delete <strong><?php echo e($state->name); ?></strong>?
                                                        This action cannot be undone.
                                                    </p>
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <form method="POST" action="<?php echo e(route('admin.states.destroy', $state)); ?>" style="display: inline;">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
            <p>No states found.</p>
        </div>
    <?php endif; ?>

    <!-- Pagination -->
    <?php if($states->hasPages()): ?>
        <div class="pagination-container mt-3">
            <?php echo e($states->links('pagination::bootstrap-5')); ?>

        </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u756840116/domains/pink-spider-206461.hostingersite.com/public_html/resources/views/admin/states/index.blade.php ENDPATH**/ ?>