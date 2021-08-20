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
        $message = '';

        if($module->isEnabled() == '1')
        {
            $module->disable();
            \Artisan::call('module:migrate-rollback', [
                'module' => $module->getName(),
            ]);
            $message = __('Application disabled successfully.');
        } else {
            $module->enable();
            \Artisan::call('module:migrate', [
                'module' => $module->getName(),
            ]);
            $message = __('Application enabled successfully.');
        }

        return redirect()->back()->with(['message' => $message]);
    }

    public function updateDatabase(Request $request, $application_name)
    {
        \SpeedAdminHelpers::abortIfDontHavePermission(self::APPLICATIONS_PERMISSION_ID);

        $module = \Module::find($application_name);
        $message = '';

        if($module->isEnabled() == '1')
        {
            \Artisan::call('module:migrate', [
                'module' => $module->getName(),
            ]);
            $message = __('Database updated successfully');
        } else {
            $message = __('Application is disabled. Database not updated.');
        }

        return redirect()->back()->with(['message' => $message]);
    }
}