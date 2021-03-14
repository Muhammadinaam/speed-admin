<div class="repeater" data-id="{{$form_item['id']}}" data-initialized="false"
        data-initialize_function_name="initRepeater">
    @if($form_item['label'])
    <h4>{{$form_item['label']}}</h4>
    @endif

    @if($form_item['table_view'])

        @if(isset($form_item['children']))
            <table class="table table-sm">
                <thead>
                    @foreach($form_item['children'] as $child)
                    <th>{{$child['label']}}</th>
                    @endforeach
                    <th></th>
                </thead>
                <tbody class="repeated-items-container">
                    <tr style="display: none;" class="template repeated-item">
                    <input type="hidden" name="__{{$form_item['id']}}[]" value="1">
                    @if(isset($form_item['children']))
                        @foreach($form_item['children'] as $child)
                            <?php
                            $view_path = isset($child['view_path']) ? 
                                $child['view_path'] : 
                                'speed-admin::components.form_components.' . $child['type'];

                            $rendered_view = view($view_path, [
                                'model' => $model,
                                'form_item' => $child,
                                'obj' => isset($obj) ? $obj : null,
                            ])->render();
                            ?>
                            <td>
                                {!! $rendered_view !!}
                            </td>
                        @endforeach
                        <td>
                            <button onclick="removeRepeatedItem(this)" type="button" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    @endif
                    </tr>
                </tbody>
            </table>

            <button onclick="addRepeatedItem(this)" type="button" class="btn btn-sm btn-info">
                <i class="fas fa-plus"></i>
            </button>
        @endif

    @else
        @if(isset($form_item['children']))
            <div class="repeated-items-container">
                <div style="display: none;" class="template repeated-item">
                <input type="hidden" name="__{{$form_item['id']}}[]" value="1">
                @component('speed-admin::components.form_components.form_items', [
                    'model' => $model,
                    'form_items' => $form_item['children'],
                    'obj' => isset($obj) ? $obj : null,
                ])
                @endcomponent
                <button onclick="removeRepeatedItem(this)" type="button" class="btn btn-sm btn-danger">
                    <i class="fas fa-trash"></i>
                </button>
                <hr>
                </div>
            </div>

            <button onclick="addRepeatedItem(this)" type="button" class="btn btn-sm btn-info">
                <i class="fas fa-plus"></i>
            </button>
        @endif
    @endif

    
</div>