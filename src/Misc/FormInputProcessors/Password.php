<?php

namespace MuhammadInaamMunir\SpeedAdmin\Misc\FormInputProcessors;

class Password extends BaseInputProcessor{

    public function processInput($form_item, $obj, $request, $name, $repeater_index)
    {
        if($request->has($name)) {
            $value = $repeater_index === null ? $request->{$name} : $request->{$name}[$repeater_index];
            if ( isset($form_item['dont_update_if_empty']) && $form_item['dont_update_if_empty'] && ($value == null || $value == '') )
            {
                return;
            }

            $obj->{$name} = $value != null && $value != '' ? bcrypt($value) : $value;
        }
    }
}