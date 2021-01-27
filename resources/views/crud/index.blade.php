@extends('speed-admin::layouts.layout')

@section('content')
@component('speed-admin::components.developer_options')
@endcomponent

<?php
    $route_url = url(Route::current()->uri);
    $uniqid = uniqid();
?>

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h5>{{__($title)}}</h5>
                    </div>
                    <div class="col-md-6 text-{{$is_rtl ? 'left' : 'right'}}">
                        <button class="btn btn-sm btn-info">
                            <i class="cil-filter"></i> {{__('Filter')}}
                        </button>
                        <a class="btn btn-sm btn-primary" href="{{$route_url . '/create'}}">
                            <i class="cil-plus"></i> {{__('Add new')}}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            @foreach($columns as $column)
                            <th data-id="{{$column['id']}}">{{$column['title']}}</th>
                            @endforeach
                            <th>
                                {{__('Action')}}
                            </th>
                        </tr>
                    </thead>
                    <tbody id="table-body-{{$uniqid}}">

                    </tbody>
                </table>
                <div id="loader-{{$uniqid}}" class="text-center">
                    <i class="fas fa-circle-notch fa-spin"></i>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    ready(function(){
        getData();
    })
    
    function getData() {

        document.getElementById("loader-{{$uniqid}}").style.display = 'block';

        let routeUrl = "{{$route_url}}"
        let url = new URL(routeUrl);
        url.search = new URLSearchParams({
            // get_data: true
        })
        fetch(url, {
            headers: {'get-data': '1'}
        })
        .then(response => response.json())
        .then((data) => {
            let tableBody = document.getElementById("table-body-{{$uniqid}}");

            tableBody.innerHTML = '';

            let tableRows = '';
            for(let i = 0; i < data.data.length; i++) {
                tableRows += '<tr>';
                
                let keys = Object.keys(data.data[i]);

                for(let j = 0; j < keys.length; j++) {
                    let key = keys[j];
                    tableRows += '<td>'+data.data[i][key]+'</td>';
                }

                tableRows += '</tr>';
            }
            console.log(tableRows);

            tableBody.innerHTML = tableRows;
        })
        .catch((error) => {
            alert("{{__('Error occurred while trying to get data')}}");
            console.log(error)
        })
        .finally(() => {
            document.getElementById("loader-{{$uniqid}}").style.display = 'none';
        })
    }
</script>

@endsection