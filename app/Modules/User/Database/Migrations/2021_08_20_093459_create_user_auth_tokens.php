<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAuthTokens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_auth_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('status')->nullable(false)
                ->default('active')
                ->index('idx_auth_token_status');
            $table->dateTime('created_at')->nullable(false);
            $table->foreignId('user_id')->index('auth_tokens_user');
            $table->string('type', 10)->nullable(false)->default('web');
            $table->string('access_token')->nullable(false)
                ->index('idx_auth_token_access_token');
            $table->string('push_token')->nullable()
                ->index('idx_auth_token_push_token');
            $table->string('device_id')->nullable();
            $table->string('user_host')->nullable(false);
            $table->string('user_agent')->nullable(false);
            $table->string('user_ip')->nullable(false);

            $table->foreign('user_id', 'fk_auth_token_user')
                ->references('id')
                ->on('users')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_auth_tokens');
    }
}
