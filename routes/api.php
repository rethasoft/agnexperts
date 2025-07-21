<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Client;
use App\Http\Controllers\Api\V1\CalendarController;
use App\Http\Controllers\Api\V1\EventController;
use App\Http\Controllers\Api\V1\EmployeeController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('clients', function () {
    return response()->json(['data' => Client::all()]);
});
Route::get('client/{id}', function ($id) {
    return response()->json(['data' => Client::find($id)]);
});

Route::prefix('v1')->group(function () {
    Route::get('/calendar/events', [CalendarController::class, 'events'])
        // ->middleware(['auth:tenant,employee'])
        ->name('api.calendar.events');

    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('events', EventController::class)
        ->parameters([
            'events' => 'event'
        ])
        ->names([
            'index' => 'api.events.index',
            'store' => 'api.events.store',
            'show' => 'api.events.show',
            'update' => 'api.events.update',
            'destroy' => 'api.events.destroy',
        ]);

    Route::get('events/check-availability', [EventController::class, 'checkAvailability'])
        ->name('api.events.check-availability');

});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
