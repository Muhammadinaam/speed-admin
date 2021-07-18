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

    public function setModelRegistry($model_class, $new_model_class)
    {
        app()->make('speed-admin-models-registry')->setRegistry($model_class, $new_model_class);
    }

    public function getModelRegistry($model_class)
    {
        return app()->make('speed-admin-models-registry')->getRegistry($model_class);
    }

    public function getModelInstance($model_class)
    {
        return app()->make('speed-admin-models-registry')->getModelInstance($model_class);
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

    public function setSettingModelRegistry($new_setting_model_class)
    {
        $modelsRegistry = app()->make('speed-admin-models-registry');

        $modelsRegistry->setRegistry(Setting::class, $new_setting_model_class);
    }

    public function getSettingModel()
    {
        return $this->getModelInstance(Setting::class);
    }
}