<div class="form-check">
    <label class="form-check-label">
        <input 
            type="checkbox" 
            class="form-check-input" 
            name="{{ $form_item['name'] }}" 
            value="1" 
            data-id="{{ $form_item['id'] }}"
            {{isset($obj) && filter_var($obj->{$form_item['name']}, FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' }}>
        
        {{ $form_item['label'] }}
    </label>
</div>
