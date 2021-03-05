<?php

namespace MuhammadInaamMunir\SpeedAdmin\Facades;

use Illuminate\Support\Facades\Facade;

class SpeedAdminPermissionsFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'SpeedAdminPermissions';
    }
}
