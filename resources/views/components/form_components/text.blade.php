<div class="form-group">
    <label>{{ $form_item['label'] }}</label>
    <input 
        type="text" 
        class="form-control" 
        name="{{ $form_item['name'] }}" 
        data-id="{{ $form_item['id'] }}"
        value="{{isset($obj) ? $obj->{$form_item['name']} : ''}}">
</div>
