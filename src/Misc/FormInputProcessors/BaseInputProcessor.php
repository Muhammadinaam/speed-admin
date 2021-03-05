<?php

namespace MuhammadInaamMunir\SpeedAdmin\Misc\FormInputProcessors;

class BaseInputProcessor{

    public function process($form_item, $obj, $request)
    {
        $name = $form_item['name'];
        $is_relation_field = str_contains($name, '.');

        if (!$is_relation_field) {
            $this->processNonRelationField($form_item, $obj, $request, $name);
        }
        else
        {
            $this->processRelationField($form_item, $obj, $request, $name);
        }
    }

    public function processNonRelationField($form_item, $obj, $request, $name)
    {
        $empty_value = $form_item['type'] == 'checkbox' ? false : null;
        $obj->{$name} = $request->has($name) ? $request->{$name} : $empty_value;
    }

    public function processRelationField($form_item, $obj, $request, $name)
    {
        throw new \Exception("To be implemented", 1);
        
    }
}