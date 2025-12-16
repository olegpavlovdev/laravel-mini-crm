<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WidgetController;

Route::get('/', function () {
    return redirect('/widget');
});

// Public widget for embedding
Route::get('/widget', [WidgetController::class, 'show'])->name('widget.show');

// Authentication routes
Route::get('login', [\App\Http\Controllers\AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('login', [\App\Http\Controllers\AuthController::class, 'login'])->middleware('guest');
Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin area (managers only)
Route::middleware(['auth', 'role:manager'])->prefix('admin')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.tickets.index');
    });

    Route::get('tickets', [\App\Http\Controllers\Admin\AdminTicketController::class, 'index'])->name('admin.tickets.index');
    Route::get('tickets/{ticket}', [\App\Http\Controllers\Admin\AdminTicketController::class, 'show'])->name('admin.tickets.show');
    Route::post('tickets/{ticket}/status', [\App\Http\Controllers\Admin\AdminTicketController::class, 'updateStatus'])->name('admin.tickets.updateStatus');
    Route::get('tickets/{ticket}/attachments/{media}', [\App\Http\Controllers\Admin\AdminTicketController::class, 'downloadAttachment'])->name('admin.tickets.downloadAttachment');
});
