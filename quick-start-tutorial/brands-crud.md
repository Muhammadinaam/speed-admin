# Brands CRUD

In root folder of your project, run the following command in the terminal to create model and migration for \`brands\` database table.

```text
php artisan make:model Brand -m
```

Update migration file as shown below to add database columns for `image, name and is_active` . Notice \(ADD THIS CODE\) comment below

{% tabs %}
{% tab title="PHP" %}
{% code title="database\\migrations\\2021\_06\_21\_013840\_create\_brands\_table.php" %}
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();

            // ADD THIS CODE
            ////////////////////////////////////
            $table->string('image')->nullable();
            $table->string('name')->unique();
            $table->boolean('is_active')->default(true);
            SpeedAdminHelpers::createdByUpdatedByMigrations($table);
            ////////////////////////////////////
            ///////////////////////////////////

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('brands');
    }
}

```
{% endcode %}
{% endtab %}
{% endtabs %}

