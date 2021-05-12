<?php

namespace MuhammadInaamMunir\SpeedAdmin\Misc\FormInputProcessors;

use Illuminate\Support\Facades\Storage;
use MuhammadInaamMunir\SpeedAdmin\Misc\FormHelper;

class Permission extends BaseInputProcessor{

    public function processNonRelationField($form_item, $obj, $request, $name, $repeater_index)
    {
        // dd($request);
    }
}