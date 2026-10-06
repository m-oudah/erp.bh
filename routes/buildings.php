<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Buildings\BhmDashboardController;

use App\Http\Controllers\Buildings\BhmBuildingController;

Route::middleware(['auth', 'verified'])->prefix('buildings')->name('buildings.')->group(function () {
    Route::get('/', [BhmDashboardController::class, 'index'])->name('dashboard');
    
    // Buildings
    Route::get('/buildings', [BhmBuildingController::class, 'index'])->name('buildings.index')->middleware('can:bhm.buildings.view');
    Route::get('/buildings/{id}', [BhmBuildingController::class, 'show'])->name('buildings.show')->middleware('can:bhm.buildings.view');
    
    // Economical Activities
    Route::get('/economical', [\App\Http\Controllers\Buildings\BhmEconomicalController::class, 'index'])->name('economical.index')->middleware('can:bhm.economical.view');
    Route::get('/economical/{id}', [\App\Http\Controllers\Buildings\BhmEconomicalController::class, 'show'])->name('economical.show')->middleware('can:bhm.economical.view');
    Route::get('/economical/{id}/edit', [\App\Http\Controllers\Buildings\BhmEconomicalController::class, 'edit'])->name('economical.edit')->middleware('can:bhm.economical.edit');
    Route::put('/economical/{id}', [\App\Http\Controllers\Buildings\BhmEconomicalController::class, 'update'])->name('economical.update')->middleware('can:bhm.economical.edit');
    
    // Licenses & Reports
    Route::get('/licenses', [\App\Http\Controllers\Buildings\BhmLicenseController::class, 'index'])->name('licenses.index')->middleware('can:bhm.license-forms.view');
    Route::get('/licenses/{id}', [\App\Http\Controllers\Buildings\BhmLicenseController::class, 'show'])->name('licenses.show')->middleware('can:bhm.license-forms.view');
    
    // Customers & Subscriptions
    Route::get('/customers', [\App\Http\Controllers\Buildings\BhmCustomerController::class, 'index'])->name('customers.index')->middleware('can:bhm.subscriptions.view');
    Route::get('/subscriptions/{id}', [\App\Http\Controllers\Buildings\BhmCustomerController::class, 'showSubscription'])->name('subscriptions.show')->middleware('can:bhm.subscriptions.view');
    Route::get('/customers/{id}', [\App\Http\Controllers\Buildings\BhmCustomerController::class, 'showCustomer'])->name('customers.show')->middleware('can:bhm.subscriptions.view');
});
