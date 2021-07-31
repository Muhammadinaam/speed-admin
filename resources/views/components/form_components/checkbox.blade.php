<?php
    $value = isset($form_item['default']) ? $form_item['default'] : '';
    $value = isset($obj) ? $obj->{$form_item['name']} : $value;
?>

<div class="form-check">
    <label class="form-check-label">
        <input 
            type="checkbox" 
            class="form-check-input" 
            name="{{ $form_item['name'] }}" 
            value="1" 
            data-id="{{ $form_item['id'] }}"
            {{filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' }}>
        
        {{ $form_item['label'] }}
    </label>
</div>
