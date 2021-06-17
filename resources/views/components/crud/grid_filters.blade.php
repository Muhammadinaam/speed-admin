@foreach($model->getGridColumns() as $column)
    @if(
        isset($column['field'])
        &&
        ( !isset($column['field']['filtering_disabled']) || $column['field']['filtering_disabled'] == false )
    )
        <?php
            $label = $column['title'];
            $name = "filters[" . $column['field']['name'] . "]";
        ?>

        @if($column['field']['type'] == 'image')
            @component('speed-admin::components.form_components.select', [
                'options' => [
                    'label' => $label,
                    'name' => $name,
                    'options' => [
                        [ 'value' => '', 'text' => ''],
                        [ 'value' => 'has_image', 'text' => __('Has image')],
                        [ 'value' => 'does_not_have_image', 'text' => __('Does not have image')],
                    ]
                ]
            ])
            @endcomponent
        @endif
    @endif
@endforeach
<button type="button" class="btn btn-sm btn-info mb-1" onclick="getData_{{$uniqid}}()">
    {{__('Apply filters')}}
</button>