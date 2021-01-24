<?php

namespace MuhammadInaamMunir\SpeedAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class LanguageController extends BaseController
{
    public function selectLanguage()
    {
        $locale = request()->locale;

        session(['current_locale' => $locale]);

        $return_url = request()->return_url != '' && request()->return_url != null ? 
        request()->return_url : 
        config('speed-admin.admin_url');

        if( \str_contains($return_url, 'select-language') ) {
            $return_url = config('speed-admin.admin_url');
        }

        return redirect($return_url);
    }
}