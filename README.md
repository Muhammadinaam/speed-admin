# Speed Admin

A rapid application development framework for Laravel. Documentation: [https://minaammunir.gitbook.io/speed-admin/](https://minaammunir.gitbook.io/speed-admin/)

## Installation

Install via composer

```bash
composer require muhammad-inaam-munir/speed-admin
```

### Publish package assets

```bash
php artisan vendor:publish --provider="MuhammadInaamMunir\SpeedAdmin\ServiceProvider"
```

## Add alias in app.php:

'SpeedAdminHelpers' =&gt; MuhammadInaamMunir\SpeedAdmin\Facades\SpeedAdminHelpersFacade::class,

## Run following commands

`php artisan config:cache php artisan config:clear php artisan clear-compiled composer dump autoload`

## Run following command to create admin user

`php artisan speed-admin:create-admin-user`

## Thanks to follwoing packages

spatie/laravel-translatable coreui

