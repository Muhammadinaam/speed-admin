<?php

namespace MuhammadInaamMunir\SpeedAdmin;

class SpeedAdminModelsRegister {

    private $register = [];

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
}