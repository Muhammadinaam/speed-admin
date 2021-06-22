# Brands CRUD

In root folder of your project, run the following command in the terminal to create model and migration for \`brands\` database table.

```text
php artisan make:model Brand -m
```

Add the following code to the `up()` function of the `brands` table migration file.

```php
...

public function up()
{
    Schema::create('brands', function (Blueprint $table) {
        
        $table->id();
        $table->timestamps();
        
        // column to store image path
        $table->string('image')->nullable();
        
        // column to store brand name
        $table->string('name')->unique();
        
        // column to specify if brand is active or not
        $table->boolean('is_active')->default(true);
        
        // columns for storing created_by, updated_by, craeted_at 
        // and updated_at
        SpeedAdminHelpers::createdByUpdatedByMigrations($table);
    });
}
...
```

Run command `php artisan migrate` to create the "brands" database table.

