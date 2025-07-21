<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $userType): Response
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        if (auth()->user()->type !== $userType) {
            // Redirect to appropriate dashboard or show 403 error
            return redirect()->route('unauthorized');
            // Or return response()->view('errors.403', [], 403);
        }

        return $next($request);
    }
}
