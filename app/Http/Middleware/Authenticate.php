<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Closure;

class Authenticate extends Middleware
{

    public function handle($request, Closure $next, ...$guards)
    {
        // Guard belirtilmemişse config/auth.php'den default guard'ı kullanır

        if (empty($guards)) {
            $guards = [null];
        }

        foreach ($guards as $guard) {
            // Guard ile giriş yapılmış mı kontrol eder
            if (Auth::guard($guard)->check()) {
                Auth::shouldUse($guard); // Use current guard
                return $next($request);
            }
        }

        // Giriş yapılmamışsa login sayfasına yönlendirir
        return redirect()->guest(route('login'));
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }
}
