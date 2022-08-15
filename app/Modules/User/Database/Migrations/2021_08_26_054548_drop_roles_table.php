<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('user_roles');

        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('user_roles', function(Blueprint $table) {
            $table->id();
            $table->dateTime('created_at');
            $table->unsignedBigInteger('user_id');
            $table->string('role', 50);

            $table->unique(['user_id', 'role'], 'idx_user_roles_user_unique');
            $table->foreign('user_id', 'fk_user_roles_user_id')->references('id')->on('users');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
}
