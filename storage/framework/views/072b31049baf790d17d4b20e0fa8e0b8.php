

<?php $__env->startSection('page-title', 'Add Complaint'); ?>

<?php $__env->startSection('admin-content'); ?>

<!-- Header -->
<div style="margin-bottom: 25px;">
    <h2 style="margin: 0; color: #2e59a7;">
        <i class="bi bi-exclamation-lg"></i> Add Complaint
    </h2>
</div>

<div class="table-container">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('admin.complaints.store')); ?>" id="complaintForm">
                <?php echo csrf_field(); ?>

                <!-- Complaint Information Section -->
                <h5 class="mb-3" style="color: #2e59a7; border-bottom: 2px solid #e3e6f0; padding-bottom: 10px;">
                    <i class="bi bi-chat-left-text"></i> Complaint Details
                </h5>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="complaint" class="form-label">Complaint <span style="color: red;">*</span></label>
                        <textarea class="form-control <?php $__errorArgs = ['complaint'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="complaint" name="complaint" rows="5" placeholder="Enter complaint details" required><?php echo e(old('complaint')); ?></textarea>
                        <?php $__errorArgs = ['complaint'];
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
                </div>

                <!-- Form Actions -->
                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Create Complaint
                    </button>
                    <a href="<?php echo e(route('admin.complaints.index')); ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\tech\borderless\borderless\resources\views/admin/complaints/create.blade.php ENDPATH**/ ?>