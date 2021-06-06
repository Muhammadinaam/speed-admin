<?php

namespace MuhammadInaamMunir\SpeedAdmin\Misc\FormInputProcessors;

use Illuminate\Support\Facades\Storage;

class BelongsToMany extends BaseInputProcessor{

    public function processInput($form_item, $obj, $request, $name, $repeater_index)
    {
        if($request->has($name)) {
            $relation_name = $form_item['relation_name'];
            $model = \SpeedAdminHelpers::getModelInstance($form_item['model']);

            $value = $repeater_index === null ? $request->{$name} : $request->{$name}[$repeater_index];
            
            if($obj->getKey() == null) {
                $obj->save();
            }

            $obj->{$relation_name}()->sync($value);
        }
    }
}