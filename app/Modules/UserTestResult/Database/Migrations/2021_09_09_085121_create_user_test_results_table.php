<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTestResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_test_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_test_id')->nullable(false)->index('idx_test_result_test');
            $table->foreignId('user_id')->nullable(false)->index('idx_test_result_user');
            $table->tinyInteger('is_correct')->nullable(false)->default(0)->index('idx_test_result_correct');
            $table->longText('answer')->nullable();
            $table->dateTime('created_at')->nullable(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_test_results');
    }
}
