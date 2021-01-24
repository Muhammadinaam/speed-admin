<li class="c-sidebar-nav-item c-sidebar-nav-dropdown" data-id={{$menu_item['id']}}>
    <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" href="#">
        <svg class="c-sidebar-nav-icon">
            <use xlink:href="{{ asset(config('speed-admin.coreui_assets_path').'vendors/@coreui/icons/svg/free.svg#'. $menu_item['icon']) }}">
            </use>
        </svg> {{$menu_item['title']}}
    </a>
    <ul class="c-sidebar-nav-dropdown-items">
        @foreach($menu_item['children'] as $child_menu_item)
            @if(!isset($child_menu_item['children']))
                @component('speed-admin::components.sidebar.menu_item_without_children', ['menu_item' => $child_menu_item])
                @endcomponent
            @else
                @component('speed-admin::components.sidebar.menu_item_with_children', ['menu_item' => $child_menu_item])
                @endcomponent
            @endif
        @endforeach
    </ul>
</li>
