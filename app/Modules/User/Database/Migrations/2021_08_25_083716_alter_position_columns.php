<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPositionColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('mukhtasibat_id')->nullable()->change();
            $table->foreignId('position_id')->nullable()->change();
            $table->foreignId('parish_id')->nullable()->change();
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
            $table->foreignId('mukhtasibat_id')->change();
            $table->foreignId('position_id')->change();
            $table->foreignId('parish_id')->change();
        });
    }
}
