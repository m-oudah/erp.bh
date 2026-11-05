<?php

use App\Http\Controllers\Correspondence\MemoController;
use App\Http\Controllers\Correspondence\NotificationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('correspondence')->name('correspondence.')->group(function () {
    // Internal Correspondence (المراسلات الداخلية)
    Route::get('/', [MemoController::class, 'index'])->name('index');
    Route::get('/memos', [MemoController::class, 'index'])->name('memos.index');
    Route::get('/memos/create', [MemoController::class, 'create'])->name('memos.create');
    Route::post('/memos', [MemoController::class, 'store'])->name('memos.store');
    Route::get('/memos/{memo}', [MemoController::class, 'show'])->name('memos.show');
    Route::get('/memos/{memo}/forward', [MemoController::class, 'forwardPage'])->name('memos.forward-page');
    Route::post('/memos/{memo}/forward', [MemoController::class, 'forward'])->name('memos.forward');
    Route::post('/memos/{memo}/close', [MemoController::class, 'close'])->name('memos.close');
    Route::post('/memos/{memo}/attachments', [MemoController::class, 'addAttachment'])->name('memos.attachments.store');
    Route::get('/attachments/{attachment}/download', [MemoController::class, 'downloadAttachment'])->name('memos.attachments.download');
});

// Notifications
Route::middleware(['auth'])->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/unread', [NotificationController::class, 'getUnread'])->name('unread');
    Route::get('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
    Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('markAllRead');
});
