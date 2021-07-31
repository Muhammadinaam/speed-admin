<?php
    $value = isset($form_item['default']) ? $form_item['default'] : '';
    $value = isset($obj) ? $obj->{$form_item['name']} : $value;
?>

<div class="form-group">
    <label>{{ $form_item['label'] }}</label>
    <select class="form-control" name="{{ $form_item['name'] }}">
        @foreach ($form_item['options'] as $option)
        <option value="{{$option['value']}}" test="{{$value}}" {{$value == $option['value'] ? 'selected' : ''}}>
            {{$option['text']}}
        </option>
        @endforeach
    </select>
</div>
