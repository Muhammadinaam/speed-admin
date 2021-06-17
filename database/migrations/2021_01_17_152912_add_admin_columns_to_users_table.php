<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdminColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('picture')->nullable();
            $table->boolean('is_superadmin')->default(false);
            SpeedAdminHelpers::createTenantOrganizationForeignKey($table);
            $table->boolean('is_tenant_organization_admin')->default(false);
            $table->boolean('is_active')->default(true);

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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'picture',
                'is_superadmin',
                'is_active',
            ]);
        });
    }
}
