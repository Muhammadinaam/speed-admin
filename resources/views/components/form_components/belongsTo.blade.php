<div class="form-group">
    <label>{{ $form_item['label'] }}</label>
    <select 
        name="{{ $form_item['name'] }}" 
        class="form-control"
        style="width: 100%; min-width: 400px;"
        
        data-id="{{ $form_item['id'] }}" 
        data-initialized="false"
        data-initialize_function_name="initBelongsTo"
        data-url="{{ route('admin.select.model') }}"
        data-model="{{urlencode($form_item['model'])}}"
        data-main_model="{{urlencode(get_class($model))}}"
        data-form_item_id="{{$form_item['id']}}">

        @if( isset($obj) && $obj->{$form_item['relation_name']} != null )
        <option value="{{$obj->{$form_item['relation_name']}->id}}">{{$obj->{$form_item['relation_name']}->text}}</option>
        @endif
    </select>
</div>
