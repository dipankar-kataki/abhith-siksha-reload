<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPracticeTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_practice_tests', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('set_id');
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->integer('total_correct_count')->default(0);
            $table->string('total_duration')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_practice_tests');
    }
}
