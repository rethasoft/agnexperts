<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfNotTenant
{
    public function handle($request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->type !== 'tenant') {
            return redirect('/login');
        }
        return $next($request);
    }
} 