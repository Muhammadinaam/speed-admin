@if(config('speed-admin.developer_mode'))
<?php
    $controller_action = Route::currentRouteAction();
    list($controller, $action) = explode('@', $controller_action);
    $controller = '\\' . $controller;
    $controller_instance = new $controller();
?>
<div>
    <h5>
        Developer info:
    </h5>
    <ul>
        <li>{{'Route: ' . Route::currentRouteName()}}</li>
        <li>{{'Controller: ' . $controller}}</li>
        <li>{{'Action: ' . $action}}</li>
        <li>{{'Parent Model: ' . $controller_instance->getModelClassName()}}</li>
        <li>
            {{'Model Child Classes: ' . implode(', ', \SpeedAdminHelpers::getModelChildClasses($controller_instance->getModelClassName())) }}
        </li>
    </ul>
</div>
@endif