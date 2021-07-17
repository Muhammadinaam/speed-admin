@extends('speed-admin::layouts.layout')

@section('content')
    @component('speed-admin::components.developer_options')
    @endcomponent

    @component('speed-admin::components.crud.form', [
        'model' => $model,
        'obj' => isset($obj) ? $obj : null,
        'index_url' => $index_url,
        'show_list_button' => isset($show_list_button) ? $show_list_button : true // default true
    ])
    @endcomponent()

@endsection