<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class DynamicRouteLoader
{
    public static function loadRoutes()
    {
        if (Auth::guard('tenant')->check()) {
            Route::middleware(['web', 'auth:tenant'])
                ->prefix('app/tenant')
                ->group(base_path('routes/tenant.php'));
        } elseif (Auth::guard('client')->check()) {
            Route::middleware(['web', 'auth:client'])
                ->prefix('app/client')
                ->group(base_path('routes/client.php'));
        } 
        // elseif (Auth::guard('employee')->check()) {
        //     Route::middleware(['web', 'auth:employee'])
        //         ->prefix('app/employee')
        //         ->group(base_path('routes/employee.php'));
        // }
    }
}
