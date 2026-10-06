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
        
        // Reports Routes
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Archive\ArchiveReportController::class, 'index'])->name('index');
            Route::get('/export', [\App\Http\Controllers\Archive\ArchiveReportController::class, 'export'])->name('export');
        });
        
        // Trash Routes
        Route::prefix('trash')->name('trash.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Archive\ArchiveTrashController::class, 'index'])->name('index');
            Route::post('/files/{id}/restore', [\App\Http\Controllers\Archive\ArchiveTrashController::class, 'restoreFile'])->name('files.restore');
            Route::delete('/files/{id}/force', [\App\Http\Controllers\Archive\ArchiveTrashController::class, 'forceDeleteFile'])->name('files.forceDelete');
            Route::post('/documents/{id}/restore', [\App\Http\Controllers\Archive\ArchiveTrashController::class, 'restoreDocument'])->name('documents.restore');
            Route::delete('/documents/{id}/force', [\App\Http\Controllers\Archive\ArchiveTrashController::class, 'forceDeleteDocument'])->name('documents.forceDelete');
        });

        Route::delete('documents/bulk-destroy', [\App\Http\Controllers\Archive\ArchiveDocumentController::class, 'bulkDestroy'])->name('documents.bulk-destroy');
        Route::resource('files', \App\Http\Controllers\Archive\ArchiveFileController::class);
        Route::resource('documents', \App\Http\Controllers\Archive\ArchiveDocumentController::class);
    });

    // System Settings Routes
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Settings\SystemSettingsController::class, 'index'])->name('index');

        // Users Management
        Route::resource('users', \App\Http\Controllers\Settings\UserManagementController::class)->only(['index', 'edit', 'update']);

        // Archive Module Settings
        Route::prefix('archive')->name('archive.')->group(function () {
            Route::resource('document-categories', \App\Http\Controllers\Settings\ArchiveDocumentCategoryController::class)->except(['show']);
            
            Route::get('activity-log', [\App\Http\Controllers\Settings\ArchiveActivityLogController::class, 'index'])->name('activity-log');
            
            Route::get('user-permissions', [\App\Http\Controllers\Settings\ArchiveUserPermissionsController::class, 'index'])->name('user-permissions.index');
            Route::post('user-permissions/{user}', [\App\Http\Controllers\Settings\ArchiveUserPermissionsController::class, 'update'])->name('user-permissions.update');
            
            
            
            Route::resource('/', \App\Http\Controllers\Settings\ArchiveSettingsController::class)->parameters(['' => 'archive_type'])->except(['show'])->names([
                'index' => 'types.index',
                'create' => 'types.create',
                'store' => 'types.store',
                'edit' => 'types.edit',
                'update' => 'types.update',
                'destroy' => 'types.destroy',
            ]);
        });
    });
});

require __DIR__.'/auth.php';
require __DIR__.'/buildings.php';
