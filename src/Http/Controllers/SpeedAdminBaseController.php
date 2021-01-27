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
     *      'id' => 'picture',
     *      'before_id' => null, //optional
     *      'title' => __('Picture'),
     *      'field' => [name => 'picture', type => 'image'],   // 'name' is database column name. dot 
     *                                                         // notation allowed for fields in 
     *                                                          // relations. 
     *                                                          // 'type' is column type and can be 
     *                                                          // 'image', 'file', 'boolean', 'string', 
     *                                                          // 'number', 'currency'
     *      'render_function' => // callback function, this function will be called with as follows
     *                          // render_function($field_value, $model_row)
     *                          // this callback should return html as string
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

        $select_fields = [];
        foreach ($this->grid_columns as $grid_column) {
            if(isset($grid_column['field'])) {
                array_push($select_fields, $grid_column['field']['name']);
            }
        }
        $model = $model->select($select_fields);

        $paginated_data = $model->paginate(10);

        $items = $paginated_data->getCollection();

        foreach ($items as $index => $item) {
            foreach ($this->grid_columns as $grid_column) {
                
                if(isset($grid_column['field']['type'])) {
                    $type = $grid_column['field']['type'];

                    $value = $items[$index]->{$grid_column['field']['name']};

                    switch ($type) {
                        case 'image':
                            $value = $value != '' ?
                                '<img class="img-thumbnail" width="150px" src="'.
                                route('admin.get-uploaded-image', ['path' => $value]).
                                '" />' : '';
                            break;
                        case 'boolean':
                            $bool = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                            $color_class = $bool ? "text-success" : "text-danger";
                            $font_class = $bool ? "fas fa-check" : "fas fa-times";
                            $value = '<i class="'.$color_class.' '.$font_class.'"></i>';
                            break;
                        
                        default:
                            break;

                    }
                    
                    $items[$index]->{$grid_column['field']['name']} = $value;
                }
            }
        }

        $paginated_data->setCollection($items);

        return response()
            ->json($paginated_data)
            ->header('Vary', 'Accept');
    }
}