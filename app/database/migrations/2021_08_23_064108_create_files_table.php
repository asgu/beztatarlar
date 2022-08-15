<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function(Blueprint $table) {
            $table->id();
            $table->string('uuid', 100)->unique('idx_files_uuid');
            $table->dateTime('created_at');
            $table->dateTime('deleted_at')->nullable();
            $table->string('status', 25)->index('idx_files_status')->default('active');
            $table->string('type', 25)->index('idx_files_type')->default('local');
            $table->string('file_name')->comment('Имя файла на момент загрузки');
            $table->string('file_hash')->comment('Контрольная сумма файла');
            $table->string('full_url')->comment('Полный url путь к файлу');
            $table->integer('size')->default(0);
            $table->string('extension', 25);
            $table->string('stored_name')->nullable();
            $table->string('stored_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
