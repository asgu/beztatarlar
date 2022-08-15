<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique('idx_profile_user');
            $table->string('name')->nullable(false);
            $table->string('surname')->nullable(false);
            $table->string('patronymic')->nullable();
            $table->date('birthday')->nullable();
            $table->string('gender')->nullable(false)->default('male')->comment('male, female');
            $table->string('phone')->nullable();
            $table->string('photo_uuid')->nullable();

            $table->foreign('user_id', 'fk_user_profile_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
}
