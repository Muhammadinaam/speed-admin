<?php

namespace MuhammadInaamMunir\SpeedAdmin\Misc\FormInputProcessors;

use Illuminate\Support\Facades\Storage;

class BelongsToMany extends BelongsToBase{

    public function processInput($form_item, $obj, $request, $name, $repeater_index)
    {
        if($request->has($name)) {
            list($relation_name, $value) = $this->getAndValidateValue($form_item, $repeater_index, $request, $name);
            
            if($obj->getKey() == null) {
                $obj->save();
            }

            $obj->{$relation_name}()->sync($value);
        }
    }
}
