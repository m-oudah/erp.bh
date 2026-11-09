<?php

use App\Http\Controllers\Portal\PortalController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Citizen Self-Service Portal Routes (بوابة المواطنين الإلكترونية)
|--------------------------------------------------------------------------
| Prefix: /portal
| Guard / Session: Citizen Authentication
*/

Route::prefix('portal')->name('portal.')->group(function () {
    // Guest Citizen Routes (Login & OTP)
    Route::get('/login', [PortalController::class, 'showLogin'])->name('login');
    Route::get('/login/check-cooldown', [PortalController::class, 'checkCooldown'])->name('check-cooldown');
    Route::post('/login/send-otp', [PortalController::class, 'sendOtp'])->name('send-otp');
    Route::post('/login/direct', [PortalController::class, 'directLogin'])->name('login.direct');
    Route::get('/login/verify', [PortalController::class, 'showVerifyOtp'])->name('verify-otp');
    Route::post('/login/verify', [PortalController::class, 'verifyOtp'])->name('verify-otp.submit');
    Route::post('/login/resend-otp', [PortalController::class, 'resendOtp'])->name('resend-otp');

    // Authenticated Citizen Routes
    Route::middleware(['citizen.auth'])->group(function () {
        Route::get('/', [PortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/buildings', [PortalController::class, 'buildings'])->name('buildings');
        Route::get('/units', [PortalController::class, 'units'])->name('units');
        Route::get('/crafts', [PortalController::class, 'crafts'])->name('crafts');
        Route::post('/logout', [PortalController::class, 'logout'])->name('logout');
    });
});

