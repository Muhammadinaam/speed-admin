<?php

namespace MuhammadInaamMunir\SpeedAdmin\Traits;

use MuhammadInaamMunir\SpeedAdmin\Tree;

trait Crud{

    private $singular_title = null;
    private $plural_title = null;

    public $_is_add_enabled = true;
    public $_is_edit_enabled = true;
    public $_is_delete_enabled = true;

    public $_list_permission_slug = '';
    public $_add_permission_slug = '';
    public $_edit_permission_slug = '';
    public $_delete_permission_slug = '';

    private $grid_columns = [];
    private $form_items_tree;
    private $grid_actions = [];

    public function setSingularTitle($value)
    {
        $this->singular_title = $value;
    }

    public function setPluralTitle($value)
    {
        $this->plural_title = $value;
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

    public function getGridActions()
    {
        return $this->grid_actions;
    }

    public function getGridActionById($id)
    {
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

    public function getFormItems()
    {
        return $this->form_items_tree->getTree();
    }

    public function getFormItemsFlat()
    {
        return $this->form_items_tree->getFlatTreeArray();
    }

}