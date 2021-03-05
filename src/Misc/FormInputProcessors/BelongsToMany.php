<?php

namespace MuhammadInaamMunir\SpeedAdmin\Misc\FormInputProcessors;

use Illuminate\Support\Facades\Storage;

class BelongsToMany extends BaseInputProcessor{

    public function processNonRelationField($form_item, $obj, $request, $name)
    {
        if($request->has($name)) {
            $relation_name = $form_item['relation_name'];
            $model = app()->bound($form_item['model']) ? app()->make($form_item['model']) : new $form_item['model']();
            
            if($obj->id == null)
            {
                $obj->save();   // save to generate id. id is required for sync
            }
            $obj->{$relation_name}()->sync($request->{$name});
        }
    }
}