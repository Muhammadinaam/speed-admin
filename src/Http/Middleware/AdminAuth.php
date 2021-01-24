<?php

namespace MuhammadInaamMunir\SpeedAdmin\Http\Middleware;

use Closure;

class AdminAuth
{
    public function handle($request, Closure $next)
    {
        if (!\Auth::user()) {
            return redirect(route('admin.login'));
        }

        return $next($request);
    }
}
