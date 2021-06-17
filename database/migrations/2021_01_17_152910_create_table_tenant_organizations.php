<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTenantOrganizations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenant_organizations', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');

            $table->boolean('is_active')->default(true);
            $table->timestamps();
            SpeedAdminHelpers::createdByUpdatedByMigrations($table);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenant_organizations');
    }
}
