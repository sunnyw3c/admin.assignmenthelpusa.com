<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CmsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ServiceEditorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WriterController;
use Illuminate\Support\Facades\Route;

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('admin.auth');

// Protected
Route::middleware('admin.auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('/orders/{id}/assign', [OrderController::class, 'assignWriter'])->name('orders.assign');
    Route::post('/orders/{id}/payment', [OrderController::class, 'updatePayment'])->name('orders.payment');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::post('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::post('/users/{id}/role', [UserController::class, 'updateRole'])->name('users.role');

    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{id}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{id}', [MessageController::class, 'reply'])->name('messages.reply');

    // Writers
    Route::get('/writers', [WriterController::class, 'index'])->name('writers.index');
    Route::get('/writers/create', [WriterController::class, 'create'])->name('writers.create');
    Route::post('/writers', [WriterController::class, 'store'])->name('writers.store');
    Route::get('/writers/{id}/edit', [WriterController::class, 'edit'])->name('writers.edit');
    Route::post('/writers/{id}', [WriterController::class, 'update'])->name('writers.update');

    // CMS — Website Content Editor (admin only)
    Route::get('/cms', [CmsController::class, 'index'])->name('cms.index');
    Route::get('/cms/{page}/edit', [CmsController::class, 'edit'])->name('cms.edit');
    Route::post('/cms/{page}/sections', [CmsController::class, 'addSection'])->name('cms.add-section');
    Route::post('/cms/sections/{id}/update', [CmsController::class, 'updateSection'])->name('cms.update-section');
    Route::post('/cms/sections/{id}/delete', [CmsController::class, 'deleteSection'])->name('cms.delete-section');
    Route::post('/cms/sections/{id}/toggle', [CmsController::class, 'toggleSection'])->name('cms.toggle-section');
    Route::post('/cms/{page}/reorder', [CmsController::class, 'reorderSections'])->name('cms.reorder');
    Route::post('/cms/{page}/meta', [CmsController::class, 'updateMeta'])->name('cms.meta');
    Route::post('/cms/pages', [CmsController::class, 'createPage'])->name('cms.create-page');
    Route::post('/cms/pages/{slug}/delete', [CmsController::class, 'deletePage'])->name('cms.delete-page');

    // Mail
    Route::get('/mail', [MailController::class, 'index'])->name('mail.index');
    Route::post('/mail', [MailController::class, 'send'])->name('mail.send');

    // Assignment Services Editor
    Route::get('/services-editor', [ServiceEditorController::class, 'index'])->name('services-editor.index');
    Route::get('/services-editor/create', [ServiceEditorController::class, 'create'])->name('services-editor.create');
    Route::post('/services-editor', [ServiceEditorController::class, 'store'])->name('services-editor.store');
    Route::get('/services-editor/{id}/edit', [ServiceEditorController::class, 'edit'])->name('services-editor.edit');
    Route::post('/services-editor/{id}', [ServiceEditorController::class, 'update'])->name('services-editor.update');
});
