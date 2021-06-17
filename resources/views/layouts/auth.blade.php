<!DOCTYPE html>
<!--
* CoreUI - Free Bootstrap Admin Template
* @version v3.4.0
* @link https://coreui.io
* Copyright (c) 2020 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://coreui.io/license)
-->
<html lang="en" {{ $is_rtl ? 'dir=rtl' : '' }} >

<head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="{{config('speed-admin.meta_description')}}">
    <meta name="author" content="{{config('speed-admin.meta_author')}}">
    <meta name="keyword" content="{{config('speed-admin.meta_keyword')}}">
    <title>{{config('speed-admin.title')}}</title>
    
    <link rel="apple-touch-icon" sizes="57x57" href="{{asset(config('speed-admin.speed_admin_assets_path').'coreui3.4.0/assets/favicon/apple-icon-57x57.png')}}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{asset(config('speed-admin.speed_admin_assets_path').'coreui3.4.0/assets/favicon/apple-icon-60x60.png')}}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{asset(config('speed-admin.speed_admin_assets_path').'coreui3.4.0/assets/favicon/apple-icon-72x72.png')}}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset(config('speed-admin.speed_admin_assets_path').'coreui3.4.0/assets/favicon/apple-icon-76x76.png')}}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{asset(config('speed-admin.speed_admin_assets_path').'coreui3.4.0/assets/favicon/apple-icon-114x114.png')}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{asset(config('speed-admin.speed_admin_assets_path').'coreui3.4.0/assets/favicon/apple-icon-120x120.png')}}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{asset(config('speed-admin.speed_admin_assets_path').'coreui3.4.0/assets/favicon/apple-icon-144x144.png')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{asset(config('speed-admin.speed_admin_assets_path').'coreui3.4.0/assets/favicon/apple-icon-152x152.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset(config('speed-admin.speed_admin_assets_path').'coreui3.4.0/assets/favicon/apple-icon-180x180.png')}}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{asset(config('speed-admin.speed_admin_assets_path').'coreui3.4.0/assets/favicon/android-icon-192x192.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset(config('speed-admin.speed_admin_assets_path').'coreui3.4.0/assets/favicon/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{asset(config('speed-admin.speed_admin_assets_path').'coreui3.4.0/assets/favicon/favicon-96x96.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset(config('speed-admin.speed_admin_assets_path').'coreui3.4.0/assets/favicon/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset(config('speed-admin.speed_admin_assets_path').'coreui3.4.0/assets/favicon/manifest.json')}}">

    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="assets/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <!-- Main styles for this application-->
    <link href="{{asset(config('speed-admin.speed_admin_assets_path').'coreui3.4.0/css/style.css')}}" rel="stylesheet">
    <!-- Global site tag (gtag.js) - Google Analytics-->
    <script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-118965717-3"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        // Shared ID
        gtag('config', 'UA-118965717-3');
        // Bootstrap ID
        gtag('config', 'UA-118965717-5');

    </script>
</head>

<body class="c-app flex-row align-items-center">

    @yield('content')

    <!-- CoreUI and necessary plugins-->
    <script src="{{asset(config('speed-admin.speed_admin_assets_path').'coreui3.4.0/vendors/@coreui/coreui/js/coreui.bundle.min.js')}}"></script>
    <!--[if IE]><!-->
    <script src="{{asset(config('speed-admin.speed_admin_assets_path').'coreui3.4.0/vendors/@coreui/icons/js/svgxuse.min.js')}}"></script>
    <!--<![endif]-->


</body>

</html>
