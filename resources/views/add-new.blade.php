@component('speed-admin::components.crud.form', [
    'model' => $model,
    'index_url' => $index_url,
    'show_list_button' => isset($show_list_button) ? $show_list_button : true, // default true
])
@endcomponent()