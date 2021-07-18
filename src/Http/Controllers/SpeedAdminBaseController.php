<?php

namespace MuhammadInaamMunir\SpeedAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use MuhammadInaamMunir\SpeedAdmin\Misc\GridHelper;
use MuhammadInaamMunir\SpeedAdmin\Misc\FormHelper;

class SpeedAdminBaseController extends BaseController
{
    protected $model = null;
    protected $model_obj = null;

    protected $index_url = 'please-set-index_url-variable-in-controller';

    
    public function __construct()
    {
        $this->index_url = str_replace('admin', config('speed-admin.admin_url'), $this->index_url);
        $this->model_obj = \SpeedAdminHelpers::getModelInstance($this->model);
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
        \SpeedAdminHelpers::abortIfDontHavePermission($this->model_obj->getAddPermissionId());
        return view('speed-admin::crud.create-edit', [
            'model' => $this->model_obj,
            'index_url' => url($this->index_url),
        ]);
    }

    public function edit(Request $request, $id)
    {
        $obj = $this->model_obj->find($id);
        \SpeedAdminHelpers::abortIfDontHavePermissionByTenant($obj);
        \SpeedAdminHelpers::abortIfDontHavePermission($this->model_obj->getEditPermissionId());
        return view('speed-admin::crud.create-edit', [
            'model' => $this->model_obj,
            'obj' => $obj,
            'index_url' => url($this->index_url),
        ]);
    }

    public function store(Request $request)
    {
        \SpeedAdminHelpers::abortIfDontHavePermission($this->model_obj->getAddPermissionId());
        return FormHelper::validateAndSaveFormData($request, $this->model_obj, null);
    }

    public function update(Request $request, $id)
    {
        $obj = $this->model_obj->find($id);
        \SpeedAdminHelpers::abortIfDontHavePermissionByTenant($obj);
        \SpeedAdminHelpers::abortIfDontHavePermission($this->model_obj->getEditPermissionId());
        return FormHelper::validateAndSaveFormData($request, $this->model_obj, $id);
    }

    public function destroy(Request $request, $id)
    {
        $obj = $this->model_obj->find($id);
        \SpeedAdminHelpers::abortIfDontHavePermissionByTenant($obj);
        \SpeedAdminHelpers::abortIfDontHavePermission($this->model_obj->getDeletePermissionId());

        $this->model_obj->where('id', $id)->delete();

        return ['success' => true, 'message' => __('Deleted successfully')];
    }
}
