<?php $__env->startSection('page-title', 'Campaign Types Management'); ?>

<?php $__env->startSection('admin-content'); ?>

<!-- Header with Add Button -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 10px;">
    <h2 style="margin: 0; color: #2e59a7;">
        <i class="bi bi-collection"></i> Campaign Types List 
    </h2>
    <a href="<?php echo e(route('admin.campaign-types.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle-fill"></i> Add New Campaign Type
    </a>
</div>

<!-- Campaign Types Table Container -->
<div class="table-container">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <h3 style="margin: 0;">Campaign Types List</h3>
    </div>

    <!-- Search and Filter -->
    <div class="search-filter">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Search by name..." onkeyup="searchRecords()">
        </div>
    </div>

    <!-- Records Count -->
    <div class="mb-3">
        <p class="text-muted">
            Showing <strong id="recordsCount"><?php echo e($campaignTypes->count()); ?></strong> records
        </p>
    </div>

    <?php if($campaignTypes->count() > 0): ?>
        <div class="table-responsive">
            <table class="records-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $campaignTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $campaignType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong><?php echo e($campaignType->name); ?></strong></td>
                            <td>
                                <small>
                                    <?php echo e($campaignType->description ? substr($campaignType->description, 0, 50) . (strlen($campaignType->description) > 50 ? '...' : '') : 'N/A'); ?>

                                </small>
                            </td>
                            <td>
                                <?php if($campaignType->deleted_at): ?>
                                    <span class="badge bg-danger">Deleted</span>
                                <?php else: ?>
                                    <?php if($campaignType->is_active): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td><small class="text-muted"><?php echo e($campaignType->created_at->format('M d, Y')); ?></small></td>
                            <td style="text-align: center;">
                                <div class="btn-group">
                                    <?php if($campaignType->deleted_at): ?>
                                        <form method="POST" action="<?php echo e(route('admin.campaign-types.restore', $campaignType)); ?>" style="display: inline;">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn-edit" title="Restore">
                                                <i class="bi bi-arrow-counterclockwise"></i> Restore
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <a href="<?php echo e(route('admin.campaign-types.edit', $campaignType)); ?>" class="btn-edit" title="Edit">
                                            <i class="bi bi-pencil-fill"></i> Edit
                                        </a>
                                        <button class="btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo e($campaignType->id); ?>" title="Delete">
                                            <i class="bi bi-trash-fill"></i> Delete
                                        </button>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="deleteModal<?php echo e($campaignType->id); ?>" tabindex="-1">
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
                                                            Are you sure you want to delete <strong><?php echo e($campaignType->name); ?></strong>?
                                                            This action cannot be undone.
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <form method="POST" action="<?php echo e(route('admin.campaign-types.destroy', $campaignType)); ?>" style="display: inline;">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit" class="btn btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
            <p>No campaign types found.</p>
        </div>
    <?php endif; ?>

    <!-- Pagination -->
    <?php if($campaignTypes->hasPages()): ?>
        <div class="pagination-container mt-3">
            <?php echo e($campaignTypes->links('pagination::bootstrap-5')); ?>

        </div>
    <?php endif; ?>
</div>

<script>
function searchRecords() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.querySelector('.records-table');
    const rows = table.querySelectorAll('tbody tr');
    let count = 0;

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(filter)) {
            row.style.display = '';
            count++;
        } else {
            row.style.display = 'none';
        }
    });

    document.getElementById('recordsCount').textContent = count;
}
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\tech\borderless\borderless\resources\views/admin/campaign-types/index.blade.php ENDPATH**/ ?>