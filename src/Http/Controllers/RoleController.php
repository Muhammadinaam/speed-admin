<?php

namespace MuhammadInaamMunir\SpeedAdmin\Http\Controllers;

use Illuminate\Http\Request;

class RoleController extends SpeedAdminBaseController
{
    protected $model = \MuhammadInaamMunir\SpeedAdmin\Models\Role::class;
    protected $index_url = 'admin/roles';
}