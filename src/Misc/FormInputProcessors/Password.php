<?php

namespace MuhammadInaamMunir\SpeedAdmin\Misc\FormInputProcessors;

class Password extends BaseInputProcessor{

    public function processNonRelationField($form_item, $obj, $request, $name, $repeater_index)
    {
        if($request->has($name)) {
            $value = $repeater_index === null ? $request->{$name} : $request->{$name}[$repeater_index];
            $obj->{$name} = $request->has($name) ? bcrypt($value) : $empty_value;
        }
    }
}