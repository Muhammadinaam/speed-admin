# How to add Modules/Applications

Speed-admin uses [https://github.com/nWidart/laravel-modules](https://github.com/nWidart/laravel-modules) for managing applications/modules.

### Autoload nwidart modules

Update composer.json to autoload modules. Add `"Modules\": "Modules/"` as shown below in psr-4.

{% code title="composer.json" %}
```javascript
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

### Add new module

Run the following command to add new module. \([see nwidart documentation](https://github.com/nWidart/laravel-modules)\)

```javascript
php artisan module:make <module-name>
```

