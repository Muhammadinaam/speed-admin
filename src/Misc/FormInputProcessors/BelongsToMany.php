<?php

namespace MuhammadInaamMunir\SpeedAdmin\Misc\FormInputProcessors;

use Illuminate\Support\Facades\Storage;

class BelongsToMany extends BaseInputProcessor{

    public function processNonRelationField($form_item, $obj, $request, $name, $repeater_index)
    {
        if($request->has($name)) {
            $relation_name = $form_item['relation_name'];
            $model = app()->bound($form_item['model']) ? app()->make($form_item['model']) : new $form_item['model']();

            $value = $repeater_index === null ? $request->{$name} : $request->{$name}[$repeater_index];
            
            if($obj->getKey() == null) {
                $obj->save();
            }

            $obj->{$relation_name}()->sync($value);
        }
    }
}