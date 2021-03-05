<?php

namespace MuhammadInaamMunir\SpeedAdmin\Misc\FormInputProcessors;

use Illuminate\Support\Facades\Storage;

class BelongsTo extends BaseInputProcessor{

    public function processNonRelationField($form_item, $obj, $request, $name)
    {
        if($request->has($name)) {
            $relation_name = $form_item['relation_name'];
            $model = app()->bound($form_item['model']) ? app()->make($form_item['model']) : new $form_item['model']();
    
            $foreign_key_name = $obj->{$relation_name}()->getForeignKeyName();
            $obj->{$foreign_key_name} = $request->{$name};
        }
    }
}