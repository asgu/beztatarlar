<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_languages', function (Blueprint $table) {
            $table->string('code', 100)->primary()->unique('idx_app_languages_code');
            $table->string('name');
            $table->integer('sort_index')->default(0)->index('idx_app_languages_sort_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_languages');
    }
}
