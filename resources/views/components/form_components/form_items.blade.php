@foreach($form_items as $form_item)
    <?php
        $view_path = isset($form_item['view_path']) ? 
            $form_item['view_path'] : 
            'speed-admin::components.form_components.' . $form_item['type'];
    ?>
    @component($view_path, [
        'form_item' => $form_item,
        'obj' => isset($obj) ? $obj : null,
    ])
    @endcomponent
@endforeach