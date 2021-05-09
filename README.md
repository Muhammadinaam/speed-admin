# speed-admin
A rapid application development framework for laravel

### Under development


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

'SpeedAdminHelpers' => MuhammadInaamMunir\SpeedAdmin\Facades\SpeedAdminHelpersFacade::class,

## Run following commands

`
php artisan config:cache
php artisan config:clear
php artisan clear-compiled
composer dump autoload
`

## Run following command to create admin user

`
php artisan speed-admin:create-admin-user
`
