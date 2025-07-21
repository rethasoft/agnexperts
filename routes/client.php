<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KeuringenController;
use App\Http\Controllers\Client\DashboardController;

Route::middleware('auth:client')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    // Resources
    Route::resource('keuringen', KeuringenController::class)
        ->names([
            'index' => 'client.keuringen.index',
            'create' => 'client.keuringen.create',
            'store' => 'client.keuringen.store',
            'show' => 'client.keuringen.show',
            'edit' => 'client.keuringen.edit',
            'update' => 'client.keuringen.update',
            'destroy' => 'client.keuringen.destroy'
        ]);
});
