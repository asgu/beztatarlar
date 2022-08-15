<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_emails', function (Blueprint $table) {
            $table->id();
            $table->dateTime('created_at');
            $table->dateTime('expired_at')->nullable();
            $table->string('email');
            $table->string('hash')->unique('idx_user_confirm_request_hash');
            $table->string('status', 25);
            $table->string('type', 50)
                ->nullable('false')
                ->default('message')
                ->index('idx_message_type')
                ->comment('message, registration, password_reset');
            $table->unsignedBigInteger('user_id');

            $table->foreign('user_id')->references('id')->on('users');

            $table->index(['email'], 'idx_user_confirm_request_email');
            $table->index(['status'], 'idx_user_confirm_request_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_emails');
    }
}
