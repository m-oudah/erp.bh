<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Buildings\BhmCustomerController;

Route::middleware(['auth', 'verified'])->prefix('customers')->name('customers.')->group(function () {
    Route::get('/', function () {
        return view('customers.dashboard');
    })->name('dashboard');
    
    // Customers & Subscriptions
    Route::get('/list', [BhmCustomerController::class, 'index'])->name('index')->middleware('can:Read-Subscriptions');
    Route::get('/citizens', [BhmCustomerController::class, 'citizensIndex'])->name('citizens.index')->middleware('can:Read-Subscriptions');
    Route::get('/subscriptions/{id}', [BhmCustomerController::class, 'showSubscription'])->name('subscriptions.show')->middleware('can:Read-Subscriptions');
    Route::get('/customers/{id}', [BhmCustomerController::class, 'showCustomer'])->name('show')->middleware('can:Read-Subscriptions');
});
