<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if( !auth()->guard('client')->check() || !auth()->guard('tenant')->check() || !auth()->guard('employee')->check() ){
            return redirect()->route('login', ['guard' => 'client']);
        }
        if (auth()->guard('tenant')->check()){
            return redirect('/tenant');
        }
        if (auth()->guard('client')->check()){
            return redirect('/client');
        }

        if (auth()->guard('employe')->check()){
            return redirect('/employe');
        }
        return $next($request);
    }
}
