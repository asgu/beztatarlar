<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200)->nullable(false);
            $table->integer('priority')->nullable(false)->default(0)->comment('в каком порядке идут уроки');
            $table->string('status', 45)
                ->nullable(false)
                ->default('active')
                ->index('idx_lesson_status')
                ->comment('active, inactive');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lessons');
    }
}
