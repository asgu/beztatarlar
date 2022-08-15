<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestQuestionLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_question_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->nullable(false)->index('idx_link_test');
            $table->foreignId('question_id')->nullable(false)->index('idx_link_question');
            $table->tinyInteger('priority')->nullable(false)->default(0)->index('idx_link_priority');

            $table->foreign('test_id', 'fk_link_test')
                ->on('tests')
                ->references('id')
                ->onUpdate('restrict')
                ->onDelete('restrict');

            $table->foreign('question_id', 'fk_link_question')
                ->on('test_questions')
                ->references('id')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_question_links');
    }
}
