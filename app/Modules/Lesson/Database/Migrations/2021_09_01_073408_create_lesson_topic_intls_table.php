<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonTopicIntlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_topics_intl', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('model_id');
            $table->string('lang_code');

            $table->string('title', 200)->nullable();
            $table->string('video_title')->nullable();
            $table->string('content_title')->nullable();
            $table->longText('content_text')->nullable();
            $table->string('audio_title')->nullable();
            $table->text('audio_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lesson_topics_intl');
    }
}
