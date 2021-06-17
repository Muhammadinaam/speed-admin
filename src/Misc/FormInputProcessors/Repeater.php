<?php

namespace MuhammadInaamMunir\SpeedAdmin\Misc\FormInputProcessors;

use Illuminate\Support\Facades\Storage;
use MuhammadInaamMunir\SpeedAdmin\Misc\FormHelper;

class Repeater extends BaseInputProcessor{

    public function processInput($form_item, $obj, $request, $name, $repeater_index)
    {
        if(isset($form_item['children']))
        {
            if($obj->getKey() == null) {
                $obj->save();
            }

            $relation_name = $form_item['relation_name'];
            $foreign_key_name = $obj->{$relation_name}()->getForeignKeyName();
            $local_key_name = $obj->{$relation_name}()->getLocalKeyName();

            for($i = 0; $i < count($request['__'.$form_item['id']]); $i++)
            {
                $id = $request['__'.$form_item['id']][$i];
                $repeated_obj = \SpeedAdminHelpers::getModelInstance($form_item['model']);

                if($id != -1) {
                    $repeated_obj = $repeated_obj->where($repeated_obj->getKeyName(), $id)->first();
                }

                $repeated_obj->{$foreign_key_name} = $obj->getKey();

                $form_item['children'] = FormHelper::orderFormItems($form_item['children']);
                foreach ($form_item['children'] as $child_form_item)
                {
                    FormHelper::processFormItemRecursively($child_form_item, $repeated_obj, $request, $i);
                }

                if($repeated_obj->getKey() == null) {
                    $repeated_obj->save();
                }
            }

            $deleted_items_input_name = '__'.$form_item['id'] . '_deleted_items';
            if ($request->has($deleted_items_input_name)) {
                $deleted_ids = explode(',', $request[$deleted_items_input_name]);

                $repeated_obj->whereIn($repeated_obj->getKeyName(), $deleted_ids)->delete();
            }
        }
    }
}