
<div class="repeater" data-id="{{$form_item['id']}}" data-initialized="false"
        data-initialize_function_name="initRepeater">
    
    <input type="hidden" class="deleted_items_input" name="__{{$form_item['id']}}_deleted_items" value="">

    @if($form_item['label'])
    <h4>{{$form_item['label']}}</h4>
    @endif
    @if($form_item['table_view'])

        @if(isset($form_item['children']))
            <div class="table-responsive">
                <table class="table table-sm {{$form_item['id']}}">
                    <thead>
                        @foreach($form_item['children'] as $child)
                        <th>{{$child['label']}}</th>
                        @endforeach
                        <th></th>
                    </thead>
                    <tbody class="repeated-items-container">
                        <tr style="display: none;" class="template repeated-item">
                        
                        <!-- hidden input for id (primary key) of repeated item -->
                        <input type="hidden" class="id_input" name="__{{$form_item['id']}}" value="-1">
                        
                        @if(isset($form_item['children']))
                            @foreach($form_item['children'] as $child)
                                <?php
                                $view_path = isset($child['view_path']) ? 
                                    $child['view_path'] : 
                                    'speed-admin::components.form_components.' . $child['type'];

                                $rendered_view = view($view_path, [
                                    'model' => $model,
                                    'form_item' => $child,
                                    'obj' => null,
                                ])->render();
                                ?>
                                <td>
                                    {!! $rendered_view !!}
                                </td>
                            @endforeach
                            <td>
                                <button onclick="speedAdmin.removeRepeatedItem(this)" type="button" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        @endif
                        </tr>

                        @if(isset($obj) && $obj != null && $obj->{$form_item['relation_name']} != null && count($obj->{$form_item['relation_name']}) > 0)
                        @foreach($obj->{$form_item['relation_name']} as $repeated_item)
                        <tr class="repeated-item">
                        
                        <!-- hidden input for id (primary key) of repeated item -->
                        <input type="hidden" class="id_input" name="__{{$form_item['id']}}" value="{{$repeated_item->getKey()}}">
                        
                        @if(isset($form_item['children']))
                            @foreach($form_item['children'] as $child)
                                <?php
                                $view_path = isset($child['view_path']) ? 
                                    $child['view_path'] : 
                                    'speed-admin::components.form_components.' . $child['type'];
                                
                                $rendered_view = view($view_path, [
                                    'model' => $model,
                                    'form_item' => $child,
                                    'obj' => $repeated_item,
                                ])->render();
                                ?>
                                <td>
                                    {!! $rendered_view !!}
                                </td>
                            @endforeach
                            <td>
                                <button onclick="speedAdmin.removeRepeatedItem(this)" type="button" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        @endif
                        </tr>
                        @endforeach
                        @endif

                    </tbody>
                </table>
            </div>

            <button onclick="speedAdmin.addRepeatedItem(this)" type="button" class="btn btn-sm btn-info">
                <i class="fas fa-plus"></i>
            </button>
        @endif

    @else
        @if(isset($form_item['children']))
            <div class="repeated-items-container {{$form_item['id']}}">
                <div style="display: none;" class="template repeated-item">

                <!-- hidden input for id (primary key) of repeated item -->
                <input type="hidden" class="id_input" name="__{{$form_item['id']}}" value="-1">
                
                @component('speed-admin::components.form_components.form_items', [
                    'model' => $model,
                    'form_items' => $form_item['children'],
                    'obj' => null,
                ])
                @endcomponent
                <button onclick="speedAdmin.removeRepeatedItem(this)" type="button" class="btn btn-sm btn-danger">
                    <i class="fas fa-trash"></i>
                </button>
                <hr>
                </div>

                @if(isset($obj) && $obj != null && $obj->{$form_item['relation_name']} != null && count($obj->{$form_item['relation_name']}) > 0)
                @foreach($obj->{$form_item['relation_name']} as $repeated_item)
                <div class="repeated-item">

                <!-- hidden input for id (primary key) of repeated item -->
                <input type="hidden" class="id_input" name="__{{$form_item['id']}}" value="{{$repeated_item->getKey()}}">
                
                @component('speed-admin::components.form_components.form_items', [
                    'model' => $model,
                    'form_items' => $form_item['children'],
                    'obj' => $repeated_item,
                ])
                @endcomponent
                <button onclick="speedAdmin.removeRepeatedItem(this)" type="button" class="btn btn-sm btn-danger">
                    <i class="fas fa-trash"></i>
                </button>
                <hr>
                </div>
                @endforeach
                @endif

            </div>

            <button onclick="speedAdmin.addRepeatedItem(this)" type="button" class="btn btn-sm btn-info">
                <i class="fas fa-plus"></i>
            </button>
        @endif
    @endif

    
</div>