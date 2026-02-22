<?php $__env->startSection('title', 'Role & Permissions Management'); ?>

<?php $__env->startSection('page-title', 'Role & Permissions Management'); ?>

<?php $__env->startSection('admin-content'); ?>
<div class="container-fluid">
    <!-- Header Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="m-0"><i class="bi bi-shield-lock"></i> Role & Permissions List</h2>
                            <small class="text-muted">Manage permissions for each role in your system</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Roles and Permissions Overview -->
    <div class="row mb-4">
        <?php if($roles->count() > 0): ?>
            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $rolePermissions = $role->permissions()->get();
                $permissionCount = $rolePermissions->count();
            ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <!-- Role Header -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-1">
                                    <i class="bi bi-shield-check text-primary"></i>
                                    <?php echo e(ucfirst($role->name)); ?>

                                </h5>
                                <small class="text-muted d-block mb-2"><?php echo e($role->description ?? 'No description'); ?></small>
                                <small class="text-muted">
                                    <i class="bi bi-person-fill"></i>
                                    <?php echo e($role->users()->count()); ?> user(s) assigned
                                </small>
                            </div>
                            <span class="badge bg-primary rounded-pill ms-2"><?php echo e($permissionCount); ?> perms</span>
                        </div>

                        <!-- Permission Preview -->
                        <hr class="my-2">
                        <div class="mb-3">
                            <small class="text-muted fw-bold d-block mb-2">Permissions:</small>
                            <div>
                                <?php if($permissionCount > 0): ?>
                                    <?php $__currentLoopData = $rolePermissions->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="badge bg-info mb-1"><?php echo e($permission->name); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($permissionCount > 3): ?>
                                    <span class="badge bg-secondary mb-1">+<?php echo e($permissionCount - 3); ?> more</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-warning small">No permissions assigned</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="btn-group w-100 mt-3" role="group">
                            <a href="<?php echo e(route('admin.role-permissions.show', $role)); ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i> Manage
                            </a>
                            <form method="POST" action="<?php echo e(route('admin.role-permissions.reset', $role)); ?>" style="display: none;" id="reset-form-<?php echo e($role->id); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>
                            </form>
                            <button type="button" class="btn btn-sm btn-outline-warning" onclick="if(confirm('Reset <?php echo e($role->name); ?> role to default permissions?')) document.getElementById('reset-form-<?php echo e($role->id); ?>').submit();">
                                <i class="bi bi-arrow-counterclockwise"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> No roles found in the system.
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Permissions Overview Section -->
    <?php if($permissions->count() > 0): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="m-0"><i class="bi bi-list-check"></i> Permissions by Module</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module => $modulePerms): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <h6 class="text-uppercase font-weight-bold text-primary mb-3" style="font-size: 0.85rem; letter-spacing: 1px;">
                                <i class="bi bi-folder"></i> <?php echo e(ucfirst(str_replace('_', ' ', $module))); ?>

                                <span class="badge bg-secondary float-end"><?php echo e($modulePerms->count()); ?></span>
                            </h6>
                            <div class="list-group list-group-sm">
                                <?php $__currentLoopData = $modulePerms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="list-group-item small" style="padding: 8px 12px;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?php echo e($permission->display_name); ?></strong>
                                            <br>
                                            <small class="text-muted"><?php echo e($permission->name); ?></small>
                                        </div>
                                        <span class="badge <?php echo e($permission->is_active ? 'bg-success' : 'bg-secondary'); ?>">
                                            <?php echo e($permission->is_active ? 'Active' : 'Inactive'); ?>

                                        </span>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Statistics Section -->
    <div class="row mt-4">
        <?php
            $totalPermissions = $permissions->reduce(function($carry, $group) {
                return $carry + $group->count();
            }, 0);
            $totalUsers = \App\Models\User::count();
        ?>

        <!-- Total Roles -->
        <div class="col-md-3 mb-4">
            <div class="card border-left-primary shadow h-100">
                <div class="card-body">
                    <div class="text-center">
                        <div class="text-primary" style="font-size: 2.5rem; margin-bottom: 10px;">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h3 class="text-primary mb-0"><?php echo e($roles->count()); ?></h3>
                        <small class="text-muted">Total Roles</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Permissions -->
        <div class="col-md-3 mb-4">
            <div class="card border-left-success shadow h-100">
                <div class="card-body">
                    <div class="text-center">
                        <div class="text-success" style="font-size: 2.5rem; margin-bottom: 10px;">
                            <i class="bi bi-key"></i>
                        </div>
                        <h3 class="text-success mb-0"><?php echo e($totalPermissions); ?></h3>
                        <small class="text-muted">Total Permissions</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Modules -->
        <div class="col-md-3 mb-4">
            <div class="card border-left-info shadow h-100">
                <div class="card-body">
                    <div class="text-center">
                        <div class="text-info" style="font-size: 2.5rem; margin-bottom: 10px;">
                            <i class="bi bi-folder"></i>
                        </div>
                        <h3 class="text-info mb-0"><?php echo e($permissions->count()); ?></h3>
                        <small class="text-muted">Modules</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="col-md-3 mb-4">
            <div class="card border-left-warning shadow h-100">
                <div class="card-body">
                    <div class="text-center">
                        <div class="text-warning" style="font-size: 2.5rem; margin-bottom: 10px;">
                            <i class="bi bi-people"></i>
                        </div>
                        <h3 class="text-warning mb-0"><?php echo e($totalUsers); ?></h3>
                        <small class="text-muted">Total Users</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .border-left-primary {
        border-left: 0.25rem solid #4e73df !important;
    }

    .border-left-success {
        border-left: 0.25rem solid #1cc88a !important;
    }

    .border-left-info {
        border-left: 0.25rem solid #36b9cc !important;
    }

    .border-left-warning {
        border-left: 0.25rem solid #f6c23e !important;
    }

    .card {
        border: none;
        margin-bottom: 1.5rem;
    }

    .card-header {
        border-bottom: 1px solid #e3e6f0;
    }

    .list-group-item {
        border: 1px solid #e3e6f0;
        border-radius: 0.25rem;
        margin-bottom: 0.5rem;
    }

    .list-group-item:last-child {
        margin-bottom: 0;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u756840116/domains/pink-spider-206461.hostingersite.com/public_html/resources/views/admin/role-permissions/index.blade.php ENDPATH**/ ?>