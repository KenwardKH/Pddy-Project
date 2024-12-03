<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SetDatabaseConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // Set the database connection based on the user's role
            if ($user->role === 'admin') {
                DB::setDefaultConnection('mysql'); // Admin connection
            } elseif ($user->role === 'customer') {
                DB::setDefaultConnection('customer'); // Customer connection
            } else {
                // Default to the primary connection if the role is not specified
                DB::setDefaultConnection(config('database.default'));
            }
        }

        return $next($request);
    }
}
