<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->index('idx_answer_question_id')->nullable(false);
            $table->string('answer')->nullable(false);
            $table->tinyInteger('is_correct')->default(0)->nullable(false);

            $table->foreign('question_id', 'fk_answer_question')
                ->on('test_questions')
                ->references('id')
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
        Schema::dropIfExists('test_answers');
    }
}
