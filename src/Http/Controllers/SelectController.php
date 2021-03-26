<?php

namespace MuhammadInaamMunir\SpeedAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class SelectController extends BaseController
{
    public function selectModel(Request $request)
    {
        $model_class_name = urldecode($request->model);
        $main_model_class_name = urldecode($request->main_model);

        $model = app()->bound($model_class_name) ? app()->make($model_class_name) : new $model_class_name();
        $main_model = app()->bound($main_model_class_name) ? app()->make($main_model_class_name) : new $main_model_class_name();

        $query = $model;

        foreach($main_model->getFormItemsFlat() as $form_item) {
            if($form_item['id'] == $request->form_item_id) {
                if(isset($form_item['where'])) {
                    $query = $form_item['where']($query);
                }
            }
        }

        $data = $query->limit(10)->get();

        return [
            'results' => $data
        ];
    }
}