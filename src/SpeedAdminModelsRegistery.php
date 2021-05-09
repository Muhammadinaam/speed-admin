<?php

namespace MuhammadInaamMunir\SpeedAdmin;

class SpeedAdminModelsRegistery {

    private $registry = [];

    public function setRegistry($model_class, $new_model_class)
    {
        $this->registry[$model_class] = $new_model_class;
    }

    public function getRegistry($model_class)
    {
        return isset($this->registry[$model_class]) ? $this->registry[$model_class] : null;
    }

    public function getModelInstance($model_class)
    {
        $class_name = $this->getRegistry($model_class) != null ? $this->getRegistry($model_class) : $model_class;
        return new $class_name();
    }
}