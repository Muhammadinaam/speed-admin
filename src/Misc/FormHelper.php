<?php

namespace MuhammadInaamMunir\SpeedAdmin\Misc;

use Illuminate\Support\Facades\Log;

class FormHelper{

    public static function getValidationRules($model, $id)
    {
        $form_items = $model->getFormItems();

        $validation_rules = [];

        $obj = $id != null ? $model->find($id) : null;

        foreach ($form_items as $form_item)
        {
            FormHelper::getValidationRulesRecursively($model, $obj, $form_item, $id, $validation_rules);
        }

        return $validation_rules;
    }

    private static function getValidationRulesRecursively($model, $obj, $form_item, $id, &$validation_rules)
    {
        
        $children = isset($form_item['children']) ? $form_item['children'] : null;

        $validation_rules_func_or_array = null;
        $validation_rules_array = [];
        if(isset($form_item['validation_rules'])) {
            $validation_rules_func_or_array = $form_item['validation_rules'];
        }

        if(isset($form_item['update_validation_rules']) && $id != null) {
            $validation_rules_func_or_array = $form_item['update_validation_rules'];
        }

        if (is_array($validation_rules_func_or_array)) {
            $validation_rules_array = $validation_rules_func_or_array;
        }

        if (is_callable($validation_rules_func_or_array)) {
            $validation_rules_array = $validation_rules_func_or_array(['id' => $id]);
        }

        foreach($validation_rules_array as $name => $validation_rule) 
        {    
            $validation_rule = str_replace('{{$id}}', $id, $validation_rule);

            $validation_rule = str_replace(',,', ',', $validation_rule);

            if($obj != null)
            {
                $form_item_name = $form_item['name'];
                $is_relation_field = str_contains($form_item_name, '.');

                if(!$is_relation_field)
                {
                    $is_image_deleted = request()->has($form_item_name . '_deleted') && request()->{$form_item_name . '_deleted'} == '1';

                    if($form_item['type'] == 'image' && !$is_image_deleted)
                    {
                        $validation_rule = str_replace('required', '', $validation_rule);
                    }
                }
                else
                {
                    throw new Exception("To be implemented", 1);
                    
                }
            }

            $validation_rules[$name] = $validation_rule;
        }

        if($children != null && count($children) > 0)
        {
            foreach ($children as $child_form_item)
            {
                FormHelper::getValidationRulesRecursively($model, $obj, $child_form_item, $id, $validation_rules);
            }
        }
    }

    public static function validateAndSaveFormData($request, $model_obj, $id)
    {
        $validation_rules = FormHelper::getValidationRules($model_obj, $id);
        $request->validate($validation_rules);
        
        return FormHelper::saveFormData($request, $model_obj, $id);
    }

    public static function saveFormData($request, $model, $id)
    {
        try 
        {
            \DB::beginTransaction();

            if (method_exists($model, 'beforeSave')) 
            {
                $model->beforeSave($request, $model, $id);
            }

            $obj = $id != null ? $model->find($id) : $model;
    
            $form_items = $model->getFormItems();
            $form_items = FormHelper::orderFormItems($form_items);
            foreach ($form_items as $form_item)
            {
                FormHelper::processFormItemRecursively($form_item, $obj, $request);
            }
    
            $obj->save();

            if (method_exists($model, 'afterSave')) 
            {
                $model->afterSave($request, $model, $id);
            }

            \DB::commit();

            return ['success' => true, 'message' => __('Saved successfully'), 'obj' => $obj];

        }
        catch(\Illuminate\Validation\ValidationException $validationException) {
            throw $validationException;
        }
        catch (\Exception $ex) {
            \DB::rollBack();
            Log::error($ex);

            if (env('APP_DEBUG')) {
                throw $ex;
            }

            return [
                'success' => false, 
                'message' => __('Error occurred while trying to save data')
            ];
        }
    }

    public static function processFormItemRecursively($form_item, $obj, $request, $repeater_index = null)
    {
        $children = isset($form_item['children']) ? $form_item['children'] : null;
            
        FormHelper::processInputAndSetModelValues($form_item, $obj, $request, $repeater_index);

        if($children != null && count($children) > 0 && $form_item['type'] != 'repeater')
        {
            $children = FormHelper::orderFormItems($children);
            foreach ($children as $child_form_item)
            {
                FormHelper::processFormItemRecursively($child_form_item, $obj, $request, $repeater_index);
            }
        }

    }

    public static function orderFormItems($form_items)
    {
        $ordered_form_items = collect($form_items);

        $ordered_form_items = $ordered_form_items->sortBy(function($form_item){
            $type = isset($form_item['type']) ? $form_item['type'] : null;
            $input_processing_requires_model_save = isset($form_item['input_processing_requires_model_save']) ? 
                $form_item['input_processing_requires_model_save'] : false;

            $order = $type == 'repeater' || $type == 'belongsToMany' || $input_processing_requires_model_save ?
                1 : 0;
            return $order;
        });

        return $ordered_form_items->toArray();
    }

    private static function processInputAndSetModelValues($form_item, $obj, $request, $repeater_index)
    {
        // if(!isset($form_item['name'])) {
        //     return;
        // }

        // set obj values (process input)
        $processor_classpath = \MuhammadInaamMunir\SpeedAdmin\Misc\FormInputProcessors\BaseInputProcessor::class;
        if(isset($form_item['input_processor_classpath'])) {
            $processor_classpath = $form_item['input_processor_classpath'];
        }
        $processor_classpath_by_type = '\MuhammadInaamMunir\SpeedAdmin\Misc\FormInputProcessors\\' . ucfirst($form_item['type']);
        if(class_exists($processor_classpath_by_type))
        {
            $processor_classpath = $processor_classpath_by_type;
        }
        $processor = new $processor_classpath();
        $processor->process($form_item, $obj, $request, $repeater_index);
    }
}
