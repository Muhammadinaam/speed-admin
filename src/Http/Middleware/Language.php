<?php

namespace MuhammadInaamMunir\SpeedAdmin\Http\Middleware;

use Closure;

class Language
{
    public function handle($request, Closure $next)
    {
        $locale = session('current_locale', 'en');
        \App::setLocale($locale);

        $languages = config('speed-admin.languages');
        $current_language = collect($languages)->first(function($language) {
            return $language['locale'] == \App::currentLocale();
        });

        $is_rtl = $current_language != null && $current_language['rtl'] == true;
        
        view()->share('is_rtl', $is_rtl);

        return $next($request);
    }
}
