<li class="c-sidebar-nav-item" data-id={{$menu_item['id']}} >
    <a class="c-sidebar-nav-link" href="{{$menu_item['href']}}">
        @if(!isset($menu_item['fa_icon']))
        <svg class="c-sidebar-nav-icon">
            <use
                xlink:href="{{ asset(config('speed-admin.speed_admin_assets_path').'coreui3.4.0/vendors/@coreui/icons/svg/free.svg#' . $menu_item['icon']) }}">
            </use>
        </svg>
        @else
        <i class="c-sidebar-nav-icon fa {{$menu_item['fa_icon']}}"></i>
        @endif
        {{$menu_item['title']}} <!--<span class="badge badge-info">NEW</span> -->
    </a>
</li>
