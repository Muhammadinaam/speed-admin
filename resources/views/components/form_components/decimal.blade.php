<?php
    $value = isset($form_item['default']) ? $form_item['default'] : '';
    $value = isset($obj) ? $obj->{$form_item['name']} : $value;
?>

<div class="form-group">
    <label>{{ $form_item['label'] }}</label>
    <input 
        type="number" 
        class="form-control" 
        name="{{ $form_item['name'] }}" 
        data-id="{{ $form_item['id'] }}"
        value="{{$value}}"
        {{isset($form_item['readonly']) && $form_item['readonly'] ? 'readonly' : '' }}>
</div>
