<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\TalukaController;
use App\Http\Controllers\Admin\VillageController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\CampaignTypeController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes (protected by auth + permission middleware)
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    // Dashboard - accessible to all authenticated users
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('permission:view_dashboard')
        ->name('dashboard');

    // Users management - admin only
    Route::middleware('permission:users_view,users_create,users_edit,users_delete')->group(function () {
        Route::resource('users', UserController::class);
    });

    // Roles management - admin only
    Route::get('/roles', [RoleController::class, 'index'])
        ->middleware('permission:roles_view')
        ->name('roles.index');

    // Role Permissions management - admin only
    Route::prefix('role-permissions')->name('role-permissions.')->middleware('permission:roles_view')->group(function () {
        Route::get('/', [RolePermissionController::class, 'index'])->name('index');
        Route::get('/{role}', [RolePermissionController::class, 'show'])->name('show');
        Route::put('/{role}', [RolePermissionController::class, 'update'])->name('update');
        Route::put('/{role}/reset', [RolePermissionController::class, 'reset'])->name('reset');
        Route::post('/bulk-update', [RolePermissionController::class, 'bulkUpdate'])->name('bulk-update');
        Route::get('/api/module/{module}', [RolePermissionController::class, 'getModulePermissions'])->name('get-module-permissions');
        Route::get('/api/role/{role}', [RolePermissionController::class, 'getRolePermissions'])->name('get-role-permissions');
    });

    // Activity logs - admin only
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])
        ->middleware('permission:activity_logs_view')
        ->name('activity-logs.index');

    // Location Management - admin only with granular permissions
    Route::middleware('permission:countries_view,countries_create,countries_edit,countries_delete')->group(function () {
        Route::resource('countries', CountryController::class);
    });

    Route::middleware('permission:states_view,states_create,states_edit,states_delete')->group(function () {
        Route::resource('states', StateController::class);
    });

    Route::middleware('permission:districts_view,districts_create,districts_edit,districts_delete')->group(function () {
        Route::resource('districts', DistrictController::class);
    });

    Route::middleware('permission:talukas_view,talukas_create,talukas_edit,talukas_delete')->group(function () {
        Route::resource('talukas', TalukaController::class);
    });

    Route::middleware('permission:villages_view,villages_create,villages_edit,villages_delete')->group(function () {
        Route::resource('villages', VillageController::class)->only(['index', 'show', 'create', 'store']);
        Route::resource('talukas.villages', VillageController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    });

    // Location Cascade API endpoints - view only permissions required
    Route::get('states/by-country/{country}', [StateController::class, 'getByCountry'])
        ->middleware('permission:states_view');
    Route::get('districts/by-state/{state}', [DistrictController::class, 'getByState'])
        ->middleware('permission:districts_view');
    Route::get('talukas/by-district/{district}', [TalukaController::class, 'getByDistrict'])
        ->middleware('permission:talukas_view');

    // Campaign Types Management
    Route::resource('campaign-types', CampaignTypeController::class);
    Route::post('campaign-types/{campaignType}/restore', [CampaignTypeController::class, 'restore'])
        ->name('campaign-types.restore');

    // Patient Management - Custom import routes (must be outside resource group)
    Route::middleware('permission:patients_view,patients_create')->group(function () {
        Route::get('/patients/import-form', [PatientController::class, 'importForm'])->name('patients.import-form');
        Route::post('/patients/import', [PatientController::class, 'import'])->name('patients.import');
        Route::get('/patients/download-template', [PatientController::class, 'downloadTemplate'])->name('patients.download-template');
    });

    // Patient Resource Routes (CRUD)
    Route::middleware('permission:patients_view,patients_create,patients_edit,patients_delete')->group(function () {
        Route::resource('patients', PatientController::class);
    });

    // Analytics Dashboard - Admin + Analytics Viewer roles
    Route::prefix('analytics')->name('analytics.')->middleware('permission:analytics_view')->group(function () {
        // Main dashboard view
        Route::get('/', [AnalyticsController::class, 'index'])->name('index');

        // API endpoints for AJAX calls
        Route::get('/api/stats', [AnalyticsController::class, 'getPatientStats'])->name('api.stats');
        Route::get('/api/registration-trend', [AnalyticsController::class, 'getRegistrationTrend'])->name('api.registration-trend');
        Route::get('/api/demographics', [AnalyticsController::class, 'getDemographics'])->name('api.demographics');
        Route::get('/api/health-metrics', [AnalyticsController::class, 'getHealthMetrics'])->name('api.health-metrics');
        Route::get('/api/lab-diagnostics', [AnalyticsController::class, 'getLabDiagnostics'])->name('api.lab-diagnostics');
        Route::get('/api/treatment-analytics', [AnalyticsController::class, 'getTreatmentAnalytics'])->name('api.treatment-analytics');
        Route::get('/api/patients', [AnalyticsController::class, 'getPatients'])->name('api.patients');

        // Location cascade endpoints
        Route::get('/api/states/{country}', [AnalyticsController::class, 'getStates'])->name('api.states');
        Route::get('/api/districts/{state}', [AnalyticsController::class, 'getDistricts'])->name('api.districts');
        Route::get('/api/talukas/{district}', [AnalyticsController::class, 'getTalukas'])->name('api.talukas');
    });
});

// API routes are now in routes/api.php and use token-based authentication
