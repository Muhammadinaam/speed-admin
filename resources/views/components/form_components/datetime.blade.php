<?php
    $value = isset($form_item['default']) ? $form_item['default'] : '';
    $value = isset($obj) ? $obj->{$form_item['name']} : $value;

    $alt_format = isset($form_item['no_calendar']) && $form_item['no_calendar'] ?
        config('speed-admin.time_format') : 
        config('speed-admin.date_format') . ' ' . config('speed-admin.time_format');

?>
<div class="form-group">
    <label>{{ $form_item['label'] }}</label>
    <input 
        type="text" 
        class="form-control" 
        name="{{ $form_item['name'] }}" 
        data-id="{{ $form_item['id'] }}"
        data-initialized="false"
        data-initialize_function_name="speedAdminDateTime.initDateTime"
        data-enable_time="{{ isset($form_item['enable_time']) && $form_item['enable_time'] ? 'true' : 'false' }}"
        data-no_calendar="{{ isset($form_item['no_calendar']) && $form_item['no_calendar'] ? 'true' : 'false' }}"
        data-alt_format="{{ $alt_format }}"
        value="{{$value}}">

</div>


