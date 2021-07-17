<?php

namespace MuhammadInaamMunir\SpeedAdmin\Http\Controllers;

use Illuminate\Http\Request;
use \MuhammadInaamMunir\SpeedAdmin\Models\Setting;

class SettingController extends SpeedAdminBaseController
{
    protected $model = Setting::class;
    protected $index_url = 'admin/settings';

    public function editSettings()
    {
        // \SpeedAdminHelpers::abortIfDontHavePermission($this->model_obj->getEditPermissionId());
        $obj = $this->model_obj->firstOrNew();
        try {
            $obj->save();
        } catch (\Throwable $th) {
            throw new \Exception(
                "Error occurred in saving settings. Please make sure that ".
                "settings table exists and all columns should be nullable. ".
                "Error: ". $th->getMessage(),
                1
            );
        }
        return view('speed-admin::crud.create-edit', [
            'model' => $this->model_obj,
            'obj' => $obj,
            'index_url' => url($this->index_url),
            'show_list_button' => false
        ]);
    }

    public function updateSettings()
    {
        return 'hello';
    }
}