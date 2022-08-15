<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropResetTokenFromUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_user_password_token');
            $table->dropColumn('password_reset_token');
            $table->dropColumn('reset_token_expired_at');
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
            $table->string('password_reset_token')->nullable()->index('idx_user_password_token');
            $table->dateTime('reset_token_expired_at')->nullable();
        });
    }
}
