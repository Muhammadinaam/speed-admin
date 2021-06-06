<?php

namespace MuhammadInaamMunir\SpeedAdmin\Models;

use App\Models\User as BaseUser;
use MuhammadInaamMunir\SpeedAdmin\Traits\Crud;
use MuhammadInaamMunir\SpeedAdmin\Traits\TenantOrganization;
use MuhammadInaamMunir\SpeedAdmin\Misc\GridHelper;
use Illuminate\Validation\ValidationException;

class User extends BaseUser{
    
    use Crud, TenantOrganization;
    
    protected $appends = ['text'];

    public function __construct()
    {
        parent::__construct();

        $this->setSingularTitle('User');
        $this->setPluralTitle('Users');
        $this->setPermissionId('user');

        $this->addGridColumn([
            'id' => 'picture', 
            'title' => __('Picture'), 
            'order_by' => 'users.picture',
            'render_function' => function ($user) {
                return $user->picture != '' ? GridHelper::renderImage($user->picture) : '';
            }
        ]);
        $this->addGridColumn([
            'id' => 'name', 
            'title' => __('Name'),
            'order_by' => 'users.name', 
            'search_by' => 'users.name', 
            'render_function' => function ($user) {
                return $user->user_name;
            }
        ]);
        $this->addGridColumn([
            'id' => 'email', 
            'title' => __('Email'),
            'order_by' => 'users.email', 
            'search_by' => 'users.email', 
            'render_function' => function ($user) {
                return $user->email;
            }
        ]);
        $this->addGridColumn([
            'id' => 'roles', 
            'title' => __('Roles'),
            'search_by' => 'roles.name', 
            'render_function' => function ($user) {
                $html = '';
                foreach ($user->roles as $role) {
                    $html .= '<span class="badge badge-primary p-1 m-1">'.$role->text.'</span>';
                }
                return $html;
            }
        ]);
        $this->addGridColumn([
            'id' => 'is_superadmin', 
            'title' => __('Superadmin'), 
            'order_by' => 'users.is_superadmin', 
            'search_by' => 'users.is_superadmin', 
            'render_function' => function ($user) {
                return GridHelper::renderBoolean($user->is_superadmin);
            }
        ]);

        $this->addTenantOrganizationGridColumn();

        if(config('speed-admin.enable_tenant_organization_feature')) {
            $this->addGridColumn([
                'id' => 'is_tenant_organization_admin', 
                'title' => __('Tenant Organization Admin'), 
                'order_by' => 'users.is_tenant_organization_admin', 
                'search_by' => 'users.is_tenant_organization_admin', 
                'render_function' => function ($user) {
                    return GridHelper::renderBoolean($user->is_tenant_organization_admin);
                }
            ]);
        }

        $this->addGridColumn([
            'id' => 'is_active', 
            'title' => __('Active'),
            'order_by' => 'products.is_active',
            'render_function' => function ($product) {
                return GridHelper::renderBoolean($product->is_active);
            }
        ]);

        $this->addFormItem([
            'id' => 'main-row',
            'type' => 'div',
            'class' => 'row'
        ]);
        $this->addFormItem([
            'id' => 'left-col',
            'parent_id' => 'main-row',
            'type' => 'div',
            'class' => 'col-md-4'
        ]);
        $this->addFormItem([
            'id' => 'right-col',
            'parent_id' => 'main-row',
            'type' => 'div',
            'class' => 'col-md-8'
        ]);

        $this->addFormItem([
            'id' => 'picture',
            'parent_id' => 'left-col',
            'type' => 'image',
            'label' => __('Picture'),
            'name' => 'picture',
            'upload_path' => 'users',
            'validation_rules' => ['picture' => 'required|image|max:2048'],
        ]);

        // $this->addFormItem([
        //     'id' => 'custom',
        //     'parent_id' => 'right-col',
        //     'type' => 'custom',
        //     'view_path' => 'components.form_components.custom',
        //     'input_processor_classpath' => '\App\InputProcessors\custom::class'
        //     'options' => [
        //         'label' => __('Custom'),
        //         'name' => 'custom',
        //     ]
        // ]);

        $this->addFormItem([
            'id' => 'name',
            'parent_id' => 'right-col',
            'type' => 'text',
            'validation_rules' => ['name' => 'required|unique:users,name,{{$id}}'],
            'label' => __('Name'),
            'name' => 'name'
        ]);

        $this->addFormItem([
            'id' => 'email',
            'parent_id' => 'right-col',
            'type' => 'text',
            'validation_rules' => ['email' => 'required|email|unique:users,email,{{$id}}'],
            'label' => __('Email'),
            'name' => 'email'
        ]);

        $this->addFormItem([
            'id' => 'password',
            'parent_id' => 'right-col',
            'type' => 'password',
            'validation_rules' => ['password' => 'required|confirmed'],
            'update_validation_rules' => ['password' => 'confirmed'],
            'label' => __('Password'),
            'name' => 'password',
            'update_help' => __('Leave empty if you don\'t want to change password'),
            'dont_update_if_empty' => true,
            'password_confirmation_options' => [
                'show' => true,
                'label' => __('Password Confirmation')
            ]
        ]);

        $this->addFormItem([
            'id' => 'roles',
            'parent_id' => 'right-col',
            'type' => 'belongsToMany',
            'relation_name' => 'roles',
            'model' => '\MuhammadInaamMunir\SpeedAdmin\Models\Role',
            // 'where' => function($query){
            //     return $query;
            // },
            'show_select_from_table_button' => false,
            'show_add_new_button' => true,
            'validation_rules' => ['roles' => 'array'],
            'label' => __('Roles'),
            'name' => 'roles'
        ]);

        $this->addTenantOrganizationSelectorFormItem('right-col');

        if(config('speed-admin.enable_tenant_organization_feature')) {
            $this->addFormItem([
                'id' => 'is_tenant_organization_admin',
                'parent_id' => 'right-col',
                'type' => 'checkbox',
                'label' => __('Tenant Organization Admin'),
                'name' => 'is_tenant_organization_admin',
                'is_visible' => function() {
                    $user = \SpeedAdminHelpers::currentUser();
                    return \SpeedAdminHelpers::userHasAccessToAllTenantOrganizations($user);
                }
            ]);
        }

        $this->addFormItem([
            'id' => 'is_superadmin',
            'parent_id' => 'right-col',
            'type' => 'checkbox',
            'label' => __('Super Admin'),
            'name' => 'is_superadmin',
        ]);

        $this->addFormItem([
            'id' => 'is_active',
            'parent_id' => 'right-col',
            'type' => 'checkbox',
            'label' => __('Active'),
            'name' => 'is_active',
        ]);
    }

    public function getTextAttribute()
    {
        return $this->name;
    }

    public function getGridQuery($request)
    {
        $query = $this->with(['roles', 'tenantOrganization'])
            ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
            ->leftJoin('roles', 'roles.id', '=', 'role_user.role_id')
            ->select([
                'users.id',
                'users.picture',
                'users.name as user_name',
                'users.email',
                'users.is_superadmin',
                'users.is_tenant_organization_admin',
                'users.is_active',
                'tenant_organizations.name as tenant_organizations_name',
            ])
            ->groupBy(
                'users.id',
                'users.picture',
                'users.name',
                'users.email',
                'users.is_superadmin',
                'users.is_tenant_organization_admin',
                'users.is_active',
                'tenant_organizations.name'
            );

        $query = $this->addTenantOrganizationColumnToQuery($query);

        return $query;
    }

    public function beforeSave($request, $model, $id)
    {
        $this->roleTenantAndUserTenantShouldBeSame($request, $model, $id);
    }

    private function roleTenantAndUserTenantShouldBeSame($request, $model, $id)
    {
        $tenant_organization_id = $request->tenant_organization;
        $roles_ids = $request->roles;

        if ($roles_ids == null)
        {
            return;
        }

        $roles = Role::whereIn('id', $roles_ids)->get();

        $is_tenant_org_of_roles_and_user_same = true;
        foreach ($roles as $role) {
            if ($role->tenant_organization_id != $tenant_organization_id)
            {
                $is_tenant_org_of_roles_and_user_same = false;
                break;
            }
        }

        if ( ! $is_tenant_org_of_roles_and_user_same) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'roles' => __('Tenant organization of roles is not same as tenant organization of user')
            ]);
        }
    }

    // relations

    public function roles()
    {
        return $this->belongsToMany(\MuhammadInaamMunir\SpeedAdmin\Models\Role::class);
    }
}