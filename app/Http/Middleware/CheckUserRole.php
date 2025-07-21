<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Use the dynamic guard name from the request
        $guard = $request->route('guard');

        if ($guard == 'tenants' && !auth()->guard('tenants')->check()) {
            // Handle logic for tenants guard
            return route('login', ['guard' => 'tenants']);
            // Example: $user = auth()->guard($guard)->user();
        } elseif ($guard == 'clients') {
            // Handle logic for clients guard
            return route('login', ['guard' => 'clients']);
            // Example: $user = auth()->guard($guard)->user();
        } else {
            // Handle other cases or throw an exception
            return route('login', ['guard' => 'clients']);
        }

        return $next($request);
    }
}
