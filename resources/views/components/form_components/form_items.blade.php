@foreach($form_items as $form_item)
    <?php
        $view_path = isset($form_item['view_path']) ? 
            $form_item['view_path'] : 
            'speed-admin::components.form_components.' . $form_item['type'];

        $is_visible = true;
        if (isset($form_item['is_visible'])) {
            $is_visible = $form_item['is_visible']();
        }
    ?>
    @if($is_visible)
        @component($view_path, [
            'model' => $model,
            'form_item' => $form_item,
            'obj' => isset($obj) ? $obj : null,
            'locale' => isset($locale) ? $locale : null
        ])
        @endcomponent
    @endif
@endforeach