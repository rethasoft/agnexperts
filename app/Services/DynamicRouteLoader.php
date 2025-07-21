<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class DynamicRouteLoader
{
    public static function loadRoutes()
    {
        dd(auth()->user());
        if (Auth::guard('tenant')->check()) {
            Route::middleware(['web', 'auth:tenant'])
                ->prefix('app/tenant')
                ->group(base_path('routes/tenant.php'));
        } elseif (Auth::guard('client')->check()) {
            Route::middleware(['web', 'auth:client'])
                ->prefix('app/client')
                ->group(base_path('routes/client.php'));
        }
    }
}
