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
        $this->setPermissionId('role');

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
            'id' => 'permissions', 
            'title' => __('Permissions'),
            'render_function' => function ($role) {
                $html = '';
                foreach ($role->permissions as $permission) {
                    $speed_admin_permissions = app()->make('speed-admin-permissions');
                    $html .= '<span class="badge badge-primary p-1 m-1">' . 
                    $speed_admin_permissions->getPermissionLabel($permission->permission_id) . 
                    '</span>';
                }
                return $html;
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

        $this->addTenantOrganizationSelectorFormItem(null);

        $this->addFormItem([
            'id' => 'permissions',
            'parent_id' => null,
            'type' => 'custom',
            'input_processing_requires_model_save' => true,
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
        $tenant_org_name = '';

        if(\SpeedAdminHelpers::userHasAccessToAllTenantOrganizations(\Auth::user()))
        {
            $tenant_org_name = $this->tenantOrganization != null ? ' [' .$this->tenantOrganization->name. ']' : ''; 
        }

        return $this->name . $tenant_org_name;
    }

    public function getGridQuery($request)
    {
        $query = $this->with(['permissions', 'tenantOrganization'])
            ->select([
                'roles.id',
                'roles.name'
            ]);

        $query = $this->addTenantOrganizationColumnToQuery($query);

        return $query;
    }

    public function permissions()
    {
        return $this->hasMany(
            \MuhammadInaamMunir\SpeedAdmin\Models\PermissionRole::class
        );
    }
}