<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\InspectionController;

Route::middleware(['auth:tenant,employee'])->group(function () {
    // URL'deki prefix'i ve route name'deki prefix'i otomatik olarak belirle
    $prefix = auth()->guard('tenant')->check() ? 'tenant' : 'employee';
    
    Route::prefix($prefix)->name("$prefix.")->group(function () {
        // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');
        Route::get('/inspections', [InspectionController::class, 'index'])->name('inspections.index');
        // ... diğer ortak route'lar
    });
}); 