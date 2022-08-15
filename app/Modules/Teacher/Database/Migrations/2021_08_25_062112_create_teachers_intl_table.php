<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachersIntlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers_intl', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('model_id');
            $table->string('lang_code');

            $table->string('name')->nullable(false);
            $table->text('description')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teachers_intl');
    }
}
