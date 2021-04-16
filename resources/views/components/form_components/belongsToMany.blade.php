<div class="form-group">
    <label>{{ $form_item['label'] }}</label>
    <div style="display: flex; min-width: 200px; align-items: flex-start;">
        <div style="width: 85%;">
            <select
                name="{{ $form_item['name'] }}[]" 
                class="form-control" 
                multiple="multiple"

                data-id="{{ $form_item['id'] }}" 
                data-initialized="false"
                data-initialize_function_name="initBelongsTo"
                data-url="{{ route('admin.select.model') }}"
                data-model="{{urlencode($form_item['model'])}}"
                data-main_model="{{urlencode(get_class($model))}}"
                data-form_item_id="{{$form_item['id']}}">
                @if( isset($obj) && $obj->{$form_item['relation_name']} != null && count($obj->{$form_item['relation_name']}) > 0 )
                    @foreach($obj->{$form_item['relation_name']} as $related_item)
                    <option value="{{$related_item->id}}" selected>{{$related_item->text}}</option>    
                    @endforeach
                @endif
            </select>
        </div>
        @if( isset($form_item['show_select_from_table_button']) && $form_item['show_select_from_table_button'] )
        <button type="button" class="btn mx-1 btn-sm btn-secondary">
            <i class="fas fa-list"></i>
        </button>
        @endif

        @if( isset($form_item['show_add_new_button']) && $form_item['show_add_new_button'] )
        <button type="button" class="btn mx-1 btn-sm btn-secondary">
            <i class="fas fa-plus-circle"></i>
        </button>
        @endif
    </div>
</div>
