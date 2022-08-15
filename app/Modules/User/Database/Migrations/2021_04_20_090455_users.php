<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Users extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->dateTime('created_at');
            $table->string('status', 50);
            $table->string('password')->nullable();
            $table->string('remember_token')->nullable();
            $table->string('name')->nullable();
            $table->string('phone')->unique('idx_users_phone');
            $table->string('email')->unique('idx_users_email')->nullable();
            $table->string('timezone')->default('UTC');
            $table->string('photo_uuid')->nullable();
            $table->tinyInteger('push_notifications')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
