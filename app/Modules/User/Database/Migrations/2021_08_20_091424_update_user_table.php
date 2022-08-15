<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_phone');
            $table->dropColumn('name');
            $table->dropColumn('phone');
            $table->dropColumn('photo_uuid');

            $table->string('password_reset_token')->nullable()->index('idx_user_password_token');
            $table->dateTime('reset_token_expired_at')->nullable();
            $table->string('activate_token')->index('idx_user_activate_token');
            $table->foreignId('mukhtasibat_id')->index('idx_user_mukhtasibat');
            $table->foreignId('position_id')->index('idx_user_position');
            $table->foreignId('parish_id')->index('idx_user_parish');

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
            $table->string('name')->nullable();
            $table->string('phone')->unique('idx_users_phone');
            $table->string('photo_uuid')->nullable();

            $table->dropIndex('idx_user_password_token');
            $table->dropIndex('idx_user_activate_token');
            $table->dropIndex('idx_user_mukhtasibat');
            $table->dropIndex('idx_user_position');
            $table->dropIndex('idx_user_parish');

            $table->dropColumn('password_reset_token');
            $table->dropColumn('reset_token_expired_at');

//            $table->dropColumn('push_enabled');
            $table->dropColumn('activate_token');
            $table->dropColumn('mukhtasibat_id');
            $table->dropColumn('position_id');
            $table->dropColumn('parish_id');
        });
    }
}
