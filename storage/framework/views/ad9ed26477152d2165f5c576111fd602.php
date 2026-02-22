<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title>Patient Management System - <?php echo $__env->yieldContent('title', 'Admin Dashboard'); ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/admin.css')); ?>">

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar Navigation -->
        <div class="sidebar">
            <div class="sidebar-header">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="sidebar-brand">
                    <i class="bi bi-hospital"></i>
                    <h3>Healthcare Admin</h3>
                </a>
                <button class="sidebar-toggle">
                    <i class="bi bi-chevron-left"></i>
                </button>
            </div>

            <ul class="sidebar-menu">
                <!-- Dashboard -->
                <?php if(Auth::user()->hasPermission('view_dashboard')): ?>
                    <li>
                        <a href="<?php echo e(route('admin.dashboard')); ?>" class="<?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                            <i class="bi bi-speedometer2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Administration (Users, Roles, Activity Logs) -->
                <?php if(Auth::user()->hasPermission('users_view') || Auth::user()->hasPermission('roles_view') || Auth::user()->hasPermission('activity_logs_view')): ?>
                    <li class="sidebar-menu-header">Administration</li>

                    <?php if(Auth::user()->hasPermission('users_view')): ?>
                        <li>
                            <a href="<?php echo e(route('admin.users.index')); ?>" class="<?php echo e(request()->routeIs('admin.users.*') ? 'active' : ''); ?>">
                                <i class="bi bi-people-fill"></i>
                                <span>Users</span>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if(Auth::user()->hasPermission('roles_view')): ?>
                        <li class="sidebar-submenu-container">
                            <a href="#adminSubmenu" class="sidebar-submenu-toggle" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                <i class="bi bi-shield-check"></i>
                                <span>Roles & Permissions</span>
                                <i class="bi bi-chevron-down sidebar-submenu-chevron"></i>
                            </a>
                            <ul class="sidebar-submenu collapse" id="adminSubmenu">
                                <li>
                                    <a href="<?php echo e(route('admin.roles.index')); ?>" class="<?php echo e(request()->routeIs('admin.roles.*') ? 'active' : ''); ?>">
                                        <i class="bi bi-shield-check"></i>
                                        <span>Roles</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo e(route('admin.role-permissions.index')); ?>" class="<?php echo e(request()->routeIs('admin.role-permissions.*') ? 'active' : ''); ?>">
                                        <i class="bi bi-shield-lock"></i>
                                        <span>Role Permissions</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if(Auth::user()->hasPermission('activity_logs_view')): ?>
                        <li>
                            <a href="<?php echo e(route('admin.activity-logs.index')); ?>" class="<?php echo e(request()->routeIs('admin.activity-logs.*') ? 'active' : ''); ?>">
                                <i class="bi bi-clock-history"></i>
                                <span>Activity Logs</span>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Location Management (Admin Only) -->
                <?php if(Auth::user()->hasAnyPermission(['countries_view', 'states_view', 'districts_view', 'talukas_view', 'villages_view'])): ?>
                    <li class="sidebar-menu-header">Location Management</li>
                    <li class="sidebar-submenu-container">
                        <a href="#locationSubmenu" class="sidebar-submenu-toggle" data-bs-toggle="collapse" role="button" aria-expanded="false">
                            <i class="bi bi-map"></i>
                            <span>Locations</span>
                            <i class="bi bi-chevron-down sidebar-submenu-chevron"></i>
                        </a>
                        <ul class="sidebar-submenu collapse" id="locationSubmenu">
                            <?php if(Auth::user()->hasPermission('countries_view')): ?>
                                <li>
                                    <a href="<?php echo e(route('admin.countries.index')); ?>" class="<?php echo e(request()->routeIs('admin.countries.*') ? 'active' : ''); ?>">
                                        <i class="bi bi-globe"></i>
                                        <span>Countries</span>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if(Auth::user()->hasPermission('states_view')): ?>
                                <li>
                                    <a href="<?php echo e(route('admin.states.index')); ?>" class="<?php echo e(request()->routeIs('admin.states.*') ? 'active' : ''); ?>">
                                        <i class="bi bi-map"></i>
                                        <span>States</span>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if(Auth::user()->hasPermission('districts_view')): ?>
                                <li>
                                    <a href="<?php echo e(route('admin.districts.index')); ?>" class="<?php echo e(request()->routeIs('admin.districts.*') ? 'active' : ''); ?>">
                                        <i class="bi bi-pin-map"></i>
                                        <span>Districts</span>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if(Auth::user()->hasPermission('talukas_view')): ?>
                                <li>
                                    <a href="<?php echo e(route('admin.talukas.index')); ?>" class="<?php echo e(request()->routeIs('admin.talukas.*') ? 'active' : ''); ?>">
                                        <i class="bi bi-geo-alt"></i>
                                        <span>Talukas</span>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php if(Auth::user()->hasPermission('villages_view')): ?>
                                <li>
                                    <a href="<?php echo e(route('admin.villages.index')); ?>" class="<?php echo e(request()->routeIs('admin.villages.*') ? 'active' : ''); ?>">
                                        <i class="bi bi-houses"></i>
                                        <span>Villages</span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <!-- Patient Management (Admin + Data Entry) -->
                <?php if(Auth::user()->hasPermission('patients_view')): ?>
                    <li class="sidebar-menu-header">Patient Management</li>
                    <li class="sidebar-submenu-container">
                        <a href="#patientSubmenu" class="sidebar-submenu-toggle" data-bs-toggle="collapse" role="button" aria-expanded="false">
                            <i class="bi bi-heart-pulse"></i>
                            <span>Patients</span>
                            <i class="bi bi-chevron-down sidebar-submenu-chevron"></i>
                        </a>
                        <ul class="sidebar-submenu collapse" id="patientSubmenu">
                            <li>
                                <a href="<?php echo e(route('admin.patients.index')); ?>" class="<?php echo e(request()->routeIs('admin.patients.index') || request()->routeIs('admin.patients.show') || request()->routeIs('admin.patients.edit') || request()->routeIs('admin.patients.create') ? 'active' : ''); ?>">
                                    <i class="bi bi-list-ul"></i>
                                    <span>View All Patients</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('admin.patients.create')); ?>" class="<?php echo e(request()->routeIs('admin.patients.create') ? 'active' : ''); ?>">
                                    <i class="bi bi-person-plus-fill"></i>
                                    <span>Add Patient</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('admin.patients.import-form')); ?>" class="<?php echo e(request()->routeIs('admin.patients.import-form') || request()->routeIs('admin.patients.import') ? 'active' : ''); ?>">
                                    <i class="bi bi-upload"></i>
                                    <span>Bulk Import</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>

                <!-- Campaign Management (Admin Only) -->
                <li class="sidebar-menu-header">Campaign Management</li>
                <li>
                    <a href="<?php echo e(route('admin.campaign-types.index')); ?>" class="<?php echo e(request()->routeIs('admin.campaign-types.*') ? 'active' : ''); ?>">
                        <i class="bi bi-collection"></i>
                        <span>Campaign Types</span>
                    </a>
                </li>

                <!-- Analytics & Reports -->
                <?php if(Auth::user()->hasPermission('analytics_view')): ?>
                    <li class="sidebar-menu-header">Analytics & Reports</li>
                    <li class="sidebar-submenu-container">
                        <a href="#analyticsSubmenu" class="sidebar-submenu-toggle" data-bs-toggle="collapse" role="button" aria-expanded="false">
                            <i class="bi bi-graph-up-arrow"></i>
                            <span>Analytics</span>
                            <i class="bi bi-chevron-down sidebar-submenu-chevron"></i>
                        </a>
                        <ul class="sidebar-submenu collapse" id="analyticsSubmenu">
                            <li>
                                <a href="<?php echo e(route('admin.analytics.index')); ?>" class="<?php echo e(request()->routeIs('admin.analytics.*') ? 'active' : ''); ?>">
                                    <i class="bi bi-bar-chart-line"></i>
                                    <span>Health Dashboard</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>

            <div class="sidebar-footer">  
                <div class="user-info">
                    <div class="user-avatar">
                        <?php echo e(strtoupper(substr(Auth::user()->name, 0, 2))); ?>

                    </div>
                    <div class="user-details">
                        <h4><?php echo e(Auth::user()->name); ?></h4>
                        <p><?php echo e(Auth::user()->isAdmin() ? 'Administrator' : 'User'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Top Navigation Bar -->
            <div class="topbar">
                <div class="topbar-left">
                    <button class="menu-toggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <h1><?php echo $__env->yieldContent('page-title', 'Patient Management System'); ?></h1>
                </div>
                <div class="topbar-right">
                    <div class="notification-icon">
                        <i class="bi bi-bell"></i>
                        <span class="notification-badge">0</span>
                    </div>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" style="display: inline;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </button>
                    </form>
                </div>
            </div>

            <!-- Content Area with Flash Messages -->
            <div class="content-area">
                <!-- Flash Messages -->
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="bi bi-exclamation-circle"></i> Error!</strong>
                        <?php if(count($errors) === 1): ?>
                            <?php echo e($errors->first()); ?>

                        <?php else: ?>
                            <ul class="mb-0 mt-2">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        <?php endif; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle"></i> <?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Page Content -->
                <?php echo $__env->yieldContent('admin-content'); ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo e(asset('assets/js/admin.js')); ?>"></script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\tech\resources\views/layouts/admin.blade.php ENDPATH**/ ?>