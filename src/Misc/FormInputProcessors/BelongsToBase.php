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
                $message .= __('Row Number: ') . ($repeater_index + 1);
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

    protected function getAndValidateValue($form_item, $repeater_index, $request, $name)
    {
        $relation_name = $form_item['relation_name'];
        $model = \SpeedAdminHelpers::getModelInstance($form_item['model']);
        
        $value = null;
        if ($repeater_index === null) {
            $value = $request->{$name};
        } else if (isset($request->{$name}[$repeater_index])) {
            $value = $request->{$name}[$repeater_index];
        }
        
        if ($value !== null) {
            if (is_array($value)) {
                foreach($value as $val) {
                    $this->checkWhereCondition($model, $val, $form_item, $repeater_index);
                }
            } else {
                $this->checkWhereCondition($model, $value, $form_item, $repeater_index);
            }
        }

        return array($relation_name, $value);
    }
}
