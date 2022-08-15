<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMukhtasibatIntlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mukhtasibats_intl', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('model_id');
            $table->string('lang_code');

            $table->string('title', 100)->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mukhtasibats_intl');
    }
}
