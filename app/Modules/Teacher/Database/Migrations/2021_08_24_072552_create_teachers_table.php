<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->text('description')->nullable(false);
            $table->string('photo_uuid', 100)->nullable()->index('idx_teachers_photo');
            $table->string('status', 10)
                ->nullable(false)
                ->default('active')
                ->index('idx_teacher_status')
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
        Schema::dropIfExists('teachers');
    }
}
