<?php

namespace MuhammadInaamMunir\SpeedAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ApplicationController extends BaseController
{
    const APPLICATIONS_PERMISSION_ID = 'can-manage-applications';

    public function index()
    {
        \SpeedAdminHelpers::abortIfDontHavePermission(self::APPLICATIONS_PERMISSION_ID);
        $modules = \Module::all();
        return view('speed-admin::applications.index', compact('modules'));
    }

    public function changeStatus(Request $request, $application_name)
    {
        \SpeedAdminHelpers::abortIfDontHavePermission(self::APPLICATIONS_PERMISSION_ID);

        $module = \Module::find($application_name);

        if($module->isEnabled() == '1')
        {
            $module->disable();
        } else {
            $module->enable();
        }

        return redirect()->back();
    }
}