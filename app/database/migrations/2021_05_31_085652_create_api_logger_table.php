<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiLoggerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_loggers', function(Blueprint $table) {
            $table->id();
            $table->dateTime('date')->index('idx_api_logger_date');
            $table->string('url');
                //->index('idx_api_logger_url');
            $table->string('method')->index('idx_api_logger_method');
            $table->string('client_ip')->nullable()->index('idx_api_logger_client_ip');
            $table->integer('status_code')->nullable()->index('idx_api_logger_status_code');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->mediumText('request')->nullable();
            $table->mediumText('answer')->nullable();
            $table->mediumText('headers')->nullable();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_loggers');
    }
}
