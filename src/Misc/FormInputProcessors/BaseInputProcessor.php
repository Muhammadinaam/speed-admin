<?php

namespace MuhammadInaamMunir\SpeedAdmin\Misc\FormInputProcessors;

class BaseInputProcessor{

    public function process($form_item, $obj, $request, $repeater_index)
    {
        $name = isset($form_item['name']) ? $form_item['name'] : null;
        $is_relation_field = $name == null ? false : str_contains($name, '.');

        if (!$is_relation_field) {
            $this->processNonRelationField($form_item, $obj, $request, $name, $repeater_index);
        }
        else
        {
            $this->processRelationField($form_item, $obj, $request, $name, $repeater_index);
        }
    }

    public function processNonRelationField($form_item, $obj, $request, $name, $repeater_index)
    {
        if($name == null) {
            return;
        }

        $empty_value = $form_item['type'] == 'checkbox' ? false : null;
        $value = $repeater_index === null ? $request->{$name} : $request->{$name}[$repeater_index];
        $obj->{$name} = $request->has($name) ? $value : $empty_value;
    }

    public function processRelationField($form_item, $obj, $request, $name, $repeater_index)
    {
        throw new \Exception("To be implemented", 1);
        
    }
}