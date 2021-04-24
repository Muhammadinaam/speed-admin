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

        $model = app()->bound($model_class_name) ? app()->make($model_class_name) : new $model_class_name();

        $action = $model->getGridActionById($action_id);

        $function = $action['function'];

        $function($ids);

        return ['success' => true];
    }
}