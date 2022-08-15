<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopicUserProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topic_user_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->index('idx_progress_topic');
            $table->foreignId('lesson_id')->index('idx_topic_progress_lesson');
            $table->foreignId('user_id')->index('idx_topic_progress_user');
            $table->dateTime('created_at')->nullable(false);

            $table->foreign('topic_id', 'fk_topic_progress_topic')
                ->on('lesson_topics')
                ->references('id')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->foreign('lesson_id', 'fk_topic_progress_lesson')
                ->on('lessons')
                ->references('id')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->foreign('user_id', 'fk_topic_progress_user')
                ->on('users')
                ->references('id')
                ->onDelete('restrict')
                ->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('topic_user_progress');
    }
}
