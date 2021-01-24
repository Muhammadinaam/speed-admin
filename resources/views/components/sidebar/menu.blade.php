<?php
    $menu = \App::make('speed-admin-menu')->getMenu('side-bar');
?>

@foreach($menu as $menu_item)
    @if(!isset($menu_item['children']))
        @component('speed-admin::components.sidebar.menu_item_without_children', ['menu_item' => $menu_item])
        @endcomponent
    @else
        @component('speed-admin::components.sidebar.menu_item_with_children', ['menu_item' => $menu_item])
        @endcomponent
    @endif
@endforeach
