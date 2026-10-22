<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Buildings\BhmDashboardController;

use App\Http\Controllers\Buildings\BhmBuildingController;

Route::middleware(['auth', 'verified'])->prefix('buildings')->name('buildings.')->group(function () {
    Route::get('/', [BhmDashboardController::class, 'index'])->name('dashboard');
    
    // Buildings
    Route::get('/buildings/search-archive-files', [BhmBuildingController::class, 'searchArchiveFiles'])->name('buildings.search-archive-files')->middleware('can:Read-Buildings');
    Route::post('/buildings/{id}/link-archive-file', [BhmBuildingController::class, 'linkArchiveFile'])->name('buildings.link-archive-file')->middleware('can:Read-Buildings');
    Route::post('/buildings/{id}/unlink-archive-file', [BhmBuildingController::class, 'unlinkArchiveFile'])->name('buildings.unlink-archive-file')->middleware('can:Read-Buildings');
    Route::get('/buildings', [BhmBuildingController::class, 'index'])->name('buildings.index')->middleware('can:Read-Buildings');
    Route::get('/buildings/{id}', [BhmBuildingController::class, 'show'])->name('buildings.show')->middleware('can:Read-Buildings');
    Route::get('/buildings/{id}/edit', [BhmBuildingController::class, 'edit'])->name('buildings.edit');
    Route::put('/buildings/{id}', [BhmBuildingController::class, 'update'])->name('buildings.update');
    
    // Licenses & Reports
    Route::get('/licenses/search-citizens', [\App\Http\Controllers\Buildings\BhmLicenseController::class, 'searchCitizens'])->name('licenses.search-citizens')->middleware('can:Read-LicenseForms');
    Route::post('/licenses/{id}/link-citizen', [\App\Http\Controllers\Buildings\BhmLicenseController::class, 'linkCitizen'])->name('licenses.link-citizen')->middleware('can:Read-LicenseForms');
    Route::post('/licenses/{id}/unlink-citizen', [\App\Http\Controllers\Buildings\BhmLicenseController::class, 'unlinkCitizen'])->name('licenses.unlink-citizen')->middleware('can:Read-LicenseForms');
    Route::get('/licenses', [\App\Http\Controllers\Buildings\BhmLicenseController::class, 'index'])->name('licenses.index')->middleware('can:Read-LicenseForms');
    Route::get('/licenses/{id}', [\App\Http\Controllers\Buildings\BhmLicenseController::class, 'show'])->name('licenses.show')->middleware('can:Read-LicenseForms');

    // Streets
    Route::get('/streets', [\App\Http\Controllers\Buildings\BhmStreetController::class, 'index'])->name('streets.index')->middleware('can:Read-Streets');
    Route::put('/streets/{id}', [\App\Http\Controllers\Buildings\BhmStreetController::class, 'update'])->name('streets.update')->middleware('can:Update-Street');
});
