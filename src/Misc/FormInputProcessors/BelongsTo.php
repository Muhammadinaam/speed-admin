<?php

namespace MuhammadInaamMunir\SpeedAdmin\Misc\FormInputProcessors;

use Illuminate\Support\Facades\Storage;

class BelongsTo extends BelongsToBase{

    public function processInput($form_item, $obj, $request, $name, $repeater_index)
    {
        if($request->has($name)) {
            list($relation_name, $value) = $this->getAndValidateValue($form_item, $repeater_index, $request, $name);
            
            $foreign_key_name = $obj->{$relation_name}()->getForeignKeyName();
            $obj->{$foreign_key_name} = $value;
        }
    }
}