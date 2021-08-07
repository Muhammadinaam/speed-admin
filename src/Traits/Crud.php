<?php

namespace MuhammadInaamMunir\SpeedAdmin\Traits;

use MuhammadInaamMunir\SpeedAdmin\Tree;

trait Crud{

    private $singular_title = null;
    private $plural_title = null;
    private $permission_id = null;

    public $_is_add_enabled = true;
    public $_is_edit_enabled = true;
    public $_is_delete_enabled = true;

    private $grid_columns = [];
    private $form_items_tree;
    private $grid_actions = [];
    private $model_hooks = [];

    private static function callHooks($model, $hook_type)
    {
        $model->performModelOperations();
        foreach($model->model_hooks as $model_hook)
        {
            if ($model_hook['type'] == $hook_type) {
                $model_hook['function']($model);
            }
        }
    }

    protected static function bootCrud()
    {
        static::saving(function ($model) {
            self::callHooks($model, 'before_save');
        });

        static::saved(function ($model) {
            self::callHooks($model, 'after_save');
        });

        static::deleting(function ($model) {
            self::callHooks($model, 'before_delete');
        });

        static::deleted(function ($model) {
            self::callHooks($model, 'after_delete');
        });
    }

    private function removeByIdKeyFromArray(&$array, $id) 
    {
        $new_item_array = [];
        foreach($array as $item_item) {
            if($item_item['id'] != $id) {
                array_push($new_item_array, $item_item);
            }
        }
        $array = $new_item_array;
    }

    public function setSingularTitle($value)
    {
        $this->singular_title = $value;
    }

    public function setPluralTitle($value)
    {
        $this->plural_title = $value;
    }

    public function setPermissionId($value)
    {
        $this->permission_id = $value;
    }

    public function getPermissionId()
    {
        if ($this->permission_id == null) {
            $short_class_name = strtolower(preg_replace('/^(\w+\\\)*/', '', __CLASS__));
            throw new \Exception(
                'Set permission_id in model\'s ['.__CLASS__.'] constructor. '.
                'Example: $this->setPermissionId(\''.$short_class_name.'\');',
                1
            );
            
        }
        return $this->permission_id;
    }

    public function getAddPermissionId()
    {
        return $this->getPermissionId() . '_add';
    }

    public function hasAddPermission()
    {
        return \SpeedAdminHelpers::userHasPermission(\Auth::user()->id, $this->getAddPermissionId());
    }

    public function getEditPermissionId()
    {
        return $this->getPermissionId() . '_edit';
    }

    public function hasEditPermission()
    {
        return \SpeedAdminHelpers::userHasPermission(\Auth::user()->id, $this->getEditPermissionId());
    }

    public function getListPermissionId()
    {
        return $this->getPermissionId() . '_list';
    }

    public function hasListPermission()
    {
        return \SpeedAdminHelpers::userHasPermission(\Auth::user()->id, $this->getListPermissionId());
    }

    public function getDeletePermissionId()
    {
        return $this->getPermissionId() . '_delete';
    }

    public function hasDeletePermission()
    {
        return \SpeedAdminHelpers::userHasPermission(\Auth::user()->id, $this->getDeletePermissionId());
    }

    public function getSingularTitle()
    {
        return $this->singular_title;
    }

    public function getPluralTitle()
    {
        return $this->plural_title;
    }

    public function getGridColumns()
    {
        $this->performModelOperations();
        return $this->grid_columns;
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
     *      'ordering_disabled' => true //optional
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

        if(str_contains($column['id'], '.')) {
            throw new \Exception("dot [.] is not allowed in id of column", 1);      
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

    public function removeGridColumn($id)
    {
        $this->removeByIdKeyFromArray($this->grid_columns, $id);
    }

    public function addGridAction(array $action)
    {
        // check if id already exists
        foreach($this->grid_actions as $grid_action) {
            if($grid_action['id'] == $action['id']) {
                throw new \Exception("Duplicate action id not allowed. id [".$action['id']."] already exists", 1);      
            }
        }

        array_push($this->grid_actions, $action);
    }

    public function removeGridAction($id)
    {
        $this->removeByIdKeyFromArray($this->grid_actions, $id);
    }

    public function addModelHook(array $hook)
    {
        // check if id already exists
        foreach($this->model_hooks as $model_hook) {
            if($model_hook['id'] == $hook['id']) {
                throw new \Exception("Duplicate hook id not allowed. id [".$hook['id']."] already exists", 1);      
            }
        }

        array_push($this->model_hooks, $hook);
    }

    public function removeModelHook($id)
    {
        $this->removeByIdKeyFromArray($this->model_hooks, $id);
    }

    public function getGridActions()
    {
        $this->performModelOperations();
        return $this->grid_actions;
    }

    public function getGridActionById($id)
    {
        $this->performModelOperations();
        return collect($this->grid_actions)->firstWhere('id', $id);
    }

    /**
     * $column = [
     *      'id' => 'picture',
     *      'type' => 
     *      'before_id' => null, //optional
     *      'parent_id' => null, //optional
     * ]
     */
    public function addFormItem(array $item)
    {
        if($this->form_items_tree == null)
        {
            $this->form_items_tree = new Tree();
        }

        $this->form_items_tree->addItem($item);
    }

    public function removeFormItem($id)
    {
        if($this->form_items_tree != null)
        {
            $this->form_items_tree->removeItem($id);
        }
    }

    private function performModelOperations()
    {
        $model_operations = app()->make('speed-admin-models-register')
            ->model_operations;

        $model_operations = isset($model_operations[__CLASS__]) ?
            $model_operations[__CLASS__] :
            [];

        foreach ($model_operations as $model_operation) {
            $value = $model_operation['value'];
            if($model_operation['operation'] == 'add') {
                if ($model_operation['type'] == 'form_item') {
                    $this->addFormItem($value);
                }
                if ($model_operation['type'] == 'grid_column') {
                    $this->addGridColumn($value);
                }
                if ($model_operation['type'] == 'grid_action') {
                    $this->addGridAction($value);
                }
                if ($model_operation['type'] == 'model_hook') {
                    $this->addModelHook($value);
                }
            }
            if($model_operation['operation'] == 'remove') {
                if ($model_operation['type'] == 'form_item') {
                    $this->removeFormItem($value);
                }
                if ($model_operation['type'] == 'grid_column') {
                    $this->removeGridColumn($value);
                }
                if ($model_operation['type'] == 'grid_action') {
                    $this->removeGridAction($value);
                }
                if ($model_operation['type'] == 'model_hook') {
                    $this->removeModelHook($value);
                }
            }
        }
    }

    public function getFormItems()
    {
        
        $this->performModelOperations();

        return $this->form_items_tree != null ? 
            $this->form_items_tree->getTree() :
            [];
    }

    public function getFormItemsFlat()
    {
        $this->performModelOperations();

        return $this->form_items_tree != null ? 
            $this->form_items_tree->getFlatTreeArray() : [];
    }

    public function getAppends()
    {
        return $this->getArrayableAppends();
    }

}