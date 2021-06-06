<?php

namespace MuhammadInaamMunir\SpeedAdmin\Misc\FormInputProcessors;

class BaseInputProcessor{

    public function process($form_item, $obj, $request, $repeater_index)
    {
        $name = isset($form_item['name']) ? $form_item['name'] : null;

        if(isset($form_item['display_only']) && $form_item['display_only']) {
            // don't process
            return;
        }
        
        $this->setTranslations($obj, $name, $request);

        $this->processInput($form_item, $obj, $request, $name, $repeater_index);
    }

    private function setTranslations($obj, $name, $request)
    {
        if($obj->translatable && in_array($name, $obj->translatable))
        {
            foreach(config('speed-admin.additional_model_locales') as $locale)
            {
                $obj->setTranslation($name, $locale['locale'], $request[$name . '_' . $locale['locale']]);
            }
        }
    }

    public function processInput($form_item, $obj, $request, $name, $repeater_index)
    {
        if($name == null) {
            return;
        }

        $empty_value = $form_item['type'] == 'checkbox' ? false : null;
        $value = $repeater_index === null ? $request->{$name} : $request->{$name}[$repeater_index];
        $obj->{$name} = $request->has($name) ? $value : $empty_value;
    }
}