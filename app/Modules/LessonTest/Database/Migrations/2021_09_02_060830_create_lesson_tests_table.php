<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_tests', function (Blueprint $table) {
            $table->id();
            $table->string('status', 45)
                ->nullable(false)
                ->default('active')
                ->index('idx_lesson_test_status')
                ->comment('active, inactive');
            $table->foreignId('lesson_id')->index('idx_lesson_test_lesson');
            $table->foreignId('test_id')->index('idx_lesson_test_test');

            $table->foreign('lesson_id', 'fk_lesson_test_lesson')
                ->on('lessons')
                ->references('id')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->foreign('test_id', 'fk_lesson_test_test')
                ->on('tests')
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
        Schema::dropIfExists('lesson_tests');
    }
}
