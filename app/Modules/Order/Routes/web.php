<?php

use App\Modules\Order\Controllers\OrderController;
use Illuminate\Support\Facades\Route;


Route::prefix('keuring-aanvragen')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('order.index');
    Route::post('/create', [OrderController::class, 'store'])->name('order.store');
    
    // AJAX routes
    Route::post('/validate-coupon', [OrderController::class, 'validateCoupon'])->name('order.validateCoupon');
    Route::post('/check-combi-discount', [OrderController::class, 'checkCombiDiscount'])->name('order.checkCombiDiscount');
    Route::get('/location/{location}/services', [OrderController::class, 'getServices'])
        ->name('order.services');
    Route::get('/service/{service}/subservices', [OrderController::class, 'getSubServices'])
        ->name('order.subservices');
    Route::post('/submit', [OrderController::class, 'store'])->name('order.submit');
});
