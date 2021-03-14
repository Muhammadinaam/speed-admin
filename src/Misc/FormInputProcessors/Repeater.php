<?php

namespace MuhammadInaamMunir\SpeedAdmin\Misc\FormInputProcessors;

use Illuminate\Support\Facades\Storage;
use MuhammadInaamMunir\SpeedAdmin\Misc\FormHelper;

class Repeater extends BaseInputProcessor{

    public function processNonRelationField($form_item, $obj, $request, $name, $repeater_index)
    {
        if(isset($form_item['children']))
        {
            $relation_name = $form_item['relation_name'];
            for($i = 0; $i < count($request['__'.$form_item['id']]); $i++)
            {
                $model = app()->bound($form_item['model']) ? app()->make($form_item['model']) : new $form_item['model']();

                foreach ($form_item['children'] as $child_form_item)
                {
                    FormHelper::processFormItemRecursively($child_form_item, $model, $request, $i);
                }

                $foreign_key_name = $obj->{$relation_name}()->getForeignKeyName();

                if($obj->id == null) {
                    $obj->save();
                }

                $model->{$foreign_key_name} = $obj->id;

                $model->save();
            }
        }
    }
}