<?php

namespace MuhammadInaamMunir\SpeedAdmin\Facades;

use Illuminate\Support\Facades\Facade;
use MuhammadInaamMunir\SpeedAdmin\Models\Setting;

class SpeedAdminHelpers
{
    public function userHasPermission($user_id, $permission_id)
    {
        return true;
    }

    public function hasPermission($permission_id)
    {
        if(\Auth::check()) {
            return SpeedAdminHelpers::userHasPermission(\Auth::user()->id, $permission_id);
        }
    }

    public function abortIfDontHavePermission($permission_id)
    {
        if (! SpeedAdminHelpers::hasPermission($permission_id) )
        {
            abort(403);
        }
    }

    public function abortIfDontHavePermissionByTenant($obj)
    {
        return $obj->tenant_organization_id == \Auth::user()->tenant_organization_id;
    }

    public function userHasAccessToAllTenantOrganizations($user)
    {
        return $user->is_superadmin || $user->tenant_organization_id == null;
    }

    public function currentUser()
    {
        $id = \Auth::user()->id;
        $model = $this->getModelInstance(\App\Models\User::class);
        return $model->find($id);
    }

    public function createTenantOrganizationForeignKey($table)
    {
        $table->foreignUuid('tenant_organization_id')->nullable()->constrained('tenant_organizations');
    }

    public function createdByUpdatedByMigrations($table)
    {
        if(config('speed-admin.user_primary_key_type') == 'integer')
        {
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
        }
        else if(config('speed-admin.user_primary_key_type') == 'uuid')
        {
            $table->foreignUuid('created_by')->nullable()->constrained('users');
            $table->foreignUuid('updated_by')->nullable()->constrained('users');
        }
        else
        {
            throw new \Exception("config value for [speed-admin.user_primary_key_type] is set to ["
            . config('speed-admin.user_primary_key_type')
            . "]. It should be integer or uuid", 1);
            
        }
    }

    public function getModelInstance($model_class)
    {
        return app()->make('speed-admin-models-register')->getModelInstance($model_class);
    }

    public function getLocaleSuffixAndValue($data)
    {
        $locale_suffix = '';
        $value = '';
        $obj = $data['obj'];
        $locale = $data['locale'];
        $form_item = $data['form_item'];

        if($obj != null)
        {
            $value = $obj->translatable ? 
                $obj->getTranslation($form_item['name'], config('speed-admin.default_model_locale')) : 
                $obj->{$form_item['name']};
        }

        if($locale != null)
        {
            $locale_suffix = '_'.$locale;

            if(isset($obj))
            {
                $value = $obj->getTranslation($form_item['name'], $locale);
            }
        }

        return compact(['locale_suffix', 'value']);
    }

    public function registerModelChildClass($model_class, $model_child_class)
    {
        return app()->make('speed-admin-models-register')
            ->registerModelChildClass($model_class, $model_child_class);
    }

    public function getModelChildClasses($model_class)
    {
        return app()->make('speed-admin-models-register')
            ->getModelChildClasses($model_class);
    }

    public function getSettingModel()
    {
        return $this->getModelInstance(Setting::class);
    }

    public function getLatestParentClass($parent_class, $namespace, $current_class_name, $latest_parent_class_alias_name)
    {
        app()->make('speed-admin-models-register')
            ->getLatestParentClass($parent_class, $namespace, $current_class_name, $latest_parent_class_alias_name);
    }
}