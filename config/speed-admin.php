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
    'footer_left_html' => '<a href="#">CoreUI</a> © Speed Admin.',

    'user_primary_key_type' => 'integer',    // 'integer' or 'uuid'

    'enable_tenant_organization_feature' => false,

    'default_model_locale' => 'en',
    'additional_model_locales' => [
        ['name' => 'Urdu', 'locale' => 'ur'],
        ['name' => 'Arabic', 'locale' => 'ar'],
    ],

    'date_format' => 'd-M-Y',
    'time_format' => 'H:i',

    'settings_enabled' => false,
    'applications_enabled' => true,
];
