<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Transactions\BhmTransactionController;
use App\Http\Controllers\Transactions\BhmTransactionTypeController;
use App\Http\Controllers\Transactions\BhmDraftTransactionController;
use App\Http\Controllers\Transactions\BhmDepartmentResponseController;

Route::middleware(['auth'])->prefix('transactions')->name('transactions.')->group(function () {
    
    // Types (Settings)
    Route::resource('types', BhmTransactionTypeController::class)->except(['create', 'show', 'edit']);

    // Drafts (Portal Requests)
    Route::prefix('drafts')->name('drafts.')->group(function () {
        Route::get('/', [BhmDraftTransactionController::class, 'index'])->name('index');
        Route::get('/{id}', [BhmDraftTransactionController::class, 'show'])->name('show');
        Route::post('/{id}/convert', [BhmDraftTransactionController::class, 'convertToTransaction'])->name('convert');
        Route::delete('/{id}', [BhmDraftTransactionController::class, 'destroy'])->name('destroy');
    });

    // Department Responses
    Route::post('department-responses', [BhmDepartmentResponseController::class, 'store'])->name('department-responses.store');
    Route::put('department-responses/{id}', [BhmDepartmentResponseController::class, 'update'])->name('department-responses.update');
    Route::delete('department-responses/{id}', [BhmDepartmentResponseController::class, 'destroy'])->name('department-responses.destroy');

    // Main Transactions
    Route::put('/{id}/status', [BhmTransactionController::class, 'updateStatus'])->name('status.update');
    
    Route::get('/', [BhmTransactionController::class, 'dashboard'])->name('index');
    Route::get('/history', [BhmTransactionController::class, 'history'])->name('history');
    Route::get('/create', [BhmTransactionController::class, 'create'])->name('create');
    Route::post('/', [BhmTransactionController::class, 'store'])->name('store');
    Route::get('/{id}', [BhmTransactionController::class, 'show'])->name('show');
    Route::delete('/{id}', [BhmTransactionController::class, 'destroy'])->name('destroy');

});
