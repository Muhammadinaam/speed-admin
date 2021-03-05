@if(isset($form_item['attributes']))
    @foreach($form_item['attributes'] as $attribute_key => $attribute_value)
    {{$attribute_key}}="{{$attribute_value}}"
    @endforeach
@endif