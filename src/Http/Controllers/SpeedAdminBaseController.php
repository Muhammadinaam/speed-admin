<?php

namespace MuhammadInaamMunir\SpeedAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use MuhammadInaamMunir\SpeedAdmin\Misc\GridHelper;
use MuhammadInaamMunir\SpeedAdmin\Misc\FormHelper;

class SpeedAdminBaseController extends BaseController
{
    protected $model = null;
    private $model_obj = null;

    protected $index_url = 'please-set-index_url-variable-in-controller';

    public function __construct()
    {
        $this->model_obj = app()->bound($this->model) ? app()->make($this->model) : new $this->model();
    }    

    public function index(Request $request)
    {
        return view('speed-admin::crud.index', [
            'model' => $this->model_obj,
            'index_url' => url($this->index_url),
            'get_data_url' => url($this->index_url) . '-data'
        ]);
    }
    
    public function getData()
    {
        return GridHelper::gridData($this->model_obj);
    }

    public function create(Request $request)
    {
        \SpeedAdminPermissions::abortIfDontHavePermission($this->model_obj->_add_permission_slug);
        return view('speed-admin::crud.create-edit', [
            'model' => $this->model_obj,
            'index_url' => url($this->index_url),
        ]);
    }

    public function edit(Request $request, $id)
    {
        \SpeedAdminPermissions::abortIfDontHavePermission($this->model_obj->_edit_permission_slug);
        return view('speed-admin::crud.create-edit', [
            'model' => $this->model_obj,
            'obj' => $this->model_obj->find($id),
            'index_url' => url($this->index_url),
        ]);
    }

    public function store(Request $request)
    {
        \SpeedAdminPermissions::abortIfDontHavePermission($this->model_obj->_add_permission_slug);
        return $this->saveData($request, null);
    }

    public function update(Request $request, $id)
    {
        \SpeedAdminPermissions::abortIfDontHavePermission($this->model_obj->_edit_permission_slug);
        return $this->saveData($request, $id);
    }
    
    private function saveData($request, $id)
    {
        $validation_rules = FormHelper::getValidationRules($this->model_obj, $id);
        $request->validate($validation_rules);
        
        return FormHelper::saveFormData($request, $this->model_obj, $id);
    }

    public function destroy(Request $request, $id)
    {
        \SpeedAdminPermissions::abortIfDontHavePermission($this->model_obj->_delete_permission_slug);

        $this->model_obj->where('id', $id)->delete();

        return ['success' => true, 'message' => __('Deleted successfully')];
    }
}
