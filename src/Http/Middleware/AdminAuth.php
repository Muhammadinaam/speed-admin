<?php

namespace MuhammadInaamMunir\SpeedAdmin\Http\Middleware;

use Closure;

class AdminAuth
{
    public function handle($request, Closure $next)
    {
        if (!\Auth::user()) {

            if ($request->ajax()){
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }

            return redirect()->guest(route('admin.login'));
        }

        return $next($request);
    }
}
