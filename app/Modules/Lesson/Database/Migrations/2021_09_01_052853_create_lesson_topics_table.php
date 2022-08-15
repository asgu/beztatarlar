<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->index('idx_topic_lesson_id');
            $table->string('title', 200)->nullable(false);
            $table->string('video_title')->nullable();
            $table->string('video_url')->nullable();
            $table->string('content_title')->nullable();
            $table->longText('content_text')->nullable();
            $table->string('audio_title')->nullable();
            $table->text('audio_description')->nullable();
            $table->string('audio_uuid', 100)->nullable()->index('idx_topic_audio_uuid');
            $table->integer('priority')->nullable(false)->default(0)->index('idx_topic_priority');
            $table->string('timer')->nullable();
            $table->string('status', 45)
                ->nullable(false)
                ->default('active')
                ->index('idx_topic_status')
                ->comment('active, inactive');

            $table->foreign('lesson_id', 'fk_topic_lesson')
                ->on('lessons')
                ->references('id')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->foreign('audio_uuid', 'fk_topic_audio_uuid')
                ->on('files')
                ->references('uuid')
                ->onUpdate('restrict')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lesson_topics');
    }
}
