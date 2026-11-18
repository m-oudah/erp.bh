<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Buildings\BhmDashboardController;

use App\Http\Controllers\Buildings\BhmBuildingController;
use App\Http\Controllers\Buildings\BhmUnitController;

Route::middleware(['auth', 'verified', 'module.access:buildings'])->prefix('buildings')->name('buildings.')->group(function () {
    Route::get('/', [BhmDashboardController::class, 'index'])->name('dashboard');
    
    // Buildings
    Route::get('/buildings/search-archive-files', [BhmBuildingController::class, 'searchArchiveFiles'])->name('buildings.search-archive-files')->middleware('can:Read-Buildings');
    Route::get('/buildings/search-citizens', [BhmBuildingController::class, 'searchCitizens'])->name('buildings.search-citizens')->middleware('can:Read-Buildings');
    Route::post('/buildings/{id}/link-archive-file', [BhmBuildingController::class, 'linkArchiveFile'])->name('buildings.link-archive-file')->middleware('can:Read-Buildings');
    Route::post('/buildings/{id}/unlink-archive-file', [BhmBuildingController::class, 'unlinkArchiveFile'])->name('buildings.unlink-archive-file')->middleware('can:Read-Buildings');
    Route::post('/buildings/{id}/set-primary-archive-file', [BhmBuildingController::class, 'setPrimaryArchiveFile'])->name('buildings.set-primary-archive-file')->middleware('can:Read-Buildings');
    Route::get('/buildings', [BhmBuildingController::class, 'index'])->name('buildings.index')->middleware('can:Read-Buildings');
    Route::get('/buildings/create', [BhmBuildingController::class, 'create'])->name('buildings.create')->middleware('can:Read-Buildings');
    Route::post('/buildings', [BhmBuildingController::class, 'store'])->name('buildings.store');
    Route::get('/buildings/{id}', [BhmBuildingController::class, 'show'])->name('buildings.show')->middleware('can:Read-Buildings');
    Route::get('/buildings/{id}/edit', [BhmBuildingController::class, 'edit'])->name('buildings.edit');
    Route::put('/buildings/{id}', [BhmBuildingController::class, 'update'])->name('buildings.update');

    // Building Owners Management
    Route::get('/buildings/{id}/owners', [BhmBuildingController::class, 'getOwners'])->name('buildings.owners.index')->middleware('can:Read-Buildings');
    Route::post('/buildings/{id}/owners', [BhmBuildingController::class, 'storeOwner'])->name('buildings.owners.store');
    Route::put('/buildings/{id}/owners/{ownerId}', [BhmBuildingController::class, 'updateOwner'])->name('buildings.owners.update');
    Route::delete('/buildings/{id}/owners/{ownerId}', [BhmBuildingController::class, 'destroyOwner'])->name('buildings.owners.destroy');

    // Units Management (الوحدات العقارية)
    Route::get('/units/search-buildings', [BhmUnitController::class, 'searchBuildings'])->name('units.search-buildings')->middleware('can:Read-Buildings');
    Route::get('/units', [BhmUnitController::class, 'index'])->name('units.index')->middleware('can:Read-Buildings');
    Route::get('/units/create', [BhmUnitController::class, 'create'])->name('units.create')->middleware('can:Read-Buildings');
    Route::post('/units', [BhmUnitController::class, 'store'])->name('units.store');
    Route::get('/units/{id}', [BhmUnitController::class, 'show'])->name('units.show')->middleware('can:Read-Buildings');
    Route::get('/units/{id}/edit', [BhmUnitController::class, 'edit'])->name('units.edit');
    Route::put('/units/{id}', [BhmUnitController::class, 'update'])->name('units.update');
    Route::delete('/units/{id}', [BhmUnitController::class, 'destroy'])->name('units.destroy');
    
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
