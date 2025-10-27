<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\GuardResolver;

class ResolveGuard
{
    public function __construct(private GuardResolver $ctx) {}

    public function handle($request, Closure $next)
    {
        $guard = auth()->guard('client')->check() ? 'client' : 'tenant';

        $this->ctx->set($guard);
        view()->share('guard', $guard);

        return $next($request);
    }
}


