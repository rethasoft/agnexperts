<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Session\TokenMismatchException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }


    public function render($request, Throwable $exception)
    {

        
        if ($exception instanceof TokenMismatchException) {
            $guard = $request->route('guard');
            // Check if user is logged in as a tenant or client
            if ($guard === 'tenant' && auth()->guard('tenant')->check()) {
                // Tenant is logged in, redirect to tenant login page
                return Redirect::guest(route('login', ['guard' => 'tenant']));
            } elseif ($guard === 'client' && auth()->guard('client')->check()) {
                // Client is logged in, redirect to client login page
                return Redirect::guest(route('login', ['guard' => 'client']));
            }
        }
        return parent::render($request, $exception);
    }
}
