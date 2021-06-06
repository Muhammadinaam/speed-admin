<?php
$uniqid = uniqid();
$name = isset($multiple) && $multiple == true ? $form_item['name'] . '[]' : $form_item['name'];
?>
<div class="form-group">
    <label>{{ $form_item['label'] }}</label>
    <div style="display: flex; min-width: 200px; align-items: flex-start;">
        <div style="width: 85%;">
            <select
                name="{{ $name }}" 
                class="form-control"

                @if(isset($multiple) && $multiple == true)
                multiple="multiple"
                @endif

                data-id="{{ $form_item['id'] }}" 
                data-initialized="false"
                data-initialize_function_name="speedAdminBelongsTo.initBelongsTo"
                data-url="{{ route('admin.select.model') }}"
                data-model="{{urlencode($form_item['model'])}}"
                data-main_model="{{urlencode(get_class($model))}}"
                data-form_item_id="{{$form_item['id']}}">
                
                @if(isset($multiple) && $multiple == true)
                    @if( isset($obj) && $obj->{$form_item['relation_name']} != null && count($obj->{$form_item['relation_name']}) > 0 )
                        @foreach($obj->{$form_item['relation_name']} as $related_item)
                        <option value="{{$related_item->id}}" selected data-data="{{ json_encode($related_item) }}">
                            {{$related_item->text}}
                        </option>    
                        @endforeach
                    @endif
                @else
                    @if( isset($obj) && $obj->{$form_item['relation_name']} != null )
                    <option value="{{$obj->{$form_item['relation_name']}->id}}" data-data="{{ json_encode($obj->{$form_item['relation_name']}) }}">
                        {{$obj->{$form_item['relation_name']}->text}}
                    </option>
                    @endif
                @endif

            </select>
        </div>
        @component('speed-admin::components.form_components.belongsToButtons', [
            'form_item' => $form_item,
            'uniqid' => $uniqid
        ])
        @endcomponent
    </div>
</div>
