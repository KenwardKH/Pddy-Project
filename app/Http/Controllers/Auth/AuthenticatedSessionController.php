<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        if (Auth::check()) {
            // Redirect based on the user's role
            if (Auth::user()->role === 'kasir') {
                return redirect()->route('kasir.home');
            } elseif (Auth::user()->role === 'pemilik') {
                return redirect()->route('owner.home');
            } else {
                return redirect('/pengguna/home');
            }
        }

        return view('auth.login');
    }


    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
        if(Auth::check() && Auth::user()->role === 'kasir'){
            return redirect()->intended(route('kasir.home', absolute: false));
        } else if (Auth::check() && Auth::user()->role === 'pemilik'){
            return redirect()->intended(route('owner.home', absolute: false));
        } else{ 
        return redirect()->intended('/pengguna/home');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
