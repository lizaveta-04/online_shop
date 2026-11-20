<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ApplicationController;

/*
|--------------------------------------------------------------------------
| Маршруты админ-панели
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Дашборд
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Товары (ресурсный контроллер, удалённые записи доступны для просмотра и правки)
    Route::resource('products', ProductController::class)
        ->withTrashed(['show', 'edit', 'update']);

    // Категории (ресурсный контроллер)
    Route::resource('categories', CategoryController::class)
        ->except(['show'])
        ->withTrashed(['edit', 'update']);

    // Заказы
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Заявки
    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::patch('/applications/{application}/status', [ApplicationController::class, 'updateStatus'])->name('applications.status');
});
