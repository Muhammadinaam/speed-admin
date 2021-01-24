<li class="c-sidebar-nav-item" data-id={{$menu_item['id']}} >
    <a class="c-sidebar-nav-link" href="{{$menu_item['href']}}">
        <svg class="c-sidebar-nav-icon">
            <use
                xlink:href="{{ asset(config('speed-admin.coreui_assets_path').'vendors/@coreui/icons/svg/free.svg#' . $menu_item['icon']) }}">
            </use>
        </svg> {{$menu_item['title']}} <!--<span class="badge badge-info">NEW</span> -->
    </a>
</li>
