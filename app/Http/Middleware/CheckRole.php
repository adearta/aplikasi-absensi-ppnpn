<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, String $role): Response
    {
        if(!Auth::check() || Auth::user()->role !== $role) {
            return redirect('/loginauth');
        }
        return $next($request);
    }
}
