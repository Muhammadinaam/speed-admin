<?php

namespace MuhammadInaamMunir\SpeedAdmin\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends SpeedAdminBaseController
{
    protected $singular_title = 'User';
    protected $plural_title = 'Users';
    protected $model = \App\Models\User::class;

    public function __construct()
    {
        parent::__construct();

        $this->addGridColumn([
            'id' => 'picture', 
            'title' => __('Picture'), 
            'field' => 'picture', 
            'type' => 'image'
        ]);
        $this->addGridColumn(['id' => 'name', 'title' => __('Name'), 'field' => 'name',]);
        $this->addGridColumn(['id' => 'email', 'title' => __('Email'), 'field' => 'email']);
        $this->addGridColumn([
            'id' => 'is_superadmin', 
            'title' => __('Superadmin'), 
            'field' => 'is_superadmin',
            'type' => 'boolean' 
        ]);
        $this->addGridColumn([
            'id' => 'is_active', 
            'title' => __('Active'), 
            'field' => 'is_active',
            'type' => 'boolean' 
        ]);
    }
}