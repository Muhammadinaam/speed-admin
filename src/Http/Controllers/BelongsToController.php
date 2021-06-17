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

        $model = \SpeedAdminHelpers::getModelInstance($model_class_name);
        $main_model = \SpeedAdminHelpers::getModelInstance($main_model_class_name);

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
        $model = \SpeedAdminHelpers::getModelInstance($model_name);

        \SpeedAdminHelpers::abortIfDontHavePermission($model->getAddPermissionId());

        return view('speed-admin::add-new', [
            'model' => $model,
            'index_url' => route('admin.save-data-of-add-new-form'),
            'show_list_button' => false
        ]);
    }

    public function saveDataOfAddNewForm(Request $request)
    {
        $model_name = urldecode(request()->_model_);
        $model = \SpeedAdminHelpers::getModelInstance($model_name);

        \SpeedAdminHelpers::abortIfDontHavePermission($model->getAddPermissionId());
        return FormHelper::validateAndSaveFormData($request, $model, null);
    }
}