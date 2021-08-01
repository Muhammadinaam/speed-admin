<?php

namespace MuhammadInaamMunir\SpeedAdmin;

class SpeedAdminModelsRegister {

    private $register = [];
    public $model_operations = [];

    public function registerModelChildClass($model_class, $model_child_class)
    {
        if ( ! isset($this->register[$model_class]) || $this->register[$model_class] == null) {
            $this->register[$model_class] = [];
        }

        array_push($this->register[$model_class], $model_child_class);
    }

    public function getLatestParentClass($parent_class, $namespace, $current_class_name, $latest_parent_class_alias_name)
    {
        $parent_class_register = isset($this->register[$parent_class]) ? $this->register[$parent_class] : null;

        $latest_parent_class = $parent_class;
        if ($parent_class_register != null) {
            foreach($parent_class_register as $parent_class_register_item) {
                if ($parent_class_register_item == $namespace . '\\' . $current_class_name) {
                    break;
                }
                $latest_parent_class = $parent_class_register_item;
            }
        }

        class_alias($latest_parent_class, $namespace . '\\' . $latest_parent_class_alias_name);
    }

    public function getModelInstance($model_class)
    {
        $model_class_register = isset($this->register[$model_class]) ? $this->register[$model_class] : null;
        $model_class_name = '';
        if ($model_class_register != null)
        {
            // last entry in register
            $model_class_name = $model_class_register[count($model_class_register) - 1];
        } else {
            $model_class_name = $model_class;
        }

        if ($model_class_name[0] != '\\') {
            $model_class_name = '\\' . $model_class_name;
        }

        return new $model_class_name();
    }

    public function getModelChildClasses($model_class)
    {
        return isset($this->register[$model_class]) ? $this->register[$model_class] : [];
    }

    private function initModelOperations($model_class)
    {
        if( !isset($this->model_operations[$model_class]) )
        {
            $this->model_operations[$model_class] = [];
        }
    }

    public function addGridColumn($model_class, $column)
    {
        $this->initModelOperations($model_class);
        array_push(
            $this->model_operations[$model_class],
            [
                'operation' => 'add',
                'type' => 'grid_column',
                'value' => $column
            ]
        );
    }

    public function removeGridColumn($model_class, $id)
    {
        $this->initModelOperations($model_class);
        array_push(
            $this->model_operations[$model_class],
            [
                'operation' => 'remove',
                'type' => 'grid_column',
                'value' => $id
            ]
        );
    }

    public function addGridAction($model_class, $action)
    {
        $this->initModelOperations($model_class);
        array_push(
            $this->model_operations[$model_class],
            [
                'operation' => 'add',
                'type' => 'grid_action',
                'value' => $action
            ]
        );
    }

    public function removeGridAction($model_class, $id)
    {
        $this->initModelOperations($model_class);
        array_push(
            $this->model_operations[$model_class],
            [
                'operation' => 'remove',
                'type' => 'grid_action',
                'value' => $id
            ]
        );
    }

    public function addFormItem($model_class, $form_item)
    {
        $this->initModelOperations($model_class);
        array_push(
            $this->model_operations[$model_class],
            [
                'operation' => 'add',
                'type' => 'form_item',
                'value' => $form_item
            ]
        );
    }

    public function removeFormItem($model_class, $id)
    {
        $this->initModelOperations($model_class);
        array_push(
            $this->model_operations[$model_class],
            [
                'operation' => 'remove',
                'type' => 'form_item',
                'value' => $id
            ]
        );
    }
}