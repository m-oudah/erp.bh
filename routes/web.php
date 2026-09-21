<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Archive Module Routes
    Route::prefix('archive')->name('archive.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Archive\ArchiveController::class, 'index'])->name('index');
        Route::resource('files', \App\Http\Controllers\Archive\ArchiveFileController::class);
        Route::resource('documents', \App\Http\Controllers\Archive\ArchiveDocumentController::class);
    });

    // System Settings Routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Settings\SystemSettingsController::class, 'index'])->name('index');
        Route::resource('archive-types', \App\Http\Controllers\Settings\ArchiveSettingsController::class);
    });
});

require __DIR__.'/auth.php';
