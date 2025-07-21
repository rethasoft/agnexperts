<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class ClientController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }
    public function loginCheck(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (auth('client')->attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->intended('/');
            }
            return back()->withErrors([
                'email' => __('validation.custom.auth_credential_error'),
            ])->onlyInput('email');
        } catch (\Throwable $th) {
            return back()->withErrors(['msg' => 'Error']);
        }
    }
    public function logout(Request $request): RedirectResponse
    {
        auth('client')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
