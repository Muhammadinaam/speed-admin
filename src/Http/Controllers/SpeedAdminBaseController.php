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
        \SpeedAdminPermissions::abortIfDontHavePermission($this->model_obj->getAddPermissionSlug());
        return view('speed-admin::crud.create-edit', [
            'model' => $this->model_obj,
            'index_url' => url($this->index_url),
        ]);
    }

    public function edit(Request $request, $id)
    {
        \SpeedAdminPermissions::abortIfDontHavePermission($this->model_obj->getEditPermissionSlug());
        return view('speed-admin::crud.create-edit', [
            'model' => $this->model_obj,
            'obj' => $this->model_obj->find($id),
            'index_url' => url($this->index_url),
        ]);
    }

    public function store(Request $request)
    {
        \SpeedAdminPermissions::abortIfDontHavePermission($this->model_obj->getAddPermissionSlug());
        return FormHelper::validateAndSaveFormData($request, $this->model_obj, null);
    }

    public function update(Request $request, $id)
    {
        \SpeedAdminPermissions::abortIfDontHavePermission($this->model_obj->getEditPermissionSlug());
        return FormHelper::validateAndSaveFormData($request, $this->model_obj, $id);
    }

    public function destroy(Request $request, $id)
    {
        \SpeedAdminPermissions::abortIfDontHavePermission($this->model_obj->getDeletePermissionSlug());

        $this->model_obj->where('id', $id)->delete();

        return ['success' => true, 'message' => __('Deleted successfully')];
    }
}
