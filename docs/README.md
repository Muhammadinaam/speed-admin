# Speed Admin

**A rapid application development framework** for Laravel 8+. It has built-in Users Management with Roles and Permissions. It speeds up the development of CRUD functionality \(adding data tables and forms\).

## Quickly create Forms and Grids:

Easy to create data tables \(grid\)

![Easy to create datatables \(grid\)](.gitbook/assets/grid_pic.png)

Easy to add Forms with **translations support**

![Easy to add Forms with translations support](.gitbook/assets/form_pic.png)

## Installation

Install via composer

```bash
composer require muhammad-inaam-munir/speed-admin
```

### Publish package config file and assets

```bash
php artisan vendor:publish --tag=speed-admin-config
php artisan vendor:publish --tag=speed-admin-public
```

### Add alias in app.php \(Optional\):

```php
'SpeedAdminHelpers' => MuhammadInaamMunir\SpeedAdmin\Facades\SpeedAdminHelpersFacade::class,
```

### Run following commands

```bash
php artisan migrate
php artisan config:cache
php artisan config:clear
php artisan clear-compiled
composer dump-autoload
```

### Autoloading Applications/Modules \(nwidart/modules\)

Update composer.json to autoload modules. Add `"Modules\": "Modules/"` as shown below in psr-4.

{% code title="composer.json" %}
```bash
{
  "autoload": {
    "psr-4": {
      "App\\": "app/",
      
      
      "Modules\\": "Modules/"
    }
  }
}
```
{% endcode %}

### Run the following command to create the admin user

The following command will create admin user username: **admin@admin.com** and password: **admin**.

```bash
php artisan speed-admin:create-admin-user
```

### Visit admin page:

Visit `http://localhost:8000/admin` if you are running server through `php artisan serve` or you can directly visit `http://localhost/project_folder/public/admin`. Use the above credentials to log in.

## Credits: thanks to the following packages

* spatie/laravel-translatable
* coreui

## License

MIT

