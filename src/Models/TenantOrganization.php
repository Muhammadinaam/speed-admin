<?php

namespace MuhammadInaamMunir\SpeedAdmin\Models;

use Illuminate\Database\Eloquent\Model;
use MuhammadInaamMunir\SpeedAdmin\Traits\Crud;
use MuhammadInaamMunir\SpeedAdmin\Traits\UsesUuid;
use MuhammadInaamMunir\SpeedAdmin\Misc\GridHelper;

class TenantOrganization extends Model{
    
    use Crud, UsesUuid;

    protected $appends = ['text'];

    public function __construct()
    {
        parent::__construct();

        $this->setSingularTitle('Tenant Organization');
        $this->setPluralTitle('Tenant Organizations');
        $this->setPermissionId('tenant-organizations');

        $this->addGridColumn([
            'id' => 'name', 
            'title' => __('Name'),
            'order_by' => 'tenant_organizations.name', 
            'search_by' => 'tenant-organizations.name', 
            'render_function' => function ($org) {
                return $org->name;
            }
        ]);

        $this->addFormItem([
            'id' => 'name',
            'parent_id' => null,
            'type' => 'text',
            'validation_rules' => ['name' => 'required|unique:roles,name,{{$id}}'],
            'label' => __('Name'),
            'name' => 'name'
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