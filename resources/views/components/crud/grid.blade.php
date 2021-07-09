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
                        <button onclick="speedAdmin.getGridData('{{$uniqid}}')" class="btn btn-outline-secondary" type="button">
                            Search
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-{{ $is_rtl ? 'left' : 'right' }}">


                <!-- <button class="btn btn-sm btn-info" onclick="speedAdmin.toggleGridFilters('{{$uniqid}}')">
                    <i class="cil-filter"></i> {{ __('Filter') }}
                </button> -->

                @if($model->_is_add_enabled)
                    @if(\SpeedAdminHelpers::hasPermission($model->getAddPermissionId()))
                    <a class="btn btn-sm btn-primary" href="{{ $index_url . '/create' }}">
                        <i class="cil-plus"></i> {{ __('Add new') }}
                    </a>
                    @endif
                @endif
            </div>
        </div>
    </div>
    <div class="card-body p-2">
        <form id="form-options-{{ $uniqid }}" onsubmit="event.preventDefault(); speedAdmin.getGridData('{{$uniqid}}')">
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

        <div class="row mb-2">
            <div class="col-md-6 input-group input-group-sm">
                <select class="form-control" id="grid_action_{{$uniqid}}">
                    <option value="">---</option>
                    <option value="__delete__">{{__('Delete')}}</option>
                    @foreach($model->getGridActions() as $action)
                    <option value="{{$action['id']}}">{{$action['title']}}</option>
                    @endforeach
                </select>
                <div class="input-group-append">
                    <button type="button" onclick="speedAdmin.gridActionSelectedBtnClicked('{{$uniqid}}')" class="btn btn-outline-secondary">{{__('Apply bulk action on selected')}}</button>
                </div>
                <div class="input-group-append">
                    <button type="button" onclick="speedAdmin.gridActionAllBtnClicked('{{$uniqid}}')" class="btn btn-outline-secondary">{{__('Apply on all')}}</button>
                </div>
            </div>
        </div>

        <div id="selected_items_container_{{$uniqid}}" class="mb-2">
            <input type="hidden" name="selected_items_ids_{{$uniqid}}">
            <textarea style="display:none;" class="form-control" readonly name="selected_items_texts_{{$uniqid}}"></textarea>
        </div>

        <div class="table-responsive">

            <?php
            $other_action_buttons = '';
            foreach($model->getGridActions() as $action) {
                $other_action_buttons .= '<button type="button" '.
                    'onclick="speedAdmin.gridOtherActionButtonClicked(\''.$uniqid.'\', this, \''.$action['id'].'\')" '.
                    'class="'.$action['button_classes'].'">' . 
                    $action['button_inner_html'] . 
                '</button>';
            }
            ?>
            <div style="display: none;" id="other_actions_buttons_template_{{$uniqid}}">
                {!! $other_action_buttons !!}
            </div>

            <table
                data-model="{{urlencode(get_class($model))}}"
                data-has_edit_permission="{{\SpeedAdminHelpers::hasPermission($model->getEditPermissionId())}}"
                data-has_delete_permission="{{\SpeedAdminHelpers::hasPermission($model->getDeletePermissionId())}}"
                data-get_data_url="{{$get_data_url}}"
                data-index_url="{{$index_url}}"
                id="table_{{$uniqid}}" 
                class="table table-sm">
                <thead>
                    <tr class="bg-dark">
                    
                        @if(isset($show_check_boxes) && $show_check_boxes)
                        <th class="checkboxes">

                        </th>
                        @endif

                        @if(isset($show_radio_buttons) && $show_radio_buttons)
                        <th class="radiobuttons">
                            
                        </th>
                        @endif

                        @foreach($model->getGridColumns() as $column)
                            <th style="white-space: nowrap;" data-id="{{ $column['id'] }}">
                                {{ $column['title'] }}
                                @if( isset($column['order_by']) )
                                <button 
                                    class="btn-order btn btn-secondary btn-sm py-0 px-1" 
                                    id="order_button_{{$column['id']}}_{{$uniqid}}" onclick='speedAdmin.setGridOrder("{{$uniqid}}", "{{$column['id']}}")'>
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
                    <select onchange="speedAdmin.getGridData('{{$uniqid}}')" class="form-control-sm" name="per_page" form="form-options-{{$uniqid}}">
                        @foreach([5, 10, 20, 30, 50, 100] as $per_page)
                        <option value="{{$per_page}}" 
                        {{request()->per_page == '' && $per_page == 10 ? 'selected' : (request()->per_page == $per_page ? 'selected' : '')}}>
                            {{__(':count per page', ['count' => $per_page])}}
                        </option>
                        @endforeach
                    </select>
                    <span id="table-pagination-{{$uniqid}}">
                    </span>
                    <span>
                        <button onclick="speedAdmin.getGridData('{{$uniqid}}')" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-circle-notch"></i> {{__('Reload')}}
                        </button>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    speedAdmin.ready(function(){
        speedAdmin.getGridData('{{$uniqid}}')

        document.addEventListener('click', function (event) {

            if (event.target.matches("#table-pagination-{{$uniqid}} a")) {
                // Don't follow the link
                event.preventDefault();

                // Log the clicked element in the console
                let page = speedAdmin.getParameterByNameFromUrl('page', event.target.href)
                document.querySelector('#form-options-{{$uniqid}} [name="page"]').value = page;
                speedAdmin.getGridData('{{$uniqid}}');
            }

        }, false);

        speedAdmin.updateGridOrderButtonsUI('{{$uniqid}}')
    })
</script>