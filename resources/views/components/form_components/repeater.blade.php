<?php
    $uniqid = uniqid();
?>

<div id="repeater_{{$uniqid}}" data-id="{{$form_item['id']}}">
    @if($form_item['label'])
    <h4>{{$form_item['label']}}</h4>
    @endif

    @if($form_item['table_view'])

        @if(isset($form_item['children']))
            <table class="table">
                <thead>
                    @foreach($form_item['children'] as $child)
                    <th>{{$child['label']}}</th>
                    @endforeach
                    <th></th>
                </thead>
                <tbody>
                </tbody>
            </table>

            <button onclick="addRow_{{$uniqid}}()" type="button" class="btn btn-sm btn-info">
                <i class="fas fa-plus"></i>
            </button>
        @endif

    @else
    only table_view is supported at the moment
    @endif

    <script>
    function addRow_{{$uniqid}}() {
        let templateRow = '<tr>';

        @if(isset($form_item['children']))
            @foreach($form_item['children'] as $child)
                <?php
                    $view_path = isset($child['view_path']) ? 
                        $child['view_path'] : 
                        'speed-admin::components.form_components.' . $child['type'];
                ?>
                templateRow += "<td>" +
                @component($view_path, [
                    'model' => $model,
                    'form_item' => $child,
                    'obj' => isset($obj) ? $obj : null,
                ])
                @endcomponent 
                + "</td>"
            @endforeach
        @endif

        templateRow += '</tr>';

        $('#repeater_{{$uniqid}} tbody').append(templateRow);
    }
    </script>
</div>