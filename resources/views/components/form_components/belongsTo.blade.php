<?php
$uniqid = uniqid();
?>
<div class="form-group">
    <label>{{ $form_item['label'] }}</label>
    <select id="select_{{$uniqid}}" name="{{ $form_item['name'] }}" 
        data-id="{{ $form_item['id'] }}" class="form-control">
        @if( isset($obj) && $obj->{$form_item['relation_name']} != null )
        <option value="{{$obj->{$form_item['relation_name']}->id}}">{{$obj->{$form_item['relation_name']}->text}}</option>
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
                        where: "{{urlencode(json_encode($form_item['where']))}}"
                    }
                    return query
                }
            }
        });
    })
    </script>
</div>
