<?php

namespace MuhammadInaamMunir\SpeedAdmin\Facades;

use Illuminate\Support\Facades\Facade;

class SpeedAdminHelpers
{
    public function userHasPermission($user_id, $permission_slug)
    {
        return true;
    }

    public function hasPermission($permission_slug)
    {
        if(\Auth::check()) {
            return SpeedAdminHelpers::userHasPermission(\Auth::user()->id, $permission_slug);
        }
    }

    public function abortIfDontHavePermission($permission_slug)
    {
        if (! SpeedAdminHelpers::hasPermission($permission_slug) )
        {
            abort(403);
        }
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
}
