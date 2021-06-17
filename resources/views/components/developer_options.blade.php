@if(config('speed-admin.developer_mode'))
<div>
    Developer info: {{'Route: ' . Route::currentRouteName() . ', Route Action: ' . Route::currentRouteAction()}}
</div>
@endif