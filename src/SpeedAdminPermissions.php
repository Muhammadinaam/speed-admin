<?php

namespace MuhammadInaamMunir\SpeedAdmin;

class SpeedAdminPermissions{

    protected $permissions = [];

    public function addPermission($group, $sub_group, $permission_id, $permission_label)
    {
        $permissions_collection = collect($this->permissions);

        $duplicate_permission = $permissions_collection->first(function($permission, $index) use ($permission_id) {
            return $permission['permission_id'] == $permission_id;
        });

        if ($duplicate_permission != null) {
            throw new \Exception("Duplicate permission_id not allowed. id [". $permission_id ."] already exists", 1);
        }

        array_push($this->permissions, [
            'group' => $group, 
            'sub_group' => $sub_group, 
            'permission_id' => $permission_id, 
            'permission_label' => $permission_label
        ]);
    }

    public function getPermissionLabel($permission_id)
    {
        $permissions_collection = collect($this->permissions);

        $permission = $permissions_collection->first(function($permission, $index) use ($permission_id) {
            return $permission['permission_id'] == $permission_id;
        });

        if($permission != null)
        {
            return $permission['permission_label'];
        }

        return '';
    }

    public function addModelPermissions($group, $model_class_name, $add, $edit, $delete, $list)
    {
        $model = \SpeedAdminHelpers::getModelInstance($model_class_name);

        if ($add)
        {
            $this->addPermission($group, $model->getPluralTitle(), $model->getAddPermissionId(), 'Add ' . $model->getPluralTitle());
        }

        if ($edit)
        {
            $this->addPermission($group, $model->getPluralTitle(), $model->getEditPermissionId(), 'Edit ' . $model->getPluralTitle());
        }

        if ($delete)
        {
            $this->addPermission($group, $model->getPluralTitle(), $model->getDeletePermissionId(), 'Delete ' . $model->getPluralTitle());
        }

        if ($list)
        {
            $this->addPermission($group, $model->getPluralTitle(), $model->getListPermissionId(), 'List ' . $model->getPluralTitle());
        }
    }

    public function getPermissions()
    {
        return $this->permissions;
    }
}
