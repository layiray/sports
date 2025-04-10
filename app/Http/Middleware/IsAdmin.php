<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class IsAdmin
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request);
        }

        return redirect()->route('show.login')->withErrors(['You do not have admin access.']);
    }
}
