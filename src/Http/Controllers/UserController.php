<?php

namespace MuhammadInaamMunir\SpeedAdmin\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends SpeedAdminBaseController
{
    protected $model = \MuhammadInaamMunir\SpeedAdmin\Models\User::class;
    protected $index_url = 'admin/users';
}