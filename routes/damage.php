<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Damage\DamageDashboardController;
use App\Http\Controllers\Damage\DamageRecordController;
use App\Http\Controllers\Damage\PricingCriteriaController;
use App\Http\Controllers\Damage\DamagePublicPortalController;
use App\Http\Controllers\Damage\DamageCertificateController;

/*
|--------------------------------------------------------------------------
| Public Routes: External Portal & QR Verification (No Auth Required)
|--------------------------------------------------------------------------
*/
// External Damage Portal
Route::prefix('damage-portal')->name('damage.portal.')->group(function () {
    Route::get('/', [DamagePublicPortalController::class, 'index'])->name('index');
    Route::get('/map-data', [DamagePublicPortalController::class, 'mapData'])->name('mapData');
    Route::get('/donor-space', function () {
        return redirect()->route('damage.portal.index');
    })->name('donorSpace');
    Route::post('/donor-intervention', [DamagePublicPortalController::class, 'storeDonorIntervention'])->name('donorIntervention');
    Route::get('/report', [DamagePublicPortalController::class, 'exportReport'])->name('report');
});

// Official QR Code Verification Link
Route::get('/verify-certificate/{uuid}', [DamageCertificateController::class, 'verify'])->name('damage.verify-certificate');

/*
|--------------------------------------------------------------------------
| Internal ERP Routes: Municipal Management & Engineering Review (Auth Required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('damage')->name('damage.')->group(function () {
    // Dashboard
    Route::get('/', [DamageDashboardController::class, 'index'])->name('dashboard');

    // AJAX checks
    Route::get('/ajax/check-duplicate', [DamageRecordController::class, 'checkDuplicate'])->name('ajax.duplicate');

    // Pricing & Engineering Criteria
    Route::prefix('pricing')->name('pricing.')->group(function () {
        Route::get('/', [PricingCriteriaController::class, 'index'])->name('index');
        Route::post('/', [PricingCriteriaController::class, 'store'])->name('store');
        Route::put('/{id}', [PricingCriteriaController::class, 'update'])->name('update');
        Route::post('/{id}/bulk-recalculate', [PricingCriteriaController::class, 'bulkRecalculate'])->name('bulkRecalculate');
    });

    // Damage Records Management
    Route::prefix('records')->name('records.')->group(function () {
        Route::get('/', [DamageRecordController::class, 'index'])->name('index');
        Route::get('/create', [DamageRecordController::class, 'create'])->name('create');
        Route::get('/export', [DamageRecordController::class, 'export'])->name('export');
        Route::post('/', [DamageRecordController::class, 'store'])->name('store');
        Route::get('/{id}', [DamageRecordController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [DamageRecordController::class, 'edit'])->name('edit');
        Route::put('/{id}', [DamageRecordController::class, 'update'])->name('update');
        Route::post('/{id}/workflow', [DamageRecordController::class, 'updateWorkflow'])->name('workflow');
        Route::post('/{id}/recalculate', [DamageRecordController::class, 'recalculate'])->name('recalculate');
        Route::get('/{id}/certificate', [DamageCertificateController::class, 'printCertificate'])->name('certificate');
    });
});
