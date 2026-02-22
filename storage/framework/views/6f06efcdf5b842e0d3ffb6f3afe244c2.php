

<?php $__env->startSection('page-title', 'Known Conditions Management'); ?>

<?php $__env->startSection('admin-content'); ?>

<div class="container-fluid">

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Known Conditions List</h4>

            <a href="<?php echo e(route('admin.known-conditions.create')); ?>" class="btn btn-primary">
                <i class="fa fa-plus"></i> Add Known Condition
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">SR No</th>
                            <th>Title</th>
                            <th width="20%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $knownConditions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $condition): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e(($knownConditions->currentPage() - 1) * 10 + $key + 1); ?></td>
                                <td><?php echo e($condition->title); ?></td>
                                <td>

                                    <a href="<?php echo e(route('admin.known-conditions.edit', $condition->id)); ?>"
                                       class="btn btn-sm btn-warning">
                                        Edit
                                    </a>

                                    <form action="<?php echo e(route('admin.known-conditions.destroy', $condition->id)); ?>"
                                          method="POST"
                                          class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>

                                        <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure?')">
                                            Delete
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="3" class="text-center">
                                    No known conditions found.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if($knownConditions->hasPages()): ?>
                <div class="d-flex justify-content-center mt-4">
                    <?php echo e($knownConditions->links('pagination::bootstrap-5')); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\tech\borderless\borderless\resources\views/admin/known_conditions/index.blade.php ENDPATH**/ ?>