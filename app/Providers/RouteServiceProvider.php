<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            // API Routes
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            // Client Routes
            Route::middleware(['web', 'auth:client'])
                ->prefix('app/client')
                ->group(base_path('routes/client.php'));
            // Tenant Routes
            Route::middleware(['web', 'auth:tenant'])
                ->prefix('app/tenant')
                ->group(base_path('routes/tenant.php'));


            // Employee Routes
            Route::middleware(['web', 'auth:employee'])
                ->prefix('app/employee')
                ->group(base_path('routes/employee.php'));

            // Web Routes
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('web')
                ->group(base_path('routes/shared.php'));
        });

        // Modules rotalarını kaydet
        $this->registerModuleRoutes();
    }

    /**
     * Register routes from all modules
     */
    protected function registerModuleRoutes(): void
    {
        $modulesPath = app_path('Modules');

        // Check if Modules directory exists
        if (!File::exists($modulesPath)) {
            return;
        }

        // Get all modules
        $modules = File::directories($modulesPath);

        foreach ($modules as $modulePath) {
            $moduleName = basename($modulePath);

            // Register web routes if they exist
            $webRoutePath = "$modulePath/Routes/web.php";
            if (File::exists($webRoutePath)) {
                Route::middleware('web')
                    ->namespace("App\\Modules\\$moduleName\\Controllers")
                    ->group($webRoutePath);
            }

            // Register API routes if they exist
            $apiRoutePath = "$modulePath/Routes/api.php";
            if (File::exists($apiRoutePath)) {
                Route::middleware('api')
                    ->prefix('api')
                    ->namespace("App\\Modules\\$moduleName\\Controllers")
                    ->group($apiRoutePath);
            }
        }
    }
}
