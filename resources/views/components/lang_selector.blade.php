<?php
    $languages = config('speed-admin.languages');
    $current_language = collect($languages)->first(function($language) {
        return $language['locale'] == \App::currentLocale();
    })
?>
@if(count($languages) > 1)
<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        {{__('Language') == 'Language' ? __('Language') : __('Language') . '(Language)'}} - {{ $current_language != null ? __($current_language['name']) : '' }}
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        @foreach($languages as $language)
        <a class="dropdown-item" 
            href="{{route('admin.select-language') . '?locale=' . $language['locale'] . '&return_url=' . url()->current() }}">
            {{$language['name']}} ({{__($language['name'])}})
        </a>
        @endforeach
    </div>
</div>
@endif