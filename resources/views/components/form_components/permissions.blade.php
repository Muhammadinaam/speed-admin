<?php
    $permissions = collect(app()->make('speed-admin-permissions')->getPermissions());
?>

<table class="table table-hover">
    <thead>
        <th colspan=2 class="bg-dark">
            Permissions
        </th>
    </thead>
    <tbody>
        @foreach($permissions->groupBy('group') as $group => $grouped_permissions)
        <tr>
            <td style="text-indent: 0px; font-weight: bold;" colspan="2">{{$group}}</td>
        </tr>
            @foreach($grouped_permissions->groupBy('sub_group') as $sub_group => $sub_grouped_permissions)
            <tr>
                <td style="text-indent: 20px; font-weight: bold;" colspan="2">{{$sub_group}}</td>
            </tr>
                @foreach($sub_grouped_permissions as $sub_grouped_permission)
                <tr>
                    <td style="text-indent: 40px;">{{$sub_grouped_permission['permission_label']}}</td>
                    <td>

                        <?php
                            $checked = false;
                            if(isset($obj) && $obj->permissions != null)
                            {
                                $found_permission = $obj->permissions->first(function($p) use ($sub_grouped_permission) {
                                    return $p->permission_id == $sub_grouped_permission['permission_id'];
                                });

                                $checked = $found_permission != null;
                            }
                        ?>

                        <input type="checkbox" 
                            {{$checked ? 'checked' : ''}}
                            name="permissions[{{$sub_grouped_permission['permission_id']}}]" 
                            id="{{$sub_grouped_permission['permission_id']}}"
                            value="1">
                    </td>
                </tr>
                
                @endforeach
            @endforeach
        @endforeach
    </tbody>
</table>