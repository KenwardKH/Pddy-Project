<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\SetDatabaseConnection;
use DB;
class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (Auth::check() && Auth::user()->role === 'pelanggan') {
            DB::setDefaultConnection('customer'); 
            return $next($request);
        }
        else if(Auth::check() && Auth::user()->role === 'kasir'){
            DB::setDefaultConnection('cashier'); 
            return $next($request);
        }
        else if(Auth::check() && Auth::user()->role === 'owner'){
            DB::setDefaultConnection('owner'); 
            return $next($request);
        }
        return redirect()->intended('/dashboard');
    }
}
