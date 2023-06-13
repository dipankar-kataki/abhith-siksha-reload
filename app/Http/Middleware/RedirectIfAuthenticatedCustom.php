<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticatedCustom
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next,  $guard = null)
    {
        if (Auth::guard($guard)->check() ) {
            if(Auth::user()->type_id == 1){
                return redirect('admin/dashboard');
            }
        }
        return $next($request);
    }
}
