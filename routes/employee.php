<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    InspectionController,
    ClientController,
    StatusController,
    TypeController,
    AgendaController
};

use App\Http\Controllers\Employee\DashboardController;

Route::middleware(['auth:employee'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // Resources
    // Route::resource('inspections', InspectionController::class)->names('employee.inspections');
    Route::resource('client', ClientController::class)->names('employee.client')->except(['destroy']);
    Route::resource('status', StatusController::class)->names('employee.status')->except(['destroy']);
    Route::resource('dienst', TypeController::class)->names('employee.dienst');

    // Route::resource('/agenda', AgendaController::class)->names('employee.agenda');
}); 