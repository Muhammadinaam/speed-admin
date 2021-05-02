<?php

namespace MuhammadInaamMunir\SpeedAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use MuhammadInaamMunir\SpeedAdmin\Misc\FormHelper;

class BelongsToController extends BaseController
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

    public function showAddNewForm()
    {
        $model_name = urldecode(request()->model);
        $model = app()->bound($model_name) ? app()->make($model_name) : new $model_name();

        return view('speed-admin::add-new', [
            'model' => $model,
            'index_url' => route('admin.save-data-of-add-new-form'),
            'show_list_button' => false
        ]);
    }

    public function saveDataOfAddNewForm(Request $request)
    {
        $model_name = urldecode(request()->_model_);
        $model = app()->bound($model_name) ? app()->make($model_name) : new $model_name();

        \SpeedAdminPermissions::abortIfDontHavePermission($model->getAddPermissionSlug());
        return FormHelper::validateAndSaveFormData($request, $model, null);
    }
}