

<?php $__env->startSection('page-title', 'Add Known Condition'); ?>

<?php $__env->startSection('admin-content'); ?>

<div style="margin-bottom: 25px;">
    <h2 style="margin: 0; color: #2e59a7;">
        <i class="bi bi-plus-circle"></i> Add Known Condition
    </h2>
</div>

<div class="card">
    <div class="card-body">

        <form method="POST" action="<?php echo e(route('admin.known-conditions.store')); ?>">
            <?php echo csrf_field(); ?>

            <div class="mb-3">
                <label class="form-label">
                    Condition Title <span class="text-danger">*</span>
                </label>

                <input type="text"
                       name="title"
                       class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('title')); ?>"
                       placeholder="Enter condition title"
                       required>

                <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i> Save
            </button>

            <a href="<?php echo e(route('admin.known-conditions.index')); ?>"
               class="btn btn-secondary">
                Cancel
            </a>

        </form>

    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\tech\borderless\borderless\resources\views/admin/known_conditions/create.blade.php ENDPATH**/ ?>