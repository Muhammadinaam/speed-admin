<?php

namespace MuhammadInaamMunir\SpeedAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class SelectController extends BaseController
{
    public function selectModel(Request $request)
    {
        $model_class_name = urldecode($request->model);
        $wheres = json_decode(urldecode($request->where));

        $model = app()->bound($model_class_name) ? app()->make($model_class_name) : new $model_class_name();
        $query = $model;

        if($wheres != null) 
        {
            foreach($wheres as $where)
            {
                $query = $query->where($where[0], $where[1], $where[2]);
            }
        }

        $data = $query->limit(10)->get();

        return [
            'results' => $data
        ];
    }
}