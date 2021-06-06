<?php

namespace MuhammadInaamMunir\SpeedAdmin\Misc\FormInputProcessors;

use Illuminate\Support\Facades\Storage;
use MuhammadInaamMunir\SpeedAdmin\Misc\FormHelper;

class Permission extends BaseInputProcessor{

    public function processInput($form_item, $obj, $request, $name, $repeater_index)
    {
        if($obj->getKey() == null) {
            $obj->save();
        }

        \MuhammadInaamMunir\SpeedAdmin\Models\PermissionRole::where('role_id', $obj->getKey())
            ->delete();

        if ($request->permissions == null) {
            return;
        }

        foreach ($request->permissions as $permission_id => $permission_value) {
            $permission_role = new \MuhammadInaamMunir\SpeedAdmin\Models\PermissionRole();
            $permission_role->permission_id = $permission_id;
            $permission_role->role_id = $obj->getKey();
            $permission_role->save();
        }
    }
}