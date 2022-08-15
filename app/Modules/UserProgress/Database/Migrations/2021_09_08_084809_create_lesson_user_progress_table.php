<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonUserProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_user_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index('idx_progress_user');
            $table->foreignId('lesson_id')->index('idx_progress_lesson');
            $table->dateTime('created_at')->nullable(false);

            $table->foreign('user_id', 'fk_progress_user')
                ->on('users')
                ->references('id')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->foreign('lesson_id', 'fk_progress_lesson')
                ->on('lessons')
                ->references('id')
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
        Schema::dropIfExists('lesson_user_progress');
    }
}
