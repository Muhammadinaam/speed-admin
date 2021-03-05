<?php

namespace MuhammadInaamMunir\SpeedAdmin\Models;

use App\Models\User as BaseUser;
use MuhammadInaamMunir\SpeedAdmin\Traits\Crud;

class User extends BaseUser{
    
    use Crud;

    public function __construct()
    {
        parent::__construct();

        $this->setSingularTitle('User');
        $this->setPluralTitle('Users');

        $this->addGridColumn([
            'id' => 'picture', 
            'title' => __('Picture'), 
            'field' => ['name' => 'picture', 'type' => 'image', 'ordering_disabled' => true],
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