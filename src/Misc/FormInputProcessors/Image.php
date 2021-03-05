<?php

namespace MuhammadInaamMunir\SpeedAdmin\Misc\FormInputProcessors;

use Illuminate\Support\Facades\Storage;

class Image extends BaseInputProcessor{

    public function processNonRelationField($form_item, $obj, $request, $name)
    {
        $is_image_deleted = $request->has($name . '_deleted') && $request->{$name . '_deleted'} == '1';

        if ($request->has($name) && !$is_image_deleted) {
                
            // delete old picture
            Storage::delete($obj->{$name});

            $file_path = $request->{$name}
                ->store( isset($form_item['upload_path']) ? $form_item['upload_path'] : 'uploads' );

            $obj->{$name} = $file_path;
        }

        if ($is_image_deleted) {
            Storage::delete($obj->{$name});
            $obj->{$name} = null;
        }
    }
}