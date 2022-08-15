<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('app_language_messages');

        Schema::create('app_language_messages', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100);
            $table->string('type', 25);
            $table->longText('message_values');
            $table->string('file_uuid',100)->nullable();

            $table->foreign('code', 'fk_app_language_messages_code')->references('code')->on('app_languages');
            $table->foreign('file_uuid', 'fk_app_language_messages_file_uuid')->references('uuid')->on('files');
            $table->unique(['code', 'type'], 'idx_app_language_messages_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app_language_messages');
    }
}
