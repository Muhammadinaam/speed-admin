@extends('speed-admin::layouts.layout')

@section('content')
    @component('speed-admin::components.developer_options')
    @endcomponent

    @component('speed-admin::components.crud.grid', [
        'model' => $model,
        'index_url' => $index_url,
        'get_data_url' => $get_data_url,
        'show_check_boxes' => true
    ])
    @endcomponent()

@endsection