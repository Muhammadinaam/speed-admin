<?php
    $uniqid = uniqid();
?>
<div class="form-group">
    <label>{{ $form_item['label'] }}</label>
    <input 
        id="datetime_{{$uniqid}}"
        type="text" 
        class="form-control" 
        name="{{ $form_item['name'] }}" 
        data-id="{{ $form_item['id'] }}"
        value="{{isset($obj) ? $obj->{$form_item['name']} : ''}}">

    <script>
    speedAdmin.ready(function(){
        $('#datetime_{{$uniqid}}').flatpickr({
            enableTime: {{ $form_item['enable_time'] ? 'true' : 'false' }},
        })
    })
    </script>

</div>


