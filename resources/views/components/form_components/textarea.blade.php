<div class="form-group">
    <label>{{ $form_item['label'] }}</label>
    <textarea
        data-id="{{ $form_item['id'] }}" 
        class="form-control" 
        name="{{ $form_item['name'] }}" 
        {{isset($form_item['readonly']) && $form_item['readonly'] ? 'readonly' : '' }}
        rows="4">{{isset($obj) ? $obj->{$form_item['name']} : ''}}</textarea>
</div>
