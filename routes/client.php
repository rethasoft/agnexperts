<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KeuringenController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\Client\DashboardController;

Route::middleware('auth:client')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Client inspections (tüm CRUD işlemleri)
    Route::resource('inspections', InspectionController::class)
        ->names([
            'index' => 'client.inspections.index',
            'create' => 'client.inspections.create',
            'store' => 'client.inspections.store',
            'show' => 'client.inspections.show',
            'edit' => 'client.inspections.edit',
            'update' => 'client.inspections.update',
            'destroy' => 'client.inspections.destroy'
        ]);

    // Eski keuringen yolu client tarafında inspections listesine yönlendirilsin
    Route::get('/keuringen', function() {
        return redirect()->route('client.inspections.index');
    })->name('client.keuringen.index');
});
