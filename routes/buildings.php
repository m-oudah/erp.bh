<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Buildings\BhmDashboardController;

use App\Http\Controllers\Buildings\BhmBuildingController;

Route::middleware(['auth', 'verified'])->prefix('buildings')->name('buildings.')->group(function () {
    Route::get('/', [BhmDashboardController::class, 'index'])->name('dashboard');
    
    // Buildings
    Route::get('/buildings', [BhmBuildingController::class, 'index'])->name('buildings.index')->middleware('can:Read-Buildings');
    Route::get('/buildings/{id}', [BhmBuildingController::class, 'show'])->name('buildings.show')->middleware('can:Read-Buildings');
    
    // Licenses & Reports
    Route::get('/licenses', [\App\Http\Controllers\Buildings\BhmLicenseController::class, 'index'])->name('licenses.index')->middleware('can:Read-LicenseForms');
    Route::get('/licenses/{id}', [\App\Http\Controllers\Buildings\BhmLicenseController::class, 'show'])->name('licenses.show')->middleware('can:Read-LicenseForms');

    // Streets
    Route::get('/streets', [\App\Http\Controllers\Buildings\BhmStreetController::class, 'index'])->name('streets.index')->middleware('can:Read-Streets');
    Route::put('/streets/{id}', [\App\Http\Controllers\Buildings\BhmStreetController::class, 'update'])->name('streets.update')->middleware('can:Update-Street');
});
