<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_roles', function(Blueprint $table) {
            $table->id();
            $table->dateTime('created_at');
            $table->unsignedBigInteger('user_id');
            $table->string('role', 50);

            $table->unique(['user_id', 'role'], 'idx_user_roles_user_unique');
            $table->foreign('user_id', 'fk_user_roles_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_roles');
    }
}
