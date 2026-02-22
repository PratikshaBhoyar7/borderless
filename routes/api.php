<?php

use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Controllers\Admin\AnalyticsController;
use Illuminate\Support\Facades\Route;

// Public API endpoints (no authentication required)
Route::post('/auth/login', [ApiAuthController::class, 'login'])->name('auth.login');
Route::get('/auth/check', [ApiAuthController::class, 'check'])->name('auth.check');

// Protected API endpoints (token authentication required)
Route::middleware(['auth:token'])->group(function () {
    // Authentication endpoints
    Route::get('/auth/me', [ApiAuthController::class, 'me'])->name('auth.me');
    Route::post('/auth/logout', [ApiAuthController::class, 'logout'])->name('auth.logout');
    Route::post('/auth/refresh', [ApiAuthController::class, 'refresh'])->name('auth.refresh');

    // Analytics endpoints
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/stats', [AnalyticsController::class, 'getPatientStats'])->name('stats');
        Route::get('/registration-trend', [AnalyticsController::class, 'getRegistrationTrend'])->name('registration-trend');
        Route::get('/demographics', [AnalyticsController::class, 'getDemographics'])->name('demographics');
        Route::get('/health-metrics', [AnalyticsController::class, 'getHealthMetrics'])->name('health-metrics');
        Route::get('/lab-diagnostics', [AnalyticsController::class, 'getLabDiagnostics'])->name('lab-diagnostics');
        Route::get('/treatment-analytics', [AnalyticsController::class, 'getTreatmentAnalytics'])->name('treatment-analytics');
        Route::get('/patients', [AnalyticsController::class, 'getPatients'])->name('patients');
        Route::get('/states/{country}', [AnalyticsController::class, 'getStates'])->name('states');
        Route::get('/districts/{state}', [AnalyticsController::class, 'getDistricts'])->name('districts');
        Route::get('/talukas/{district}', [AnalyticsController::class, 'getTalukas'])->name('talukas');
    });
});
