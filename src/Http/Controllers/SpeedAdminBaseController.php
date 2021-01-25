<?php

namespace MuhammadInaamMunir\SpeedAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class SpeedAdminBaseController extends BaseController
{
    protected $singular_title = 'Singular Title';
    protected $plural_title = 'Plural Title';
    protected $model = null;

    private $grid_columns = [];

    public function __construct()
    {
        
    }

    /**
     * $column = [
     *      'id' => 'name',
     *      'before_id' => null, //optional
     *      'title' => __('Name')
     *      'field' => 'name'   // database column name. dot notation allowed for fields in relations
     * ]
     */
    public function addGridColumn(array $column)
    {
        // check if id already exists
        foreach($this->grid_columns as $grid_column) {
            if($grid_column['id'] == $column['id']) {
                throw new \Exception("Duplicate column id not allowed. id [".$column['id']."] already exists", 1);      
            }
        }

        $before_id = isset($column['before_id']) ? $column['before_id'] : null;

        if($before_id == null) {
            array_push($this->grid_columns, $column);
        } else {
            $before_id_index = null;
            for ($i = 0; $i < count($this->grid_columns); $i++) {
                if($before_id == $this->grid_columns['id']) {
                    $before_id_index = $i;
                    break;
                }
            }

            if($before_id_index != null) {
                // insert at specific index
                // https://stackoverflow.com/a/3797526/5013099
                array_splice( $this->grid_columns, $before_id_index, 0, $column );
            }
        }
    }

    public function index(Request $request)
    {
        // dd(request()->headers);

        $is_data_request = $request->header('get-data') == '1';
        
        if(!$is_data_request) {
            return view('speed-admin::crud.index', [
                'title' => $this->plural_title,
                'columns' => $this->grid_columns,
                'is_data_request' => $request->header('get-data')
            ]);
        }

        $model = new $this->model();
        
        $data = $model->paginate(10);

        return response()
            ->json($data)
            ->header('Vary', 'Accept');
    }
}