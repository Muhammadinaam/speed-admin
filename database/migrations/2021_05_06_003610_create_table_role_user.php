<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableRoleUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_user', function (Blueprint $table) {
            $table->foreignUuid('role_id')->constrained('roles');

            if(config('speed-admin.user_primary_key_type') == 'integer')
            {
                $table->foreignId('user_id')->constrained('users');
            }
            else if(config('speed-admin.user_primary_key_type') == 'uuid')
            {
                $table->foreignUuid('user_id')->constrained('users');
            }
            
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
        Schema::dropIfExists('role_user');
    }
}
