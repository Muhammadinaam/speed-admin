<?php

namespace MuhammadInaamMunir\SpeedAdmin\Facades;

use Illuminate\Support\Facades\Facade;

class SpeedAdminPermissions
{
    public function userHasPermission($user_id, $permission_slug)
    {
        return true;
    }

    public function hasPermission($permission_slug)
    {
        if(\Auth::check()) {
            return SpeedAdminPermissions::userHasPermission(\Auth::user()->id, $permission_slug);
        }
    }

    public function abortIfDontHavePermission($permission_slug)
    {
        if (! SpeedAdminPermissions::hasPermission($permission_slug) )
        {
            abort(403);
        }
    }
}
