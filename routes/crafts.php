<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Buildings\BhmEconomicalController;

Route::middleware(['auth', 'verified'])->prefix('crafts')->name('crafts.')->group(function () {
    Route::get('/', function () {
        $e = \App\Models\Buildings\BhmEconomical::query();

        $stats = [
            'total'           => (clone $e)->count(),
            // isActive: 2=نشط, 1=مغلق
            'active'          => (clone $e)->where('isActive', 2)->count(),
            'closed'          => (clone $e)->where('isActive', 1)->count(),
            // work_state: 0=جديد, 1=نشط/مستمر, null=غير محدد
            'new_unapproved'  => (clone $e)->whereNull('work_state')->count(),
            // isDanger: 1=خطرة, 0=عادية, 2=غير محدد
            'dangerous'       => (clone $e)->where('isDanger', 1)->count(),
            'safe'            => (clone $e)->where('isDanger', 0)->count(),
            // isLicensed: 1=مرخص, 2=غير مرخص
            'licensed'        => (clone $e)->where('isLicensed', 1)->count(),
            'unlicensed'      => (clone $e)->where('isLicensed', 2)->count(),
        ];

        return view('crafts.dashboard', compact('stats'));
    })->name('dashboard');
    
    // Economical Activities (Crafts)
    Route::get('/economical', [\App\Http\Controllers\Buildings\BhmEconomicalController::class, 'index'])->name('economical.index')->middleware('can:Read-Crafts');
    Route::get('/economical/{id}', [\App\Http\Controllers\Buildings\BhmEconomicalController::class, 'show'])->name('economical.show')->middleware('can:Read-Crafts');
    Route::get('/economical/{id}/edit', [\App\Http\Controllers\Buildings\BhmEconomicalController::class, 'edit'])->name('economical.edit')->middleware('can:Update-Craft');
    Route::put('/economical/{id}', [\App\Http\Controllers\Buildings\BhmEconomicalController::class, 'update'])->name('economical.update')->middleware('can:Update-Craft');

    // Crafts
    Route::get('/crafts', [\App\Http\Controllers\Crafts\BhmCraftController::class, 'index'])->name('crafts.index')->middleware('can:Read-Crafts');
});
