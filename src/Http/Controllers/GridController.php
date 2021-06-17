<?php

namespace MuhammadInaamMunir\SpeedAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class GridController extends BaseController
{
    public function performGridAction()
    {

        $action_id = request()->action_id;
        $ids = request()->ids;
        $model_class_name = urldecode(request()->model);

        $model = \SpeedAdminHelpers::getModelInstance($model_class_name);

        $query = $model->query();
        $query = $ids == '__all__' ? $query : $query->whereIn('id', $ids);
        
        $tenant_id = \Auth::user()->tenant_id;
        if ($tenant_id != null && method_exists($model, 'tenantOrganization')) {
            $query = $query->where('tenant_organization_id', '=', $tenant_id);
        }

        if (request()->action_id == '__delete__') {
            \SpeedAdminHelpers::abortIfDontHavePermission($model->getDeletePermissionId());

            $query->delete();

            return ['success' => true];
        }

        
        $action = $model->getGridActionById($action_id);
        
        \SpeedAdminHelpers::abortIfDontHavePermission($action['permission']);

        $function = $action['function'];

        $function($ids, $query);

        return ['success' => true];
    }
}