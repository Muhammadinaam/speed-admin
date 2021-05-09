<?php

return [

    'developer_mode' => false,

    'title' => 'Speed Admin',

    'meta_description' => 'Speed Admin Project',
    'meta_author' => 'Muhammad Inaam Munir',
    'meta_keyword' => 'Speed Admin, Laravel Admin, CRUD Admin Applications',

    'admin_url' => 'admin',
    'speed_admin_assets_path' => 'vendor/speed-admin/',

    'languages' => [
        ['name' => 'English', 'locale' => 'en', 'rtl' => false],
        ['name' => 'Urdu', 'locale' => 'ur', 'rtl' => true],
    ],

    'footer_right_html' => 'Powered by&nbsp;<a href="#">Speed Admin</a>',
    'footer_left_html' => '<a href="#">CoreUI</a> © 2021 Speed Admin.',

    'user_primary_key_type' => 'integer'    // 'integer' or 'uuid'
];
