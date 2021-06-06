<?php
    $uniqid = uniqid();
?>
<form
    id="form_{{$uniqid}}"
    onsubmit="speedAdmin.submitForm(event)"
    action="{{ isset($obj) ? $index_url . '/' . $obj->id : $index_url }}" 
    method="post" 
    data-uniqid="{{$uniqid}}"
    data-model="{{get_class($model)}}">

    @csrf

    @if(isset($obj))
    @method('PUT')
    @endif

    <div class="card">
        <div class="card-header p-1">
            <div class="row">
                <div class="col">
                    <h5 class="mb-0 p-1">{{ __($model->getSingularTitle()) }}</h5>
                </div>
                @if(isset($index_url))
                <div
                    class="col text-{{ $is_rtl ? 'left' : 'right' }}">
                    <?php
                        $show_list_button = isset($show_list_button) ? $show_list_button : true; // default true
                    ?>

                    @if($show_list_button)
                        @if(\SpeedAdminHelpers::hasPermission($model->getListPermissionId()))
                        <a class="btn btn-sm btn-primary" href="{{ $index_url }}">
                            <i class="fas fa-list"></i> {{ __('List') }}
                        </a>
                        @endif
                    @endif

                </div>
                @endif
            </div>
        </div>
        <div class="card-body p-2">

            @component('speed-admin::components.form_components.form_items', [
                'model' => $model,
                'form_items' => $model->getFormItems(),
                'obj' => isset($obj) ? $obj : null,
            ])
            @endcomponent

            @component('speed-admin::components.form_components.translations_form_items', [
                'model' => $model,
                'obj' => isset($obj) ? $obj : null,
                'uniqid' => $uniqid
            ])
            @endcomponent

        </div>
        <div class="card-footer p-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> {{__('Save')}}
            </button>
        </div>
    </div>

    @if(!request()->has('dont_redirect_on_save'))
    <script>  
        speedAdmin.ready(function(){
            form = document.querySelector('#form_{{$uniqid}}')
            form.addEventListener('saved', function(){
                window.location.href = "{{$index_url}}"
            })
        })
    </script>
    @endif
    <script>
        speedAdmin.ready(function(){
            form = document.querySelector('#form_{{$uniqid}}')
            speedAdmin.initializeUninitializedItems(form);
        })
    </script>
</form>