<?php

namespace MuhammadInaamMunir\SpeedAdmin\Models;

use Illuminate\Database\Eloquent\Model;
use MuhammadInaamMunir\SpeedAdmin\Traits\Crud;
use MuhammadInaamMunir\SpeedAdmin\Traits\UsesUuid;
use MuhammadInaamMunir\SpeedAdmin\Misc\GridHelper;
use MuhammadInaamMunir\SpeedAdmin\Traits\TenantOrganization;

class Role extends Model{
    
    use Crud, UsesUuid, TenantOrganization;

    protected $appends = ['text'];

    public function __construct()
    {
        parent::__construct();

        $this->setSingularTitle('Role');
        $this->setPluralTitle('Roles');
        $this->setPermissionSlug('role');

        $this->addGridColumn([
            'id' => 'name', 
            'title' => __('Name'),
            'order_by' => 'roles.name', 
            'search_by' => 'roles.name', 
            'render_function' => function ($role) {
                return $role->name;
            }
        ]);

        $this->addGridColumn([
            'id' => 'level', 
            'title' => __('Level'),
            'order_by' => 'roles.level', 
            'search_by' => 'roles.level', 
            'render_function' => function ($role) {
                return $role->level;
            }
        ]);

        $this->addTenantOrganizationGridColumn();

        $this->addFormItem([
            'id' => 'name',
            'parent_id' => null,
            'type' => 'text',
            'validation_rules' => ['name' => 'required|unique:roles,name,{{$id}}'],
            'label' => __('Name'),
            'name' => 'name'
        ]);

        $this->addFormItem([
            'id' => 'level',
            'parent_id' => null,
            'type' => 'decimal',
            'validation_rules' => ['level' => 'required|integer'],
            'label' => __('Level'),
            'name' => 'level'
        ]);

        $this->addTenantOrganizationSelectorFormItem(null);

        $this->addFormItem([
            'id' => 'permissions',
            'parent_id' => null,
            'type' => 'custom',
            'view_path' => 'speed-admin::components.form_components.permissions',
            'input_processor_classpath' => 'MuhammadInaamMunir\SpeedAdmin\Misc\FormInputProcessors\Permission',
            'options' => [
                'label' => __('Custom'),
                'name' => 'custom',
            ]
        ]);
    }

    public function getTextAttribute()
    {
        return $this->name;
    }

    public function getGridQuery($request)
    {
        return $this;
    }
}