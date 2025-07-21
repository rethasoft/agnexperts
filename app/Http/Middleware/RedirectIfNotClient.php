<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfNotClient
{
    public function handle($request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->type !== 'client') {
            return redirect('/login');
        }
        return $next($request);
    }
} 