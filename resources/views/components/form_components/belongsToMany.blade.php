<?php
$uniqid = uniqid();
?>
<div class="form-group">
    <label>{{ $form_item['label'] }}</label>
    <select id="select_{{$uniqid}}" name="{{ $form_item['name'] }}[]" 
        data-id="{{ $form_item['id'] }}" class="form-control" multiple="multiple">
        @if( isset($obj) && $obj->{$form_item['relation_name']} != null && count($obj->{$form_item['relation_name']}) > 0 )
            @foreach($obj->{$form_item['relation_name']} as $related_item)
            <option value="{{$related_item->id}}" selected>{{$related_item->text}}</option>    
            @endforeach
        @endif
    </select>

    <script>
    $(document).ready(function(){
        $('#select_{{$uniqid}}').select2({
            ajax: {
                url: "{{ route('admin.select.model') }}",
                dataType: 'json',
                data: function (params) {
                    var query = {
                        model: "{{urlencode($form_item['model'])}}",
                        main_model: "{{urlencode(get_class($model))}}",
                        form_item_id: "{{$form_item['id']}}"
                    }
                    return query
                }
            }
        });
    })
    </script>
</div>
