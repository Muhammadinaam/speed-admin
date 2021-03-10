<div 
    id="{{$form_item['id']}}"
    
    @component('speed-admin::components.form_components.html_attributes', ['form_item' => $form_item])
    @endcomponent

    class="{{$form_item['class']}}"
    >

    @if(isset($form_item['children']))
        @component('speed-admin::components.form_components.form_items', [
            'model' => $model,
            'form_items' => $form_item['children'],
            'obj' => isset($obj) ? $obj : null,
        ])
        @endcomponent
    @endif
    
</div>