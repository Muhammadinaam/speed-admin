# Brands CRUD

In root folder of your project, run the following command in the terminal to create model and migration for \`brands\` database table.

```text
php artisan make:model Brand -m
```

Update migration file as shown below to add database columns for `image, name and is_active` . Add following code in `up()` function.

{% tabs %}
{% tab title="PHP" %}
```php
$table->string('image')->nullable();
$table->string('name')->unique();
$table->boolean('is_active')->default(true);
SpeedAdminHelpers::createdByUpdatedByMigrations($table);
            
```
{% endtab %}
{% endtabs %}

