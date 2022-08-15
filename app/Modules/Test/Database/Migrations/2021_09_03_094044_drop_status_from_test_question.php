<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropStatusFromTestQuestion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('test_questions', function (Blueprint $table) {
            $table->dropIndex('idx_test_question_status');
            $table->dropColumn('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('test_questions', function (Blueprint $table) {
            $table->string('status', 45)
                ->nullable(false)
                ->default('active')
                ->index('idx_test_question_status')
                ->comment('active, inactive');
        });
    }
}
