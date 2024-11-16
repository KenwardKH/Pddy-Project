<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callbackGoogle()
    {
        try {
            // Retrieve user data from Google
            $google_user = Socialite::driver('google')->user();

            // Check if the user exists by google_id or email
            $user = User::where('google_id', $google_user->getId())
                        ->orWhere('email', $google_user->getEmail())
                        ->first();

            if ($user) {
                // If the user exists but doesn't have a google_id, update it
                if (is_null($user->google_id)) {
                    $user->google_id = $google_user->getId();
                    $user->save(); // Save the updated google_id to the database
                }

                // Log in the user
                Auth::login($user);

                // Redirect based on user role
                if (Auth::user()->role === 'kasir') {
                    return redirect()->intended('/kasir/home');
                } elseif (Auth::user()->role === 'pelanggan') {
                    return redirect()->intended('/pengguna/home');
                } elseif (Auth::user()->role === 'pemilik') {
                    return redirect()->intended('/pemilik/home');
                }
            } else {
                // If the user does not exist, show an error
                return redirect('/login')->withErrors(['email' => 'This email is not registered with Google login.']);
            }
        } catch (\Throwable $th) {
            // Catch any exceptions and show an error message
            return redirect('/login')->withErrors(['email' => 'Something went wrong! ' . $th->getMessage()]);
        }
    }

}
