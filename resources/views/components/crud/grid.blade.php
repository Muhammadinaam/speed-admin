<?php
    $uniqid = uniqid();
?>
<div class="card">
    <div class="card-header p-1">
        <div class="row">
            <div class="col-md-8">
                <h5 class="mb-0 p-1" style="display: inline;">{{ __($model->getPluralTitle()) }}</h5>
                
                <div class="input-group input-group-sm" style="display: inline-flex; width: 250px">
                    <input form="form-options-{{ $uniqid }}" type="text" name="__search__" class="form-control" placeholder="click search button">
                    <div class="input-group-append">
                        <button onclick="getData_{{$uniqid}}()" class="btn btn-outline-secondary" type="button">
                            Search
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-{{ $is_rtl ? 'left' : 'right' }}">


                <button class="btn btn-sm btn-info" onclick="toggleFilters_{{$uniqid}}()">
                    <i class="cil-filter"></i> {{ __('Filter') }}
                </button>
                @if($model->_is_add_enabled)
                    @if(\SpeedAdminPermissions::hasPermission($model->_add_permission_slug))
                    <a class="btn btn-sm btn-primary" href="{{ $index_url . '/create' }}">
                        <i class="cil-plus"></i> {{ __('Add new') }}
                    </a>
                    @endif
                @endif
            </div>
        </div>
    </div>
    <div class="card-body p-2">
        <form id="form-options-{{ $uniqid }}">
            <input type="hidden" name="page" value="{{request()->page}}">
            <input type="hidden" name="order" value="{{request()->order}}">

            <div class="filters d-none">
                @component('speed-admin::components.crud.grid_filters', [
                    'model' => $model,
                    'uniqid' => $uniqid
                ])
                @endcomponent
            </div>
        </form>

        <div class="table-responsive">
            <table id="table_{{$uniqid}}" class="table table-sm">
                <thead>
                    <tr class="bg-dark">
                        @foreach($model->getGridColumns() as $column)
                            <th style="white-space: nowrap;" data-id="{{ $column['id'] }}">
                                {{ $column['title'] }}
                                @if( isset($column['order_by']) )
                                <button 
                                    class="btn-order btn btn-secondary btn-sm py-0 px-1" 
                                    id="order_button_{{$column['id']}}_{{$uniqid}}" onclick='setOrder_{{$uniqid}}("{{$column['id']}}")'>
                                        <span></span>
                                        <i class="fas fa-arrows-alt-v"></i>
                                </button>
                                @endif
                            </th>
                        @endforeach
                        <th>
                            {{ __('Action') }}
                        </th>
                    </tr>
                </thead>
                <tbody id="table-body-{{ $uniqid }}">

                </tbody>
            </table>
        </div>
        <div id="loader-{{ $uniqid }}" class="text-center">
            <i class="fas fa-circle-notch fa-spin"></i>
        </div>
        <div class="row">
            <div class="col-md-12" id="table-pagination-info-{{$uniqid}}">
            </div>
            <div class="col-md-12">
                <div class="row px-3">
                    <select onchange="getData_{{$uniqid}}()" class="form-control-sm" name="per_page" form="form-options-{{$uniqid}}">
                        @foreach([5, 10, 20, 30, 50, 100] as $per_page)
                        <option value="{{$per_page}}" 
                        {{request()->per_page == '' && $per_page == 10 ? 'selected' : request()->per_page == $per_page ? 'selected' : ''}}>
                            {{__(':count per page', ['count' => $per_page])}}
                        </option>
                        @endforeach
                    </select>
                    <span id="table-pagination-{{$uniqid}}">
                    </span>
                    <span>
                        <button onclick="getData_{{$uniqid}}()" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-circle-notch"></i> {{__('Reload')}}
                        </button>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    ready(function(){
        getData_{{$uniqid}}();

        document.addEventListener('click', function (event) {

            if (event.target.matches("#table-pagination-{{$uniqid}} a")) {
                // Don't follow the link
                event.preventDefault();

                // Log the clicked element in the console
                let page = getParameterByNameFromUrl('page', event.target.href)
                document.querySelector('#form-options-{{$uniqid}} [name="page"]').value = page;
                getData_{{$uniqid}}();
            }

        }, false);

        updateOrderButtonsUI_{{$uniqid}}()
    })

    function deleteItem_{{$uniqid}}(button, id) {
        Swal.fire({
            title: "{{__('Are you sure?')}}",
            text: "{!! __('You won\'t be able to revert this!') !!}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: "{{__('Yes, delete it!')}}"
        }).then((result) => {
            if (result.isConfirmed) {

                axios.post("{{$index_url}}/" + id, {
                    '_method': 'DELETE', 
                    '_token': "{{@csrf_token()}}"
                })
                .catch(error => {
                    handleAjaxError(error);
                })
                .then((response) => {
                    data = response.data;
                    Swal.fire(
                    "",
                    data.message,
                    data.success ? 'success' : 'error'
                    )

                    getData_{{$uniqid}}()
                })
            }
        })
    }

    function setOrder_{{$uniqid}}(fieldName) {
        // update order field
        let order = document.querySelector('#form-options-{{$uniqid}} [name="order"]').value;
        let orderItems = order == '' ? [] :order.split(',');
        let newOrderItems = [];

        let alreadyExists = false;
        for(let i = 0; i < orderItems.length; i++) {
            let orderItemParts = orderItems[i].split(':');
            let orderItemField = orderItemParts[0];
            let orderItemAscOrDesc = orderItemParts[1];

            if(orderItemField == fieldName) {
                alreadyExists = true;

                if(orderItemAscOrDesc == 'asc')
                {
                    orderItemAscOrDesc = 'desc';
                    newOrderItems.push(orderItemField + ":" + orderItemAscOrDesc);
                }
            } else {
                newOrderItems.push(orderItemField + ":" + orderItemAscOrDesc);
            }
        }

        if(alreadyExists == false) {
            newOrderItems.push(fieldName+":asc")
        }
        document.querySelector('#form-options-{{$uniqid}} [name="order"]').value = newOrderItems.join(',');

        // update order buttons look
        updateOrderButtonsUI_{{$uniqid}}();

        // get data
        getData_{{$uniqid}}()
    }

    function updateOrderButtonsUI_{{$uniqid}}() {

        // reset all buttons
        buttons = document.querySelectorAll('#table_{{$uniqid}} .btn-order')
        for(let i = 0; i < buttons.length; i++) {
            const button = buttons[i];
            button.classList.remove('btn-info');
            button.classList.add('btn-secondary');
            button.querySelector('span').innerHTML = '';
            button.querySelector('i').className = 'fas fa-arrows-alt-v';
        }

        let order = document.querySelector('#form-options-{{$uniqid}} [name="order"]').value;
        let orderItems = order == '' ? [] :order.split(',');
        for(let i = 0; i < orderItems.length; i++) {
            let orderItem = orderItems[i];
            let orderItemParts = orderItem.split(':');
            let orderItemField = orderItemParts[0];
            let orderItemAscOrDesc = orderItemParts[1];

            let button = document.querySelector('#order_button_'+orderItemField+'_{{$uniqid}}');
            button.classList.remove('btn-secondary');
            button.classList.add('btn-info');
            button.querySelector('span').innerHTML = i+1;
            button.querySelector('i').className = orderItemAscOrDesc == 'asc' ? 'fas fa-arrow-up' : 'fas fa-arrow-down';
        }
    }

    function toggleFilters_{{$uniqid}}(){
        document.querySelector('#form-options-{{$uniqid}} .filters').classList.toggle('d-none');
    }
    
    function getData_{{$uniqid}}() {

        document.getElementById("loader-{{$uniqid}}").style.display = 'block';
        document.getElementById("table-body-{{$uniqid}}").innerHTML = '';
        document.getElementById("table-pagination-info-{{$uniqid}}").innerHTML = '';

        let getDataUrl = "{{$get_data_url}}"
        let indexUrl = new URL("{{$index_url}}")
        let url = new URL(getDataUrl);

        var form = document.querySelector('#form-options-{{$uniqid}}');
        var data = new FormData(form);

        url.search = new URLSearchParams(data)
        indexUrl.search = new URLSearchParams(data);

        axios.get(url)
        .then(response => {
            let data = response.data;

            let items = data.paginated_data.data;

            let tableBody = document.getElementById("table-body-{{$uniqid}}");
            let tablePagination = document.getElementById("table-pagination-{{$uniqid}}");

            tablePagination.innerHTML = '';
            tablePagination.innerHTML = data.links;
            let pagination = document.querySelector("#table-pagination-{{$uniqid}} .pagination");
            if(pagination) {
                pagination.classList.add('pagination-sm');
            }

            document.getElementById("table-pagination-info-{{$uniqid}}").innerHTML = data.pagination_info;

            let tableRows = '';
            for(let i = 0; i < items.length; i++) {
                tableRows += '<tr>';
                
                let keys = Object.keys(items[i]);

                for(let j = 0; j < keys.length; j++) {
                    let key = keys[j];
                    if (key != '__id__') {
                        tableRows += '<td>'+items[i][key]+'</td>';
                    }
                }

                // action column
                let editButton = '';
                @if(\SpeedAdminPermissions::hasPermission($model->_edit_permission_slug))
                editButton = `<a href="{{$index_url}}/`+items[i].__id__+`/edit" class="btn btn-sm btn-info" >
                    <i class="fas fa-edit"></i>
                </a>`
                @endif

                let deleteButton = '';
                @if(\SpeedAdminPermissions::hasPermission($model->_delete_permission_slug))
                deleteButton = `<button type="button" onclick="deleteItem_{{$uniqid}}(this, `+items[i].__id__+`)" class="btn btn-sm btn-danger" >
                    <i class="fas fa-trash"></i>
                </button>`
                @endif

                tableRows += '<td>'+editButton+deleteButton+'</td>';

                tableRows += '</tr>';
            }

            tableBody.innerHTML = tableRows;

            window.history.pushState("object or string", "Title", indexUrl);
        })
        .catch(error => {
            handleAjaxError(error)
        })
        .finally(() => {
            document.getElementById("loader-{{$uniqid}}").style.display = 'none';
        });
    }
</script>