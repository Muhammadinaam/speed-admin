<?php

namespace MuhammadInaamMunir\SpeedAdmin\Misc\FormInputProcessors;

use Illuminate\Support\Facades\Storage;

class BelongsToMany extends BelongsToBase{

    public function processInput($form_item, $obj, $request, $name, $repeater_index)
    {
        if($request->has($name)) {
            $relation_name = $form_item['relation_name'];
            $model = \SpeedAdminHelpers::getModelInstance($form_item['model']);

            $value = null;
            if ($repeater_index === null) {
                $value = $request->{$name};
            } else if (isset($request->{$name}[$repeater_index])) {
                $value = $request->{$name}[$repeater_index];
            }

            foreach($value as $val) {
                $this->checkWhereCondition($model, $val, $form_item, $repeater_index);
            }
            
            if($obj->getKey() == null) {
                $obj->save();
            }

            $obj->{$relation_name}()->sync($value);
        }
    }
}