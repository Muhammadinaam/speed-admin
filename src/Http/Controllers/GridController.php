<?php

namespace MuhammadInaamMunir\SpeedAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class GridController extends BaseController
{
    public function performGridAction()
    {
        // TODO
        throw new \Exception("Add permission checking code here", 1);

        $action_id = request()->action_id;
        $ids = request()->ids;
        $model_class_name = urldecode(request()->model);

        $model = \SpeedAdminHelpers::getModelInstance($model_class_name);

        $action = $model->getGridActionById($action_id);

        $function = $action['function'];

        $function($ids);

        return ['success' => true];
    }
}