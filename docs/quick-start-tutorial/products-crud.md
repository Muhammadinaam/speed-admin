# Products CRUD

Everything is the same as creating CRUD for Brands except few things. We will only discuss **NEW** things here.

## Database migration for products table:

```php
php artisan make:model Product -m
```

```php
public function up()
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();

        $table->string('image')->nullable();
        $table->string('name')->unique();
        $table->foreignId('brand_id');
        $table->decimal('price');
        $table->boolean('is_active')->default(true);

        SpeedAdminHelpers::createdByUpdatedByMigrations($table);

        $table->timestamps();
    });
}
```

## Database migration for "category\_product" table for many\_to\_many relation between products and categories

```php
php artisan make:migration create_table_category_product --create=category_product
```

```php
public function up()
{
    Schema::create('category_product', function (Blueprint $table) {
        $table->id();
        $table->foreignId('product_id');
        $table->foreignId('category_id');
        $table->timestamps();
    });
}
```



