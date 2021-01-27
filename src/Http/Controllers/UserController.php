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
            'field' => ['name' => 'picture', 'type' => 'image'], 
        ]);
        $this->addGridColumn([
            'id' => 'name', 
            'title' => __('Name'), 
            'field' => ['name' => 'name', 'type' => 'string'],
        ]);
        $this->addGridColumn([
            'id' => 'email', 
            'title' => __('Email'), 
            'field' => ['name' => 'email', 'type' => 'string'],
        ]);
        $this->addGridColumn([
            'id' => 'is_superadmin', 
            'title' => __('Superadmin'), 
            'field' => ['name' => 'is_superadmin', 'type' => 'boolean'],
        ]);
        $this->addGridColumn([
            'id' => 'is_active', 
            'title' => __('Active'), 
            'field' => ['name' => 'is_active', 'type' => 'boolean'],
        ]);
    }
}