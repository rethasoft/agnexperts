<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class AppController extends Controller
{
    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showLoginForm()
    {
        // Eğer kullanıcı zaten giriş yapmışsa, tipine göre yönlendir
        if (Auth::check()) {
            return $this->redirectBasedOnUserType(Auth::user());
        }

        return view('app.auth.login');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function authenticate(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            $user = Auth::getProvider()->retrieveByCredentials($credentials);

            if (!$user || !Hash::check($credentials['password'], $user->password)) {
                return back()->withErrors([
                    'email' => __('validation.custom.auth_credential_error'),
                ]);
            }            
            // Auth::login($user);
            Auth::guard($user->type)->login($user);
            
            $request->session()->regenerate();

            return $this->redirectBasedOnUserType($user);

        } catch (\Throwable $th) {
            dd($th);
            return back()->withErrors(['msg' => 'Error occurred during login']);
        }
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $guard = Auth::getDefaultDriver();
        
        Auth::guard($guard)->logout();
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Redirect user based on their type.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectBasedOnUserType($user)
    {
        return redirect()->intended('/app/' . $user->type . '/dashboard');
    }
}
