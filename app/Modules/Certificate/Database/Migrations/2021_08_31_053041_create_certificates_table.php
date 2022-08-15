<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->string('file_uuid', 100)->nullable(false)->index('idx_certificate_file');
            $table->dateTime('created_at')->nullable(false);
            $table->foreignId('user_id')->nullable(false)->index('idx_certificate_user')->comment('uploaded by');

            $table->foreign('file_uuid', 'fk_certificate_file')
                ->on('files')
                ->references('uuid')
                ->onDelete('restrict')
                ->onUpdate('restrict');

            $table->foreign('user_id', 'fk_certificate_user')
                ->on('users')
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
        Schema::dropIfExists('certificates');
    }
}
