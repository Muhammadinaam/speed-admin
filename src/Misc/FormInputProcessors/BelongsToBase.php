<?php

namespace MuhammadInaamMunir\SpeedAdmin\Misc\FormInputProcessors;

use Illuminate\Support\Facades\Storage;

class BelongsToBase extends BaseInputProcessor{

    protected function checkWhereCondition($model, $value, $form_item, $repeater_index)
    {
        if (! isset($form_item['where'])) {
            return;
        }

        $exists = $model->where($model->getKeyName(), $value)
            ->where($form_item['where'])
            ->exists();
        
        if (!$exists) {
        
            $message = isset($form_item['where_validation_error_message']) ?
                $form_item['where_validation_error_message']:
                $form_item['name'] . ": " . __('Selection not valid.');
        
            if ($repeater_index !== null) {
                $message .= __('Row Number: ') . $repeater_index + 1;
            }
        
            $error_messages_array = [];
            $error_messages_array[$form_item['name']] = [
                $message
            ];
            $error = \Illuminate\Validation\ValidationException::withMessages(
                $error_messages_array
            );
            throw $error;
        }
    }
}
