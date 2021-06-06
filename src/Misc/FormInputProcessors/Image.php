<?php

namespace MuhammadInaamMunir\SpeedAdmin\Misc\FormInputProcessors;

use Illuminate\Support\Facades\Storage;

class Image extends BaseInputProcessor{

    public function processInput($form_item, $obj, $request, $name, $repeater_index)
    {
        $deleted_value = $repeater_index === null ? $request->{$name . '_deleted'} : $request->{$name . '_deleted'}[$repeater_index];

        $value = $repeater_index === null ? $request->{$name} : $request->{$name}[$repeater_index];

        $is_image_deleted = $request->has($name . '_deleted') && $deleted_value == '1';

        if ($value != null && !$is_image_deleted) {
                
            // delete old picture
            Storage::delete($obj->{$name});

            $file_path = $value
                ->store( isset($form_item['upload_path']) ? $form_item['upload_path'] : 'uploads' );

            $obj->{$name} = $file_path;
        }

        if ($is_image_deleted) {
            Storage::delete($obj->{$name});
            $obj->{$name} = null;
        }
    }
}