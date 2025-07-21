<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('*', function ($view) {
            $guard = $this->getCurrentGuard();
            
            $view->with([
                'authUser' => Auth::user(),
                'currentGuard' => $guard,
                'prefix' => $guard ?? 'guest'
            ]);
        });
    }

    protected function getCurrentGuard()
    {
        if (Auth::guard('tenant')->check()) return 'tenant';
        if (Auth::guard('client')->check()) return 'client';
        if (Auth::guard('employee')->check()) return 'employee';
        return null;
    }
} 