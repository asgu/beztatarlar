<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMukhtasibatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mukhtasibats', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100)->nullable(false)->unique('idx_mukhtasibats_title');
            $table->string('status', 10)
                ->nullable(false)
                ->default('active')
                ->index('idx_mukhtasibat_status')
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
        Schema::dropIfExists('mukhtasibats');
    }
}
