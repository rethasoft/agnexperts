<?php

// app/Providers/AuthServiceProvider.php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        //
    ];

    public function boot(): void
    {
        Auth::viaRequest('tenant', function ($request) {
            if ($user = Auth::user()) {
                return $user->type === 'tenant' ? $user : null;
            }
            return null;
        });

        Auth::viaRequest('client', function ($request) {
            if ($user = Auth::user()) {
                return $user->type === 'client' ? $user : null;
            }
            return null;
        });
    }

    // Other methods...
}
